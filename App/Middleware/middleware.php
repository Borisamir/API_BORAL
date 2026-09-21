<?php 

namespace App\Middleware;

use Exception;
use App\Models\RevokedToken;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;


class Middleware implements IMiddleware{



     public function handle(Request $request): void{
          Middleware::verify_jwt();


     }



     private static function verify_jwt():void{

       

       try{

           $token =$_COOKIE['jwt'] ?? null;

           if(!$token){
                 http_response_code(401);
                 throw new Exception('Token inexistente');
            
            }

           $user=JWT::decode(
               $token,
              new Key($_ENV['jwt'] ,'HS256' )
              );


           if(isset($user->jti) && RevokedToken::find($user->jti) !== null){
               http_response_code(401);
               throw new Exception("Sesion cerrada, vuelve a iniciar sesion");
           }


           if($user->role !== "admin"){
               http_response_code(401);
               throw new Exception("No tiene permitido hacer esta accion");
           }

       }catch(Exception $e){
            
          http_response_code(401);

          throw $e;
       }

     }

}








?>