<?php 
namespace App\Services;
use App\Models\Auditory;


class Service_Auditory {
    
    public static function create_new_auditory($data){
        try{

            (new Auditory())->create($data);

        }catch(\Exception  $e){
            error_log($e);
            throw new \Exception('Ocurrio un error');

        }
    }

}
















?>