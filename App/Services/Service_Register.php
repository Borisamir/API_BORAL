<?php 


namespace App\Services;

use App\Middleware\RateLimiter;
use App\Services\Service_Items;
use App\Services\Service_Products;
use App\Models\Sell_Register;
use App\Models\Sell_History;
use App\Services\Service_Auditory;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;



class Service_Register{




    public function create_temporal_register( array $data){

        try{
            

             
            $data['state'] = "Pendiente";

           $registro=Sell_Register::create($data);

           return $registro->id_sell;

        }catch(QueryException $e){
            error_log($e->getMessage());
            throw new \Exception('Ocurrio un error');
        }catch(Exception $e){
            error_log($e->getMessage());
            throw new \Exception('Ocurrio un error');
       }
        
        
         
    }

    public function delete_temporal_register($id){

        try{

            

            Sell_Register::find($id)->delete();

        }catch(QueryException $e){
            error_log($e->getMessage());
            throw new \Exception('Ocurrio un error');
        }catch(Exception $e){
            error_log($e->getMessage());
            throw new \Exception('Ocurrio un error');
       }
        
        
    }


    public function confirm_register($data){

       try{

          $user_id = $data["user_id"];

          unset($data["user_id"]);

          $id=$data['productos'][0]['id_sell'];


          RateLimiter::check('confirm_register',$id,100,'1 minute');


           
          foreach( $data['productos'] as $data){
              (new Service_Items)->create_items($data);
              (new Service_Products)->actualize_stock($data['id_producto'], $data['quantity']);
              $this->verify_sell_history($data['subtotal_sell'], $data['subcost_sell']);
              $this->update_sell_history($data['subtotal_sell'] , $data['subcost_sell']);
          }


          Sell_Register::find($id)->update(['state' => 'Confirmado']);

          Service_Auditory::create_new_auditory([
                'id_usuario' => $user_id,
                'accion' => 'POST',
                'entidad' => 'Register',
                'entidad_id' => $id,
                'exito' => true,
                'detalle' => 'Se confirmo una venta',
                'ip' => $_SERVER['REMOTE_ADDR'],
        ]);



       }catch(QueryException $e){
          error_log($e->getMessage());
          throw new \Exception('Ocurrio un error');
       }catch(Exception $e){
          error_log($e->getMessage());
          throw new \Exception('Ocurrio un error');
       }
       
    }

    public function verify_sell_history($register = 0 , $register_cost = 0){

         try{
                

                $ultimo_registro = Sell_History::latest('id_sell_history')->first();

                if($ultimo_registro === null){
                    $this->create_sell_history($register , $register_cost);
                    return;
                    
                }


                $tiempo_registro = $ultimo_registro->fecha_actual;
                

                if(date('Y-m-d') !== $tiempo_registro){
                    $this->create_sell_history($register , $register_cost);
                    
                }

                


          }catch(QueryException $e){
                error_log($e->getMessage());
                throw new \Exception('Ocurrio un error');
          }catch(\Exception $e){
                error_log($e->getMessage());
                throw new \Exception('Ocurrio un error');
          }
    }

    public function create_sell_history($first_register , $first_cost_register){
        
        try{
            

            Sell_History::create([
                'fecha_actual' => date('Y-m-d'),
                'ingresos_totales_diarios' => $first_register,
                'ventas_totales_diarios' => 0,
                'costos_totales_diarios' => $first_cost_register
            ]);

        }catch(QueryException $e){
            error_log($e->getMessage());
            throw new \Exception('Ocurrio un error');
        }catch(\Exception $e){
            error_log($e->getMessage());
            throw new \Exception('Ocurrio un error');
        }

    }

