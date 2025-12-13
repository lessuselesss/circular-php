<?php
/**
 * LLMs.txt/AGENTS.md Metadata:
 * Circular Protocol - Standard APIs SDK
 * This file, 'DomainTrait.php', is part of the Circular Protocol PHP SDK.
 * Module: Service/Blockchain/DomainTrait
 * Purpose: Handles domain resolution operations.
 */

namespace CircularProtocol\Api\Service\Blockchain;

use CircularProtocol\Api\Util\Helper;

trait DomainTrait
{
    /**
     * Resolves a domain name to a wallet address.
     *
     * @param string $blockchain The blockchain identifier.
     * @param string $name The domain name.
     * @return object JSON response.
     */
    public function getDomain(string $blockchain, string $name): object
    {
        $blockchain = $this->helper->hexFix($blockchain);
        $data = [
            "Blockchain" => $blockchain,
            "Domain"     => $name,
            "Version"    => $this->version
        ];
        return $this->helper->fetch($this->nagUrl . Helper::RESOLVE_DOMAIN, $data);
    }
}
