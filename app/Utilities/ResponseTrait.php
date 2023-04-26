<?php

namespace App\Utilities;

use ResponseConstant;

trait ResponseTrait
{
    /**
     * Success Response
     * @param array $aData
     * @return array
     */
    public function successResponse(array $aData): array
    {
        return [
            ResponseConstant::RESULT => ResponseConstant::SUCCESS_RESULT,
            ResponseConstant::DATA   => $aData
        ];
    }

    /**
     * Failed Response
     * @param $iCode
     * @param $sMessage
     * @return array
     */
    public function failedResponse($iCode, $sMessage): array
    {
        return [
            ResponseConstant::RESULT  => ResponseConstant::FAILED_RESULT,
            ResponseConstant::CODE    => $iCode,
            ResponseConstant::MESSAGE => $sMessage
        ];
    }
}