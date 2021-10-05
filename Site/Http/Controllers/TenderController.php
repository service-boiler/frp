<?php

namespace ServiceBoiler\Prf\Site\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use ServiceBoiler\Prf\Site\Mail\Guest\MailingHtmlEmail;
use ServiceBoiler\Prf\Site\Concerns\StoreMessages;
use ServiceBoiler\Prf\Site\Exports\Excel\TenderExcel;
use ServiceBoiler\Prf\Site\Events\TenderStatusChangeEvent;
use ServiceBoiler\Prf\Site\Http\Requests\MessageRequest;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\TenderRequest;
use ServiceBoiler\Prf\Site\Http\Requests\Admin\TenderStatusRequest;
use ServiceBoiler\Prf\Site\Http\Resources\ProductResource;
use ServiceBoiler\Prf\Site\Filters\Region\SelectFilter;
use ServiceBoiler\Prf\Site\Filters\Region\SortFilter;
use ServiceBoiler\Prf\Site\Filters\Tender\TenderAddressSearchFilter;
use ServiceBoiler\Prf\Site\Filters\Tender\TenderBelongsUserFilter;
use ServiceBoiler\Prf\Site\Filters\Tender\TenderSubjectSearchFilter;
use ServiceBoiler\Prf\Site\Filters\Tender\TenderPerPageFilter;
use ServiceBoiler\Prf\Site\Filters\Tender\TenderDistributorFilter;
use ServiceBoiler\Prf\Site\Filters\Tender\TenderEquipmentFilter;
use ServiceBoiler\Prf\Site\Filters\Tender\TenderDateFromFilter;
use ServiceBoiler\Prf\Site\Filters\Tender\TenderDateToFilter;
use ServiceBoiler\Prf\Site\Filters\Tender\TenderUserFilter;
use ServiceBoiler\Prf\Site\Filters\Tender\TenderRegionFilter;
use ServiceBoiler\Prf\Site\Filters\Tender\TenderStatusFilter;
use ServiceBoiler\Prf\Site\Filters\Tender\TenderSortFilter;
use ServiceBoiler\Prf\Site\Models\Country;
use ServiceBoiler\Prf\Site\Models\Currency;
use ServiceBoiler\Prf\Site\Models\Customer;
use ServiceBoiler\Prf\Site\Models\CustomerContact;
use ServiceBoiler\Prf\Site\Models\CustomerRole;
use ServiceBoiler\Prf\Site\Models\Product;
use ServiceBoiler\Prf\Site\Models\Tender;
use ServiceBoiler\Prf\Site\Models\TenderCategory;
use ServiceBoiler\Prf\Site\Models\TenderProduct;
use ServiceBoiler\Prf\Site\Models\TenderStatus;
use ServiceBoiler\Prf\Site\Models\TenderStage;
use ServiceBoiler\Prf\Site\Models\File;
use ServiceBoiler\Prf\Site\Models\FileType;
use ServiceBoiler\Prf\Site\Models\Variable;
use ServiceBoiler\Prf\Site\Repositories\CountryRepository;
use ServiceBoiler\Prf\Site\Repositories\FileTypeRepository;
use ServiceBoiler\Prf\Site\Repositories\FileRepository;
use ServiceBoiler\Prf\Site\Repositories\MessageRepository;
use ServiceBoiler\Prf\Site\Repositories\RegionRepository;
use ServiceBoiler\Prf\Site\Repositories\TenderRepository;
use ServiceBoiler\Prf\Site\Support\CommentBox;
use ServiceBoiler\Prf\Site\Pdf\TenderResultPdf;


class TenderController extends Controller
{
    use AuthorizesRequests, StoreMessages;
   
    protected $tenders;
    protected $types;
    protected $files;
    protected $regions;
    private $countries;
	protected $messages;
    
