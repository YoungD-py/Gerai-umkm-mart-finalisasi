<?php

namespace Tests\Feature\Api;

use App\Http\Controllers\Api\ExpiredProductController;
use App\Models\Good;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Tests\TestCase;

class ExpiredProductControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_index_returns_expired_products()
    {
        $paginator = new LengthAwarePaginator([
            (object) ['id' => 1, 'nama' => 'Produk Expired'],
        ], 1, 15, 1);

        $builder = Mockery::mock();
        $builder->shouldReceive('where')->andReturnSelf();
        $builder->shouldReceive('whereBetween')->andReturnSelf();
        $builder->shouldReceive('with')->with('category')->andReturnSelf();
        $builder->shouldReceive('orderBy')->with('expired_date', 'asc')->andReturnSelf();
        $builder->shouldReceive('paginate')->with(15)->andReturn($paginator);

        $alias = Mockery::mock('alias:' . Good::class);
        $alias->shouldReceive('query')->andReturn($builder);

        $request = Request::create('/api/expired-products', 'GET', [
            'status' => 'expired',
        ]);

        $response = app(ExpiredProductController::class)->index($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertTrue($response->getData(true)['success']);
    }

    public function test_show_returns_not_found_when_product_missing()
    {
        $alias = Mockery::mock('alias:' . Good::class);
        $alias->shouldReceive('with')->with('category')->andReturnSelf();
        $alias->shouldReceive('find')->with(10)->andReturn(null);

        $response = app(ExpiredProductController::class)->show(10);

        $this->assertSame(404, $response->getStatusCode());
        $this->assertFalse($response->getData(true)['success']);
    }
}
