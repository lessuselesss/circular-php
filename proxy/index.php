<?php

require_once __DIR__ . '/../vendor/autoload.php';

use CircularProtocol\Api\CircularProtocolAPI;

// Enable CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Initialize SDK
$client = new CircularProtocolAPI();

// Extract CEP action
$cep = $_GET['cep'] ?? '';

if (empty($cep)) {
    http_response_code(400);
    echo json_encode(['error' => "Missing 'cep' query parameter"]);
    exit;
}

// Parse JSON body
$input = file_get_contents('php://input');
$args = json_decode($input, true);

if (json_last_error() !== JSON_ERROR_NONE && !empty($input)) {
    http_response_code(400);
    echo json_encode(['error' => "Invalid JSON body"]);
    exit;
}

$result = null;

try {
    switch ($cep) {
        case 'Circular_CheckWallet_':
            $result = $client->checkWallet($args['Blockchain'], $args['Address']);
            break;
        case 'Circular_GetWallet_':
            $result = $client->getWallet($args['Blockchain'], $args['Address']);
            break;
        case 'Circular_GetWalletNonce_':
            $result = $client->getWalletNonce($args['Blockchain'], $args['Address']);
            break;
        case 'Circular_GetLatestTransactions_':
            $result = $client->getLatestTransactions($args['Blockchain'], $args['Address']);
            break;
        case 'Circular_GetWalletBalance_':
            $result = $client->getWalletBalance($args['Blockchain'], $args['Address'], $args['Asset']);
            break;
        case 'Circular_AddTransaction_':
            $result = $client->sendTransaction(
                $args['ID'], $args['From'], $args['To'], $args['Timestamp'],
                $args['Type'], $args['Payload'], $args['Nonce'], $args['Signature'],
                $args['Blockchain']
            );
            break;
        case 'Circular_GetBlock_':
            $result = $client->getBlock($args['Blockchain'], (int)$args['BlockNumber']);
            break;
        case 'Circular_GetBlockHeight_':
            $result = $client->getBlockCount($args['Blockchain']);
            break;
        case 'Circular_GetBlockRange_':
            $result = $client->getBlockRange($args['Blockchain'], (int)$args['Start'], (int)$args['End']);
            break;
        case 'Circular_GetAssetList_':
            $result = $client->getAssetList($args['Blockchain']);
            break;
        case 'Circular_GetAsset_':
            $result = $client->getAsset($args['Blockchain'], $args['AssetName']);
            break;
        case 'Circular_GetAssetSupply_':
            $result = $client->getAssetSupply($args['Blockchain'], $args['AssetName']);
            break;
        case 'Circular_ResolveDomain_':
            $result = $client->getDomain($args['Blockchain'], $args['Domain']);
            break;
        case 'Circular_GetVoucher_':
            $result = $client->getVoucher($args['Blockchain'], $args['Code']);
            break;
        case 'Circular_GetAnalytics_':
            $result = $client->getAnalytics($args['Blockchain']);
            break;
        case 'Circular_GetPendingTransaction_':
            $result = $client->getPendingTransaction($args['Blockchain'], $args['ID']);
            break;
        case 'Circular_GetTransactionbyID_':
            $result = $client->getTransactionByID($args['Blockchain'], $args['ID'], (int)$args['Start'], (int)$args['End']);
            break;
        case 'Circular_GetTransactionbyNode_':
            $result = $client->getTransactionByNode($args['Blockchain'], $args['NodeID'], (int)$args['Start'], (int)$args['End']);
            break;
        case 'Circular_GetTransactionbyAddress_':
            $result = $client->getTransactionByAddress($args['Blockchain'], $args['Address'], (int)$args['Start'], (int)$args['End']);
            break;
        case 'Circular_GetTransactionbyDate_':
            $result = $client->getTransactionByDate($args['Blockchain'], $args['Address'], $args['StartDate'], $args['EndDate']);
            break;
        case 'Circular_TestContract_':
            $result = $client->testContract($args['Blockchain'], $args['From'], $args['Project']);
            break;
        case 'Circular_CallContract_':
            $result = $client->callContract($args['Blockchain'], $args['From'], $args['Address'], $args['Request']);
            break;
        default:
            http_response_code(400);
            echo json_encode(['error' => "Unknown action: $cep"]);
            exit;
    }

    header('Content-Type: application/json');
    echo json_encode($result);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
