<?php

namespace App\Services;


use App\Models\Products;
use App\Services\Service_Auditory;
use Illuminate\Database\QueryException;



class Service_Products {

      
    public function createProduct($data){
        try{
            $user_id = $data["user_id"];

            unset($data["user_id"]);

            $product = Products::create($data);

            Service_Auditory::create_new_auditory([
                'id_usuario' => $user_id,
                'accion' => 'POST',
                'entidad' => 'Product',
                'entidad_id' => $product->id_producto,
                'exito' => true,
                'detalle' => 'Se creo un Producto : ' . $product->nombre_producto,
                'ip' => $_SERVER['REMOTE_ADDR'],
            ]);

            return $product;
        }catch(QueryException $e){
            error_log($e->getMessage());
            throw new \Exception('Ocurrio un error');
        }catch(\Exception $e){
            error_log($e->getMessage());
            throw new \Exception('Ocurrio un error');
        }

        

    }

    public function deleteProduct($data){
        try{

            $user_id = $data["user_id"];

            unset($data["user_id"]);
            
            $product=Products::find($data["id_producto"]);
            
            $product->delete();

            Service_Auditory::create_new_auditory([
                'id_usuario' => $user_id,
                'accion' => "DELETE",
                'entidad' => 'Product',
                'entidad_id' => null,
                'exito' => true,
                'detalle' => 'Se elimino el Producto : ' . $product->nombre_producto ,
                'ip' => $_SERVER['REMOTE_ADDR'],
            ]);

        }catch(QueryException $e){
            error_log($e->getMessage());
            throw new \Exception('Ocurrio un error');
        }catch(\Exception $e){
            error_log($e->getMessage());
            throw new \Exception('Ocurrio un error');
        }

        

    }

    public function updateProduct($data){
        try{

            $user_id = $data["user_id"];

            unset($data["user_id"]);
            
            $found_product=Products::find($data["id_producto"]);

            unset($data["id_producto"]);

            $found_product->update($data);

            Service_Auditory::create_new_auditory([
                'id_usuario' => $user_id,
                'accion' => "PUT",
                'entidad' => 'Product',
                'entidad_id' => $found_product->id_producto,
                'exito' => true,
                'detalle' => 'Se actualizo un Producto : ' . $found_product->nombre_producto,
                'ip' => $_SERVER['REMOTE_ADDR'],
            ]);
        }catch(QueryException $e){
            error_log($e->getMessage());
            throw new \Exception('Ocurrio un error');
        }catch(\Exception $e){
            error_log($e->getMessage());
            throw new \Exception('Ocurrio un error');
        }

        
    }

    public function getAllProducts(){
        try{
            
            
            $products=Products::all();
            
            
            if($products->isEmpty()){
                throw new \Exception('No devolvio ni un valor');
            };

            
            foreach($products as $product){
                $product->setAttribute('nombre_categoria' , $product->category->categoria);
                $product->setAttribute('estado', $product->state->estado);
                $product->setAttribute('marca' , $product->brand->brand);
            }

            return $products;
        }catch(QueryException $e){
            error_log($e->getMessage());
            throw new \Exception('Ocurrio un error');
        }catch(\Exception $e){
            error_log($e->getMessage());
            throw new \Exception('Ocurrio un error');
        }

        
    }

    public function getProductById($id){

        try{
            
            if(Products::find($id)->isEmpty()){
                throw new \Exception('No devolvio ni un valor');
            }
            return Products::find($id);
        }catch(QueryException $e){
            error_log($e->getMessage());
            throw new \Exception('Ocurrio un error');
        }catch(\Exception $e){
            error_log($e->getMessage());
            throw new \Exception('Ocurrio un error');
        }

        
    }

    public function getallProductsCount(){
        try{
           

           return Products::count();

        }catch(QueryException $e){
            error_log($e->getMessage());
            throw new \Exception('Ocurrio un error');
        }catch(\Exception $e){
            error_log($e->getMessage());
            throw new \Exception('Ocurrio un error');
        }
    }

    public function getallproductsWithoutStock(){

       try{
           
           return Products::where('stock' , 0)->count();
       
       }catch(QueryException $e){
            error_log($e->getMessage());
            throw new \Exception('Ocurrio un error');
        }catch(\Exception $e){
            error_log($e->getMessage());
            throw new \Exception('Ocurrio un error');
        }

    }

    public function actualize_stock($id , $quantity){
        
        try{
            
            $producto=Products::find($id);
            $act_quantity=$producto->stock - $quantity;
            $producto->update(['stock' => $act_quantity]);
        }catch(QueryException $e){
            error_log($e->getMessage());
            throw new \Exception('Ocurrio un error');
        }catch(\Exception $e){
            error_log($e->getMessage());
            throw new \Exception('Ocurrio un error');
        }

    }

    

    
}

?>