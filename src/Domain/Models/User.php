<?php
namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model{
    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    public $timestamps = false;
    protected $fillable = ['nombre','email','password','rol'];
    protected $hidden = ['password'];
}