<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogKeyword extends Model
{
    use HasFactory;
 protected $fillable = ['keyword_en', 'keyword_ar', 'blog_id'];

    // // Accessor to decode keyword_en attribute from JSON
    // public function getKeywordEnAttribute($value)
    // {
    //     return json_decode($value, true);
    // }

    // // Accessor to decode keyword_ar attribute from JSON
    // public function getKeywordArAttribute($value)
    // {
    //     return json_decode($value, true);
    // }
    protected $casts = [
        'keyword_en' => 'array',
        'keyword_ar' => 'array',
    ];
}
