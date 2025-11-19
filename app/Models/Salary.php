<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Salary extends Model
{
    protected $fillable = [
        'employee_id', 
        'salary', 
        'pf', 
        'leave_deduction', 
        'net_salary',   
        'month',
    ];

    public function employee() {
        return $this->belongsTo(Employees::class, 'employee_id');
    }
}