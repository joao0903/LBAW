<?php

namespace App\Models;

// use App\Content;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'comment';
    protected $primaryKey = 'id';
    protected $fillable = ['content', 'date', 'likes', 'id_post', 'id_user'];

    /**
     * The post of this comment
     */
    public function post() {     
        return $this->belongsTo('App\Models\Post', 'id_post', 'id', 'id_user');        
    }

    public function commentAuthor() {
        return $this->belongsTo(User::class, 'id_user');
    }
}