    public function __construct(CountryRepository $countries, 
                                RegionRepository $regions, 
                                TenderRepository $tenders, 
                                FileTypeRepository $types, 
                                FileRepository $files, 
                                MessageRepository $messages )
    {
        $this->countries = $countries;
        $this->regions = $regions;
        $this->tenders = $tenders;
        $this->files = $files;
        $this->types = $types;
        $this->messages = $messages;
    }

    public function index(TenderRequest $request)
    {  
        $this->tenders->trackFilter();
        $this->tenders->applyFilter(New TenderSortFilter());
        $this->tenders->applyFilter(New TenderBelongsUserFilter());
        $this->tenders->pushTrackFilter(TenderStatusFilter::class);
        $this->tenders->pushTrackFilter(TenderEquipmentFilter::class);
        $this->tenders->pushTrackFilter(TenderRegionFilter::class);
        $this->tenders->pushTrackFilter(TenderAddressSearchFilter::class);
        $this->tenders->pushTrackFilter(TenderSubjectSearchFilter::class);
        $this->tenders->pushTrackFilter(TenderDateFromFilter::class);
        $this->tenders->pushTrackFilter(TenderDateToFilter::class);
        $this->tenders->pushTrackFilter(TenderPerPageFilter::class);
 
        if ($request->has('excel')) {
			(new TenderExcel())->setRepository($this->tenders)->render();
		}
        return view('site::tender.index', [
            'repository' => $this->tenders,
            'tenders'    => $this->tenders->paginate($request->input('filter.per_page', config('site.per_page.tender', 10)), ['tenders.*'])
        ]);
    }


    public function show(Tender $tender)
    {
        if(auth()->user()->hasRole('ferroli_user')) {
            return redirect()->route('admin.tenders.show',$tender);

        }
        $this->authorize('view', $tender);
        $sub_head_id = Variable::query()->where('name','tender_sub_head_user_id')->first()->value;
        $customers=$tender->customers();
        $rates = round(Currency::find('978')->rates, 2);
        $statuses = $tender->statuses()->get();
        $files = $tender->files()->get();
        $file_types = FileType::query()->where('enabled', 1)->where('group_id', 10)->orderBy('sort_order')->get();
        $commentBox = new CommentBox($tender);
        

         if($tender->cb_rate && !in_array($tender->status_id,[6,10,11])){
            $tender->update(['rates'=>$rates,'rates_min'=>$rates,'rates_max'=>$rates,]);
         }
         
        return view('site::tender.show', compact('tender', 'customers','files', 'statuses', 'file_types','commentBox','sub_head_id'));
    }

    public function create(TenderRequest $request)
    {
        //$this->authorize('create', Tender::class);
        $regions = $this->regions->applyFilter(new SelectFilter())->applyFilter(new SortFilter())->all();
        $countries = Country::query()->where('enabled', 1)->orderBy('name')->get();
        $tenderCategories = TenderCategory::query()->where('enabled', 1)->orderBy('name')->get();
        $tenderProducts = $this->getTenderProducts($request);
        $file_types = FileType::query()->where('enabled', 1)->where('group_id', 10)->orderBy('sort_order')->get();
        $files = $this->getFiles($request);
        $planned_purchase_years = [Carbon::now()->format('Y'),Carbon::now()->addYears(1)->format('Y')];
        $products = Product::query()->where('forsale','1')->whereHas('group',function ($query) {$query->whereIn('product_groups.type_id',['1','3']);})->orderBy('name')->get();
        $rates = round(Currency::find('978')->rates, 2);
       
        return view('site::tender.create', compact('countries','regions','tenderProducts','tenderCategories','files','products','file_types','planned_purchase_years','rates'));
    }


