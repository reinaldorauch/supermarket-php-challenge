<?php

declare(strict_types=1);

namespace App\Application\Actions\Auth;

use App\Application\Actions\Action;
use App\Application\Settings\SettingsInterface;
use App\Domain\User\UserRepository;
use Psr\Log\LoggerInterface;

abstract class AuthAction extends Action
{
    public function __construct(
        LoggerInterface $logger,
        protected UserRepository $userRepository,
        protected SettingsInterface $settings,
    ) {
        parent::__construct($logger);
    }
}
