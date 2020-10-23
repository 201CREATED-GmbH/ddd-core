<?php

namespace C201\Ddd\Tests\Events\Application;

use C201\Ddd\Events\Application\EventBus;
use C201\Ddd\Events\Application\EventManager;
use C201\Ddd\Events\Domain\DomainEvent;
use C201\Ddd\Events\Domain\EventRegistry;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

class EventManagerTest extends TestCase
{
    use ProphecyTrait;

    /**
     * @var ObjectProphecy|EventRegistry
     */
    private $eventRegistry;

    /**
     * @var ObjectProphecy|EventBus
     */
    private $eventBus;

    private EventManager $fixture;

    protected function setUp(): void
    {
        $this->eventRegistry = $this->prophesize(EventRegistry::class);
        $this->eventBus = $this->prophesize(EventBus::class);
        $this->fixture = new EventManager($this->eventRegistry->reveal(), $this->eventBus->reveal());
    }

    public function testClearDequeuesRegistryButDoesNotDispatchToBus()
    {
        $this->eventRegistry->dequeueEvents()->shouldBeCalledTimes(1);
        $this->eventBus->dispatch(Argument::any())->shouldNotBeCalled();
        $this->fixture->clear();
    }

    public function testFlushDequeuesRegistryAndDispatchesDequeuedEventsToBus()
    {
        $event = $this->prophesize(DomainEvent::class)->reveal();
        $this->eventRegistry->dequeueEvents()->willReturn([$event]);
        $this->eventRegistry->dequeueEvents()->shouldBeCalledTimes(1);
        $this->eventBus->dispatch($event)->shouldBeCalledTimes(1);
        $this->fixture->flush();
    }
}
