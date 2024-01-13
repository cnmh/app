<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
 use Illuminate\Database\Eloquent\Factories\HasFactory;
class CouvertureMedical extends Model
{
    use HasFactory;    public $table = 'couverture_medicals';

    public $fillable = [
        'nom',
        'description'
    ];

    protected $casts = [
        'nom' => 'string',
        'description' => 'string'
    ];

    public static array $rules = [
        'nom' => 'required|string|max:255',
        'description' => 'string|max:255',
        'created_at' => 'nullable',
        'updated_at' => 'nullable'
    ];


}
