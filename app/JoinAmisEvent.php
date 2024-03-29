<?php

namespace App;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class JoinAmisEvent implements ShouldBroadcast
{
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $id_receiver;
  public $ami;
  public $idSession;

  public function __construct($id_receiver, $ami, $idSession)
  {
      $this->id_receiver = $id_receiver;
      $this->ami = $ami;
      $this->idSession = $idSession;
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