<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class post extends Model
{
   protected $fillable = ['title', 'content'];
   
   public function comments() {
      return $this->hasMany(Comment::class);
   }
   
   public function likes() {
      return $this->morphMany(Like::class, 'likeable');
   }

   public function user() {
      return $this->belongsTo(User::class);
   }

   public function isLiked(User $user) {
      return $this->likes()->where('user_id', $user->id)->exists();
   }

   public function tags() {
    return $this->belongsToMany(Tag::class);
   }

   public function category() {
      return $this->belongsTo(Category::class);
   }
   

}
