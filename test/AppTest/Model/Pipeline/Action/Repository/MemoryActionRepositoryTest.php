<?php

declare(strict_types=1);

namespace AppTest\Model\Pipeline\Action\Repository;

use App\Model\Pipeline\Action\Entity\Action;
use App\Model\Pipeline\Action\Entity\SimpleAction;
use App\Model\Pipeline\Action\Repository\MemoryActionRepository;
use PHPUnit\Framework\TestCase;

class MemoryActionRepositoryTest extends TestCase
{

    public function testFindAll(): void
    {
        $memoryActionRepository = new MemoryActionRepository();
        $actions = $memoryActionRepository->findAll();

        $this->assertCount(2, $actions);
        $this->assertInstanceOf(Action::class, \current($actions));
        $this->assertInstanceOf(SimpleAction::class, \current($actions));
    }

    public function testFetch(): void
    {
        $memoryActionRepository = new MemoryActionRepository();

        $this->assertNull($memoryActionRepository->fetch(1));
        $this->assertInstanceOf(Action::class, $memoryActionRepository->fetch(123));
    }
}
