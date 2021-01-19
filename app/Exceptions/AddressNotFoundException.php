<?php


namespace App\Exceptions;


use Illuminate\Http\Response;

class AddressNotFoundException extends \Exception
{

    private $id = null;

    public function __construct($id)
    {
        $this->id = $id;
    }


    public function render(){
        return response()->json(
            [
                'status' => Response::HTTP_NOT_FOUND,
                'success' => false,
                'data' => [
                    'message' => "Address Not Found With Id : " . $this->id
                ]
            ], Response::HTTP_NOT_FOUND
        );
    }
}
