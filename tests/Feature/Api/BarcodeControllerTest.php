<?php

namespace Tests\Feature\Api;

use App\Http\Controllers\Api\BarcodeController;
use App\Models\Good;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class BarcodeControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    private function mockBarcodeLookup($good): void
    {
        $goodMock = Mockery::mock('alias:' . Good::class);
        $goodMock->shouldReceive('with')->with('category')->andReturnSelf();
        $goodMock->shouldReceive('whereRaw')->andReturnSelf();
        $goodMock->shouldReceive('first')->andReturn($good);
    }

    public function test_generate_barcode_returns_new_barcode()
    {
        $goodMock = Mockery::mock('alias:' . Good::class);
        $goodMock->shouldReceive('generateBarcodeStatic')
            ->once()
            ->with('makanan', 'Produk Baru')
            ->andReturn('M-PRD-999');

        $request = Request::create('/api/barcode/generate', 'POST', [
            'type' => 'makanan',
            'nama' => 'Produk Baru',
        ]);

        $response = app(BarcodeController::class)->generate($request);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('M-PRD-999', $response->getData(true)['data']['barcode']);
    }

    public function test_validate_barcode_returns_success_when_barcode_exists()
    {
        $good = (object) [
            'id' => 1,
            'barcode' => 'M-PRD-001',
            'nama' => 'Produk Test',
        ];

        $this->mockBarcodeLookup($good);

        $request = Request::create('/api/barcode/validate', 'POST', [
            'barcode' => 'M-PRD-001',
        ]);

        $response = app(BarcodeController::class)->validateBarcode($request);

        $this->assertSame(200, $response->getStatusCode());
        $payload = $response->getData(true);
        $this->assertTrue($payload['data']['exists']);
        $this->assertSame(1, $payload['data']['good']['id']);
        $this->assertSame('M-PRD-001', $payload['data']['good']['barcode']);
    }

    public function test_validate_barcode_returns_not_found_when_barcode_missing()
    {
        $this->mockBarcodeLookup(null);

        $request = Request::create('/api/barcode/validate', 'POST', [
            'barcode' => 'MISSING-001',
        ]);

        $response = app(BarcodeController::class)->validateBarcode($request);

        $this->assertSame(404, $response->getStatusCode());
        $this->assertFalse($response->getData(true)['data']['exists']);
    }

    public function test_get_by_barcode_returns_good_data()
    {
        $good = (object) [
            'id' => 2,
            'barcode' => 'F-ABC-123',
            'nama' => 'Produk Lain',
        ];

        $this->mockBarcodeLookup($good);

        $response = app(BarcodeController::class)->getByBarcode('F-ABC-123');

        $this->assertSame(200, $response->getStatusCode());
        $payload = $response->getData(true);
        $this->assertSame(2, $payload['data']['id']);
        $this->assertSame('F-ABC-123', $payload['data']['barcode']);
    }

    public function test_get_by_barcode_returns_not_found_when_missing()
    {
        $this->mockBarcodeLookup(null);

        $response = app(BarcodeController::class)->getByBarcode('UNKNOWN-999');

        $this->assertSame(404, $response->getStatusCode());
    }

    public function test_scan_returns_good_and_qty()
    {
        $good = (object) [
            'id' => 3,
            'barcode' => 'H-XYZ-777',
            'nama' => 'Produk Scan',
        ];

        $this->mockBarcodeLookup($good);

        $request = Request::create('/api/barcode/scan', 'POST', [
            'barcode' => 'H-XYZ-777',
            'qty' => 3,
        ]);

        $response = app(BarcodeController::class)->scan($request);

        $this->assertSame(200, $response->getStatusCode());
        $payload = $response->getData(true);
        $this->assertSame(3, $payload['data']['good']['id']);
        $this->assertSame(3, $payload['data']['qty']);
    }

    public function test_scan_returns_not_found_when_barcode_missing()
    {
        $this->mockBarcodeLookup(null);

        $request = Request::create('/api/barcode/scan', 'POST', [
            'barcode' => 'UNKNOWN-999',
            'qty' => 1,
        ]);

        $response = app(BarcodeController::class)->scan($request);

        $this->assertSame(404, $response->getStatusCode());
    }
}
