<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'body', 'category_id', 'excerpt', 'slug'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeWithOrder($query, $order)
    {
        // 不同的排序，使用不同的数据读取逻辑
        switch ($order) {
            case 'recent':
                $query->recent();
                break;

            default:
                $query->recentReplied();
                break;
        }
      }

    public function scopeRecent($query){
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeRecentReplied($query){
        return $query->orderBy('updated_at', 'desc');
    }

    public function link($param = []){
        return route('topics.show', array_merge([$this->id, $this->slug], $param));
    }

    public function replies(){
        return $this->hasMany(Reply::class);
    }

    public function updateReplyCount($reply){
        $this->reply_count = $this->replies->count();
        $this->save();
    }
}
