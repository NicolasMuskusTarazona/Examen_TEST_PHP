<?php
namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class Miembros extends Model{
    protected $table = 'miembros';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'usuario_id',
        'faccion_id',
        'rango',
        'fecha_ingreso'];
}