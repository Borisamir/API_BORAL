<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;




class User extends Model{


    protected $table = 'user';
    protected $primaryKey = 'id_estado';

    public $timestamps = false;

    protected $fillable = [
        'name_user',
        'password_hash',
        'role'
        
    ];



    
}











?>