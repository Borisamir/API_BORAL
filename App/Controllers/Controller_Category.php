<?php 


namespace App\Controllers;

use App\Services\Service_Category;




class Controller_Category{

    

    public function getAllCategories(){

        $categorias=(new Service_Category())->getAllCategories();

        return json_encode($categorias);
    }

    public function getCategoryById($id){

        $categoria=(new Service_Category())->getCategoryById($id);

        return json_encode($categoria);
    }
}








?>