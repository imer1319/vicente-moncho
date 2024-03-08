<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValeItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'valet_id',
        'cantidad',
        'importe'
    ];

    public function valet()
    {
        return $this->belongsTo(Valet::class);
    }
}
