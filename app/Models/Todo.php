<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    public function scopeUncompleted(Builder $query) : Builder
    {
        return $query->where('completed', '=', 0);
    }
}
