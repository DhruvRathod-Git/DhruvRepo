<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employees extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'employees';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'image',
        'documents',
        'experiences',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

     public function attendance(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function leave(): HasMany
    {
        return $this->hasMany(Leave::class);
    }   

     public function salaries()
        {
            return $this->hasMany(Salary::class);
        }
}
