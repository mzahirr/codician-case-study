<?php

namespace App\Events;

use App\Models\Company;
use App\Models\Person;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PersonCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $company = null;
    private $person = null;

    /**
     * PersonCreated constructor.
     * @param Person $person
     * @param Company $company
     */
    public function __construct(Person $person, Company $company)
    {
        $this->person = $person;
        $this->company = $company;
    }

    /**
     * @return Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * @return Company
     */
    public function getCompany()
    {
        return $this->company;
    }
}
