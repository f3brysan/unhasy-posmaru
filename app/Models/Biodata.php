<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Biodata extends Model
{
    use HasFactory;

    protected $table = 'biodatas';
    protected $fillable = ['id', 'prodi_kode', 'fakultas_kode', 'gender', 'chart_size', 'hp', 'created_at', 'updated_at'];

    public function prodi()
    {
        return $this->belongsTo(Prodis::class, 'prodi_kode', 'kode_prodi');
    }

    public function fakultas()
    {
        return $this->belongsTo(Prodis::class, 'fakultas_kode', 'kode_fakultas');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }
}
