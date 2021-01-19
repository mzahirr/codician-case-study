<?php

namespace App\Providers;

use App\Events\AddressCreated;
use App\Events\CompanyCreated;
use App\Events\PersonCreated;
use App\Listeners\Company\AddAddressInDb;
use App\Listeners\Company\AddCompanyInfoInDb;
use App\Listeners\Company\AddPersonInDb;
use App\Listeners\Company\SaveHtmlToDatabase;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        CompanyCreated::class => [
            AddCompanyInfoInDb::class,
            SaveHtmlToDatabase::class
        ],

        PersonCreated::class => [
            AddPersonInDb::class
        ],

        AddressCreated::class => [
            AddAddressInDb::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
