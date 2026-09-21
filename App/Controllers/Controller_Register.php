<?php 

namespace App\Controllers;

use App\Services\Service_Register;
use App\Validators\RegisterValidator;
use Exception;

class Controller_Register{


     public function create_temporal_register(){

         try{

            $data=json_decode(file_get_contents("php://input"), true);

            $validator=RegisterValidator::for_create_temporal_register($data);
            if($validator !== true){
                return $validator;
            }

            $id=(new Service_Register)->create_temporal_register($data);

            return json_encode([
                'state' => true,
                'id_sell' => $id
            ]);
         }catch(Exception $e){
            return json_encode([
                'state' => false,
                'mensaje' => $e->getMessage()
            ]);
         }
     }


     public function delete_temporal_register(){
        try{

            $data=json_decode(file_get_contents("php://input"), true);

            $validator=RegisterValidator::for_delete_temporal_register($data);
            if($validator !== true){
                return $validator;
            }

            (new Service_Register())->delete_temporal_register($data['id_sell']);
        }catch(Exception $e){
            return json_encode([
                'state' => false,
                'mensaje' => $e->getMessage()
            ]);
         }
     }

     public function confirm_register(){
        try{
            $data=json_decode(file_get_contents("php://input"), true);

            foreach($data['productos'] as $producto){
                $validator=RegisterValidator::for_confirm_register($producto);
                if($validator !== true){
                    return $validator;
                }
            }

            (new Service_Register())->confirm_register($data);

            return json_encode([
                'state' => true,
            ]);

        }catch(Exception $e){
            return json_encode([
                'state' => false,
                'mensaje' => $e->getMessage()
            ]);
         }

           
        
     }

    public function get_register_history(){
        try{

            $history=(new Service_Register())->get_register_history();


            return json_encode([
                'state' => true,
                'ventas_hoy' => $history->ventas_totales_diarios,
                'ingresos_totales_hoy' => $history->ingresos_totales_diarios
            ]);

        }catch(Exception $e){
            return json_encode([
                'state' => false,
                'mensaje' => $e->getMessage()
            ]);
         }

    }

    public function get_stats(){
        try{
            $data = json_decode(file_get_contents("php://input"), true);

            $stats=(new Service_Register())->get_stats($data);

            return json_encode([
                'state' => true,
                'stats' => $stats,
            ]);

        }catch(Exception $e){
            return json_encode([
                'state' => false,
                'mensaje' => $e->getMessage()
            ]);
         }

    }

}



















?>