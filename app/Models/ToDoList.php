<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToDoList extends Model
{
    use HasFactory;
    protected $table = "to_do_lists";
    protected $fillable = [
        'task',
        'description',
        'due_date',
        'marked_as_completed'
    ];
}
