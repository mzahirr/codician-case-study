<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Person extends Model
{

    use SoftDeletes;

    protected $table = 'persons';

    protected $fillable = [
        'name',
        'last_name',
        'title',
        'email',
        'phone_number'
    ];

    public function setName($name)
    {
        $this->name = $name;;
    }

    public function setLastName($lastName)
    {
        $this->last_name = $lastName;;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPhoneNumber($phoneNumber)
    {
        $this->phone_number = $phoneNumber;
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
