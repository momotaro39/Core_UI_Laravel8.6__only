<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClaimerInComming
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    /**
     * @var \App\Models\BandMember
     * 初期値の設定を行う
     */
    public $claimer;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(\App\Models\band\BandMembers $claimer)
    {
        $this->claimer = $claimer;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
