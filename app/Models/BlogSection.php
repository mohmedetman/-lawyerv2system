<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogSection extends Model
{
    use HasFactory;
    protected $fillable = [
        'title_en',
        'title_ar',
        'text_en',
        'text_ar',
        'image_path',
        'image_url',
        'blog_id'
    ];
    
     public function subsections()
    {
        return $this->hasMany(BlogSubSection::class ,'section_id' , 'id');
    }

}
