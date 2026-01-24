<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\Contracts\InvoiceServiceContract;
use Illuminate\Http\Request;

class InvoicesController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(string $vendorOrderId, InvoiceServiceContract $service)
    {
        try {
            $order = Order::where('vendor_order_id', $vendorOrderId);

            if (auth()->user()->cannot('view', $order)){
                notify()->warning('You do not have permission to access this page.');
                return redirect()->route('home');
            }

            return $service->generate($order)->stream();
        } catch (\Throwable $exception) {
            logs()->error('[InvoicesController]' . $exception->getMessage(), [
                'exception' => $exception,
                'vendor_order_id' => $vendorOrderId,
                'user_id' => auth()->id(),
            ]);

            notify()->warning('You do not have permission to access this page.');

            return redirect()->route('home');

        }

    }
}
