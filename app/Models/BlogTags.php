<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogTags extends Model {

    use SoftDeletes;


    protected $dates = ['deleted_at'];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */


    protected $table = 'blog_tag';

    protected $fillable = [
        'titulo',
        'slug',
        'blog_id',
    ];

  
}