    /**
     * @param  TenderRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(TenderRequest $request)
    { 
      //  $this->authorize('create', Tender::class);
       // dd($request->input());
        //$tender=Tender::findOrFail('1');
        
        
        
         $tender = Tender::create(
         array_merge(['creator_id'=>$request->user()->id,
                      'cb_rate'     => $request->filled('tender.cb_rate')],
         $request->except(['_token', '_method', '_create', 'tender.cb_rate','tender.status_id'])['tender']));
        
        if($request->filled('customers')) {
        
            foreach($request->input('customers') as $role_name => $customers_role){
                $role_id = CustomerRole::where('name',$role_name)->first()->id;
                foreach($customers_role as $customer) {
                    
                   if(!empty($customer['customer']['id'])) {
                        $tmp_customer = Customer::findOrFail($customer['customer']['id']);
                        if(!empty($customer['contact']['id'])) {
                            $tender->customers()->attach([$customer['customer']['id'] => ['customer_role_name'=>$role_name , 'customer_contact_id'=>$customer['contact']['id']]]);
                            
                        } else {
                            $tmp_contact = CustomerContact::where('name',$customer['contact']['name'])->where('email',$customer['contact']['email'])->first();
                            if(empty($tmp_contact) && !empty($customer['contact']['name'])) {
                                $customer['contact']['customer_id']=$tmp_customer->id;
                                $tmp_contact = CustomerContact::create($customer['contact']);
                                
                            }
                            if(!empty($customer['contact']['name'])) {
                                $tender->customers()->attach([$customer['customer']['id'] => ['customer_role_name'=>$role_name , 'customer_contact_id'=>$tmp_contact->id]]);
                            } else {
                                $tender->customers()->attach([$customer['customer']['id'] => ['customer_role_name'=>$role_name]]);
                            }
                        }
                    
                    } else {
                        
                        $tmp_customer = Customer::where('name',$customer['customer']['name'])->where('email',$customer['customer']['email'])->first();
                        
                        if(empty($tmp_customer)) {
                            $tmp_customer = Customer::create($customer['customer']);
                        }
                        $tmp_contact = CustomerContact::where('name',$customer['contact']['name'])->where('email',$customer['contact']['email'])->first();
                            if(empty($tmp_contact) && !empty($customer['contact']['name'])) {
                                if(!empty($customer['contact']['lpr']) && $customer['contact']['lpr']=='on') $customer['contact']['lpr']=1 ;
                                $customer['contact']['customer_id']=$tmp_customer->id;
                                $tmp_contact = CustomerContact::create($customer['contact']);
                            
                            }
                        if(!empty($customer['contact']['name'])) {    
                            $tender->customers()->attach([$tmp_customer->id => ['customer_role_name'=>$role_name, 'customer_contact_id'=>$tmp_contact->id]]);
                        } else {
                            $tender->customers()->attach([$tmp_customer->id => ['customer_role_name'=>$role_name]]);                    
                        }
                    } 
                   
                    if(!empty($tmp_customer->roles()) && !in_array($role_id,$tmp_customer->roles()->pluck('id')->toArray())) {
                            $tmp_customer->attachCustomerRole($role_id);
                        }
                  
                }
                
                
            
            
            }
            
        }
       
        
        
        
        if ($request->filled('count')) {
            $tenderProducts = (collect($request->input('count')))->map(function ($count, $product_id) {
                $product = Product::query()->findOrFail($product_id);
                return new TenderProduct([
                    'product_id' => $product_id,
                    'count'=> $count['count'],
                    'discount'=> $count['discount'],
                    'discount_object'=> $count['discount_object'],
                    'cost' => $product->hasPrice ? $product->tenderPrice->valueRaw : 0,
                    'currency_id' => $product->hasPrice ? $product->tenderPrice->type->currency->id : 978,
                    //'discount' => $product->hasPrice ? number_format(($product->retailPrice->value - $product->tenderPrice->value)*100 / $product->retailPrice->value,2) : 30
                ]);
           });
            $tender->tenderProducts()->saveMany($tenderProducts);
        }
        
        $this->setFiles($request, $tender);
        
        //event(new TenderCreateEvent($tender));

        return redirect()->route('tenders.show', $tender)->with('success', trans('site::tender.created'));
    }

 
    public function edit(TenderRequest $request, Tender $tender)
    {
        $this->authorize('update', $tender);
        
        $regions = $this->regions->applyFilter(new SelectFilter())->applyFilter(new SortFilter())->all();
        $countries = Country::query()->where('enabled', 1)->orderBy('name')->get();
        $tenderCategories = TenderCategory::query()->where('enabled', 1)->orderBy('name')->get();
        $tenderProducts = $this->getTenderProducts($request, $tender);
       
        $file_types = FileType::query()->where('enabled', 1)->where('group_id', 10)->orderBy('sort_order')->get();
        //$files = $this->getFiles($request);
        $files = $tender->files;
        $planned_purchase_years = [Carbon::now()->format('Y'),Carbon::now()->addYears(1)->format('Y')];
        $products = Product::query()->where('forsale','1')->whereHas('group',function ($query) {$query->whereIn('product_groups.type_id',['1','3']);})->orderBy('name')->get();
        $statuses = $tender->statuses()->get();
        $rates = round(Currency::find('978')->rates, 2);
        
        return view('site::tender.edit', compact('tender','countries','regions','tenderProducts','tenderCategories','files','products','file_types','planned_purchase_years','statuses','rates'));
    }

    /**
     * @param  TenderRequest $request
     * @param Tender $tender
     * @return \Illuminate\Http\Response
     */
    public function update(TenderRequest $request, Tender $tender)
    {   
        $count_changes = 0;
        $text = '';
        
        $tender->fill(array_merge($request->input('tender'),
                      ['cb_rate'     => $request->filled('tender.cb_rate')]));
        
        if ($tender->isDirty()) {
                    $tender->syncChanges();
                    $changes = ['В тендере были изменены поля: '];
                    foreach ($tender->getChanges() as $key => $value) {
                        $count_changes++;
                        $changes[] = trans('site::tender.message.item', [
                            'column' => trans('site::tender.' . $key),
                            'original' => $tender->getOriginal($key),
                            'change' => $value,
                        ]);
                    }
                    $text = $text."\r\n\r\n" .implode("\r\n", $changes);
                    $tender->save();
                }
        
       
       
       $oldFiles = ($this->setFiles($request, $tender))->pluck('id')->toArray();
       
       $file_types = $request->input('file');
       
       if (!is_null($file_types) && is_array($file_types)) {
            foreach ($file_types as $type_id => $values) {
                foreach ($values as $file_id) {
                    if(!in_array($file_id,$oldFiles)){
                            $file=File::query()->findOrFail($file_id);
                            $text = $text ."\r\n\r\n Добавлен файл" .$file->name;
                            $count_changes++;
                    }
                }
            }
        } 
       
        
        
        if($request->filled('customers')) {
        
            foreach($request->input('customers') as $role_name => $customer_group){
                $role_id = CustomerRole::where('name',$role_name)->first()->id;
                foreach($tender->customers()->wherePivot('customer_role_name',$role_name)->get() as $item)
                {
                    if(!$this->customerInGroupExists($item, $customer_group)){
                        $tender->customers()->detach($item); 
                        
                    }
                    
                } 
                foreach($customer_group as $customer) {
                    //dd($tender->customers()->wherePivot('customer_role_name',$role_name)->get());
                    if(!empty($customer['contact']['lpr']) && $customer['contact']['lpr']=='on') $customer['contact']['lpr']=1 ;
                    if(!empty($customer['customer']['id'])) {
                        $tmp_customer = Customer::findOrFail($customer['customer']['id']);
                        $tmp_customer->fill($customer['customer']);
                        if ($tmp_customer->isDirty()) {
                                
                                $tmp_customer->syncChanges();
                                $changes = ['В тендере были изменены поля: '];
                                
                                foreach ($tmp_customer->getChanges() as $key => $value) {
                                    $count_changes++;
                                    $changes[] = $tmp_customer->name;
                                    $changes[] = trans('site::tender.message.item', [
                                        'column' => trans('site::admin.customer.' . $key),
                                        'original' => $tmp_customer->getOriginal($key),
                                        'change' => $value,
                                    ]);
                                }
                                $text = $text."\r\n\r\n" .implode("\r\n", $changes);
                                $tmp_customer->save();
                        }
                    } else {
                        $tmp_customer = Customer::where('name',$customer['customer']['name'])->where('email',$customer['customer']['email'])->first();
                        
                        if(empty($tmp_customer && !empty($customer['customer']['name']))) {
                           $tmp_customer = Customer::create($customer['customer']);
                        }
                        if(!empty($tmp_customer->roles()) && !in_array($role_id,$tmp_customer->roles()->pluck('id')->toArray())) {
                            $tmp_customer->attachCustomerRole($role_id);
                        }
                        
                    } 
                    
                    
                    if(!empty($customer['contact']['id'])) {
                        $tmp_contact = CustomerContact::findOrFail($customer['contact']['id']);
                        $tmp_contact->fill($customer['contact']);
                        if ($tmp_contact->isDirty()) {
                                
                                $tmp_contact->syncChanges();
                                $changes = [];
                                
                                foreach ($tmp_contact->getChanges() as $key => $value) {
                                    $count_changes++;
                                    $changes[] = $tmp_contact->name;
                                    $changes[] = trans('site::tender.message.item', [
                                        'column' => trans('site::admin.contact.' . $key),
                                        'original' => $tmp_contact->getOriginal($key),
                                        'change' => $value,
                                    ]);
                                }
                                $text = $text."\r\n\r\n" .implode("\r\n", $changes);
                                $tmp_contact->save();
                        }
                        
                    } else {
                            $tmp_contact = CustomerContact::where('name',$customer['contact']['name'])->where('customer_id',$customer['customer']['id'])->first();
                            $customer['contact']['customer_id']=$tmp_customer->id;
                            
                            if(empty($tmp_contact) && !empty($customer['contact']['name'])) {
                                $tmp_contact = CustomerContact::create($customer['contact']);
                               
                            } elseif(!empty($tmp_contact)) {
                                $tmp_contact->update($customer['contact']);
                                
                            }
                            $tender->customers()->detach($tmp_customer);
                            if(!empty($tmp_contact)){
                                $tender->customers()->attach([$tmp_customer->id => ['customer_role_name'=>$role_name, 'customer_contact_id'=>$tmp_contact->id]]); 
                                $text = $text."\r\n\r\n Добавлен контакт ".$tmp_customer->name ." " .$tmp_contact->name;
                                $count_changes++;
                            } else {
                                $tender->customers()->attach([$tmp_customer->id => ['customer_role_name'=>$role_name]]);
                                                            
                            }
                            
                            
                    }
                    
                    
                    if(!in_array($tmp_customer->id,$tender->customers()->wherePivot('customer_role_name',$role_name)->pluck('customers.id')->toArray())){
                      
                       if(!empty($tmp_contact)){
                            $tender->customers()->attach([$tmp_customer->id => ['customer_role_name'=>$role_name, 'customer_contact_id'=>$tmp_contact->id]]); 
                       } else {
                            $tender->customers()->attach([$tmp_customer->id => ['customer_role_name'=>$role_name]]);
                                                        
                       }
                        $text = $text."\r\n\r\n" .$role_name ." добавлен " .$tmp_customer->name;
                    }
                    
                }
            
            }
            
        }     
        
        foreach($tender->tenderProducts as $tenderProduct) 
        {
            if(!empty($request->input('count')) && array_key_exists($tenderProduct->product->id, $request->input('count')))
            {   
                $tenderProduct->fill($request->input('count.' . $tenderProduct->product->getKey()));
                
                if ($tenderProduct->isDirty()) {
                    $head='';
                    $tenderProduct->syncChanges();
                    foreach ($tenderProduct->getChanges() as $key => $value) {
                        if($tenderProduct->getOriginal($key)!=$value) {
                        $count_changes++;
                        $head=trans('site::tender.message.product', ['product' => $tenderProduct->product->name]);
                        $changes[] = trans('site::tender.message.item', [
                                'column' => trans('site::tender.' . $key) . trans('site::tender.message.columns.' . $key),
                                'original' => $tenderProduct->getOriginal($key),
                                'change' => $value,
                            ]);
                        }
                    }
                    if(!empty($changes)){
                    $text = $text."\r\n\r\n" .$head .implode("\r\n", $changes);
                    $tenderProduct->save();
                    }
                }
            } else {
            $tender->tenderStages()->whereHas('tenderStageProducts', function ($q) use ($tenderProduct) { $q->where('tender_product_id',$tenderProduct->id);})->delete();
            $tenderProduct->delete();
            $text = $text."\r\n\r\n" .trans('site::tender.message.delete', ['product' => $tenderProduct->product->name]);
            $count_changes++;
            }
             
        }
        
        if(!empty($request->input('count'))) {
            foreach($request->input('count') as $key=>$tenderItem)
            {
                if(!$tender->tenderProducts->contains('product_id',$key)) {
                    $tenderProduct=$tender->tenderProducts()->create(['product_id'=>$key, 'count'=>$tenderItem['count'], 'discount'=>$tenderItem['discount']]);
                    $text = $text."\r\n\r\n" .trans('site::tender.message.add', ['product' => $tenderProduct->product->name]);
                    $count_changes++;
                }
            }
        }


        if($count_changes>0 && $tender->status_id!='11') {
            $receiver_id = $request->user()->getKey();
            $tender->messages()->save($request->user()->outbox()->create(['text'=>$text, 'receiver_id'=>$receiver_id, 'personal'=>'1']));
        }
        

        //event(new TenderEditEvent($tender));
        return redirect()->route('tenders.show', $tender)->with('success', trans('site::tender.updated'));
    }


