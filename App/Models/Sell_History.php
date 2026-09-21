<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;




class Sell_History extends Model{
    
     protected $primaryKey='id_sell_history';

     protected $table="sell_history";

     public $timestamps = false;

     protected $fillable = [
        'fecha_actual',
        'ingresos_totales_diarios',
        'ventas_totales_diarios',
        'costos_totales_diarios'
     ];
}




?>