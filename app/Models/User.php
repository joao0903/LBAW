<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'password',
        'reputation',
        'firstname',
        'lastname',
        'email',
        'username_tsvectors',
    ];
    protected $ts_vectors = [
        'username_tsvectors' => 'tsvector',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function isAdmin() {
        return count($this->hasOne('App\Models\Admin', 'id_admin')->get());
    }
    public function isBanned() {
        return $this->ban()->exists();
        
    }
    public function followedUsers() {
        return $this->belongsToMany(User::class, 'followuser', 'id_user1', 'id_user2');
    }
    public function followingUsers() {
        return $this->belongsToMany(User::class, 'followuser', 'id_user2', 'id_user1');
    }
    

    public function followedTags() {
        return $this->belongsToMany(Tag::class, 'followtag', 'id_user', 'id_tag');
    }

    public function image() {
        return $this->hasOne(Image::class, 'id_user', 'id');
    }

    public function posts() {
        return $this->hasMany(Post::class, 'id_user');
    }

    public function comments() {
        return $this->hasMany(Comment::class, 'id_user', 'id');
    }

    public function ban(){
        return $this->hasMany(Ban::class,'id_user');
    }

    public function notifications(){
    return $this->hasMany(Notification::class, 'id_user');
    }

}
