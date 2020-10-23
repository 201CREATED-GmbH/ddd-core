<?php

namespace C201\Ddd\Tests\Events\Domain;

use C201\Ddd\Events\Domain\DomainEvent;
use C201\Ddd\Events\Domain\EventProvider;
use C201\Ddd\Events\Domain\EventRegistry;
use C201\Ddd\Events\Domain\EventStore;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @author Marko Vujnovic <mv@201created.de>
 * @since  2020-04-06
 */
class EventRegistryTest extends TestCase
{
    use ProphecyTrait;

    private EventRegistry $fixture;

    /**
     * @var ObjectProphecy|EventStore
     */
    private $eventStore;

    protected function setUp(): void
    {
        $this->eventStore = $this->prophesize(EventStore::class);
        $this->fixture = new EventRegistry($this->eventStore->reveal());
    }

    public function testRegisterEvent()
    {
        $event = $this->prophesize(DomainEvent::class)->reveal();
        $this->fixture->registerEvent($event);
        $events = $this->fixture->dequeueEvents();
        $this->assertCount(1, $events);
        $this->assertSame($event, $events[0]);
        $this->eventStore->append($event)->shouldHaveBeenCalledTimes(1);
    }

    public function testDequeueProviderAndRegister()
    {
        $event = $this->prophesize(DomainEvent::class)->reveal();
        /** @var EventProvider|ObjectProphecy $provider */
        $provider = $this->prophesize(EventProvider::class);
        $provider->dequeueEvents()->willReturn([$event]);
        $this->fixture->dequeueProviderAndRegister($provider->reveal());
        $events = $this->fixture->dequeueEvents();
        $this->assertCount(1, $events);
        $this->assertSame($event, $events[0]);
        $this->eventStore->append($event)->shouldHaveBeenCalledTimes(1);
    }
}
