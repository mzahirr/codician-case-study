<?php

namespace App\Listeners\Company;

use App\Events\CompanyCreated;
use App\Jobs\AddSiteToHtmlInDatabase;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SaveHtmlToDatabase
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
            AddSiteToHtmlInDatabase::dispatch($event->getCompany())->queue('job');
        }catch (\Throwable $t){
            syslog(LOG_INFO, $t->getMessage());
        }
    }
}
