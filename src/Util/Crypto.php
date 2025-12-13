<?php
/**
 * LLMs.txt/AGENTS.md Metadata:
 * Circular Protocol - Standard APIs SDK
 * This file is part of the Circular Protocol PHP SDK for Standard API/Blockchain interaction.
 * 
 * 'Util/Crypto.php' provides cryptographic operations for the SDK including
 * key generation, message signing, and signature verification using secp256k1
 * elliptic curve cryptography.
 *
 * @package CircularProtocol\Api
 */

namespace CircularProtocol\Api\Util;

use Elliptic\EC;

/**
 * Crypto utility class for elliptic curve cryptographic operations.
 */
class Crypto
{
    /**
     * @var EC The elliptic curve instance.
     */
    private EC $ec;

    /**
     * Initializes the Crypto utility with secp256k1 curve.
     */
    public function __construct()
    {
        $this->ec = new EC('secp256k1');
    }

    /**
     * Signs a message using a private key.
     *
     * @param string $message The message to sign.
     * @param string $privateKey The private key in hex format (without '0x').
     * @return string The DER-encoded signature in hex format.
     */
    public function signMessage(string $message, string $privateKey): string
    {
        $key = $this->ec->keyFromPrivate($privateKey, 'hex');
        $msgHash = hash('sha256', $message);
        $signature = $key->sign($msgHash)->toDER('hex');
        return $signature;
    }

    /**
     * Verifies a message signature.
     *
     * @param string $publicKey The public key in hex format.
     * @param string $message The original message.
     * @param string $signature The signature to verify.
     * @return bool True if signature is valid, false otherwise.
     */
    public function verifySignature(string $publicKey, string $message, string $signature): bool
    {
        $key = $this->ec->keyFromPublic($publicKey, 'hex');
        $msgHash = hash('sha256', $message);
        return $key->verify($msgHash, $signature, 'hex');
    }

    /**
     * Derives a public key from a private key.
     *
     * @param string $privateKey The private key in hex format.
     * @return string The public key in hex format.
     */
    public function getPublicKey(string $privateKey): string
    {
        $key = $this->ec->keyFromPrivate($privateKey, 'hex');
        return $key->getPublic('hex');
    }

    /**
     * Generates keys from a seed phrase.
     *
     * @param string $seedphrase The seed phrase.
     * @return array{privateKey: string, publicKey: string, walletAddress: string}
     */
    public function keysFromSeedPhrase(string $seedphrase): array
    {
        $seed = hash('sha256', $seedphrase, false);
        $keyPair = $this->ec->keyFromPrivate($seed);

        $privateKey = $keyPair->getPrivate('hex');
        $publicKey = $keyPair->getPublic('hex');
        $walletAddress = hash("sha256", $publicKey, false);

        return [
            'privateKey'    => $privateKey,
            'publicKey'     => $publicKey,
            'walletAddress' => $walletAddress
        ];
    }
}
