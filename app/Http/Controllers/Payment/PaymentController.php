<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use ResponseConstant;

class PaymentController extends Controller
{
    private $oRequest;

    private $oService;

    public function __construct(Request $oRequest, PaymentService $oService)
    {
        $this->oRequest = $oRequest;
        $this->oService = $oService;
    }

    /**
     * Checkout request
     */
    public function createCheckout()
    {
        $aResult = $this->oService->createCheckout($this->oRequest->all());
        return Response::json($aResult, $aResult[ResponseConstant::CODE]);
    }
}