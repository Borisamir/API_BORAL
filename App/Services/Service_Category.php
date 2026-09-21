<?php 


namespace App\Services;

use App\Models\Category;



class Service_Category{




    public function getAllCategories(){
         return Category::all();
    }

    public function getCategoryById($id){
        return Category::find($id);
    }



     
}








?>