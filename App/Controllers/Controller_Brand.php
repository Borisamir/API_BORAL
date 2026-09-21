<?php 
namespace App\Controllers;
use App\Models\Brand;



class Controller_Brand{


    public function getallbrands(){

        return json_encode((new Brand())::all());

    }
}








?> 