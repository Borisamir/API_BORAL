<?php

namespace App\Controllers;

use App\Services\Service_Products;

use App\Validators\ProductsValidator;

class Controller_Products{

      public function createProduct(){
         try{

            $data = json_decode(file_get_contents("php://input"), true);

            $validator=ProductsValidator::forCreate($data);
            if($validator !== true){
               return $validator;
            }

            $product=(new Service_Products())->createProduct($data);

            return json_encode([
               'state' => true,
               'mensaje' => "Se ha creado el Producto con exito",
               'id_producto' => $product->id_producto

            ]);

            
         }catch(\Exception $e){
            return json_encode([
                   'state' => false,
                   'error' => $e->getMessage()
            ]);
        }

        
           

          
        
      }

      public function deleteProduct(){

         
         try{

              $data = json_decode(file_get_contents("php://input"), true);

              $validator=ProductsValidator::forDelete($data);
              if($validator !== true){
                 return $validator;
              }

             (new Service_Products())->deleteProduct($data);

             return json_encode([
                  'state' => true,
                  'mensaje' => "Se ha eliminado el Producto con exito"
             ]);

            
         }catch(\Exception $e){
            return json_encode([
                   'state' => false,
                   'error' => $e->getMessage()
            ]);
        }


         

         
      }


      public function updateProduct(){

         try{

             $data = json_decode(file_get_contents("php://input"), true);

             $validator=ProductsValidator::forUpdate($data);
             
             if($validator !== true){
                return $validator;
             }

            (new Service_Products())->updateProduct($data);

            return json_encode([
                  'state' => true,
                  'mensaje' => "Se ha actualizado el producto con exito"
             ]);

            
         }catch(\Exception $e){
            return json_encode([
                   'state' => false,
                   'error' => $e->getMessage()
            ]);
        }

         

         

      }

      public function getAllProducts(){
         try{
            
            
            $products=(new Service_Products())->getAllProducts();

            
            return json_encode($products,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

            
         }catch(\Exception $e){
            return json_encode([
                   'error' => $e->getMessage()
            ]);
        }

      }

      public function getallDataProducts(){

         try{
            $count_products=(new Service_Products())->getallProductsCount();

            $count_products_ws=(new Service_Products())->getallproductsWithoutStock();

            return json_encode([
               "cantidad_productos" => $count_products,
               "cantidad_productos_ws" => $count_products_ws,
               "state" => true
            ]);

            
         }catch(\Exception $e){
            return json_encode([
                   'error' => $e->getMessage()
            ]);
        }


      }


      public function getProductById($id){
          try{
            
            $product=(new Service_Products())->getProductById($id);

            return json_encode($product);

            
         }catch(\Exception $e){
            return json_encode([
                   'error' => $e->getMessage()
            ]);
        }

         

         

      }

      


      
    







}
















?>