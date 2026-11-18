public function user(): BelongsTo
{
    return $this->belongsTo(User::class, 'user_id', 'user_id');
}