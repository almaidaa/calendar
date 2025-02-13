<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'start', 'end','username','description'
    ];

    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
    ];


    // protected $guarded=[];
    public function user()
{
    return $this->belongsTo(User::class, 'username', 'username');
}

}
