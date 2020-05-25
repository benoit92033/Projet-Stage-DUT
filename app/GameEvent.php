<?php

namespace App;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class GameEvent implements ShouldBroadcast
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $id_receiver;
  public $partie;

  public function __construct($partie, $id_receiver)
  {
      $this->id_receiver = $id_receiver;
      $this->partie = $partie;
  }

  public function broadcastOn()
  {
      return ['game.'.$this->id_receiver];
  }

  public function broadcastAs()
  {
      return 'GameEvent';
  }
}