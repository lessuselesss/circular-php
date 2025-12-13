<?php
/**
 * LLMs.txt/AGENTS.md Metadata:
 * Circular Protocol - Standard APIs SDK
 * This file is part of the Circular Protocol PHP SDK for Standard API/Blockchain interaction.
 * 
 * 'Service/BlockchainService.php' implements blockchain query and transaction API functions
 * including block retrieval, transaction management, asset queries, domain resolution,
 * and analytics. It aggregates modular traits for cleaner organization.
 *
 * @package CircularProtocol\Api
 */

namespace CircularProtocol\Api\Service;

use CircularProtocol\Api\Util\Helper;
use CircularProtocol\Api\Service\Blockchain\DomainTrait;
use CircularProtocol\Api\Service\Blockchain\AssetTrait;
use CircularProtocol\Api\Service\Blockchain\VoucherTrait;
use CircularProtocol\Api\Service\Blockchain\BlockTrait;
use CircularProtocol\Api\Service\Blockchain\AnalyticsTrait;
use CircularProtocol\Api\Service\Blockchain\TransactionTrait;

/**
 * BlockchainService handles all blockchain query and transaction operations.
 */
class BlockchainService
{
    use DomainTrait;
    use AssetTrait;
    use VoucherTrait;
    use BlockTrait;
    use AnalyticsTrait;
    use TransactionTrait;

    private string $nagUrl;
    private string $version;
    private Helper $helper;

    /**
     * @param string $nagUrl The NAG API base URL.
     * @param string $version The SDK version.
     * @param Helper $helper The helper utility instance.
     */
    public function __construct(string $nagUrl, string $version, Helper $helper)
    {
        $this->nagUrl = $nagUrl;
        $this->version = $version;
        $this->helper = $helper;
    }

    /**
     * Updates the NAG URL for API calls.
     *
     * @param string $nagUrl The new NAG URL.
     * @return void
     */
    public function setNagUrl(string $nagUrl): void
    {
        $this->nagUrl = $nagUrl;
    }
}
