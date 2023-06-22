<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Persistence\User;

use App\Domain\User\UserRepository;
use Tests\TestCase;

class DatabaseUserRepositoryTest extends TestCase
{
    public function testIsDefined()
    {
        $app = $this->getAppInstance();
        $repo = $app->getContainer()->get(UserRepository::class);
        $this->assertInstanceOf(UserRepository::class, $repo);
    }
}
