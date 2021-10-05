<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use ServiceBoiler\Prf\Site\Concerns\StoreMessages;
use ServiceBoiler\Prf\Site\Events\RepairCreateEvent;
use ServiceBoiler\Prf\Site\Events\RepairEditEvent;
use ServiceBoiler\Prf\Site\Filters\BelongsUserFilter;
use ServiceBoiler\Prf\Site\Filters\ByNameSortFilter;
use ServiceBoiler\Prf\Site\Filters\CountryEnabledFilter;
use ServiceBoiler\Prf\Site\Filters\CountrySortFilter;
use ServiceBoiler\Prf\Site\Filters\FileType\ModelHasFilesFilter;
use ServiceBoiler\Prf\Site\Filters\FileType\RepairFilter;
use ServiceBoiler\Prf\Site\Filters\FileType\SortFilter;
use ServiceBoiler\Prf\Site\Filters\Repair\RepairPerPageFilter;
use ServiceBoiler\Prf\Site\Http\Requests\MessageRequest;
use ServiceBoiler\Prf\Site\Http\Requests\RepairRequest;
use ServiceBoiler\Prf\Site\Http\Resources\ProductResource;
use ServiceBoiler\Prf\Site\Models\Country;
use ServiceBoiler\Prf\Site\Models\Difficulty;
use ServiceBoiler\Prf\Site\Models\Distance;
use ServiceBoiler\Prf\Site\Models\File;
use ServiceBoiler\Prf\Site\Models\FileType;
use ServiceBoiler\Prf\Site\Models\Part;
use ServiceBoiler\Prf\Site\Models\Product;
use ServiceBoiler\Prf\Site\Models\Repair;
use ServiceBoiler\Prf\Site\Repositories\ContragentRepository;
use ServiceBoiler\Prf\Site\Repositories\CountryRepository;
use ServiceBoiler\Prf\Site\Repositories\DifficultyRepository;
use ServiceBoiler\Prf\Site\Repositories\DistanceRepository;
use ServiceBoiler\Prf\Site\Repositories\EngineerRepository;
use ServiceBoiler\Prf\Site\Repositories\EquipmentRepository;
use ServiceBoiler\Prf\Site\Repositories\FileRepository;
use ServiceBoiler\Prf\Site\Repositories\FileTypeRepository;
use ServiceBoiler\Prf\Site\Repositories\LaunchRepository;
use ServiceBoiler\Prf\Site\Repositories\RepairRepository;
use ServiceBoiler\Prf\Site\Repositories\TradeRepository;

class RepairController extends Controller
{
    use AuthorizesRequests, StoreMessages;
    /**
     * @var RepairRepository
     */
    protected $repairs;
    /**
     * @var EngineerRepository
     */
    protected $engineers;
    /**
     * @var TradeRepository
     */
    protected $trades;
    /**
     * @var FileTypeRepository
     */
    protected $types;
    /**
     * @var FileRepository
     */
    protected $files;
    /**
     * @var CountryRepository
     */
    private $countries;
    /**
     * @var EquipmentRepository
     */
    private $equipments;
    /**
     * @var ContragentRepository
     */
    private $contragents;
    /**
     * @var DistanceRepository
     */
    private $distances;
    /**
     * @var DifficultyRepository
     */
    private $difficulties;
    
    
    /**
     * Create a new controller instance.
     *
     * @param RepairRepository $repairs
     * @param EngineerRepository $engineers
     * @param TradeRepository $trades
     * @param FileTypeRepository $types
     * @param CountryRepository $countries
     * @param EquipmentRepository $equipments
     * @param FileRepository $files
     * @param ContragentRepository $contragents
     * @param DistanceRepository $distances
     * @param DifficultyRepository $difficulties
     */
    public function __construct(
        RepairRepository $repairs,
        EngineerRepository $engineers,
        TradeRepository $trades,
        FileTypeRepository $types,
        FileRepository $files,
        CountryRepository $countries,
        EquipmentRepository $equipments,
        ContragentRepository $contragents,
        DistanceRepository $distances,
        DifficultyRepository $difficulties
    )
    {
        $this->repairs = $repairs;
        $this->engineers = $engineers;
        $this->trades = $trades;
        $this->types = $types;
        $this->files = $files;
        $this->countries = $countries;
        $this->equipments = $equipments;
        $this->contragents = $contragents;
        $this->distances = $distances;
        $this->difficulties = $difficulties;
    }

