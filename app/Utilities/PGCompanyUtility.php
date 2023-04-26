<?php

namespace App\Utilities;

use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;

class PGCompanyUtility
{
    use ResponseTrait;
    /**
     * @var StripeClient
     */
    private $oStripe;

    /**
     * Authenticate stripe api key
     */
    public function setupClient(string $sApiKey)
    {
        $this->oStripe = new StripeClient($sApiKey);
    }

    /**
     * Generate checkout sesssion
     * @param array $aCheckoutParams
     * @return array
     */
    public function createCheckout(array $aCheckoutParams): array
    {
        try {
            $oCheckoutResponse = $this->oStripe->checkout->sessions->create($aCheckoutParams);
            return $this->successResponse($oCheckoutResponse->toArray());
        } catch(ApiErrorException $oException) {
            return $this->failedResponse($oException->getHttpStatus(), $oException->getMessage());
        }
    }

    /**
     * Cancels order in stripe
     * @param string $sPaymentId
     * @return array  
     */
    public function cancelOrder(string $sPaymentId): array
    {
        try {
            $oCancelResponse = $this->oStripe->paymentIntents->cancel($sPaymentId);
            return $this->successResponse($oCancelResponse->toArray());
        } catch(ApiErrorException $oException) {
            return $this->failedResponse($oException->getHttpStatus(), $oException->getMessage());
        }
    }

    /**
     * Retrieve order in stripe api
     * @param string $sPaymentId
     * @return array
     */
    public function getOrder(string $sPaymentId)
    {
        try {
            $oOrderData = $this->oStripe->paymentIntents->retrieve($sPaymentId);
            return $this->successResponse($oOrderData->toArray());
        } catch(ApiErrorException $oException) {
            return $this->failedResponse($oException->getHttpStatus(), $oException->getMessage());
        }
    }

    /**
     * Refund Order in stripe
     * @param array $aRefundParams
     * @return array
     */
    public function refundOrder(array $aRefundParams)
    {
        try {
            $oRefundData = $this->oStripe->refunds->create($aRefundParams);
            return $this->successResponse($oRefundData->toArray());
        } catch (ApiErrorException $oException) {
            return $this->failedResponse($oException->getHttpStatus(), $oException->getMessage());
        }
    }
}