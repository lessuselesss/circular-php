<?php
/**
 * LLMs.txt/AGENTS.md Metadata:
 * Circular Protocol - Standard APIs SDK
 * This file is part of the Circular Protocol PHP SDK for Standard API/Blockchain interaction.
 * 
 * 'CircularProtocolAPI.php' is the main non-breaking Facade for the SDK. It preserves
 * the original public interface and delegates all public method calls to internal,
 * modular service classes (WalletService, BlockchainService, ContractService),
 * ensuring backward compatibility for external users interacting with the
 * Circular Layer 1 Blockchain Protocol.
 *
 * @package CircularProtocol\Api
 */

namespace CircularProtocol\Api;

use CircularProtocol\Api\Service\WalletService;
use CircularProtocol\Api\Service\BlockchainService;
use CircularProtocol\Api\Service\ContractService;
use CircularProtocol\Api\Util\Helper;
use CircularProtocol\Api\Util\Crypto;

/**
 * CircularProtocolAPI - Main SDK Facade
 * 
 * This class provides a unified interface to the Circular Protocol blockchain.
 * All original method signatures are preserved for backward compatibility.
 */
class CircularProtocolAPI
{
    // =========================================================================
    // PRIVATE STATE VARIABLES
    // =========================================================================
    
    private string $version;
    public $lastError;
    private string $NAG_KEY;
    private string $NAG_URL;

    // =========================================================================
    // INTERNAL SERVICE DELEGATES
    // =========================================================================
    
    private Helper $helper;
    private Crypto $crypto;
    private WalletService $walletService;
    private BlockchainService $blockchainService;
    private ContractService $contractService;

    // =========================================================================
    // CONSTRUCTOR
    // =========================================================================

    public function __construct()
    {
        $this->version   = '1.0.8';
        $this->lastError = null;
        $this->NAG_KEY   = '';
        $this->NAG_URL   = 'https://nag.circularlabs.io/NAG.php?cep=';

        // Initialize utility instances
        $this->helper = new Helper();
        $this->crypto = new Crypto();

        // Initialize service instances with dependencies
        $this->walletService = new WalletService(
            $this->NAG_URL,
            $this->version,
            $this->helper,
            $this->crypto
        );
        $this->blockchainService = new BlockchainService(
            $this->NAG_URL,
            $this->version,
            $this->helper
        );
        $this->contractService = new ContractService(
            $this->NAG_URL,
            $this->version,
            $this->helper
        );
    }

    // =========================================================================
    // PUBLIC GETTERS AND SETTERS (State Management - Kept in Facade)
    // =========================================================================

    public function setNAGKey($key)
    {
        $this->NAG_KEY = $key;
    }

    public function getNAGKey()
    {
        return $this->NAG_KEY;
    }

    public function setNAGURL($url)
    {
        $this->NAG_URL = $url;
        // Update all services with new URL
        $this->walletService->setNagUrl($url);
        $this->blockchainService->setNagUrl($url);
        $this->contractService->setNagUrl($url);
    }

    public function getNAGURL()
    {
        return $this->NAG_URL;
    }

    public function getVersion()
    {
        return $this->version;
    }

    // =========================================================================
    // UTILITY METHODS (Delegated to Helper)
    // =========================================================================

    public function fetch($url, $data)
    {
        return $this->helper->fetch($url, $data);
    }

    public function padNumber($num)
    {
        return $this->helper->padNumber($num);
    }

    public function getFormattedTimestamp()
    {
        return $this->helper->getFormattedTimestamp();
    }

    public function stringToHex($str)
    {
        return $this->helper->stringToHex($str);
    }

    public function hexToString($hex)
    {
        return $this->helper->hexToString($hex);
    }

    public function hexFix($word)
    {
        return $this->helper->hexFix($word);
    }

    // =========================================================================
    // SIGNATURE FUNCTIONS (Delegated to Crypto)
    // =========================================================================

    public function signMessage($message, $privateKey)
    {
        return $this->crypto->signMessage($message, $privateKey);
    }

    public function verifySignature($publicKey, $message, $signature)
    {
        return $this->crypto->verifySignature($publicKey, $message, $signature);
    }

    public function getPublicKey($privateKey)
    {
        return $this->crypto->getPublicKey($privateKey);
    }

    public function keysFromSeedPhrase($seedphrase)
    {
        return $this->crypto->keysFromSeedPhrase($seedphrase);
    }

    // =========================================================================
    // SMART CONTRACT FUNCTIONS (Delegated to ContractService)
    // =========================================================================

    public function testContract($blockchain, $from, $project)
    {
        return $this->contractService->testContract($blockchain, $from, $project);
    }

