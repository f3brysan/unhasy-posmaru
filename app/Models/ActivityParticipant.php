<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActivityParticipant extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'activity_participants';

    public $incrementing = false;

    protected $guarded = [];

    public function activity()
    {
        return $this->belongsTo(MasterActivity::class, 'activity_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
