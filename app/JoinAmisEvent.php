<?php

namespace App;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\PrivateChannel;

class JoinAmisEvent implements ShouldBroadcast
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $id_receiver;
  public $id_ami;

  public function __construct($id_receiver, $id_ami)
  {
      $this->id_receiver = $id_receiver;
      $this->id_ami = $id_ami;
  }

  public function broadcastOn()
  {
      return ['joinAmis.'.$this->id_receiver];
  }

  public function broadcastAs()
  {
      return 'JoinAmisEvent';
  }
}