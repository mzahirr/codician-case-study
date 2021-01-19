<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Company extends Model
{

    use SoftDeletes;

    protected $table = 'companies';

    protected $fillable = [
        'name',
        'internet_address'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
    ];

    public function setName($name)
    {
        $this->name = $name;;
    }

    public function setInternetAddress($url)
    {
        $this->internet_address = $url;;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getInternetAddress()
    {
        return $this->internet_address;
    }

    public function scopeId($query, $id)
    {
        return $query->where('id', $id);
    }

    public function persons()
    {
        return $this->hasMany('\App\Models\Person', 'company_id', 'id')->orderBy('id', 'asc');
    }

    public function addresses()
    {
        return $this->hasMany('\App\Models\Address', 'company_id', 'id')->orderBy('id', 'asc');
    }

}
