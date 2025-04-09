<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'start_date',
        'due_date',
        'priority',
        'status',
        'bucket_id',
        'progress',
        'notes',
        'checklist',
        'attachments',
        'comments',
        'created_by',
        'updated_by',
    ];
    
    
    protected $casts = [
        'due_date' => 'datetime',
        'start_date' => 'datetime',
    ];

    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'task_user'); // Many-to-Many
    }
}

