<?php

namespace App\Events;

use App\Models\Application;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ApplicationStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $application;
    public $oldStatus;

    public function __construct(Application $application, $oldStatus)
    {
        $this->application = $application;
        $this->oldStatus = $oldStatus;
    }
}