<?php

namespace C201\Ddd\Tests\Events\Domain;

use C201\Ddd\Events\Domain\AbstractDomainEvent;
use C201\Ddd\Events\Domain\DomainEvent;
use C201\Ddd\Events\Domain\EventId;
use Ramsey\Uuid\Uuid;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @author Marko Vujnovic <mv@201created.de>
 * @since  2020-04-06
 */
class AbstractDomainEventTest extends TestCase
{
    public function testRaisedTsReturnsRaisedTsPassedToConstructor()
    {
        $raisedTs = new \DateTimeImmutable();
        /** @var MockObject|DomainEvent $event */
        $event = $this->getMockForAbstractClass(AbstractDomainEvent::class, [EventId::fromString(Uuid::uuid4()), $raisedTs]);
        $this->assertSame($raisedTs, $event->raisedTs());
    }

    public function testIdReturnsIdPassedToConstructor()
    {
        $id = EventId::fromString(Uuid::uuid4());
        /** @var MockObject|DomainEvent $event */
        $event = $this->getMockForAbstractClass(AbstractDomainEvent::class, [$id, new \DateTimeImmutable()]);
        $this->assertSame($id, $event->id());
    }
}
