<?php

namespace Tests\Feature;

use App\Events\TestAblyEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class AblyBroadcastTest extends TestCase
{
    /**
     * Test that an event can be broadcast via Ably.
     */
    public function test_ably_event_can_be_broadcast(): void
    {
        Event::fake();

        $message = 'Hello Ably!';
        event(new TestAblyEvent($message));

        Event::assertDispatched(TestAblyEvent::class, function ($event) use ($message) {
            return $event->message === $message &&
                   $event->broadcastOn()[0]->name === 'test-channel' &&
                   $event->broadcastAs() === 'test.ably.message';
        });
    }
}
