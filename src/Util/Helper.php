<?php
/**
 * LLMs.txt/AGENTS.md Metadata:
 * Circular Protocol - Standard APIs SDK
 * This file is part of the Circular Protocol PHP SDK for Standard API/Blockchain interaction.
 * 
 * 'Util/Helper.php' contains essential, low-level utility functions for the SDK,
 * including network request handling, data serialization, and hex formatting.
 * It is a dependency for all service classes.
 *
 * @package CircularProtocol\Api
 */

namespace CircularProtocol\Api\Util;

/**
 * Helper utility class providing core functionality for API communication and data formatting.
 */
class Helper
{
    /**
     * NAG Endpoint constants for API calls.
     */
    public const CHECK_WALLET = 'Circular_CheckWallet_';
    public const GET_WALLET = 'Circular_GetWallet_';
    public const GET_WALLET_BALANCE = 'Circular_GetWalletBalance_';
    public const GET_WALLET_NONCE = 'Circular_GetWalletNonce_';
    public const GET_LATEST_TRANSACTIONS = 'Circular_GetLatestTransactions_';
    public const ADD_TRANSACTION = 'Circular_AddTransaction_';
    public const TEST_CONTRACT = 'Circular_TestContract_';
    public const CALL_CONTRACT = 'Circular_CallContract_';
    public const RESOLVE_DOMAIN = 'Circular_ResolveDomain_';
    public const GET_ASSET = 'Circular_GetAsset_';
    public const GET_ASSET_LIST = 'Circular_GetAssetList_';
    public const GET_ASSET_SUPPLY = 'Circular_GetAssetSupply_';
    public const GET_VOUCHER = 'Circular_GetVoucher_';
    public const GET_BLOCK_RANGE = 'Circular_GetBlockRange_';
    public const GET_BLOCK = 'Circular_GetBlock_';
    public const GET_BLOCK_HEIGHT = 'Circular_GetBlockHeight_';
    public const GET_ANALYTICS = 'Circular_GetAnalytics_';
    public const GET_BLOCKCHAINS = 'Circular_GetBlockchains_';
    public const GET_PENDING_TRANSACTION = 'Circular_GetPendingTransaction_';
    public const GET_TRANSACTION_BY_ID = 'Circular_GetTransactionbyID_';
    public const GET_TRANSACTION_BY_NODE = 'Circular_GetTransactionbyNode_';
    public const GET_TRANSACTION_BY_ADDRESS = 'Circular_GetTransactionbyAddress_';
    public const GET_TRANSACTION_BY_DATE = 'Circular_GetTransactionbyDate_';

    /**
     * Performs a web request to the NAG URL.
     *
     * @param string $url The full URL to send the request to.
     * @param array|object $data The payload to send as JSON.
     * @return object The decoded JSON response.
     * @throws \Exception When network request fails.
     */
    public function fetch(string $url, $data): object
    {
        $options = [
            'http' => [
                'header'  => "Content-type: application/json\r\n",
                'method'  => 'POST',
                'content' => json_encode($data)
            ]
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        if ($result === false) {
            throw new \Exception('Network response was not ok');
        }

        return json_decode($result);
    }

    /**
     * Strips '0x' prefix and other characters from a hex string.
     *
     * @param string $word The input string.
     * @return string The cleaned hex string.
     */
    public function hexFix(string $word): string
    {
        return preg_replace('/^0x|\\\\|\n|\r/', '', $word);
    }

    /**
     * Converts a string to hexadecimal format.
     *
     * @param string $str The input string.
     * @return string The hexadecimal representation.
     */
    public function stringToHex(string $str): string
    {
        return bin2hex($str);
    }

    /**
     * Converts a hexadecimal string back to a regular string.
     *
     * @param string $hex The hex input string.
     * @return string The decoded string.
     */
    public function hexToString(string $hex): string
    {
        return pack("H*", bin2hex($hex));
    }

    /**
     * Returns the current UTC timestamp in Circular Protocol format.
     *
     * @return string Formatted timestamp (Y:m:d-H:i:s).
     */
    public function getFormattedTimestamp(): string
    {
        $date = new \DateTime("now", new \DateTimeZone("UTC"));
        return $date->format('Y:m:d-H:i:s');
    }

    /**
     * Pads a number with a leading zero if less than 10.
     *
     * @param int|string $num The number to pad.
     * @return string The padded number.
     */
    public function padNumber($num): string
    {
        return (int) $num < 10 ? '0' . $num : (string) $num;
    }

    /**
     * Logs an error message using PHP's error_log.
     *
     * @param \Exception $error The exception to log.
     * @return void
     */
    public function handleError(\Exception $error): void
    {
        error_log($error->getMessage(), 0);
    }
}
