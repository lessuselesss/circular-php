<?php
/**
 * LLMs.txt/AGENTS.md Metadata:
 * Circular Protocol - Standard APIs SDK
 * This file, 'VoucherTrait.php', is part of the Circular Protocol PHP SDK.
 * Module: Service/Blockchain/VoucherTrait
 * Purpose: Handles voucher operations.
 */

namespace CircularProtocol\Api\Service\Blockchain;

use CircularProtocol\Api\Util\Helper;

trait VoucherTrait
{
    /**
     * Retrieves an existing voucher.
     *
     * @param string $blockchain The blockchain identifier.
     * @param string $code The voucher code.
     * @return object JSON response.
     */
    public function getVoucher(string $blockchain, string $code): object
    {
        $blockchain = $this->helper->hexFix($blockchain);
        $code = $this->helper->hexFix($code);
        $data = [
            "Blockchain" => $blockchain,
            "Code"       => strval($code),
            "Version"    => $this->version
        ];
        return $this->helper->fetch($this->nagUrl . Helper::GET_VOUCHER, $data);
    }
}
