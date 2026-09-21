<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class Sell_Register extends Model{

     protected $primaryKey='id_sell';

     protected $table="sell_register";

     public $timestamps = true;

     protected $fillable = [
        'total_sell',
        'pay_method',
        'state'
     ];

     







}








?>