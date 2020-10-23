<?php

namespace C201\Ddd\Identity\Domain;

/**
 * @author Marko Vujnovic <mv@201created.de>
 * @since  2020-10-19
 */
interface EntityId
{
    public function asString(): string;

    public function equals(EntityId $other): bool;

    public function entityType(): string;
}
