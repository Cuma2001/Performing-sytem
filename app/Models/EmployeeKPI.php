<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeKPI extends Model
{
    protected $table = 'employee_k_p_i_s'; // match your DB table if needed

    protected $fillable = [
        'user_id',
        'kpi',
        'score',
        'remarks',
    ];
}