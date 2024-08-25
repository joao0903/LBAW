<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Topic extends Model
{
    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    protected $table = 'topic';
    protected $fillable = ['title', 'description', 'type'];

    /**
     * The posts that have this topic.
     */
    public function posts() {
        return $this->belongsToMany('App\Post', 'post_topic', 'id_topic', 'id_post');
    }

}