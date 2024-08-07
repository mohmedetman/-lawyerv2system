<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;
    protected $fillable = [
        'title_en',
        'title_ar',
        'sub_title_en',
        'sub_title_ar',
        'category_en',
        'category_ar',
        'image_path',
        'image_url'
    ];
    
   public function keywords()
    {
        return $this->hasMany(BlogKeyword::class);
    }

    public function sections()
    {
        return $this->hasMany(BlogSection::class);
    }
}
