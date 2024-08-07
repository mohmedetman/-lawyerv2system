<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryCaseFile extends Model
{
    use HasFactory;
    protected $fillable = [
        'court_en',
        'case_id',
        'user_id',
        'user_name',
        'user_code',
        'user_status_en',
        'enemy_status_en',
        'last_session_en',
        'decision_en',
        'court_ar',
        'user_status_ar',
        'enemy_status_ar',
        'last_session_ar',
        'permission',
        'decision_ar',
        'status',
    ];
}
