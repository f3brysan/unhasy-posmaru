<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActivitySession extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'activity_sessions';

    public $incrementing = false;

    protected $fillable = ['id', 'name', 'activity_id', 'student_report_start', 'student_report_end'];

    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id', 'id');
    }
}
