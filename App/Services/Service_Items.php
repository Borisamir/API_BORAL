<?php 


namespace App\Services;


use App\Models\Sell_Items;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Database\Query\Expression;



class Service_Items{




    public function create_items($data){

        try{


            Sell_Items::create($data);
             
          
        }catch(QueryException $e){
            error_log($e->getMessage());
            throw new \Exception('Ocurrio un error');
        }catch(Exception $e){
            error_log($e->getMessage());
            throw new \Exception('Ocurrio un error');
       }
        
        
         
    }

     public function obtain_last_five_products_by_units_sell(){

       try{
           

           $five_productos= Sell_Items::query()
                  ->join('sell_register as s' , 's.id_sell' , '=' , 'sell_item.id_sell')
                  ->join('products as p' , 'p.id_producto' , '=' , 'sell_item.id_producto')
                  ->select(
                       'p.nombre_producto',
                       new Expression('SUM(sell_item.quantity) as unidades_vendidas')
                  )
                  ->where('s.created_at', '>=', Carbon::now()->subDays(30))
                 ->groupBy('p.id_producto', 'p.nombre_producto')
                 ->orderByDesc('unidades_vendidas')
                 ->limit(5)
                 ->get();

            return $five_productos;
           


       }catch(QueryException $e){
           error_log($e->getMessage());
          throw new \Exception('Ocurrio un error');
       }catch(\Exception $e){
           error_log($e->getMessage());
          throw new \Exception('Ocurrio un error');
       }

    }

    



     
}