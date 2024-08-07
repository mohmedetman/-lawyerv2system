<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialContact extends Model
{
    use HasFactory;
    protected $fillable = [
        'facebook_url',
        'instgram_url',
        'twitter_url',
        'whatsapp_url',
        'linkedin_url'
    ];
}
