<?php

namespace App\Listeners\Company;

use App\Events\CompanyCreated;
use App\Events\PersonCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AddPersonInDb
{

    public function __construct()
    {

    }

    /**
     * @param PersonCreated $event
     */
    public function handle(PersonCreated $event)
    {
        try{
            $event->getCompany()
                ->persons()
                ->save($event->getPerson());
        }catch (\Throwable $t){
            syslog(LOG_INFO, $t->getMessage());
        }
    }
}
