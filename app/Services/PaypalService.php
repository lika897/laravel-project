<?php

namespace App\Services;

use App\Enums\TransactionStatusEnum;
use App\Facades\Cart;
use App\Services\Contracts\PaypalServiceContract;
use Srmklive\PayPal\Services\PayPal;

class PaypalService implements PaypalServiceContract
{
    protected Paypal $paypal;

    public function __construct()
    {
        $this->paypal = app(PayPal::class);
        $this->paypal->setApiCredentials(config('paypal'));
        $this->paypal->setAccessToken($this->paypal->getAccessToken());
//        $this->paypal = new PayPalClient;
//
//        $this->paypal->setApiCredentials(config('paypal'));
//        $this->paypal->getAccessToken();

    }

    public function create(): ?string
    {
        $paypalOrder = $this->paypal->createOrder(
            $this->buildOrderRequestDate()
        );

        logs()->info('[Paypal::create] Getting paypal order', [
            'response' => $paypalOrder
        ]);

        return $paypalOrder['id'] ?? null;
    }

    public function capture(string $vendorOrderId): TransactionStatusEnum
    {
        $result = $this->capture($vendorOrderId);

        return match ($result['status']) {
            'COMPLETED', 'APPROVED' => TransactionStatusEnum::Success,
            'CREATE', 'SAVED' => TransactionStatusEnum::Pending,
            default => TransactionStatusEnum::Cancelled
        };
    }

//    protected function buildOrderRequestDate(): array
//    {
//        $currencyCode = config('paypal.currency');
//
//        $items = [];
//
//        Cart::all()->each(function ($item) use ($items, $currencyCode) {
//            $items = [
//                'name' => $item['title'],
//                'quantity' => $item['quantity'],
//                'sku' => $item['sku'],
//                'url' => url(route('products.show', $item['slug'])),
//                'category' => 'PHYSICAL_GOODS',
//                'unit_amount' => [
//                    'currency_code' => $currencyCode,
//                    'value' => round($item['price'], 2),
//                ],
//                'tax' => [
//                    'currency_code' => $currencyCode,
//                    'value' => round(
//                        $item['price'] * config('cart.tax') / 100, 2
//                    ),
//                ]
//
//
//            ];
//        });
//
//        return [
//            'intent' => 'CAPTURE',
//            'purchase_units' => [
//                [
//                    'amount' => [
//                        'currency_code' => $currencyCode,
//                        'value' => Cart::total(),
//                        'breakdown' => [
//                            'item_total' => [
//                                'currency_code' => $currencyCode,
//                                'value' => Cart::subTotal(),
//                            ],
//                            'tax_total' => [
//                                'currency_code' => $currencyCode,
//                                'value' => Cart::tax(),
//                            ]
//                        ]
//                    ],
//                    'items' => []
//                ]
//            ]
//        ];
//    }
    protected function buildOrderRequestDate(): array
    {
        $currencyCode = config('paypal.currency');

        $items = [];
        $itemSubtotal = 0;
        $taxTotal = 0;

        foreach (Cart::all() as $item) {
            $itemTotalValue = $item['price'] * $item['quantity'];
            $itemTaxValue = ($item['price'] * config('cart.tax') / 100) * $item['quantity'];

            $itemSubtotal += $itemTotalValue;
            $taxTotal += $itemTaxValue;

            $items[] = [
                'name' => $item['title'],
                'unit_amount' => [
                    'currency_code' => $currencyCode,
                    'value' => number_format($item['price'], 2, '.', ''),
                ],
                'quantity' => (string) $item['quantity'],
                'tax' => [
                    'currency_code' => $currencyCode,
                    'value' => number_format($item['price'] * config('cart.tax') / 100, 2, '.', ''),
                ],
                'sku' => (string) $item['id'],
                'category' => 'PHYSICAL_GOODS'
            ];
        }

        $totalValue = $itemSubtotal + $taxTotal;

        return [
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => $currencyCode,
                        'value' => number_format($totalValue, 2, '.', ''),
                        'breakdown' => [
                            'item_total' => [
                                'currency_code' => $currencyCode,
                                'value' => number_format($itemSubtotal, 2, '.', ''),
                            ],
                            'tax_total' => [
                                'currency_code' => $currencyCode,
                                'value' => number_format($taxTotal, 2, '.', ''),
                            ],
                        ],
                    ],
                    'items' => $items
                ]
            ]
        ];
    }


}