    /**
     * @param RepairRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(RepairRequest $request)
    {  
        $this->repairs->trackFilter();
        $this->repairs->applyFilter(new BelongsUserFilter());
        $this->repairs->pushTrackFilter(RepairPerPageFilter::class);
 
        return view('site::repair.index', [
            'repository' => $this->repairs,
            'repairs'    => $this->repairs->paginate($request->input('filter.per_page', config('site.per_page.repair', 10)), ['repairs.*'])
        ]);
    }


    /**
     * @param Repair $repair
     * @return \Illuminate\Http\Response
     */
    public function show(Repair $repair)
    {
        $this->authorize('view', $repair);
        $statuses = $repair->statuses()->get();
        $fails = $repair->fails()->get();
        $this->types->applyFilter((new ModelHasFilesFilter())->setId($repair->getAttribute('id'))->setMorph('repairs'));
        $file_types = $this->types->all();
        $files = $repair->files()->get();

        return view('site::repair.show', compact('repair', 'fails', 'file_types', 'files', 'statuses'));
    }

    /**
     * @param RepairRequest $request
     * @return \Illuminate\Http\Response
     */
    public function create(RepairRequest $request)
    {

        $this->authorize('create', Repair::class);
        $user = $request->user();
        $engineers = $request->user()->engineers()->orderBy('name')->get();
        $trades = $request->user()->trades()->orderBy('name')->get();
        $contragents = $request->user()->contragents()->orderBy('name')->get();
        $countries = Country::query()->where('enabled', 1)->orderBy('name')->get();
        $file_types = FileType::query()->where('enabled', 1)->where('group_id', 1)->orderBy('sort_order')->get();
        $files = $this->getFiles($request);
        $difficulties = Difficulty::query()->where('active', 1)->orderBy('sort_order')->get();
        $distances = Distance::query()->where('active', 1)->orderBy('sort_order')->get();
        $products = Product::query()
            ->whereNotNull('sku')
            ->where('enabled', 1)
            ->where('warranty', 1)
            ->orderBy('name')
            ->get(['id', 'name', 'sku']);
        $parts = $this->getParts($request);
        $repair_price_ratio = Auth::user()->repair_price_ratio;
        
        $fails = collect([]);
        //$product = old('product_id', false) ? Product::query()->find(old('product_id'))->name : null;
        $product = (new ProductResource(Product::query()->findOrNew(old('repair.product_id'))))->toArray($request);

        return view('site::repair.create', compact(
            'user',
            'engineers',
            'trades',
            'contragents',
            'products',
            'countries',
            'file_types',
            'files',
            'parts',
            'fails',
            'product',
            'difficulties',
            'distances',
            'repair_price_ratio'
        ));
    }

    /**
     * @param RepairRequest $request
     * @param Repair|null $repair
     * @return \Illuminate\Support\Collection
     */
    private function getParts(RepairRequest $request, Repair $repair = null)
    {   $repair_price_ratio = Auth::user()->repair_price_ratio;
        $parts = collect([]);
        $old = $request->old('count');
        if (!is_null($old) && is_array($old)) {

            foreach ($old as $product_id => $count) {
                $product = Product::query()->findOrFail($product_id);
                $parts->put($product->id, collect([
                    'product' => $product,
                    'count'   => $count,
                    'cost' => $product->hasPrice ? $product->repairPrice->valueBack*$repair_price_ratio : 0
                ]));
            }
        } elseif (!is_null($repair)) {
            foreach ($repair->parts as $part) {
                $parts->put($part->product_id, collect([
                    'product' => $part->product,
                    'count'      => $part->count,
                    'cost' => $part->product->hasPrice ? $part->product->repairPrice->valueBack*$repair_price_ratio : 0
                ]));
            }
        }
        return $parts;
    }

    /**
     * @param RepairRequest $request
     * @param Repair|null $repair
     * @return \Illuminate\Support\Collection
     */
    private function getFiles(RepairRequest $request, Repair $repair = null)
    {
        $files = collect([]);
        $old = $request->old('file');
        if (!is_null($old) && is_array($old)) {
            foreach ($old as $type_id => $values) {
                foreach ($values as $file_id) {
                    $files->push(File::query()->findOrFail($file_id));
                }
            }
        } elseif (!is_null($repair)) {
            $files = $files->merge($repair->files);
        }

        return $files;
    }

