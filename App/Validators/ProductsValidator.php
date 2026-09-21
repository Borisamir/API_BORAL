<?php 

namespace App\Validators;

use Respect\Validation\Validator as v;
class ProductsValidator{



      public static function forCreate(array $data){
           $validator=v::key('nombre_producto' , v::stringType()->notEmpty()->length(0,100))
              ->key('precio' , v::numericVal()->positive())
              ->key('stock' , v::intVal()->min(0))
              ->key('id_categoria' , v::intVal())
              ->key('id_estado' , v::intVal())
              ->key('id_brand' , v::intVal())
              ->key('precio_venta' , v::numericVal()->positive());

            if(!$validator->validate($data)){
                http_response_code(400);
                return json_encode([
                    'state' => false,
                    'error' => 'Datos invalidos'
                ]);
            }
        return true;
      }

     public static function forDelete(array $data){
           $validator=v::key('id_producto' , v::intVal()->min(1));

           if(!$validator->validate($data)){
                http_response_code(400);
                return json_encode([
                    'state' => false,
                    'error' => 'Datos invalidos'
                ]);
            }
            return true;

     }

     public static function forUpdate(array $data){
        $validator=v::key('nombre_producto' , v::stringType()->notEmpty()->length(0,100))
              ->key('id_producto' , v::numericVal()->positive())
              ->key('precio' , v::numericVal()->positive())
              ->key('stock' , v::intVal()->min(0))
              ->key('id_categoria' , v::intVal())
              ->key('id_estado' , v::intVal())
              ->key('id_brand' , v::intVal())
              ->key('precio_venta' , v::numericVal()->positive());

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