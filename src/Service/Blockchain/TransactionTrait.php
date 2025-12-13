<?php
/**
 * LLMs.txt/AGENTS.md Metadata:
 * Circular Protocol - Standard APIs SDK
 * This file, 'TransactionTrait.php', is part of the Circular Protocol PHP SDK.
 * Module: Service/Blockchain/TransactionTrait
 * Purpose: Handles transaction operations.
 */

namespace CircularProtocol\Api\Service\Blockchain;

use CircularProtocol\Api\Util\Helper;

trait TransactionTrait
{
    /**
     * Retrieves a pending transaction by ID.
     *
     * @param string $blockchain The blockchain identifier.
     * @param string $txID The transaction ID.
     * @return object JSON response.
     */
    public function getPendingTransaction(string $blockchain, string $txID): object
    {
        $blockchain = $this->helper->hexFix($blockchain);
        $txID = $this->helper->hexFix($txID);
        $data = [
            "Blockchain" => $blockchain,
            "ID"         => $txID,
            "Version"    => $this->version
        ];
        return $this->helper->fetch($this->nagUrl . Helper::GET_PENDING_TRANSACTION, $data);
    }

    /**
     * Retrieves a transaction by ID.
     *
     * @param string $blockchain The blockchain identifier.
     * @param string $txID The transaction ID.
     * @param int|string $start The starting block.
     * @param int|string $end The ending block.
     * @return object JSON response.
     */
    public function getTransactionByID(string $blockchain, string $txID, $start, $end): object
    {
        $blockchain = $this->helper->hexFix($blockchain);
        $txID = $this->helper->hexFix($txID);
        $data = [
            "Blockchain" => $blockchain,
            "ID"         => $txID,
            "Start"      => strval($start),
            "End"        => strval($end),
            "Version"    => $this->version
        ];
        return $this->helper->fetch($this->nagUrl . Helper::GET_TRANSACTION_BY_ID, $data);
    }

    /**
     * Retrieves transactions by node ID.
     *
     * @param string $blockchain The blockchain identifier.
     * @param string $nodeID The node ID.
     * @param int|string $start The starting block.
     * @param int|string $end The ending block.
     * @return object JSON response.
     */
    public function getTransactionByNode(string $blockchain, string $nodeID, $start, $end): object
    {
        $blockchain = $this->helper->hexFix($blockchain);
        $nodeID = $this->helper->hexFix($nodeID);
        $data = [
            "Blockchain" => $blockchain,
            "NodeID"     => $nodeID,
            "Start"      => strval($start),
            "End"        => strval($end),
            "Version"    => $this->version
        ];
        return $this->helper->fetch($this->nagUrl . Helper::GET_TRANSACTION_BY_NODE, $data);
    }

    /**
     * Retrieves transactions by wallet address.
     *
     * @param string $blockchain The blockchain identifier.
     * @param string $address The wallet address.
     * @param int|string $start The starting block.
     * @param int|string $end The ending block.
     * @return object JSON response.
     */
    public function getTransactionByAddress(string $blockchain, string $address, $start, $end): object
    {
        $blockchain = $this->helper->hexFix($blockchain);
        $address = $this->helper->hexFix($address);
        $data = [
            "Blockchain" => $blockchain,
            "Address"    => $address,
            "Start"      => strval($start),
            "End"        => strval($end),
            "Version"    => $this->version
        ];
        return $this->helper->fetch($this->nagUrl . Helper::GET_TRANSACTION_BY_ADDRESS, $data);
    }

    /**
     * Retrieves transactions by date range.
     *
     * @param string $blockchain The blockchain identifier.
     * @param string $address The wallet address.
     * @param string $startDate The start date.
     * @param string $endDate The end date.
     * @return object JSON response.
     */
    public function getTransactionByDate(string $blockchain, string $address, string $startDate, string $endDate): object
    {
        $blockchain = $this->helper->hexFix($blockchain);
        $address = $this->helper->hexFix($address);
        $data = [
            "Blockchain" => $blockchain,
            "Address"    => $address,
            "StartDate"  => $startDate,
            "EndDate"    => $endDate,
            "Version"    => $this->version
        ];
        return $this->helper->fetch($this->nagUrl . Helper::GET_TRANSACTION_BY_DATE, $data);
    }

    /**
     * Sends a transaction to the blockchain.
     *
     * @param string $id The transaction ID.
     * @param string $from The sender address.
     * @param string $to The recipient address.
     * @param string $timestamp The transaction timestamp.
     * @param string $type The transaction type.
     * @param string $payload The transaction payload.
     * @param string $nonce The transaction nonce.
     * @param string $signature The transaction signature.
     * @param string $blockchain The blockchain identifier.
     * @return object JSON response.
     */
    public function sendTransaction(string $id, string $from, string $to, string $timestamp, string $type, string $payload, string $nonce, string $signature, string $blockchain): object
    {
        $from       = $this->helper->hexFix($from);
        $to         = $this->helper->hexFix($to);
        $id         = $this->helper->hexFix($id);
        $payload    = $this->helper->hexFix($payload);
        $signature  = $this->helper->hexFix($signature);
        $blockchain = $this->helper->hexFix($blockchain);
        $data = [
            "ID"         => $id,
            "From"       => $from,
            "To"         => $to,
            "Timestamp"  => $timestamp,
            "Payload"    => strval($payload),
            "Nonce"      => strval($nonce),
            "Signature"  => $signature,
            "Blockchain" => $blockchain,
            "Type"       => $type,
            "Version"    => $this->version
        ];
        return $this->helper->fetch($this->nagUrl . Helper::ADD_TRANSACTION, $data);
    }

    /**
     * Polls for a transaction outcome until completion or timeout.
     *
     * @param string $blockchain The blockchain identifier.
     * @param string $txID The transaction ID.
     * @param int $timeoutSec The timeout in seconds.
     * @return object The transaction response.
     * @throws \Exception When timeout is exceeded.
     */
    public function getTransactionOutcome(string $blockchain, string $txID, int $timeoutSec): object
    {
        $blockchain  = $this->helper->hexFix($blockchain);
        $txID        = $this->helper->hexFix($txID);
        $startTime   = time();
        $intervalSec = 5;
        $timeout     = $timeoutSec;

        while (true) {
            $elapsedTime = time() - $startTime;
            if ($elapsedTime > $timeout) {
                throw new \Exception('Timeout exceeded');
            }

            $transactionData = $this->getTransactionByID($blockchain, $txID, 0, 10);
            if ($transactionData && $transactionData->Result === 200 &&
                $transactionData->Response !== 'Transaction Not Found' &&
                $transactionData->Response->Status !== 'Pending') {
                return $transactionData->Response;
            }

            sleep($intervalSec);
        }
    }
}
