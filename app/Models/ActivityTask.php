<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityTask extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'activity_tasks';    
    public $incrementing = false;
    protected $fillable = ['user_id', 'activity_id', 'picture', 'description', 'tgl_setor'];

    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
