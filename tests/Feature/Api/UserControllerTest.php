<?php

namespace Tests\Feature\Api;

use App\Http\Controllers\Api\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_index_returns_paginated_users()
    {
        $paginator = new LengthAwarePaginator([
            (object) ['id' => 1, 'nama' => 'Admin Test', 'username' => 'admin_test', 'role' => 'ADMIN'],
        ], 1, 10, 1);

        $builder = Mockery::mock();
        $builder->shouldReceive('latest')->andReturnSelf();
        $builder->shouldReceive('paginate')->with(10)->andReturn($paginator);

        $userAlias = Mockery::mock('alias:' . User::class);
        $userAlias->shouldReceive('query')->andReturn($builder);

        $request = Request::create('/api/users', 'GET');
        $response = app(UserController::class)->index($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('Data users berhasil diambil', $response->getData(true)['message']);
    }

    public function test_show_returns_not_found_when_user_missing()
    {
        $userAlias = Mockery::mock('alias:' . User::class);
        $userAlias->shouldReceive('find')->with(99)->andReturn(null);

        $response = app(UserController::class)->show(99);

        $this->assertSame(404, $response->getStatusCode());
        $this->assertFalse($response->getData(true)['success']);
    }

    public function test_destroy_rejects_self_deletion()
    {
        $currentUser = (object) ['id' => 1];
        $targetUser = (object) ['id' => 1];

        $request = Request::create('/api/users/1', 'DELETE');
        $request->setUserResolver(fn () => $currentUser);

        $userAlias = Mockery::mock('alias:' . User::class);
        $userAlias->shouldReceive('find')->with(1)->andReturn($targetUser);

        $response = app(UserController::class)->destroy($request, 1);

        $this->assertSame(403, $response->getStatusCode());
        $this->assertSame('Anda tidak dapat menghapus akun Anda sendiri', $response->getData(true)['message']);
    }
}
