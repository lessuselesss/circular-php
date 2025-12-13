<?php
/**
 * LLMs.txt/AGENTS.md Metadata:
 * Circular Protocol - Standard APIs SDK
 * This file, 'AssetTrait.php', is part of the Circular Protocol PHP SDK.
 * Module: Service/Blockchain/AssetTrait
 * Purpose: Handles asset operations.
 */

namespace CircularProtocol\Api\Service\Blockchain;

use CircularProtocol\Api\Util\Helper;

trait AssetTrait
{
    /**
     * Retrieves an asset by name.
     *
     * @param string $blockchain The blockchain identifier.
     * @param string $name The asset name.
     * @return object JSON response.
     */
    public function getAsset(string $blockchain, string $name): object
    {
        $blockchain = $this->helper->hexFix($blockchain);
        $data = [
            "Blockchain" => $blockchain,
            "AssetName"  => $name,
            "Version"    => $this->version
        ];
        return $this->helper->fetch($this->nagUrl . Helper::GET_ASSET, $data);
    }

    /**
     * Retrieves the list of all assets on a blockchain.
     *
     * @param string $blockchain The blockchain identifier.
     * @return object JSON response.
     */
    public function getAssetList(string $blockchain): object
    {
        $blockchain = $this->helper->hexFix($blockchain);
        $data = [
            "Blockchain" => $blockchain,
            "Version"    => $this->version
        ];
        return $this->helper->fetch($this->nagUrl . Helper::GET_ASSET_LIST, $data);
    }

    /**
     * Retrieves the supply information for an asset.
     *
     * @param string $blockchain The blockchain identifier.
     * @param string $name The asset name.
     * @return object JSON response.
     */
    public function getAssetSupply(string $blockchain, string $name): object
    {
        $blockchain = $this->helper->hexFix($blockchain);
        $data = [
            "Blockchain" => $blockchain,
            "AssetName"  => $name,
            "Version"    => $this->version
        ];
        return $this->helper->fetch($this->nagUrl . Helper::GET_ASSET_SUPPLY, $data);
    }
}
