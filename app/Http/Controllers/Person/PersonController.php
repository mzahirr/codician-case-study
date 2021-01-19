<?php


namespace App\Http\Controllers\Person;


use App\Events\PersonCreated;
use App\Exceptions\CompanyNotFoundException;
use App\Exceptions\PersonNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\PersonCreateRequest;
use App\Models\Company;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PersonController extends Controller
{

    /**
     * @param PersonCreateRequest $request
     * @param $companyId
     * @return \Illuminate\Http\JsonResponse
     * @throws CompanyNotFoundException
     */
    public function create(PersonCreateRequest $request, int $companyId)
    {

        $company = Company::id($companyId)->first();

        if (!$company){
            throw new CompanyNotFoundException($request->get('company_id'));
        }

        $person = new Person();

        $person->setName($request->get('name'));
        $person->setLastName($request->get('last_name'));
        $person->setTitle($request->get('title'));
        $person->setEmail($request->get('email'));
        $person->setPhoneNumber($request->get('phone_number'));
        $person->setCompanyId($company->id);

        event(new PersonCreated($person,$company));

        return response()->json([
            'status' => Response::HTTP_OK,
            'success' => true,
            'data' => [
                'person' => $person,
                'message' => 'Company created',
            ]
        ], Response::HTTP_OK);
    }

    public function delete($id){

        $person = Person::id($id)->first();

        if (!$person){
            throw new PersonNotFoundException($id);
        }

        $person->delete();

        return response()->json([
            'status' => Response::HTTP_OK,
            'success' => true,
            'message' => 'Person deleted',
            'data' => [
                'company' => $person
            ]
        ], Response::HTTP_OK);
    }
}
