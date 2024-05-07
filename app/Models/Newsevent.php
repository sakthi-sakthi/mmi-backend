<?php

namespace App\Models;

use App\Traits\CommonTraits;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Newsevent extends Model
{
    use HasFactory, SoftDeletes, CommonTraits;

    protected $fillable = [
        'category_id',
        'media_id',
        'file_id',
        'status',
        'title',
        'content',
        'eventdate',
    ];

    public function getCategory()
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function getMedia()
    {
        return $this->hasOne(Media::class, 'id', 'media_id');
    }
}
