<?php

namespace App\Http\Controllers\Ajax\Payments;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;
use App\Repositories\Contracts\OrderRepositoryContracts;
use App\Services\Contracts\PaypalServiceContract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaypalController extends Controller
{
    public function __construct(
        protected OrderRepositoryContracts $repository,
        protected PaypalServiceContract $service
    )
    {

    }

    public function create(CreateOrderRequest $request)
    {
        $data = $request->validated();
        try {
            DB::beginTransaction();
            $paypalOrderId = $this->service->create();

            if (!$paypalOrderId) {
                throw new \Exception('Could not create paypal order.');
            }

            $data['vendor_order_id'] = $paypalOrderId;

            DB::commit();

            $order = $this->repository->create($data);

            return response()->json([
                'id' => $paypalOrderId
            ]);

        } catch (\Throwable $exception) {
            DB::rollBack();
            logs()->error('[PaypalController] Create order error:' . $exception->getMessage(), [
                'exception' => $exception,
                'data' => $data,
                'user_id' => auth()->id(),

            ]);

            return response()->json([
                'error' => $exception->getMessage(),

            ], 422);
        }
    }

    public function capture(string $vendorOrderId )
    {

    }
}
