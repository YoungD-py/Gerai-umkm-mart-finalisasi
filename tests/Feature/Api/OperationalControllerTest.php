<?php

namespace Tests\Feature\Api;

use App\Http\Controllers\Api\OperationalController;
use App\Models\BiayaOperasional;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Tests\TestCase;

class OperationalControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_index_returns_operational_expenses()
    {
        $paginator = new LengthAwarePaginator([
            (object) ['id' => 1, 'uraian' => 'Operasional Test'],
        ], 1, 15, 1);

        $builder = Mockery::mock();
        $builder->shouldReceive('latest')->andReturnSelf();
        $builder->shouldReceive('paginate')->with(15)->andReturn($paginator);

        $alias = Mockery::mock('alias:' . BiayaOperasional::class);
        $alias->shouldReceive('query')->andReturn($builder);

        $request = Request::create('/api/operational', 'GET');
        $response = app(OperationalController::class)->index($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertTrue($response->getData(true)['success']);
    }

    public function test_show_returns_not_found_when_operational_missing()
    {
        $alias = Mockery::mock('alias:' . BiayaOperasional::class);
        $alias->shouldReceive('find')->with(12)->andReturn(null);

        $response = app(OperationalController::class)->show(12);

        $this->assertSame(404, $response->getStatusCode());
        $this->assertFalse($response->getData(true)['success']);
    }
}
