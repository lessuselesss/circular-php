<?php
/**
 * LLMs.txt/AGENTS.md Metadata:
 * Circular Protocol - Standard APIs SDK
 * This file is part of the Circular Protocol PHP SDK for Standard API/Blockchain interaction.
 * 
 * 'Service/ContractService.php' implements smart contract API functions including
 * contract testing and local contract calls. It is a service component used
 * internally by the main CircularProtocolAPI facade.
 *
 * @package CircularProtocol\Api
 */

namespace CircularProtocol\Api\Service;

use CircularProtocol\Api\Util\Helper;

/**
 * ContractService handles all smart contract-related API operations.
 */
class ContractService
{
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

    /**
     * Test the execution of a smart contract project.
     *
     * @param string $blockchain The blockchain where the contract will be tested.
     * @param string $from The developer's wallet address.
     * @param string $project The Hyper Code Light Smart Contract project.
     * @return object JSON response.
     */
    public function testContract(string $blockchain, string $from, string $project): object
    {
        $data = [
            "Blockchain" => $this->helper->hexFix($blockchain),
            "From"       => $this->helper->hexFix($from),
            "Timestamp"  => $this->helper->getFormattedTimestamp(),
            "Project"    => $this->helper->stringToHex($project),
            "Version"    => $this->version
        ];
        return $this->helper->fetch($this->nagUrl . Helper::TEST_CONTRACT, $data);
    }

    /**
     * Perform a local smart contract call.
     *
     * @param string $blockchain The blockchain where the contract is deployed.
     * @param string $from The caller wallet address.
     * @param string $address The smart contract address.
     * @param string $request The smart contract local endpoint.
     * @return object JSON response.
     */
    public function callContract(string $blockchain, string $from, string $address, string $request): object
    {
        $data = [
            "Blockchain" => $this->helper->hexFix($blockchain),
            "From"       => $this->helper->hexFix($from),
            "Address"    => $this->helper->hexFix($address),
            "Request"    => $this->helper->stringToHex($request),
            "Timestamp"  => $this->helper->getFormattedTimestamp(),
            "Version"    => $this->version
        ];
        return $this->helper->fetch($this->nagUrl . Helper::CALL_CONTRACT, $data);
    }
}
