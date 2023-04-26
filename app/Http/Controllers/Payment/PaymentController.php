<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    private $oRequest;

    private $oService;

    public function __construct(Request $oRequest, PaymentService $oService)
    {
        $this->oRequest = $oRequest;
        $this->oService = $oService;
    }

    public function createCheckout()
    {
        $aResult = $this->oService->createCheckout($this->oRequest->all());
    }
}