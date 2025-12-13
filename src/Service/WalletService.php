<?php
/**
 * LLMs.txt/AGENTS.md Metadata:
 * Circular Protocol - Standard APIs SDK
 * This file is part of the Circular Protocol PHP SDK for Standard API/Blockchain interaction.
 * 
 * 'Service/WalletService.php' implements all wallet-specific API functions including
 * wallet existence checks, balance queries, nonce retrieval, and wallet registration.
 * It is a service component used internally by the main CircularProtocolAPI facade.
 *
 * @package CircularProtocol\Api
 */

namespace CircularProtocol\Api\Service;

use CircularProtocol\Api\Util\Helper;
use CircularProtocol\Api\Util\Crypto;

/**
 * WalletService handles all wallet-related API operations.
 */
class WalletService
{
    private string $nagUrl;
    private string $version;
    private Helper $helper;
    private Crypto $crypto;

    /**
     * @param string $nagUrl The NAG API base URL.
     * @param string $version The SDK version.
     * @param Helper $helper The helper utility instance.
     * @param Crypto $crypto The crypto utility instance.
     */
    public function __construct(string $nagUrl, string $version, Helper $helper, Crypto $crypto)
    {
        $this->nagUrl = $nagUrl;
        $this->version = $version;
        $this->helper = $helper;
        $this->crypto = $crypto;
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

    /**
     * Check if a wallet exists on a blockchain.
     *
     * @param string $blockchain The blockchain identifier.
     * @param string $address The wallet address.
     * @return object JSON response.
     */
    public function checkWallet(string $blockchain, string $address): object
    {
        $blockchain = $this->helper->hexFix($blockchain);
        $address = $this->helper->hexFix($address);
        $data = [
            "Blockchain" => $blockchain,
            "Address"    => $address,
            "Version"    => $this->version
        ];
        return $this->helper->fetch($this->nagUrl . Helper::CHECK_WALLET, $data);
    }

    /**
     * Retrieves a wallet from a blockchain.
     *
     * @param string $blockchain The blockchain identifier.
     * @param string $address The wallet address.
     * @return object JSON response.
     */
    public function getWallet(string $blockchain, string $address): object
    {
        $blockchain = $this->helper->hexFix($blockchain);
        $address = $this->helper->hexFix($address);
        $data = [
            "Blockchain" => $blockchain,
            "Address"    => $address,
            "Version"    => $this->version
        ];
        return $this->helper->fetch($this->nagUrl . Helper::GET_WALLET, $data);
    }

    /**
     * Retrieves the balance of a specified asset in a wallet.
     *
     * @param string $blockchain The blockchain identifier.
     * @param string $address The wallet address.
     * @param string $asset The asset name (e.g., 'CIRX').
     * @return object JSON response.
     */
    public function getWalletBalance(string $blockchain, string $address, string $asset): object
    {
        $blockchain = $this->helper->hexFix($blockchain);
        $address = $this->helper->hexFix($address);
        $data = [
            "Blockchain" => $blockchain,
            "Address"    => $address,
            "Asset"      => $asset,
            "Version"    => $this->version
        ];
        return $this->helper->fetch($this->nagUrl . Helper::GET_WALLET_BALANCE, $data);
    }

    /**
     * Retrieves the nonce for a wallet.
     *
     * @param string $blockchain The blockchain identifier.
     * @param string $address The wallet address.
     * @return object JSON response.
     */
    public function getWalletNonce(string $blockchain, string $address): object
    {
        $blockchain = $this->helper->hexFix($blockchain);
        $address = $this->helper->hexFix($address);
        $data = [
            "Blockchain" => $blockchain,
            "Address"    => $address,
            "Version"    => $this->version
        ];
        return $this->helper->fetch($this->nagUrl . Helper::GET_WALLET_NONCE, $data);
    }

    /**
     * Retrieves recent transactions from a wallet.
     *
     * @param string $blockchain The blockchain identifier.
     * @param string $address The wallet address.
     * @return object JSON response.
     */
    public function getLatestTransactions(string $blockchain, string $address): object
    {
        $blockchain = $this->helper->hexFix($blockchain);
        $address = $this->helper->hexFix($address);
        $data = [
            "Blockchain" => $blockchain,
            "Address"    => $address,
            "Version"    => $this->version
        ];
        return $this->helper->fetch($this->nagUrl . Helper::GET_LATEST_TRANSACTIONS, $data);
    }

    /**
     * Registers a wallet on a blockchain.
     *
     * @param string $blockchain The blockchain identifier.
     * @param string $publicKey The wallet public key.
     * @param BlockchainService $blockchainService The blockchain service for sending transactions.
     * @return object JSON response.
     */
    public function registerWallet(string $blockchain, string $publicKey, BlockchainService $blockchainService): object
    {
        $blockchain = $this->helper->hexFix($blockchain);
        $publicKey = $this->helper->hexFix($publicKey);
        $from = hash('sha256', $publicKey);
        $to = $from;
        $nonce = '0';
        $type = 'C_TYPE_REGISTERWALLET';
        $payloadObj = [
            "Action"    => "CP_REGISTERWALLET",
            "PublicKey" => $publicKey,
        ];
        $jsonstr = json_encode($payloadObj);
        $payload = $this->helper->stringToHex($jsonstr);
        $timestamp = $this->helper->getFormattedTimestamp();
        $id = hash('sha256', $blockchain . $from . $to . $payload . $nonce . $timestamp);
        $signature = "";
        
        return $blockchainService->sendTransaction($id, $from, $to, $timestamp, $type, $payload, $nonce, $signature, $blockchain);
    }
}
