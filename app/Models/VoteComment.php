<?php

// app/Models/VoteComment.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoteComment extends Model
{
    // Don't add create and update timestamps in the database.
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'votecomment'; // Updated to lowercase

    /**
     * The primary key associated with the table.
     *
     * @var array
     */
    protected $primaryKey = ['id_user', 'id_comment'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_user', 'id_comment'];

    /**
     * Get the user associated with the voteComment.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    /**
     * Get the comment associated with the voteComment.
     */
    public function comment()
    {
        return $this->belongsTo(Comment::class, 'id_comment');
    }
}

