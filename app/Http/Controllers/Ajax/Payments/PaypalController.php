<?php

namespace App\Http\Controllers\Ajax\Payments;

use App\Enums\OrderStatusEnum;
use App\Enums\PaymentSystemEnum;
use App\Facades\Cart;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;
use App\Models\Order;
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
            $data = $request->validated();

            $data['vendor_order_id'] = $paypalOrderId;
            $data['status'] = 'pending';
            $data['total'] = Cart::total();
            $data['user_id'] = auth()->id();

            $data['name'] = $data['first_name'];
            $data['surname'] = $data['last_name'];
            unset($data['first_name'], $data['last_name']);

            $this->repository->create($data);

            DB::commit();



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

    public function capture(string $vendorOrderId)
    {
        try {
            DB::beginTransaction();

            $response = $this->service->capture($vendorOrderId);

            $this->repository->setTransaction($vendorOrderId,
                PaymentSystemEnum::Paypal,
                $response
            );

            Cart::clear();

            DB::commit();

//            return response()->json($response);
            return response()->json([
                'orderId' => $vendorOrderId
            ]);


        } catch (\Throwable $exception) {
            DB::rollBack();
            logger()->error('[PaypalController] Capture error', [
                'exception' => $exception,
                'vendorOrderId' => $vendorOrderId
            ]);

            Order::where('vendorOrderId', $vendorOrderId)->update([
                'status' => OrderStatusEnum::Failed_Transaction
            ]);

            return response()->json([
                'error' => $exception->getMessage()
            ], 422);
        }
    }

}
