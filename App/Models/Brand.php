<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;



class Brand extends Model{

    protected $table = 'brand';

    protected $primary_key = 'id_brand';

    public $timestamps = false;

    protected $fillable = [
        'brand'
    ];

}











?>