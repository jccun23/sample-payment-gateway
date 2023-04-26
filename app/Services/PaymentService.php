<?php

namespace App\Services;

use App\Constants\OrderConstant;
use App\Constants\StripeConstants;
use App\Repositories\OrderRepository;
use App\Utilities\EncryptionUtility;
use App\Utilities\PGCompanyUtility;
use ResponseConstant;

class PaymentService
{
    private $oPGCompanyUtility;

    /**
     * @var OrderRepository
     */
    private $oOrderRepository;

    /**
     * PaymentService Constructor
     */
    public function __construct(PGCompanyUtility $oPGCompanyUtility, OrderRepository $oOrderRepository)
    {
        $this->oPGCompanyUtility = $oPGCompanyUtility;
        $this->oOrderRepository = $oOrderRepository;
    }

    /**
     * Handles the checkout logic
     */
    public function createCheckout(array $aCheckoutRequest)
    {
        $this->setupSettings();
        $aCheckoutParams = $this->formatCheckoutRequest($aCheckoutRequest);
        $aCheckoutResponse = $this->oPGCompanyUtility->createCheckout($aCheckoutParams);
        if($aCheckoutResponse['result'] === 'failed') {
            return $aCheckoutResponse;
        }

        $aCheckoutData = $aCheckoutResponse[ResponseConstant::DATA];
        $aRedisData = $this->formatPendingOrder($aCheckoutRequest, $aCheckoutData);
        $this->oOrderRepository->storeOrderInRedis($aCheckoutData[StripeConstants::PAYMENT_INTENT], $aRedisData);
        $sCheckoutUrl = $aCheckoutData[StripeConstants::URL];
        return [
            ResponseConstant::RESULT => ResponseConstant::SUCCESS_RESULT,
            ResponseConstant::CODE   => 200,
            ResponseConstant::DATA   => $sCheckoutUrl
        ];
    }

    /**
     * Handles the refunding of payment
     */
    public function refundPayment(array $aCheckoutParams)
    {

    }

    /**
     * Handles the retrieving of the admin settings and authentication of stripe api key
     */
    private function setupSettings()
    {
        $this->oPGCompanyUtility->setupClient(env('API_KEY'));
    }

    /**
     * Formats checkout request for stripe
     */
    private function formatCheckoutRequest(array $aParams)
    {
        return [
            StripeConstants::SUCCESS_URL => env('APP_FRONT_URL') . '/internal/callback/success',
            StripeConstants::CANCEL_URL  => env('APP_FRONT_URL') . '/internal/callback/cancel',
            StripeConstants::LINE_ITEMS  => [
                [
                    StripeConstants::PRICE_DATA => [
                        StripeConstants::PRODUCT_DATA => [
                            StripeConstants::NAME        => $aParams[OrderConstant::NAME],
                            StripeConstants::UNIT_AMOUNT => $aParams[OrderConstant::AMOUNT],
                            StripeConstants::QUANTITY    => $aParams[OrderConstant::QUANTITY],
                        ]
                    ] 
                ]
            ],
            StripeConstants::MODE                => 'payment',
            StripeConstants::PAYMENT_INTENT_DATA => [
                StripeConstants::ON_BEHALF_OF  => 'acct_....',
                StripeConstants::TRANSFER_DATA => [
                    StripeConstants::DESTINATION => 'acct_....'
                ]
            ],
            StripeConstants::PAYMENT_METHOD_TYPES => ['card']
        ];
    }

    private function formatPendingOrder(array $aOrderRequest, array $aStripeData)
    {
        return [
            OrderConstant::PAYMENT_ID => $aStripeData[StripeConstants::PAYMENT_INTENT],
            OrderConstant::AMOUNT_PAID => null,
            OrderConstant::STATUS      => 2,
            OrderConstant::QUANTITY    => $aOrderRequest[OrderConstant::QUANTITY],
            OrderConstant::CURRENCY    => EncryptionUtility::encryptString($aOrderRequest[OrderConstant::CURRENCY])
        ];
    }
}