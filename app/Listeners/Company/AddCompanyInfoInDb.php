<?php

namespace App\Listeners\Company;

use App\Events\CompanyCreated;
use App\Models\Company;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AddCompanyInfoInDb
{

    public function __construct()
    {

    }

    /**
     * @param CompanyCreated $event
     */
    public function handle(CompanyCreated $event)
    {
        try{
            $event->getCompany()->save();
        }catch (\Throwable $t){
            syslog(LOG_INFO, $t->getMessage());
        }
    }
}
