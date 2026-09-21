<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class Category extends Model{

     protected $table = 'category';

     protected $primaryKey = 'id_categoria';

     public $timestamps = false;

    protected $fillable = [
        'categoria'
    ];

    
    
}




?>