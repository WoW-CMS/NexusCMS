<?php

namespace Tests\Unit;

use App\Services\AuthService;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use PHPUnit\Framework\TestCase;

class AuthServiceTest extends TestCase
{
    public function test_attempt_login_uses_guard_attempt_and_returns_result(): void
    {
        $credentials = ['email' => 'test@example.com', 'password' => 'secret'];

        $guard = $this->createMock(StatefulGuard::class);
        $guard->expects($this->once())
            ->method('attempt')
            ->with($credentials)
            ->willReturn(true);

        $authFactory = $this->createMock(AuthFactory::class);
        $authFactory->expects($this->once())
            ->method('guard')
            ->willReturn($guard);

        $hasher = $this->createMock(HasherContract::class);

        $service = new AuthService($authFactory, $hasher);

        $this->assertTrue($service->attemptLogin($credentials));
    }

    public function test_logout_uses_guard_logout(): void
    {
        $guard = $this->createMock(StatefulGuard::class);
        $guard->expects($this->once())
            ->method('logout');

        $authFactory = $this->createMock(AuthFactory::class);
        $authFactory->expects($this->once())
            ->method('guard')
            ->willReturn($guard);

        $hasher = $this->createMock(HasherContract::class);

        $service = new AuthService($authFactory, $hasher);
        $service->logout();

        $this->assertTrue(true);
    }
}
