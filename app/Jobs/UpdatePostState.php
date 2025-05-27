<?php

namespace App\Jobs;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log; 

class UpdatePostState implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Post $post
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {//if the date now == the date in the schedule time convert the status 
        if($this->post->status=='scheduled'  && now() >= $this->post->scheduled_time){
            $this->post->status = 'published';
            log::info('Post ID: ' . $this->post->id . ' status changed to published.');

        }else{
            log::info('Post ID: ' . $this->post->id . ' status was not "scheduled". Current status: ' . $this->post->status);

        }
        $this->post->save();
    }
}
