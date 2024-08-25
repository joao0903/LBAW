<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

// Added to define Eloquent relationships.
use Illuminate\Database\Eloquent\Relations\HasMany;

class Save extends Model
{
    public $timestamps  = false;

    protected $primaryKey = ['id_post', 'id_user'];

    public $incrementing = false; 

    protected $fillable = ['id_post', 'id_user'];

    
    public function post() {
        return $this->belongsTo(post::class, 'id_post', 'id');
    }
    
}