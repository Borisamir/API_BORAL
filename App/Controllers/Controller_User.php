<?php 
namespace App\Controllers;

use App\Services\Service_User;
use App\Validators\UserValidator;
use Exception;





class Controller_User{

     public function obtain_user(){

         try{

             $data = (new Service_User())->obtain_user();

             return json_encode([
                 'state' => true,
                 'data' => $data,
             ]);

         }catch(Exception $e){
             
            return json_encode([
                'state' => false,
                'error' => $e->getMessage()
            ]);
         }

     }
    
     public function login_user(){

        try{
            $data = json_decode(file_get_contents("php://input"), true);

            $user_validator=UserValidator::forLogin($data);

            if($user_validator !== true){
               return $user_validator;
            }

           (new Service_User()->login_user($data));

           return json_encode( [
               'state' => true,
               'mensaje'=> 'Login correcto' 
           ]);

        }catch(Exception $e){

           return json_encode( [
               'state' => false,
               'error'=> $e->getMessage()
           ]);

        }

        
     }

     public function logout_user(){

         try{

             (new Service_User())->logout_user();

             return json_encode([
                 'state' => true,
                 'mensaje' => 'Cierre de sesion exitoso'

             ]);

         }catch(Exception $e){
             
            return json_encode([
                'state' => false,
                'error' => $e->getMessage()
            ]);
         }
     }


     public function create_user($data){
          (new Service_User()->create_user($data));

          return json_encode([
               'mensaje' => 'Usuario creado correctamente'
          ]);
     }

     public function delete_user($id){
          (new Service_User()->delete_user($id));

          return json_encode([
               'mensaje' => 'Usuario eliminado correctamente'
          ]);
     }

}






?>