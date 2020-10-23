<?php

namespace C201\Ddd\Tests\Events\Domain;

use C201\Ddd\Events\Domain\AbstractDomainEvent;
use C201\Ddd\Events\Domain\DomainEvent;
use C201\Ddd\Events\Domain\EventId;
use C201\Ddd\Events\Domain\EventProviderCapabilities;
use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @author Marko Vujnovic <mv@201created.de>
 * @since  2020-04-06
 */
class EventProviderCapabilitiesTest extends TestCase
{
    public function testDequeueEventsReturnsRaisedEvents(): void
    {
        /** @var MockObject|EventProviderCapabilities $eventProviderCapabilities */
        $eventProviderCapabilities = $this->getMockForTrait(EventProviderCapabilities::class);
        $reflection = new \ReflectionClass(get_class($eventProviderCapabilities));
        $method = $reflection->getMethod('raiseEvent');
        $method->setAccessible(true);

        /** @var MockObject|DomainEvent $event */
        $event = $this->getMockForAbstractClass(AbstractDomainEvent::class, [EventId::fromString(Uuid::uuid4()), new \DateTimeImmutable()]);

        $method->invokeArgs($eventProviderCapabilities, [$event]);
        $result = $eventProviderCapabilities->dequeueEvents();
        $this->assertContainsOnlyInstancesOf(DomainEvent::class, $result);
        $this->assertCount(1, $result);
        $this->assertSame($event, $result[0]);
    }

    public function testDequeueEventsRemovesRaisedEventsFromEventProvider(): void
    {
        /** @var MockObject|EventProviderCapabilities $eventProviderCapabilities */
        $eventProviderCapabilities = $this->getMockForTrait(EventProviderCapabilities::class);
        $reflection = new \ReflectionClass(get_class($eventProviderCapabilities));
        $method = $reflection->getMethod('raiseEvent');
        $method->setAccessible(true);

        /** @var MockObject|DomainEvent $event */
        $event = $this->getMockForAbstractClass(AbstractDomainEvent::class, [EventId::fromString(Uuid::uuid4()), new \DateTimeImmutable()]);

        $method->invokeArgs($eventProviderCapabilities, [$event]);
        $events = $eventProviderCapabilities->dequeueEvents();
        $this->assertNotEmpty($events);
        $result = $eventProviderCapabilities->dequeueEvents();
        $this->assertEmpty($result);
    }
}
