<?php 

namespace App\Validators;

use Respect\Validation\Validator as v;

class UserValidator{
     
    
    
    public static function forLogin(array $data){
         
        $validator=v::key('name_user' , v::stringType()->notEmpty()->length(0,100))
        ->key('password' , v::stringType()->notEmpty()->length(0,100));

        if(!$validator->validate($data)){
                http_response_code(400);
                return json_encode([
                    'state' => false,
                    'error' => 'Datos invalidos login'
                ]);
            }
        return true;

     
    }

}











?>