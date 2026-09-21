<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RevokedToken extends Model{

     protected $table = 'revoked_tokens';

     protected $primaryKey = 'jti';

     public $incrementing = false;

     protected $keyType = 'string';

     public $timestamps = false;

     protected $fillable = [
        'jti',
        'expires_at'
     ];

}

?>
