<?php

namespace App\Models;
// use...
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskType extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function Tasks()
    {
        return $this->hasMany(Tasks::class);
    }
}
