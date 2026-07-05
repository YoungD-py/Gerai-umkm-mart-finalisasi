<?php

namespace Tests\Feature\Api;

use App\Http\Controllers\Api\ReportController;
use App\Models\BiayaOperasional;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class ReportControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_summary_returns_report_data()
    {
        $transactionBuilder = Mockery::mock();
        $transactionBuilder->shouldReceive('whereBetween')->andReturnSelf();
        $transactionBuilder->shouldReceive('sum')->with('total_harga')->andReturn(100000);

        $expenseBuilder = Mockery::mock();
        $expenseBuilder->shouldReceive('whereBetween')->andReturnSelf();
        $expenseBuilder->shouldReceive('sum')->with('nominal')->andReturn(25000);

        $transactionAlias = Mockery::mock('alias:' . Transaction::class);
        $transactionAlias->shouldReceive('where')->with('status', 'LUNAS')->andReturn($transactionBuilder);

        $expenseAlias = Mockery::mock('alias:' . BiayaOperasional::class);
        $expenseAlias->shouldReceive('whereBetween')->andReturn($expenseBuilder);

        $request = Request::create('/api/reports/summary', 'GET', [
            'tgl_awal' => '2026-07-01',
            'tgl_akhir' => '2026-07-02',
        ]);

        $response = app(ReportController::class)->summary($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(100000, $response->getData(true)['data']['total_sales']);
    }

    public function test_sales_returns_validation_error_when_dates_missing()
    {
        $request = Request::create('/api/reports/sales', 'GET');
        $response = app(ReportController::class)->sales($request);

        $this->assertSame(422, $response->getStatusCode());
        $this->assertFalse($response->getData(true)['success']);
    }
}
