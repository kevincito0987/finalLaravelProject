<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // Nombre de la tabla según el diagrama: 'categories'
    protected $fillable = ['category_name'];
    protected $primaryKey = 'category_id'; 
    public $timestamps = false; 
}