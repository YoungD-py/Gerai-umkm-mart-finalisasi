<?php

namespace Tests\Feature\Api;

use App\Http\Controllers\Api\GoodController;
use App\Models\Good;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Tests\TestCase;

class GoodControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_index_returns_goods_data()
    {
        $paginator = new LengthAwarePaginator([
            (object) ['id' => 1, 'nama' => 'Produk Test', 'barcode' => 'M-PRD-001'],
        ], 1, 15, 1);

        $builder = Mockery::mock();
        $builder->shouldReceive('with')->with('category')->andReturnSelf();
        $builder->shouldReceive('filter')->andReturnSelf();
        $builder->shouldReceive('latest')->andReturnSelf();
        $builder->shouldReceive('paginate')->with(15)->andReturn($paginator);

        $goodAlias = Mockery::mock('alias:' . Good::class);
        $goodAlias->shouldReceive('query')->andReturn($builder);

        $request = Request::create('/api/goods', 'GET');
        $response = app(GoodController::class)->index($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertTrue($response->getData(true)['success']);
    }

    public function test_show_returns_not_found_when_good_missing()
    {
        $goodAlias = Mockery::mock('alias:' . Good::class);
        $goodAlias->shouldReceive('with')->with('category')->andReturnSelf();
        $goodAlias->shouldReceive('find')->with(77)->andReturn(null);

        $response = app(GoodController::class)->show(77);

        $this->assertSame(404, $response->getStatusCode());
        $this->assertSame('Good not found', $response->getData(true)['message']);
    }

    public function test_destroy_returns_not_found_when_good_missing()
    {
        $goodAlias = Mockery::mock('alias:' . Good::class);
        $goodAlias->shouldReceive('find')->with(77)->andReturn(null);

        $response = app(GoodController::class)->destroy(77);

        $this->assertSame(404, $response->getStatusCode());
        $this->assertSame('Good not found', $response->getData(true)['message']);
    }
}
