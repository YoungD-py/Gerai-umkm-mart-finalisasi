<?php

namespace Tests\Feature\Api;

use App\Http\Controllers\Api\ReturnController;
use App\Models\ReturnBarang;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Tests\TestCase;

class ReturnControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_index_returns_return_history()
    {
        $paginator = new LengthAwarePaginator([
            (object) ['id' => 1, 'qty_return' => 2],
        ], 1, 15, 1);

        $builder = Mockery::mock();
        $builder->shouldReceive('with')->with(['good.category', 'user'])->andReturnSelf();
        $builder->shouldReceive('filter')->andReturnSelf();
        $builder->shouldReceive('latest')->andReturnSelf();
        $builder->shouldReceive('paginate')->with(15)->andReturn($paginator);

        $returnAlias = Mockery::mock('alias:' . ReturnBarang::class);
        $returnAlias->shouldReceive('query')->andReturn($builder);

        $request = Request::create('/api/return', 'GET');
        $response = app(ReturnController::class)->index($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertTrue($response->getData(true)['success']);
    }

    public function test_show_returns_not_found_when_return_missing()
    {
        $returnAlias = Mockery::mock('alias:' . ReturnBarang::class);
        $returnAlias->shouldReceive('with')->with('good', 'user')->andReturnSelf();
        $returnAlias->shouldReceive('find')->with(21)->andReturn(null);

        $response = app(ReturnController::class)->show(21);

        $this->assertSame(404, $response->getStatusCode());
        $this->assertSame('Return not found', $response->getData(true)['message']);
    }
}
