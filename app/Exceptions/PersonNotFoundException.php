<?php


namespace App\Exceptions;


use Illuminate\Http\Response;

class PersonNotFoundException extends \Exception
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
                    'message' => "Person Not Found With Id : " . $this->id
                ]
            ], Response::HTTP_NOT_FOUND
        );
    }
}
