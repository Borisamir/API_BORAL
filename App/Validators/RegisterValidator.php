<?php 

namespace App\Validators;

use Respect\Validation\Validator as v;

class RegisterValidator{



     public static function for_create_temporal_register(array $data){
        $validator=v::key('total_sell' , v::numericVal()->positive())
        ->key('pay_method' , v::stringType()->notEmpty()->length(0,100));
        
        if(!$validator->validate($data)){
            http_response_code(400);
            return json_encode([
                'state' => false,
                'error' => 'Datos invalidos'
            ]);
        }
        return true;
     }

     public static function for_delete_temporal_register(array $data){
        $validator=v::key('id_sell' , v::intVal()->min(1));
        
        if(!$validator->validate($data)){
            http_response_code(400);
            return json_encode([
                'state' => false,
                'error' => 'Datos invalidos'
            ]);
        }
        return true;

     }

     public static function for_confirm_register(array $data){
        $validator=v::key('id_sell' , v::intVal()->min(1))
        ->key('quantity' , v::intVal()->min(1))
        ->key('subtotal_sell' , v::numericVal()->positive())
        ->key('subcost_sell' , v::numericVal()->positive())
        ->key('id_producto' , v::intVal()->min(1));

        if(!$validator->validate($data)){
            http_response_code(400);
            return json_encode([
                'state' => false,
                'error' => 'Datos invalidos'
            ]);
        }
        return true;

     }

    public static function for_stats(array $data){
        $validator=v::key('dias' , v::intVal()->min(1));

        if(!$validator->validate($data)){
            http_response_code(400);
            return json_encode([
                'state' => false,
                'error' => 'Datos invalidos'
            ]);
        }
        return true;


    }

}











?>