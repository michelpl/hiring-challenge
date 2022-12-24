<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Charge extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'government_id',
        'email',
        'debt_amount',
        'debt_due_date',
        'debt_id',
        'paid_at',
        'paid_amount',
        'paid_by'
    ];
}
