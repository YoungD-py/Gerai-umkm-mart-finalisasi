<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    
    // [DIUBAH] Menambahkan field baru ke $fillable
    protected $fillable = ['nama', 'nomor_penanggung_jawab', 'alamat'];

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? false, function ($query, $search) {
            return $query->where('nama', 'like', '%' . $search . '%');
        });
    }

    public function goods()
    {
        return $this->hasMany(Good::class);
    }
}
