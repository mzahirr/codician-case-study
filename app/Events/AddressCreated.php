<?php

namespace App\Events;

use App\Models\Address;
use App\Models\Company;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AddressCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $company = null;
    private $address = null;

    /**
     * PersonCreated constructor.
     * @param Address $person
     * @param Company $company
     */
    public function __construct(Address $person, Company $company)
    {
        $this->address = $person;
        $this->company = $company;
    }

    /**
     * @return Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return Company
     */
    public function getCompany()
    {
        return $this->company;
    }
}
