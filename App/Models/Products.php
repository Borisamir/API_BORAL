<?php 

namespace App\Models;

use App\Models\Category;
use App\Models\State;
use App\Models\Brand;
use Illuminate\Database\Eloquent\Model;



class Products extends Model {

     protected $primaryKey = 'id_producto';

     public $timestamps = false;

     protected $fillable = [
        'nombre_producto',
        'precio',
        'stock',
        'id_categoria',
        'id_estado',
        'id_brand',
        'precio_venta'
     ];

     public function category(){
         return $this->belongsTo(Category::class,'id_categoria','id_categoria');
     }

     public function state(){
        return $this->belongsTo(State::class,'id_estado','id_estado');
     }

     public function brand(){
       return $this->belongsTo(Brand::class , 'id_brand' , 'id_brand');
     }
}





?>