<?php

namespace Tests\Feature\Api;

use App\Http\Controllers\Api\HistoryController;
use App\Models\BiayaOperasional;
use App\Models\Restock;
use App\Models\ReturnBarang;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Tests\TestCase;

class HistoryControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_transactions_returns_history_data()
    {
        $paginator = new LengthAwarePaginator([
            (object) ['id' => 1, 'total_harga' => 15000],
        ], 1, 15, 1);

        $builder = Mockery::mock();
        $builder->shouldReceive('with')->with(['user', 'orders.good'])->andReturnSelf();
        $builder->shouldReceive('orderBy')->with('created_at', 'desc')->andReturnSelf();
        $builder->shouldReceive('paginate')->with(15)->andReturn($paginator);

        $alias = Mockery::mock('alias:' . Transaction::class);
        $alias->shouldReceive('query')->andReturn($builder);

        $request = Request::create('/api/history/transactions', 'GET');
        $response = app(HistoryController::class)->transactions($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertTrue($response->getData(true)['success']);
    }

    public function test_restock_returns_history_data()
    {
        $paginator = new LengthAwarePaginator([
            (object) ['id' => 1, 'qty_restock' => 10],
        ], 1, 15, 1);

        $builder = Mockery::mock();
        $builder->shouldReceive('with')->with('good', 'user')->andReturnSelf();
        $builder->shouldReceive('orderBy')->with('tgl_restock', 'desc')->andReturnSelf();
        $builder->shouldReceive('paginate')->with(15)->andReturn($paginator);

        $alias = Mockery::mock('alias:' . Restock::class);
        $alias->shouldReceive('query')->andReturn($builder);

        $request = Request::create('/api/history/restock', 'GET');
        $response = app(HistoryController::class)->restock($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertTrue($response->getData(true)['success']);
    }

    public function test_operational_returns_history_data()
    {
        $paginator = new LengthAwarePaginator([
            (object) ['id' => 1, 'nominal' => 5000],
        ], 1, 15, 1);

        $builder = Mockery::mock();
        $builder->shouldReceive('orderBy')->with('tanggal', 'desc')->andReturnSelf();
        $builder->shouldReceive('paginate')->with(15)->andReturn($paginator);

        $alias = Mockery::mock('alias:' . BiayaOperasional::class);
        $alias->shouldReceive('query')->andReturn($builder);

        $request = Request::create('/api/history/operational', 'GET');
        $response = app(HistoryController::class)->operational($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertTrue($response->getData(true)['success']);
    }

    public function test_return_returns_history_data()
    {
        $paginator = new LengthAwarePaginator([
            (object) ['id' => 1, 'qty_return' => 2],
        ], 1, 15, 1);

        $builder = Mockery::mock();
        $builder->shouldReceive('with')->with('good', 'user')->andReturnSelf();
        $builder->shouldReceive('orderBy')->with('tgl_return', 'desc')->andReturnSelf();
        $builder->shouldReceive('paginate')->with(15)->andReturn($paginator);

        $alias = Mockery::mock('alias:' . ReturnBarang::class);
        $alias->shouldReceive('query')->andReturn($builder);

        $request = Request::create('/api/history/return', 'GET');
        $response = app(HistoryController::class)->return($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertTrue($response->getData(true)['success']);
    }
}
