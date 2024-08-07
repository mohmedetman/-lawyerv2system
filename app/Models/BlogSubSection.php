<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogSubSection extends Model
{
    use HasFactory;
    protected $fillable = [
        'title_en',
        'title_ar',
        'list_en',
        'list_ar',
        'section_id'
    ];
    protected $casts = [
        'list_en' => 'array',
        'list_ar' => 'array',
    ];
    
    public function section()
    {
        return $this->belongsTo(BlogSection::class,'id', 'section_id');
    }
}
