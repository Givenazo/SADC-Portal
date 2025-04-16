<?php
namespace App\Events;

use App\Models\Country;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CountryBlocked implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $country;

    public function __construct(Country $country)
    {
        $this->country = $country;
    }

    public function broadcastOn()
    {
        return new Channel('countries');
    }

    public function broadcastAs()
    {
        return 'country.blocked';
    }
}
