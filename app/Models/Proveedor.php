<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'ap_pat',
        'ap_mat',
        'dni',
        'email',
    ];

    public function valets()
    {
        return $this->hasMany(Valet::class);
    }

    public function getFullNameAttribute()
    {
        return $this->nombre . ' ' . $this->ap_pat . ' ' . $this->ap_mat;
    }
}
