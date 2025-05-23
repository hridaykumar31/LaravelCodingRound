<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{

    protected $fillable = ['user_id', 'product_id', 'body'];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function product() {
        return $this->belongsTo(Product::class);
    }
    public function likes() {
      return $this->morphMany(Like::class, 'likeable');
    }
    public function isLiked(User $user) {
        return $this->likes()->where('user_id', $user->id)->exists();
    }
}
