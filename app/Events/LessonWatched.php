<?php

namespace App\Events;

use App\Models\User;
use App\Models\Lesson;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Facades\Log;

class LessonWatched
{
    use Dispatchable, SerializesModels;

    public $lesson;
    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Lesson $lesson, User $user)
    {
        $this->lesson = $lesson;
        $this->user = $user;
        Log::debug($this->lesson);

    }
}
