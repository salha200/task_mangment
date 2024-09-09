<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'description', 'priority', 'due_date', 'status', 'assigned_to','created_by'];
    // protected $primaryKey = 'task_id';
    public $incrementing = true;
    public $timestamps = true;
    
    protected $dates = ['due_date', 'deleted_at'];

    // Accessor for formatted due_date
    public function getDueDateAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y H:i');
    }

    // Mutator for due_date
    public function setDueDateAttribute($value)
    {
        $this->attributes['due_date'] = Carbon::createFromFormat('d-m-Y H:i', $value)->format('Y-m-d H:i:s');
    }

    // Scope to filter by priority
    public function scopePriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    // Scope to filter by status
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
