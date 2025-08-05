<?php
namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class Facciones extends Model{
    protected $table = 'facciones';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = [
        'nombre',
        'descripcion',
        'lider_id',
        'fecha_creacion'];
}