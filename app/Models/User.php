<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    
    use HasFactory;
      /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
   protected $fillable = [
    'name',
    'email',
    'age',
    'image',
    'group_id',
    ];

    public function group()
    {
    return $this->belongsTo(Group::class);
    }
}
