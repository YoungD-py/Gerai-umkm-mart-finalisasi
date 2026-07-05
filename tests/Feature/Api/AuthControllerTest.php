<?php

namespace Tests\Feature\Api;

use App\Http\Controllers\Api\AuthController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_login_returns_token_when_credentials_valid()
    {
        $tokenObject = new class {
            public string $plainTextToken = 'token-123';
        };

        $tokensMock = Mockery::mock();
        $tokensMock->shouldReceive('delete')->once();

        $user = Mockery::mock();
        $user->id = 1;
        $user->nama = 'Admin Test';
        $user->username = 'admin_test';
        $user->role = 'ADMIN';
        $user->password = 'hashed';
        $user->shouldReceive('tokens')->andReturn($tokensMock);
        $user->shouldReceive('createToken')
            ->once()
            ->with('auth-token', ['ADMIN'])
            ->andReturn($tokenObject);

        $userQuery = Mockery::mock();
        $userQuery->shouldReceive('first')->andReturn($user);

        $userAlias = Mockery::mock('alias:' . User::class);
        $userAlias->shouldReceive('where')
            ->once()
            ->with('username', 'admin_test')
            ->andReturn($userQuery);

        Hash::shouldReceive('check')
            ->once()
            ->with('secret123', 'hashed')
            ->andReturn(true);

        $request = Request::create('/api/login', 'POST', [
            'username' => 'admin_test',
            'password' => 'secret123',
        ]);

        $response = app(AuthController::class)->login($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('token-123', $response->getData(true)['data']['token']);
    }

    public function test_logout_returns_success_when_token_deleted()
    {
        $token = Mockery::mock();
        $token->shouldReceive('delete')->once();

        $user = Mockery::mock();
        $user->shouldReceive('currentAccessToken')->andReturn($token);

        $request = Request::create('/api/logout', 'POST');
        $request->setUserResolver(fn () => $user);

        $response = app(AuthController::class)->logout($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertTrue($response->getData(true)['success']);
    }

    public function test_profile_returns_authenticated_user_data()
    {
        $user = (object) [
            'id' => 5,
            'nama' => 'Kasir Test',
            'username' => 'kasir_test',
            'role' => 'KASIR',
        ];

        $request = Request::create('/api/profile', 'GET');
        $request->setUserResolver(fn () => $user);

        $response = app(AuthController::class)->profile($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('kasir_test', $response->getData(true)['data']['user']['username']);
    }

    public function test_logout_all_returns_success_when_all_tokens_deleted()
    {
        $tokens = Mockery::mock();
        $tokens->shouldReceive('delete')->once();

        $user = Mockery::mock();
        $user->shouldReceive('tokens')->andReturn($tokens);

        $request = Request::create('/api/logout-all', 'POST');
        $request->setUserResolver(fn () => $user);

        $response = app(AuthController::class)->logoutAll($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertTrue($response->getData(true)['success']);
    }
}
