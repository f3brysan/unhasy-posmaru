<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterActivity extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'master_activities';

    public $incrementing = false;

    protected $guarded = [];
}
