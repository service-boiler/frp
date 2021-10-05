<?php

namespace ServiceBoiler\Prf\Site\Models;

use Illuminate\Database\Eloquent\Model;

class RegionBizDistrict extends Model
{
    public $timestamps = false;
    protected $table = 'region_biz_districts';


    protected $fillable = [
        'name','sort_order'
    ];

    public function regions()
    {
        return $this->hasMany(Region::class,'biz_district_id');
    }
    public function users()
    {
        $manual = $this->belongsToMany(
            User::class,
            'user_biz_region_relations',
            'biz_district_id',
            'user_id'
             )->get();
        $def = User::whereIn('region_id', $this->regions()->pluck('id'))->get();
        return $def->merge($manual);

    }

    public function manualUsers()
    {
        return $this->belongsToMany(
            User::class,
            'user_biz_region_relations',
            'biz_district_id',
            'user_id'
             );

    }
    public function managers()
    {
        return User::whereHas('ferroliManagerRegions', function ($q) {$q->whereIn('regions.id', $this->regions()->pluck('id'));});

    }



    public function distributors()
    {
        $manual = $this->belongsToMany(
            User::class,
            'user_biz_region_relations',
            'biz_district_id',
            'user_id'
             )->whereHas('roles', function ($q){$q->whereIn('name',config('site.roles_distr'));})->get();
        $def = User::whereIn('region_id', $this->regions()->pluck('id'))
            ->whereHas('roles', function ($q){$q->whereIn('name',config('site.roles_distr'));})->get();
        $rel = UserBizRegionRelation::pluck('user_id')->toArray();
        $filtred=$def->reject(function ($value, $key) use ($rel){
            return in_array($value->id,$rel);
        });

        return $filtred->merge($manual);

    }

}