    public function orderStage(Tender $tender, TenderStage $tenderStage)
    {
        
        
        $order_products=[];
        foreach($tenderStage->tenderStageProducts as $tenderProduct) {
            $order_products[$tenderProduct->tenderProduct->product_id]=['product_id'=>$tenderProduct->tenderProduct->product_id,
                                                         'quantity'=>$tenderProduct->count,
                                                         'price'=>round($tenderProduct->tenderProduct->cost,0),
                                                         'currency_id'=>$tenderProduct->tenderProduct->currency_id];
        }
        
        $contragent=$tender->distributor->contragents()->orderByDesc('created_at')->first();
        
        $order = $tender->distributor->orders()->create(['status_id'=>'1','contragent_id'=>$contragent->id,'address_id'=>'1','in_stock_type'=>'0']);
        $order->items()->createMany($order_products);
        $tenderStage->order_id=$order->id;
        $tenderStage->save();
        
        return redirect()->route('tenders.show', $tender)->with('success', 'Заказ для тендера создан');
        
    }
    
    
    public function contragentUpdate(Tender $tender, Request $request){
    
    $tender->distr_contragent_id=$request->input('distr_contragent_id');
    $tender->save();
    return redirect()->route('tenders.show',$tender)->with('success', 'Контрагент обновлен');
    }

