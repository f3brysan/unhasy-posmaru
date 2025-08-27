<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Activity extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'activities';

    public $incrementing = false;

    protected $fillable = ['name', 'description', 'year', 'registration_start_date', 'registration_end_date', 'activity_start_date', 'activity_end_date', 'is_active', 'student_report_start', 'student_report_end', 'created_at', 'updated_at', 'updated_by', 'bg_certificate', 'x_coordinate', 'y_coordinate', 'font_size'];

    public function participants()
    {
        return $this->hasMany(ActivityParticipant::class);
    }
}
