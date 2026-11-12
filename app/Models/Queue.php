<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Queue extends Model
{
   protected $fillable = [
      'queue_number',
      'queue_date',
      'status',
      'served_by',
      'called_at',
   ];

   protected $casts = [
      'queue_date' => 'date',
      'called_at' => 'datetime',
   ];

   protected $appends = ['formatted_number'];

   public function getFormattedNumberAttribute(): string
   {
      return 'A-' . str_pad((string) $this->queue_number, 3, '0', STR_PAD_LEFT);
   }

   public function admin(): BelongsTo
   {
      return $this->belongsTo(User::class, 'served_by');
   }
}