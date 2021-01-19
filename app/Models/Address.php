<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Address extends Model
{

    use SoftDeletes;

    protected $table = 'addresses';

    protected $fillable = [
        'latitude',
        'longitude'
    ];

    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;;
    }

    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;;
    }

    public function setCompanyId($companyId)
    {
        $this->company_id = $companyId;
    }

    public function scopeId($query, $id)
    {
        return $query->where('id', $id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company(){
        return $this->belongsTo('\App\Models\Company', 'id', 'company_id');
    }



}
