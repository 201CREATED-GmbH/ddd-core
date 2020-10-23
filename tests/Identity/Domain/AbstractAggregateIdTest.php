<?php
namespace C201\Ddd\Tests\Identity\Domain;

use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Ramsey\Uuid\Uuid;
use Webmozart\Assert\Assert;

/**
 * @author Marko Vujnovic <mv@201created.de>
 * @since  2020-04-06
 */
class AbstractAggregateIdTest extends TestCase
{
    public function testFromStringReturnsInstanceWithPassedUuidAsId(): void
    {
        $uuid = Uuid::uuid4();
        $id = AbstractAggregateTestProxyId::fromString($uuid);
        $this->assertEquals($uuid, $id->asString());
    }

    public function testFromStringThrowsExceptionIfNonUuidStringIsPassed(): void
    {
        $this->expectException(\Exception::class);
        AbstractAggregateTestProxyId::fromString('foo');
    }

    public function testNextReturnsInstanceWithUuidAsId(): void
    {
        $id = AbstractAggregateTestProxyId::next();
        Assert::uuid($id->asString());
        $this->assertTrue(true);
    }

    public function testEqualsReturnsTrueIfOtherHasSameIdAndIsOfSameClass(): void
    {
        $id = AbstractAggregateTestProxyId::next();
        $id2 = AbstractAggregateTestProxyId::fromString($id->asString());
        $this->assertTrue($id->equals($id2));
    }

    public function testEqualsReturnsFalseIfOtherHasOtherIdAndIsOfSameClass(): void
    {
        $id = AbstractAggregateTestProxyId::next();
        $id2 = AbstractAggregateTestProxyId::next();
        $this->assertFalse($id->equals($id2));
    }

    public function testEqualsReturnsFalseIfOtherHasSameIdAndIsOfDifferentClass(): void
    {
        $id = AbstractAggregateTestProxyId::next();
        $id2 = AbstractAggregateTestProxy2Id::fromString($id->asString());
        $this->assertFalse($id->equals($id2));
    }

    public function testEntityTypeReturnsFullyQualifiedClassNameOfAggregate(): void
    {
        $id = AbstractAggregateTestProxyId::next();
        $this->assertEquals(AbstractAggregateTestProxy::class, $id->entityType());
    }

    public function testAggregateTypeReturnsFullyQualifiedClassNameOfAggregate(): void
    {
        $id = AbstractAggregateTestProxyId::next();
        $this->assertEquals(AbstractAggregateTestProxy::class, $id->aggregateType());
    }

    public function testAggregateTypeReturnsSamevalueAsEntityType(): void
    {
        $id = AbstractAggregateTestProxyId::next();
        $this->assertEquals($id->aggregateType(), $id->entityType());
    }
}