    /**
     * @param  RepairRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(RepairRequest $request)
    { 
        
        
        $this->authorize('create', Repair::class);
        $request->user()->repairs()->save($repair = $this->repairs->create($request->input(['repair'])));
        if ($request->filled('count')) {
            $parts = (collect($request->input('count')))->map(function ($count, $product_id) {
                $product = Product::query()->findOrFail($product_id);
                $repair_price_ratio = Auth::user()->repair_price_ratio;
                return new Part([
                    'product_id' => $product_id,
                    'count'=> $count,
                    'cost' => $product->hasPrice ? $product->repairPrice->valueBack*$repair_price_ratio : 0
                ]);
            });
            
            $repair->parts()->saveMany($parts);
        }
        $this->setFiles($request, $repair);

        event(new RepairCreateEvent($repair));

        return redirect()->route('repairs.show', $repair)->with('success', trans('site::repair.created'));
    }

    /**
     * @param RepairRequest $request
     * @param Repair $repair
     */
    private function setFiles(RepairRequest $request, Repair $repair)
    {
        $repair->detachFiles();

        if ($request->filled('file')) {
            foreach ($request->input('file') as $type_id => $values) {
                foreach ($values as $file_id) {
                    $repair->files()->save(File::find($file_id));
                }
            }
        }
        //$this->files->deleteLostFiles();
    }

    /**
     * @param RepairRequest $request
     * @param Repair $repair
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(RepairRequest $request, Repair $repair)
    {
        $repair_price_ratio = Auth::user()->repair_price_ratio;
        $engineers = $request->user()->engineers()->orderBy('name')->get();
        $trades = $request->user()->trades()->orderBy('name')->get();
        $countries = Country::query()->where('enabled', 1)->orderBy('name')->get();
        $file_types = FileType::query()->where('enabled', 1)->where('group_id', 1)->orderBy('sort_order')->get();
        $files = $this->getFiles($request, $repair);
        $parts = $this->getParts($request, $repair);
        $difficulties = Difficulty::query()->where('active', 1)->orderBy('sort_order')->get();
        $distances = Distance::query()->where('active', 1)->orderBy('sort_order')->get();
        $statuses = $repair->statuses()->get();
        $products = Product::query()
            ->whereNotNull('sku')
            ->where('enabled', 1)
            ->where('warranty', 1)
            ->orderBy('name')
            ->get(['id', 'name', 'sku']);
        $fails = $repair->fails;

        return view('site::repair.edit', compact(
            'repair',
            'engineers',
            'trades',
            'countries',
            'statuses',
            'file_types',
            'products',
            'files',
            'parts',
            'fails',
            'difficulties',
            'repair_price_ratio',
            'distances'
        ));
    }

    /**
     * @param  RepairRequest $request
     * @param Repair $repair
     * @return \Illuminate\Http\Response
     */
    public function update(RepairRequest $request, Repair $repair)
    {   
        $repair->update($request->except(['_token', '_method', '_create', 'file', 'parts']));
        if ($request->filled('message.text')) {
            $repair->messages()->save($message = $request->user()->outbox()->create($request->input('message')));
        }
        $this->setFiles($request, $repair);

        $repair->parts()->delete();

        if ($request->filled('count')) {
            $parts = (collect($request->input('count')))->map(function ($count, $product_id) {
                $product = Product::query()->findOrFail($product_id);
                $repair_price_ratio = Auth::user()->repair_price_ratio;
                return new Part([
                    'product_id' => $product_id,
                    'count'=> $count,
                    'cost' => $product->hasPrice ? $product->repairPrice->valueBack*$repair_price_ratio : 0
                ]);
            });
            $repair->parts()->saveMany($parts);
        }

        event(new RepairEditEvent($repair));
        return redirect()->route('repairs.show', $repair)->with('success', trans('site::repair.updated'));
    }

    /**
     * @param \ServiceBoiler\Prf\Site\Http\Requests\MessageRequest $request
     * @param \ServiceBoiler\Prf\Site\Models\Repair $repair
     * @return \Illuminate\Http\JsonResponse
     */
    public function message(MessageRequest $request, Repair $repair)
    {
        return $this->storeMessage($request, $repair);
    }


}