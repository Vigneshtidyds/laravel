<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'due_date', 'bucket_id'];

    protected $casts = [
        'due_date' => 'datetime',
    ];

    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'task_user'); // Many-to-Many
    }
}

