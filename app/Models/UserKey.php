<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserKey extends Model
{
    use HasFactory;

    protected $fillable = ['sender_id', 'encrypted_key'];

    public function user()
    {
        return $this->belongsTo(User::class,'sender_id','id');
    }

}