    public function status(TenderStatusRequest $request, Tender $tender)
    {   
        $text="Статус изменен: " .$tender->status->name ." => " .TenderStatus::query()->findOrFail($request->input('status_id'))->name;
           $tender->update(['status_id'=>$request->input('status_id')]);
        if($request->input('status_id')=='4') {
            $tender->update(['director_approved_status_id'=>$request->input('status_id'),'director_approved_date'=>Carbon::now()]);
        }elseif($request->input('status_id')=='3') {
            $tender->update(['head_approved_status_id'=>$request->input('status_id'),'head_approved_date'=>Carbon::now()]);
        }elseif($request->input('status_id')=='13') {
            $text.=" (";
            $last_id=$tender->region->managers->last()->id;
            foreach ($tender->region->managers as $manager){
                $text.=$manager->name;
                if ($manager->id!=$last_id) {

                    $text.=", ";
                }

            };
            $text.=")";
        }elseif(in_array($request->input('status_id'),['1','7','9','10'])) {
            $tender->update(['head_approved_status_id'=>$request->input('status_id'),'head_approved_date'=>Carbon::now()]);
            $tender->update(['director_approved_status_id'=>$request->input('status_id'),'director_approved_date'=>Carbon::now()]);
        }
        
        if($request->input('status_id')=='5') {
            $this->order($tender);
        }
        
        
        foreach($tender->tenderProducts as $item) {
            if($request->input('status_id')=='4') {
                $item->update(['approved_status'=>'1','approved_at'=>Carbon::now(),'cost'=>$item->product->retailPriceEur->valueRaw * (100 - $item->discount)/100]);
                           } 
            if(in_array($request->input('status_id'),['1','7','9','10'])) {
                $item->update(['approved_status'=>'0','approved_at'=>Carbon::now(),'cost'=>0]);
                
            } 
        }
        $receiver_id = $request->user()->getKey();
        if(!empty($request->input('message.text'))){
        $text = $text."\r\n\r\n".$request->input('message.text');
        }
        $tender->messages()->save($request->user()->outbox()->create(['text'=>$text, 'receiver_id'=>$receiver_id, 'personal'=>'1']));
        
        $tender=Tender::find($tender->id);
       
        event(new TenderStatusChangeEvent($tender));
        
        return redirect()->route('tenders.show', $tender)->with('success', 'Статус тендера обновлен');
    }
    
    
    private function getTenderProducts(TenderRequest $request, Tender $tender = null)
    {  
        $tenderProducts = collect([]);
       
        if (!is_null($tender)) {
        
            foreach ($tender->tenderProducts as $tenderProduct) {
                
                $tenderProducts->put($tenderProduct->product_id, collect([
                    'product' => $tenderProduct->product,
                    'count'      => $tenderProduct->count,
                    'discount' => $tenderProduct->discount,
                    'discount_object' => $tenderProduct->discount_object,
                    'cost_object' => $tenderProduct->cost_object,
                    'approved' => $tenderProduct->approved
                ]));
            }
        } 
        
        return $tenderProducts;
    }
    
