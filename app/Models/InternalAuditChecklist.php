<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InternalAuditChecklist extends Model
{
     use HasFactory, SoftDeletes;

    protected $table = 'internal_audit_checklists';
     protected $fillable = [
    'audit_date',
    'area_audited',
    'auditor_name',
    'non_conformities_found',
    'corrective_actions_needed',
    'follow_up_date',
    'auditor_signature',
    'store_id',
    'user_id'
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
