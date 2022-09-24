<?php

namespace App\Observers;

use App\Models\Topic;
use App\Jobs\TranslateSlug;
use Illuminate\Support\Facades\DB;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function creating(Topic $topic)
    {
        //
    }

    public function updating(Topic $topic)
    {
        //
    }

    public function saving(Topic $topic)
    {
        $topic->body = clean($topic->body, 'user_topic_body');
        $topic->excerpt = make_excerpt($topic->body);
        // if(!$topic->slug){
        //     // $topic->slug = app(SlugTranslateHandler::class)->translate($topic->title);
        //     dispatch(new TranslateSlug($topic));
        // }
    }

    public function saved(Topic $topic)
    {
        if(!$topic->slug){
            dispatch(new TranslateSlug($topic));
        }
    }

    public function deleted(Topic $topic){
        DB::table('replies')->where('topic_id', $topic->id)->delete();
    }
}
