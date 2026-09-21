<?php

namespace App\Models;

use App\Models\Sell_Register;

use Illuminate\Database\Eloquent\Model;



class Sell_Items extends Model{

     protected $primaryKey='id_sell_item';

     protected $table="sell_item";

     public $timestamps = false;

     protected $fillable = [
        'quantity',
        'subtotal_sell',
        'subcost_sell',
        'id_sell',
        'id_producto'
     ];

     public function sell_register(){
        return $this->belongsTo(Sell_Register::class , 'id_sell' , 'id_sell');
     }







}