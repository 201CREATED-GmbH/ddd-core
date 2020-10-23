<?php

namespace C201\Ddd\Transactions\Application;

/**
 * @author Marko Vujnovic <mv@201created.de>
 * @since  2019-06-07
 */
interface TransactionManager
{
    public function begin(): void;

    public function commit(): void;

    public function rollback(): void;
}
