<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use ServiceBoiler\Prf\Site\Http\Requests\StorehouseRequest;
use ServiceBoiler\Prf\Site\Imports\Url\StorehouseExcel;
use ServiceBoiler\Prf\Site\Imports\Url\StorehouseXml;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Storehouse extends Model
{

	/**
	 * @var array
	 */
	protected $fillable = ['name', 'url', 'enabled', 'everyday', 'uploaded_at', 'tried_at'];

	/**
	 * @var bool
	 */
	protected $casts = [
		'user_id' => 'integer',
		'name' => 'string',
		'url' => 'string',
		'enabled' => 'boolean',
		'everyday' => 'boolean',
		'uploaded_at' => 'datetime:Y-m-d H:i:s',
	];

	/**
	 * @param $value
	 */
	public function setUploadedAtAttribute($value)
	{
		$this->attributes['uploaded_at'] = $value ? Carbon::createFromFormat('d.m.Y H:i:s', $value) : null;
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo(User::class);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function addresses()
	{
		return $this->hasMany(Address::class);
	}

	/**
	 * @param StorehouseRequest $request
	 *
	 * @return $this
	 */
	public function attachAddresses(StorehouseRequest $request)
	{
		if ($request->filled('addresses')) {
			Address::query()->findMany($request->input('addresses'))->each(function ($address) {
				$address->update(['storehouse_id' => $this->getKey()]);
			});
		}

		return $this;
	}

	/**
	 * Обновить остатки из файла
	 *
	 * @param array $params
	 */
	public function updateFromUrl(array $params = [])
	{

		try {
			$this->update(['tried_at' => now()]);
			$storehouseXml = new StorehouseXml($this->getAttribute('url'));
            
			$storehouseXml->import();

			if ($storehouseXml->values()->isNotEmpty()) {
				$this->products()->delete();
				$this->products()->createMany($storehouseXml->values()->toArray());
				$this->update(['uploaded_at' => date('d.m.Y H:i:s')]);
			}

			if (key_exists('log', $params) && $params['log'] === true && $storehouseXml->errors()->isNotEmpty()) {
				$this->createLog($storehouseXml->errors()->toJson(JSON_UNESCAPED_UNICODE));
			}

		} catch (\Exception $e) {
			//$this->failed($e);
		} finally {
			$this->update(['tried_at' => null]);
		}

	}

	/**
	 * @param array $data
	 *
	 * @return bool
	 */
	public function updateFromArray(array $data)
	{
		$products = [];
		foreach ($data as $product_id => $quantity) {
			array_push($products, compact('product_id', 'quantity'));
		}
		$this->products()->delete();
		$this->products()->createMany($products);

		return $this->update(['uploaded_at' => date('d.m.Y H:i:s')]);
	}

	/**
	 * @param UploadedFile $path
	 *
	 * @return bool
	 * @throws \PhpOffice\PhpSpreadsheet\Exception
	 * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
	 */
	public function updateFromExcel(UploadedFile $path)
	{
		$data = (new StorehouseExcel())->get($path);
		$this->products()->delete();
		$this->products()->createMany($data);

		return $this->update(['uploaded_at' => date('d.m.Y H:i:s')]);
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public static function uploadRequired()
	{
		return self::query()
			->whereNotNull('url')
			->where('enabled', 1)
			->where('everyday', 1)
			->whereNull('tried_at')
			->where(function ($query) {
				$query
					->whereNull('uploaded_at')
					->orWhereDate('uploaded_at', '<', Carbon::today()->toDateString());
			});
	}


	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function products()
	{
		return $this->hasMany(StorehouseProduct::class, 'storehouse_id');
	}
	
    public function itemProducts()
	{
		return $this->belongsToMany(Product::class,'storehouse_products','storehouse_id','product_id');
	}

	/**
	 * @return bool
	 */
	public function hasLatestLogErrors()
	{

		return $this->logs()->exists() && (
				is_null($this->getAttribute('uploaded_at'))
				|| $this->logs()->where(
					'created_at',
					'>=',
					$this->getAttribute('uploaded_at')->toDateTimeString()
				)->exists());
	}

	public function latestLog()
	{
		return $this->logs()->latest()->first();
	}

	/**
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function logs()
	{
		return $this->hasMany(StorehouseLog::class, 'storehouse_id')->latest();
	}

	public function createLog($message)
	{
		$this->logs()->create([
			'type' => StorehouseLog::TYPE_ERROR,
			'url' => $this->getAttribute('url'),
			'message' => $message,
		]);


	}
    
    public function cost_products()
	{
        return $this->products()->get()->sum('cost_product');
	}
    
	public function getTotalCostProductsAttribute()
	{
		
		return $this->cost_products();
	}
    
    public function cost_products_type($group_type_id)
	{
        return $this->products()->whereHas('product',  function ($query) use ($group_type_id) {
                $query->leftjoin('product_groups','products.group_id','=','product_groups.id')->where('product_groups.type_id',$group_type_id);
                    
            })->get()->sum('cost_product');
	}
    
	public function getTotalCostProductsZipAttribute()
	{
		return $this->cost_products_type(2);
        
	}
	public function getTotalCostProductsEquipmentAttribute()
	{
		return $this->cost_products_type(1);
        
	}
	public function getTotalCostProductsAccessoriesAttribute()
	{
		return $this->cost_products_type(3);
        
	}
    
	public function getTotalCostProductsOthersAttribute()
	{
		return $this->cost_products_type(4);
        
	}
	
    
    public function cost_products_type_to_date($type_id, $date)
	{
        $result=Result::where('object_id',$this->id)->where('type_id',$type_id)->where('date_actual',$date)->first();
        return empty($result) ? 0 : $result->value_int;
        
        
	}
    
    public function getTotalCostProductsAllTodayAttribute()
    {
        $result=Result::where('object_id',$this->id)->where('type_id','storehouse_all_summ')->where('date_actual',Carbon::now()->format('Y-m-d'))->first();
        return empty($result) ? 0 : $result->value_int;
    }
    
    public function getResultTotalCostAllTodayAttribute()
    {
        $result=Result::where('object_id',$this->id)->where('type_id','storehouse_all_summ')->where('date_actual',Carbon::now()->format('Y-m-d'))->first();
        return empty($result) ? 0 : $result->value_int;
    }
    public function getResultTotalCostZipTodayAttribute()
    {
        $result=Result::where('object_id',$this->id)->where('type_id','storehouse_zip_summ')->where('date_actual',Carbon::now()->format('Y-m-d'))->first();
        return empty($result) ? 0 : $result->value_int;
    }
    public function getResultTotalCostEquipmentTodayAttribute()
    {
        $result=Result::where('object_id',$this->id)->where('type_id','storehouse_equipment_summ')->where('date_actual',Carbon::now()->format('Y-m-d'))->first();
        return empty($result) ? 0 : $result->value_int;
    }
    public function getResultTotalCostAccessoriesTodayAttribute()
    {
        $result=Result::where('object_id',$this->id)->where('type_id','storehouse_accessories_summ')->where('date_actual',Carbon::now()->format('Y-m-d'))->first();
        return empty($result) ? 0 : $result->value_int;
    }
    
    public function cost_products_all_storehouses_type_to_date($type_id, $date)
	{
        $result=Result::where('type_id',$type_id)->where('date_actual',$date)->first();
        return empty($result) ? 0 : $result->value_int;
        
        
	}
    
    public function getTotalCostProductsAllToDateAttribute($date)
	{
		return $this->cost_products_type(4);
        
	}

}
