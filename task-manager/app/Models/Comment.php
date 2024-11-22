<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Comment extends Model
{
    use HasFactory;
    protected $fillable = [
        'task_id', 'comment', 'parent_id','username',
    ];

    // Relationship with task
    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    // Replies to comments
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    // Relationship with user (author)
    public function user()
    {
        return $this->belongsTo(User::class); // Assuming User model exists
    }
}

