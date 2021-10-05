<?php

namespace ServiceBoiler\Prf\Site\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;


use ServiceBoiler\Prf\Site\Imports\Excel\DistributorSaleExcel;
use ServiceBoiler\Prf\Site\Imports\Url\DistributorSaleXml;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use ServiceBoiler\Prf\Site\Facades\Site;


class DistributorSale extends Model
{

    /**
     * @var array
     */
    protected $fillable = ['product_id', 'quantity','week_id','year','month','week_of_month'];

    /**
     * @var bool
     */
    protected $casts = [
        'product_id' => 'string',
        'quantity' => 'integer',
        'week_id' => 'integer',
    ];
    
    protected static function boot()
	{
	
	}

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
     
     public function week()
    {
        return $this->belongsTo(DistributorSaleWeek::class);
    }
    
   
   
   public function salesMonth()
   {
        return $this;
   }
   
    
    public function updateFromExcel(UploadedFile $path, User $user, DistributorSaleWeek $week)
	{  
		$data = (new DistributorSaleExcel())->get($path);
        
        foreach ($data as $item) {
            $sales[] = array_merge($item, ['week_id'=>$week->id, 'month'=>$week->month, 'year'=>$week->year, 'week_of_month'=>$week->number_in_month]);
        }
        
        $user->distributorSales()->where('week_id',$week->id)->delete();
       
		$user->distributorSales()->createMany($sales);
        
		return $this->update(['uploaded_at' => date('d.m.Y H:i:s')]);
	}
    
    public function updateFromUrl(array $params = [])
	{
            
		try {
			$params['user']->distributorSaleUrl->update(['tried_at' => now()]);
            
			$distributorSaleXml = new DistributorSaleXml($params['user']->distributorSaleUrl->url);
            $distributorSaleXml->import();
            
            $min_week = DistributorSaleWeek::query()->where('date_from','>',Carbon::now()->subDay(29))->orderBy('date_from')->first()->id;

			if ($distributorSaleXml->values()->isNotEmpty()) {
				$sales_flat = collect();
                $sales = collect();
                foreach($distributorSaleXml->values() as $value){
                    
                    $week_id=DistributorSaleWeek::where('date_from','<=', $value['date_sale']->format('Y-m-d'))->where('date_to','>=', $value['date_sale']->format('Y-m-d'))->first()->id;
                    $sales_flat = $sales_flat->merge([$week_id=>['week_id' => $week_id, 'product_id' => $value['product_id'], 'quantity'=>$value['quantity']]]);
                    
                }
                
                foreach($sales_flat->groupBy(['week_id']) as $key=>$sales_week) {
                    $week=DistributorSaleWeek::find($key);
                    foreach($sales_week->groupBy(['product_id']) as $pk=>$sales_week_product){
                        if($key>=$min_week){
                            $sales=$sales->merge([$key => ['week_id'=>$key, 'month'=>$week->month, 'year'=>$week->year, 'week_of_month'=>$week->number_in_month,
                                                            'product_id' => $pk , 
                                                            'quantity'=>$sales_week_product->sum('quantity')]]);
                        }
                    } 
                }
                    
                $params['user']->distributorSales()->whereIn('week_id',$sales->pluck('week_id')->toArray())->delete();
                
                $params['user']->distributorSales()->createMany($sales->toArray());
                
                
			}

			if (key_exists('log', $params) && $params['log'] === true && $distributorSaleXml->errors()->isNotEmpty()) {
				$this->createLog($distributorSaleXml->errors()->toJson(JSON_UNESCAPED_UNICODE));
			}
            
            

		} catch (\Exception $e) {
			$this->failed($e);
		} finally {
			//$this->update(['tried_at' => null]);
		}

	}

    
	public static function uploadRequired()
	{   
        return self::query()
			->whereHas('user', function ($query) {
                                $query->whereHas('distributorSaleUrl', function ($query) {
                                            $query->whereNull('tried_at')->orWhereDate('tried_at', '<', Carbon::today()->toDateString());
                                        });
                                $query->whereHas('distributorSaleUrl', function ($query) {
                                            $query->where('enabled',1);
                                        });
                            }
            );
	}
    
    
    public function hasLatestLogErrors()
	{

		return $this->logs()->exists() && (
				$this->logs()->where(
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
		return $this->hasMany(DistributorSaleLog::class, 'user_id')->latest();
	}

	public function createLog($message)
	{
		$this->user->distributorSaleLogs()->create([
			'type' => DistributorSaleLog::TYPE_ERROR,
			'url' => $this->user->distributorSaleUrl->url,
			'message' => $message,
		]);


	}
    
}
