<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class SiteHtml extends Model
{

    use SoftDeletes;

    protected $table = 'site_html';

    protected $fillable = [
        'html',
    ];

    public function setHtml($html)
    {
        $this->html = $html;;
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
