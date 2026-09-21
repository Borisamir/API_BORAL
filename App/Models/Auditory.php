<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;



class Auditory extends Model{

    protected $primary_key='id_audit';

    protected $table='auditory';

    const UPDATED_AT=null;

    protected $fillable=[
        'id_usuario',
        'accion',
        'entidad',
        'entidad_id',
        'exito',
        'detalle',
        'ip',
        'created_at'
    ];

    public function user(){
        return $this->belongsTo(User::class,'id_usuario','id_usuario');
    }

   




}











?>


