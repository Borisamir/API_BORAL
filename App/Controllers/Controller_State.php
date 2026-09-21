<?php

namespace App\Controllers;

use App\Services\Service_State;



class Controller_State{




    public function getAllStates(){

        $states=(new Service_State())->getAllStates();

        return json_encode($states);
    }

    public function getStateById($id){

        $state=(new Service_State())->getStateById($id);
    
        return json_encode($state) ;
    }

}















?>