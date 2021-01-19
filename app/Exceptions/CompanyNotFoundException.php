<?php


namespace App\Exceptions;


use Illuminate\Http\Response;

class CompanyNotFoundException extends \Exception
{

    private $companyId = null;

    public function __construct($companyId)
    {
        $this->companyId = $companyId;
    }


    public function render(){
        return response()->json(
            [
                'status' => Response::HTTP_NOT_FOUND,
                'success' => false,
                'data' => [
                    'message' => "Company Not Found With Id : " . $this->companyId
                ]
            ], Response::HTTP_NOT_FOUND
        );
    }
}
