<?php

namespace App\Models;

// use App\Content;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'image';
    protected $primaryKey = 'id';
    protected $fillable = ['imagepath', 'typeofimage', 'id_post', 'id_user'];

    /**
     * The post of this comment
     */

    public function post() {     
        return $this->belongsTo(Post::class, 'id_post', 'id');        
    }

    public function user() {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }
    
}