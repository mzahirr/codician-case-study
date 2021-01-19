<?php

namespace App\Listeners\Company;

use App\Events\AddressCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AddAddressInDb
{

    public function __construct()
    {

    }

    /**
     * @param AddressCreated $event
     */
    public function handle(AddressCreated $event)
    {
        try{
            $event->getCompany()
                ->addresses()
                ->save($event->getAddress());
        }catch (\Throwable $t){
            syslog(LOG_INFO, $t->getMessage());
        }
    }
}
