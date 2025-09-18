<?php

namespace App\Models;

use App\Enums\CommentStatuEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'comment',
        'status',
        'post_id',
    ];

    protected $casts = [
        'status' => CommentStatuEnum::class
    ];
    
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function isAccept()
    {
        return $this->status == CommentStatuEnum::ACCEPT;
    }

    public function isBlock()
    {
        return $this->status == CommentStatuEnum::BLOCK;
    }

    public function isPending()
    {
        return $this->status == CommentStatuEnum::PENDING;
    }
}