    public function update_sell_history($new_register , $new_cost_register){

        try{
            

            $actual_sell_history= Sell_History::where([
                'fecha_actual' => date('Y-m-d')
            ])->first();


            $new_actual_register=$actual_sell_history->ingresos_totales_diarios + $new_register;
            $new_cost_register=$actual_sell_history->costos_totales_diarios + $new_cost_register;
            $new_sell_register=$actual_sell_history->ventas_totales_diarios + 1;
            
            $actual_sell_history->update([
                'ingresos_totales_diarios' => $new_actual_register,
                'ventas_totales_diarios' => $new_sell_register,
                'costos_totales_diarios' => $new_cost_register
            ]);

        }catch(QueryException $e){
            error_log($e->getMessage());
            throw new \Exception('Ocurrio un error');
        }catch(\Exception $e){
            error_log($e->getMessage());
            throw new \Exception('Ocurrio un error');
        }


    }

    public function get_register_history(){
        
        try{
            

            $this->verify_sell_history();
            
            return Sell_History::where([
                'fecha_actual' => date('Y-m-d')
            ])->first();


        }catch(QueryException $e){
            error_log($e->getMessage());
            throw new \Exception('Ocurrio un error');
        }catch(\Exception $e){
            error_log($e->getMessage());
            throw new \Exception('Ocurrio un error');
        }
    }

    public function get_stats($data){
        try{
            $history_count= $this->get_count_sell_history_by_days($data);
            $method_pay_counts=$this->get_stats_method_pay();
            $products_filtered=$this->get_last_five_products_by_units_sell();
            $year_count=$this->get_count_sell_history_by_one_year();


            $stats=[
                $history_count,
                $method_pay_counts,
                $products_filtered,
                $year_count
            ];

            return $stats;



        }catch(QueryException $e){
            error_log($e->getMessage());
            throw new \Exception('Ocurrio un error');
        }catch(\Exception $e){
            error_log($e->getMessage());
            throw new \Exception('Ocurrio un error');
        }
    }

    public function get_count_sell_history_by_days($data){
        try{
            

            return Sell_History::where('fecha_actual','>=' , Carbon::now()->subDays($data['dias'])->startOfDay())->get();

        }catch(QueryException $e){
            error_log($e->getMessage());
            throw new \Exception('Ocurrio un error');
        }catch(\Exception $e){
           error_log($e->getMessage());
           throw new \Exception('Ocurrio un error');
        }
    }

    public function get_count_sell_history_by_one_year(){
        try{
            

            return Sell_History::selectRaw(
                   'YEAR(fecha_actual) as año,
                    MONTH(fecha_actual) as mes,
                    SUM(ingresos_totales_diarios) as ingresos,
                   SUM(ventas_totales_diarios) as ventas,
                    SUM(costos_totales_diarios) as costos
                    '
                      )
                   ->where(
                    'fecha_actual',
                     '>=',
                   Carbon::now()->subMonths(12)
                   )
                  ->groupByRaw('YEAR(fecha_actual), MONTH(fecha_actual)')
                  ->orderByRaw('YEAR(fecha_actual), MONTH(fecha_actual)')
                  ->get();;

        }catch(QueryException $e){
            error_log($e->getMessage());
            throw new \Exception('Ocurrio un error');
        }catch(\Exception $e){
            error_log($e->getMessage());
            throw new \Exception('Ocurrio un error');
        }

    }


    public function get_stats_method_pay(){

       try{
           

           $count_yape = Sell_Register::where('pay_method' , '=' , 'Yape')->count();
           $count_efectivo = Sell_Register::where('pay_method' , '=' , 'Efectivo')->count();

           $count=[
               'count_yape' => $count_yape,
               'count_efectivo' => $count_efectivo,
               
           ];

           return $count;
           


       }catch(QueryException $e){
           error_log($e->getMessage());
            throw new \Exception('Ocurrio un error');
       }catch(\Exception $e){
           error_log($e->getMessage());
            throw new \Exception('Ocurrio un error');
       }

    }

    public function get_last_five_products_by_units_sell(){
        try{
           

           return (new Service_Items)->obtain_last_five_products_by_units_sell();

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