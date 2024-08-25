<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Post extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    public $timestamps = false;

    protected $table = 'post';
    protected $primaryKey = 'id';

    protected $fillable = ['date', 'popularity', 'title', 'description', 'id_user', 'id_topic', 'title_tsvectors'];

    protected $ts_vectors = [
        'title_tsvectors' => 'tsvector',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function tags() {
        return $this->belongsToMany(Tag::class, 'postwithtag', 'id_post', 'id_tag');
    }

    public function topics() {
        return $this->belongsTo(Topic::class, 'id_topic');
    }

    public function postAuthor() {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function votes() {
        return $this->hasMany(Vote::class, 'id_post'); /* como vote tem acesso a post e vice-versa, chama-se a primary key da tabela */
    }

    /*
    public function save() {
        return $this->hasMany(Save::class, 'id_post');
    }
    */

    public function comments() {
        return $this->hasMany(Comment::class, 'id_post', 'id'); /* como apenas comment tem acesso a post, chama-se a protected primary key definida acima */
    }

    public function image() {
        return $this->hasOne(Image::class, 'id_post', 'id');
    }
}
