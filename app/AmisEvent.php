<?php

namespace App;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class AmisEvent implements ShouldBroadcast
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $amis;
  public $id_receiver;

  public function __construct($amis, $id_receiver)
  {
      $this->amis = $amis;
      $this->id_receiver = $id_receiver;
  }

  public function broadcastOn()
  {
      return ['updateAmis.'.$this->id_receiver];
  }

  public function broadcastAs()
  {
      return 'AmisEvent';
  }
}