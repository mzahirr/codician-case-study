<?php


namespace App\Http\Controllers\Company;


use App\Events\CompanyCreated;
use App\Exceptions\CompanyNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyCreateRequest;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use VerumConsilium\Browsershot\Facades\Screenshot;


class CompanyController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function all()
    {
        $companies = Company::all();

        return response()->json([
            'status' => Response::HTTP_OK,
            'success' => true,
            'data' => [
                'companies' => $companies
            ]
        ], Response::HTTP_OK);
    }

    /**
     * @param CompanyCreateRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CompanyCreateRequest $request)
    {
        $company = new Company();

        $company->setName($request->get('name'));
        $company->setInternetAddress($request->get('internet_address'));

        event(new CompanyCreated($company));

        return response()->json([
            'status' => Response::HTTP_OK,
            'success' => true,
            'data' => [
                'company' => $company,
                'message' => 'Company created',
            ]
        ], Response::HTTP_OK);
    }


    public function detail($companyId, Request $request)
    {
        $company = Company::with(['persons', 'addresses'])->id($companyId)->first();
        if (!$company){
            throw new CompanyNotFoundException($companyId);
        }

        return response()->json([
            'status' => Response::HTTP_OK,
            'success' => true,
            'data' => [
                'company' => $company
            ]
        ], Response::HTTP_OK);

    }

    public function delete($companyId)
    {
        $company = Company::id($companyId)->first();

        if (!$company){
            throw new CompanyNotFoundException($companyId);
        }

        $company->delete();

        return response()->json([
            'status' => Response::HTTP_OK,
            'success' => true,
            'message' => 'Company deleted',
            'data' => [
                'company' => $company
            ]
        ], Response::HTTP_OK);

    }
}
