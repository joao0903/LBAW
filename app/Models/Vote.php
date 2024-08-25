<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\HasMany;


class Vote extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    public $timestamps  = false;

    protected $table = 'vote';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['rating', 'id_post', 'id_user'];

    /**
     * The tags of this post
     */

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function tags() {
        return $this->belongsToMany('App\Tag', 'post_tag', 'id_post', 'id_tag');
    }

    /**
     * The topics of this post
     */
    public function topics() {
        return $this->belongsTo(Topic::class, 'id_topic');
    }
    public function postAuthor() {
        return $this->belongsTo(User::class, 'id_user');
    }

}