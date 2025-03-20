<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class AccountHead extends Model
{
    use HasFactory ,SoftDeletes;
    protected $fillable = ['name', 'parent_id','can_delete','opening_balance','dr_cr'];
    protected $dates = ['deleted_at']; 

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
