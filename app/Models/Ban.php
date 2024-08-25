<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ban extends Model
{
    public $timestamps  = false;

    protected $dateFormat = 'Y-m-d';
    protected $table = 'ban';
    protected $primaryKey = 'id';
    protected $fillable = ['reason', 'id_user'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    // Accessor for getting the "reason" attribute
    public function getReasonAttribute($value)
    {
        return ucfirst($value);
    }

    // Mutator for setting the "reason" attribute
    public function setReasonAttribute($value)
    {
        $this->attributes['reason'] = strtolower($value); // You can customize the transformation as needed
    }
}