    public function callContract($blockchain, $from, $address, $request)
    {
        return $this->contractService->callContract($blockchain, $from, $address, $request);
    }

    // =========================================================================
    // WALLET FUNCTIONS (Delegated to WalletService)
    // =========================================================================

    public function checkWallet($blockchain, $address)
    {
        return $this->walletService->checkWallet($blockchain, $address);
    }

    public function getWallet($blockchain, $address)
    {
        return $this->walletService->getWallet($blockchain, $address);
    }

    public function getWalletBalance($blockchain, $address, $asset)
    {
        return $this->walletService->getWalletBalance($blockchain, $address, $asset);
    }

    public function getWalletNonce($blockchain, $address)
    {
        return $this->walletService->getWalletNonce($blockchain, $address);
    }

    public function getLatestTransactions($blockchain, $address)
    {
        return $this->walletService->getLatestTransactions($blockchain, $address);
    }

    public function registerWallet($blockchain, $publicKey)
    {
        return $this->walletService->registerWallet($blockchain, $publicKey, $this->blockchainService);
    }

    // =========================================================================
    // DOMAIN MANAGEMENT FUNCTIONS (Delegated to BlockchainService)
    // =========================================================================

    public function getDomain($blockchain, $name)
    {
        return $this->blockchainService->getDomain($blockchain, $name);
    }

    // =========================================================================
    // ASSET MANAGEMENT FUNCTIONS (Delegated to BlockchainService)
    // =========================================================================

    public function getAsset($blockchain, $name)
    {
        return $this->blockchainService->getAsset($blockchain, $name);
    }

    public function getAssetList($blockchain)
    {
        return $this->blockchainService->getAssetList($blockchain);
    }

    public function getAssetSupply($blockchain, $name)
    {
        return $this->blockchainService->getAssetSupply($blockchain, $name);
    }

    // =========================================================================
    // VOUCHER MANAGEMENT FUNCTIONS (Delegated to BlockchainService)
    // =========================================================================

    public function getVoucher($blockchain, $code)
    {
        return $this->blockchainService->getVoucher($blockchain, $code);
    }

    // =========================================================================
    // BLOCK MANAGEMENT FUNCTIONS (Delegated to BlockchainService)
    // =========================================================================

    public function getBlockRange($blockchain, $start, $end)
    {
        return $this->blockchainService->getBlockRange($blockchain, $start, $end);
    }

    public function getBlock($blockchain, $num)
    {
        return $this->blockchainService->getBlock($blockchain, $num);
    }

    public function getBlockCount($blockchain)
    {
        return $this->blockchainService->getBlockCount($blockchain);
    }

    // =========================================================================
    // ANALYTICS FUNCTIONS (Delegated to BlockchainService)
    // =========================================================================

    public function getAnalytics($blockchain)
    {
        return $this->blockchainService->getAnalytics($blockchain);
    }

    // =========================================================================
    // BLOCKCHAIN FUNCTIONS (Delegated to BlockchainService)
    // =========================================================================

    public function getBlockchains()
    {
        return $this->blockchainService->getBlockchains();
    }

    // =========================================================================
    // TRANSACTION MANAGEMENT FUNCTIONS (Delegated to BlockchainService)
    // =========================================================================

    public function getPendingTransaction($blockchain, $txID)
    {
        return $this->blockchainService->getPendingTransaction($blockchain, $txID);
    }

    public function getTransactionByID($blockchain, $txID, $start, $end)
    {
        return $this->blockchainService->getTransactionByID($blockchain, $txID, $start, $end);
    }

    public function getTransactionByNode($blockchain, $nodeID, $start, $end)
    {
        return $this->blockchainService->getTransactionByNode($blockchain, $nodeID, $start, $end);
    }

    public function getTransactionByAddress($blockchain, $address, $start, $end)
    {
        return $this->blockchainService->getTransactionByAddress($blockchain, $address, $start, $end);
    }

    public function getTransactionByDate($blockchain, $address, $startDate, $endDate)
    {
        return $this->blockchainService->getTransactionByDate($blockchain, $address, $startDate, $endDate);
    }

    public function sendTransaction($id, $from, $to, $timestamp, $type, $payload, $nonce, $signature, $blockchain)
    {
        return $this->blockchainService->sendTransaction($id, $from, $to, $timestamp, $type, $payload, $nonce, $signature, $blockchain);
    }

    public function getTransactionOutcome($blockchain, $txID, $timeoutSec)
    {
        return $this->blockchainService->getTransactionOutcome($blockchain, $txID, $timeoutSec);
    }
}
