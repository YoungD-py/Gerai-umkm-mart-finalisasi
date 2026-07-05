<?php

namespace Tests\Feature\Api;

use App\Http\Controllers\Api\RestockController;
use App\Models\Restock;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Tests\TestCase;

class RestockControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_index_returns_restock_history()
    {
        $paginator = new LengthAwarePaginator([
            (object) ['id' => 1, 'qty_restock' => 5],
        ], 1, 15, 1);

        $builder = Mockery::mock();
        $builder->shouldReceive('with')->with('good', 'user')->andReturnSelf();
        $builder->shouldReceive('orderBy')->with('tgl_restock', 'desc')->andReturnSelf();
        $builder->shouldReceive('paginate')->with(15)->andReturn($paginator);

        $restockAlias = Mockery::mock('alias:' . Restock::class);
        $restockAlias->shouldReceive('with')->with('good', 'user')->andReturn($builder);

        $request = Request::create('/api/restock', 'GET');
        $response = app(RestockController::class)->index($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertTrue($response->getData(true)['success']);
    }

    public function test_show_returns_not_found_when_restock_missing()
    {
        $restockAlias = Mockery::mock('alias:' . Restock::class);
        $restockAlias->shouldReceive('with')->with('good', 'user')->andReturnSelf();
        $restockAlias->shouldReceive('find')->with(55)->andReturn(null);

        $response = app(RestockController::class)->show(55);

        $this->assertSame(404, $response->getStatusCode());
        $this->assertSame('Restock not found', $response->getData(true)['message']);
    }
}
