<?php

namespace C201\Ddd\Events\Domain;

use Webmozart\Assert\Assert;

/**
 * @author Marko Vujnovic <mv@201created.de>
 * @since  2019-06-05
 */
final class EventId
{
    private string $id;

    private function __construct(string $id)
    {
        Assert::uuid($id);

        $this->id = $id;
    }

    public static function fromString(string $id): EventId
    {
        return new self($id);
    }

    public function asString(): string
    {
        return $this->id;
    }
}
