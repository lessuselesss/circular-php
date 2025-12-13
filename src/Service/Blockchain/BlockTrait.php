<?php
/**
 * LLMs.txt/AGENTS.md Metadata:
 * Circular Protocol - Standard APIs SDK
 * This file, 'BlockTrait.php', is part of the Circular Protocol PHP SDK.
 * Module: Service/Blockchain/BlockTrait
 * Purpose: Handles block operations.
 */

namespace CircularProtocol\Api\Service\Blockchain;

use CircularProtocol\Api\Util\Helper;

trait BlockTrait
{
    /**
     * Retrieves a range of blocks.
     *
     * @param string $blockchain The blockchain identifier.
     * @param int|string $start The starting block number.
     * @param int|string $end The ending block number.
     * @return object JSON response.
     */
    public function getBlockRange(string $blockchain, $start, $end): object
    {
        $blockchain = $this->helper->hexFix($blockchain);
        $data = [
            "Blockchain" => $blockchain,
            "Start"      => strval($start),
            "End"        => strval($end),
            "Version"    => $this->version
        ];
        return $this->helper->fetch($this->nagUrl . Helper::GET_BLOCK_RANGE, $data);
    }

    /**
     * Retrieves a specific block by number.
     *
     * @param string $blockchain The blockchain identifier.
     * @param int|string $num The block number.
     * @return object JSON response.
     */
    public function getBlock(string $blockchain, $num): object
    {
        $blockchain = $this->helper->hexFix($blockchain);
        $data = [
            "Blockchain"  => $blockchain,
            "BlockNumber" => strval($num),
            "Version"     => $this->version
        ];
        return $this->helper->fetch($this->nagUrl . Helper::GET_BLOCK, $data);
    }

    /**
     * Retrieves the current block count (height).
     *
     * @param string $blockchain The blockchain identifier.
     * @return object JSON response.
     */
    public function getBlockCount(string $blockchain): object
    {
        $blockchain = $this->helper->hexFix($blockchain);
        $data = [
            "Blockchain" => $blockchain,
            "Version"    => $this->version
        ];
        return $this->helper->fetch($this->nagUrl . Helper::GET_BLOCK_HEIGHT, $data);
    }
}