    private function getFiles(TenderRequest $request, Tender $tender = null)
    {
        $files = collect([]);
        $old = $request->old('file');
        if (!is_null($old) && is_array($old)) {
            foreach ($old as $type_id => $values) {
                foreach ($values as $file_id) {
                    $files->push(File::query()->findOrFail($file_id));
                }
            }
        } elseif (!is_null($tender)) {
            $files = $files->merge($tender->files);
        }

        return $files;
    }

    private function setFiles(TenderRequest $request, Tender $tender)
    {
        $old_files=$tender->files;
        $tender->detachFiles();
        
        if ($request->filled('file')) {
            foreach ($request->input('file') as $type_id => $values) {
                foreach ($values as $file_id) {
                    $tender->files()->save(File::find($file_id));
                }
            }
        }
       
        return $tender->files;
        //$this->files->deleteLostFiles();
    }

    /**
     * @param \ServiceBoiler\Prf\Site\Http\Requests\MessageRequest $request
     * @param \ServiceBoiler\Prf\Site\Models\Tender $tender
     * @return \Illuminate\Http\JsonResponse
     */
    public function message(MessageRequest $request, Tender $tender)
    {   
        //event(new TenderMessageEvent($tender));
        return $this->storeMessage($request, $tender);
    }
    
    private function customerInGroupExists($item, $customer_group) {
    
        foreach($customer_group as $customer){
            if($customer['customer']['id']==$item->id) {
                
                return true;
            }
        }
        
     return false;           
    
    }

}