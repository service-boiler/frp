<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use ServiceBoiler\Prf\Site\Concerns\StoreMessages;
use ServiceBoiler\Prf\Site\Events\RepairStatusChangeEvent;
use ServiceBoiler\Prf\Site\Exports\Excel\RepairExcel;
use ServiceBoiler\Prf\Site\Filters\FileType\ModelHasFilesFilter;
use ServiceBoiler\Prf\Site\Filters\Repair\FerroliManagersRepairFilter;
use ServiceBoiler\Prf\Site\Filters\Repair\RegionFilter;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\RepairRequest;
use ServiceBoiler\Prf\Site\Http\Requests\MessageRequest;
use ServiceBoiler\Prf\Site\Models\Repair;
use ServiceBoiler\Prf\Site\Models\RepairStatus;
use ServiceBoiler\Prf\Site\Repositories\FileTypeRepository;
use ServiceBoiler\Prf\Site\Repositories\MessageRepository;
use ServiceBoiler\Prf\Site\Repositories\RepairRepository;
use ServiceBoiler\Prf\Site\Repositories\RepairStatusRepository;
use ServiceBoiler\Prf\Site\Support\CommentBox;

class RepairController
{

	use AuthorizesRequests,StoreMessages;
	/**
	 * @var RepairRepository
	 */
	protected $repairs;
	protected $statuses;
	protected $types;
	protected $messages;


	/**
	 * Create a new controller instance.
	 
	 *
	 * @param RepairRepository $repairs
	 * @param RepairStatusRepository $statuses
	 * @param FileTypeRepository $types
	 * @param MessageRepository $messages
	 */
	public function __construct(
		RepairRepository $repairs,
		RepairStatusRepository $statuses,
		FileTypeRepository $types,
		MessageRepository $messages
	)
	{
		$this->repairs = $repairs;
		$this->statuses = $statuses;
		$this->types = $types;
		$this->messages = $messages;
	}

	/**
	 *
	 * @param Request $request 1
	 *
	 * @return \Illuminate\Http\Response
	 * @throws \PhpOffice\PhpSpreadsheet\Exception
	 */
	public function index(Request $request)
	{
		$this->repairs->trackFilter();
		$this->repairs->applyFilter(New FerroliManagersRepairFilter());
		if ($request->has('excel')) {
			(new RepairExcel())->setRepository($this->repairs)->render();
		}

        return view('site::admin.repair.index', [
			'repository' => $this->repairs,
			'repairs' => $this->repairs->paginate($request->input('filter.per_page', config('site.per_page.repair', 10)), ['repairs.*']),
		]);
	}


	/**
	 * Display the specified resource.
	 *
	 * @param Repair $repair
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show(Repair $repair)
	{   
        $this->authorize('view', $repair);
		$statuses = RepairStatus::query()->where('id', '!=', $repair->getAttribute('status_id'))->orderBy('sort_order')->get();
		$fails = $repair->fails;
		$files = $repair->files;
		$this->types->applyFilter((new ModelHasFilesFilter())->setId($repair->id)->setMorph('repairs'));
		$file_types = $this->types->all();
        $repair_price_ratio = $repair->user->repair_price_ratio;

		$commentBox = new CommentBox($repair);

		return view('site::admin.repair.show', compact(
			'repair',
			'statuses',
			'fails',
			'files',
			'file_types',
			'repair_price_ratio',
			'commentBox'
		));
	}

	/**
	 * @param RepairRequest $request
	 * @param Repair $repair
	 *
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
	public function update(RepairRequest $request, Repair $repair)
	{
        $this->authorize('update', $repair);
		$repair->update(array_merge(
			(array)$request->input('repair'),
			(array)['called_client' => $request->filled('repair.called_client')]
		));

		$repair->fails()->delete();
		if ($request->filled('fail')) {
			$repair->fails()->createMany($request->input('fail'));
		}


		if ($request->filled('repair.status_id')) {
			event(new RepairStatusChangeEvent($repair));
		}


		return redirect()->route('admin.repairs.show', $repair)->with('success', trans('site::repair.updated'));
	}

	/**
	 * @param \ServiceBoiler\Prf\Site\Http\Requests\MessageRequest $request
	 * @param \ServiceBoiler\Prf\Site\Models\Repair $repair
	 *
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Throwable
	 */
	public function message(MessageRequest $request, Repair $repair)
	{
		return $this->storeMessage($request, $repair);
	}

}
