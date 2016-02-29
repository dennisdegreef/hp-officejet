<?php

namespace Link0\Hp\OfficeJet;

/**
 * Interfaces with HP OfficeJet J6400 series scanner (or other models maybe)
 *
 * @package Link0\Hp\OfficeJet
 */
final class Scanner
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * Scanner constructor.
     *
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function scan()
    {
        $this->ensureLock();

        $uri = '/scan/image1.jpg?id=0&type=4&size=0&fmt=1&time=' . time();
        return $this->httpClient->request('GET', $uri);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function scanPreview()
    {
        $this->ensureLock();

        $uri = '/scan/image.pdf?id=0&type=4&prev=1&time=' . time();
        return $this->httpClient->request('GET', $uri);
    }

    /**
     * @throws \Exception
     */
    private function ensureLock()
    {
        if ($this->isLocked() !== true) {

            $this->acquireLock();

            $attempt = 0;
            $maxAttempts = 10;

            do {
                $hasLock = ($this->isLocked() !== true);
                $attempt++;
                usleep(250000);
            } while ($hasLock === false && $attempt < $maxAttempts);

            if ($hasLock !== true) {
                throw new \Exception("Unable to acquire lock");
            }
        }
    }

    /**
     * @return array
     */
    private function acquireLock()
    {
        $body    = 'ws_operation=1&ws_scanid=0&ws_type=0&ws_format=0&ws_size=0&ws_scan_method=0';
        $response = $this->httpClient->request('POST', '/wsStatus.htm', $body);

        $parsedResponse = $this->parseResponse($response);
        return $this->lockHasIdleStatus($parsedResponse);
    }

    /**
     * @return bool
     */
    public function isLocked()
    {
        $response = $this->httpClient->request('POST', '/wsStatus.htm');

        $parsedResponse = $this->parseResponse($response);
        return $this->lockHasIdleStatus($parsedResponse);
    }

    /**
     * @param array $parsedResponse
     *
     * @return bool
     */
    private function lockHasIdleStatus($parsedResponse)
    {
        return (isset($parsedResponse['ws_status_code']) && $parsedResponse['ws_status_code'] != '0');
    }

    /**
     * @param string $response
     *
     * @return array
     */
    private function parseResponse($response)
    {
        $lines = explode("\n", $response);
        $result = [];

        // Filter out all response lines that start with 'ws_', they contain relevant information
        foreach ($lines as $line) {
            if (substr($line, 0, 3) === 'ws_') {
                list($key, $value) = explode(" = ", rtrim($line, ';'));
                $result[$key] = $value;
            }
        }

        return $result;
    }
}