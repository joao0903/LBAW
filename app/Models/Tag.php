<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Tag extends Model
{
    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    protected $table = 'tag';
    protected $fillable = ['title', 'description'];

    /**
     * The posts that have this tag.
     */
    public function posts() {
        return $this->belongsToMany(Post::class, 'postwithtag', 'id_tag', 'id_post');
    }

    public function users() {
        return $this->belongsToMany(User::class, 'followtag', 'id_tag', 'id_user');
    }

}