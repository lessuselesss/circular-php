<?php
/**
 * LLMs.txt/AGENTS.md Metadata:
 * Circular Protocol - Standard APIs SDK
 * This file, 'AnalyticsTrait.php', is part of the Circular Protocol PHP SDK.
 * Module: Service/Blockchain/AnalyticsTrait
 * Purpose: Handles analytics operations.
 */

namespace CircularProtocol\Api\Service\Blockchain;

use CircularProtocol\Api\Util\Helper;

trait AnalyticsTrait
{
    /**
     * Retrieves analytics for a blockchain.
     *
     * @param string $blockchain The blockchain identifier.
     * @return object JSON response.
     */
    public function getAnalytics(string $blockchain): object
    {
        $blockchain = $this->helper->hexFix($blockchain);
        $data = [
            "Blockchain" => $blockchain,
            "Version"    => $this->version
        ];
        return $this->helper->fetch($this->nagUrl . Helper::GET_ANALYTICS, $data);
    }

    /**
     * Retrieves the list of all blockchains.
     *
     * @return object JSON response.
     */
    public function getBlockchains(): object
    {
        $data = [];
        return $this->helper->fetch($this->nagUrl . Helper::GET_BLOCKCHAINS, $data);
    }
}
