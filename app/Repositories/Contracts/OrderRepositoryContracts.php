<?php

namespace App\Repositories\Contracts;

use App\Enums\PaymentSystemEnum;
use App\Enums\TransactionStatusEnum;
use App\Models\Order;
use App\Models\Transaction;

interface OrderRepositoryContracts
{
    public function create( array $data): Order|false;

    public function setTransaction(
        string $vendorOrderId,
        PaymentSystemEnum $paymentSystem,
        TransactionStatusEnum $status
    );

}
