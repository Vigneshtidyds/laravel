<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'due_date',
        'bucket_id',
        'description',   // your “notes” field
        'start_date',
        'priority',
        'status',
        'created_by',
        'updated_by',
        'checklist',
        'attachments',
        'comments',
    ];

    protected $casts = [
        'due_date'    => 'datetime',
        'start_date'  => 'datetime',
        'checklist'   => 'array',
        'attachments' => 'array',
        'comments'    => 'array',
    ];

    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'task_user');
    }
}