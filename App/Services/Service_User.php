<?php 

namespace App\Services;

use App\Models\User;
use App\Models\RevokedToken;
use App\Middleware\RateLimiter;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;







class Service_User {




       public function create_user($data){
          
          $password=password_hash($data['password_hash'],PASSWORD_DEFAULT);

          $data['password_hash']=$password;

          User::create($data);

       }


       public function obtain_user(){
           
          try{
              
             $token = $_COOKIE['jwt'] ?? null ;

             if($token){

                 $decoded = JWT::decode($token, new Key($_ENV['jwt'], 'HS256'));

                 $data = [
                    'user_id' => $decoded->user_id,
                    'user' => $decoded->user,
                    'role' => $decoded->role,
                 ];

                 return $data;
              }

             setcookie('jwt', '', [
                 'expires' => time() - 3600,
                 'path' => '/',
                 'domain' => $_ENV['COOKIE_DOMAIN'] ?? '',
                 'secure' => true,
                 'httponly' => true,
                 'samesite' => 'Lax'
              ]);
              throw new Exception('Usuario no identificado');
          }catch(Exception $e){
              error_log($e->getMessage());
              throw new \Exception($e->getMessage());

          }
       }


       public function login_user($data){
         try{

             $name_user = $data['name_user'] ?? 'unknown';

             RateLimiter::check('login_user', $name_user, 5, '5 minutes');

             $usuario = User::where('name_user' , $data['name_user'])->first();

             if(!$usuario){
                throw new Exception('Usuario no existente');
             }

             if(password_verify($data['password'],$usuario->password_hash)){

                $exp = time() + 3600;

                $payload=[
                   'user_id' => $usuario->id_usuario,
                   'user' => $usuario->name_user,
                   'role' => $usuario->role,
                   'jti' => bin2hex(random_bytes(16)),
                   'exp' => $exp
                ];

                $token=JWT::encode($payload,$_ENV['jwt'],'HS256');

                 setcookie(
                 'jwt',$token,[
                    'expires'=> time() + 3600,
                    'path' => '/',
                 'domain' => $_ENV['COOKIE_DOMAIN'] ?? '',
                    'secure' => true,
                    'httponly' => true,
                    'samesite' => 'Lax'
                 ]
                 );

                return;
                }
            throw new Exception('Contraseña incorrecta');
         }catch(Exception $e){
            error_log($e->getMessage());
            throw new \Exception($e->getMessage());
         }
         

        }

        public function logout_user(){

           try{

              $token = $_COOKIE['jwt'] ?? null;

              if($token){

                 $decoded = JWT::decode($token, new Key($_ENV['jwt'], 'HS256'));

                 RevokedToken::firstOrCreate(
                    ['jti' => $decoded->jti],
                    ['expires_at' => date('Y-m-d H:i:s', $decoded->exp)]
                 );
              }

              setcookie('jwt', '', [
                 'expires' => time() - 3600,
                 'path' => '/',
                 'domain' => $_ENV['COOKIE_DOMAIN'] ?? '',
                 'secure' => true,
                 'httponly' => true,
                 'samesite' => 'Lax'
              ]);

           }catch(Exception $e){
              
              setcookie('jwt', '', [
                 'expires' => time() - 3600,
                 'path' => '/',
                 'domain' => $_ENV['COOKIE_DOMAIN'] ?? '',
                 'secure' => true,
                 'httponly' => true,
                 'samesite' => 'Lax'
              ]);

               throw new Exception('Sesion no valida');
           }

        }

        public function delete_user($id){

           try{
              User::find($id)->delete();
           }catch(Exception $e){
               error_log($e->getMessage());
               throw new \Exception($e->getMessage());
              
           }
           

        }


        public function edit_user($id,$data){

           User::find($id)->update($data);
        }

         

          
           



       }















?>