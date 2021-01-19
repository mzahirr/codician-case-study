<?php


namespace App\Http\Controllers\Address;


use App\Events\AddressCreated;
use App\Exceptions\AddressNotFoundException;
use App\Exceptions\CompanyNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddressCreateRequest;
use App\Models\Address;
use App\Models\Company;
use Illuminate\Http\Response;

class AddressController extends Controller
{

    /**
     * @param AddressCreateRequest $request
     * @param $companyId
     * @return \Illuminate\Http\JsonResponse
     * @throws CompanyNotFoundException
     */
    public function create(AddressCreateRequest $request, int $companyId)
    {

        $company = Company::id($companyId)->first();

        if (!$company){
            throw new CompanyNotFoundException($request->get('company_id'));
        }

        $address = new Address();

        $address->setLatitude($request->get('latitude'));
        $address->setLongitude($request->get('longitude'));
        $address->setCompanyId($company->id);

        event(new AddressCreated($address,$company));

        return response()->json([
            'status' => Response::HTTP_OK,
            'success' => true,
            'data' => [
                'address' => $address,
                'message' => 'Address created',
            ]
        ], Response::HTTP_OK);
    }

    public function delete($id)
    {
        $address = Address::id($id)->first();

        if (!$address){
            throw new AddressNotFoundException($id);
        }

        $address->delete();

        return response()->json([
            'status' => Response::HTTP_OK,
            'success' => true,
            'message' => 'Address deleted',
            'data' => [
                'company' => $address
            ]
        ], Response::HTTP_OK);
    }
}
