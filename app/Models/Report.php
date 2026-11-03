<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    public const STATUSES = ['menunggu', 'diproses', 'selesai', 'ditolak'];

    protected $fillable = [
        'user_id',
        'category_id',
        'location_id',
        'handled_by',
        'title',
        'description',
        'photo_path',
        'status',
        'follow_up_note',
    ];

    /** Kode tampilan, mis. POS-2026-0007. */
    protected function code(): Attribute
    {
        return Attribute::get(fn () => sprintf('POS-%s-%04d', $this->created_at->format('Y'), $this->id));
    }

    /** Filter opsional: from, to (tanggal dibuat, inklusif) dan status. */
    public function scopeFiltered(Builder $query, array $filters): Builder
    {
        return $query
            ->when($filters['from'] ?? null, fn ($q, $from) => $q->whereDate('created_at', '>=', $from))
            ->when($filters['to'] ?? null, fn ($q, $to) => $q->whereDate('created_at', '<=', $to))
            ->when($filters['status'] ?? null, fn ($q, $status) => $q->where('status', $status));
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function handler()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }
}
