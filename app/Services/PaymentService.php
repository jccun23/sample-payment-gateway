<?php

namespace App\Services;

use App\Constants\StripeConstants;
use App\Utilities\PGCompanyUtility;

class PaymentService
{
    private $oPGCompanyUtility;

    public function __construct(PGCompanyUtility $oPGCompanyUtility)
    {
        $this->oPGCompanyUtility = $oPGCompanyUtility;
    }

    public function createCheckout(array $aCheckoutRequest)
    {
        $this->setupSettings();
        $aCheckoutParams = $this->formatCheckoutRequest($aCheckoutRequest);
        $aCheckoutResponse = $this->oPGCompanyUtility->createCheckout($aCheckoutParams);
        if($aCheckoutResponse['result'] === 'failed') {
            return $aCheckoutResponse;
        }
    }

    private function setupSettings()
    {
        $this->oPGCompanyUtility->setupClient(env('API_KEY'));
    }

    private function formatCheckoutRequest(array $aParams)
    {
        return [
            StripeConstants::SUCCESS_URL => '',
            StripeConstants::CANCEL_URL  => '',
            StripeConstants::LINE_ITEMS  => [
                [
                    StripeConstants::PRICE_DATA => [
                        StripeConstants::PRODUCT_DATA => [
                            StripeConstants::NAME => '',
                            StripeConstants::UNIT_AMOUNT => '',
                            StripeConstants::QUANTITY => 2,
                        ]
                    ] 
                ]
            ],
            StripeConstants::MODE                => 'payment',
            StripeConstants::PAYMENT_INTENT_DATA => [
                StripeConstants::ON_BEHALF_OF  => '',
                StripeConstants::TRANSFER_DATA => [
                    StripeConstants::DESTINATION => ''
                ]
            ],
            StripeConstants::PAYMENT_METHOD_TYPES => ['card']
        ];
    }
}