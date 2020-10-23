<?php

namespace C201\Ddd\Commands\Testing;

use C201\Ddd\Commands\Application\CommandHandler;
use C201\Ddd\Events\Testing\DomainEventTestTrait;
use C201\Ddd\Transactions\Testing\TransactionManagerTestTrait;

/**
 * @author Marko Vujnovic <mv@201created.de>
 * @since  2019-06-27
 */
trait CommandHandlerTestTrait
{
    use TransactionManagerTestTrait;
    use DomainEventTestTrait;

    /**
     * @var CommandHandler
     */
    protected $fixture;

    protected function initCommandHandlerTestTrait(): void
    {
        $this->initTransactionManagerTestTrait();
        $this->initDomainEventTestTrait();
    }

    protected function commandHandlerPostSetUp(): void
    {
        $this->fixture->setTransactionManager($this->transactionManager->reveal());
        $this->fixture->setEventRegistry($this->eventRegistry->reveal());
    }
}
