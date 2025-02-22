<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountHead extends Model
{
    protected $fillable = ['name', 'parent_id'];

    // Define relationship for parent
    public function parent()
    {
        return $this->belongsTo(AccountHead::class, 'parent_id');
    }

    // Define relationship for children
    public function children()
    {
        return $this->hasMany(AccountHead::class, 'parent_id');
    }
}
