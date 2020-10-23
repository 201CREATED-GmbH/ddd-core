<?php

namespace C201\Ddd\Commands\Application;

use C201\Ddd\Events\Domain\EventProvider;
use C201\Ddd\Events\Domain\EventRegistry;
use C201\Ddd\Transactions\Application\TransactionManager;

/**
 * @author Marko Vujnovic <mv@201created.de>
 * @since  2019-06-27
 */
abstract class CommandHandler
{
    private TransactionManager $transactionManager;
    protected EventRegistry $eventRegistry;

    /**
     * @required
     */
    public function setTransactionManager(TransactionManager $transactionManager): void
    {
        $this->transactionManager = $transactionManager;
    }

    /**
     * @required
     */
    public function setEventRegistry(EventRegistry $eventRegistry): void
    {
        $this->eventRegistry = $eventRegistry;
    }

    /**
     * Needs to be called by a public method on the concrete handler which is type hinted to the concrete command class
     */
    protected function handleCommand($command): void
    {
        $this->transactionManager->begin();

        try {
            $aggregateRoot = $this->execute($command);
            if ($aggregateRoot) {
                $this->eventRegistry->dequeueProviderAndRegister($aggregateRoot);
            }
            $this->transactionManager->commit();
        } catch (\Exception $e) {
            $this->transactionManager->rollback();
            $e = $this->postRollback($e, $command);
            throw $e;
        }
    }

    /**
     * Needs to be implemented by the concrete handler, performing any and all command handling logic
     */
    abstract protected function execute($command): ?EventProvider;

    /**
     * May be overridden by the concrete handler if special processing of exceptions thrown by the try method is required. Must either throw or return any
     * exceptions.
     */
    protected function postRollback(\Throwable $e, $command): \Throwable
    {
        return $e;
    }
}
