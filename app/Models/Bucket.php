<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bucket extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // Define the relationship inside the class
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
