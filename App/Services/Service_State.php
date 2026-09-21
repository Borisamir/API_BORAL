<?php 


namespace App\Services;

use App\Models\State;



class Service_State{




    public function getAllStates(){
         return State::all();
    }

    public function getStateById($id){
        return State::find($id);
    }



     
}








?>