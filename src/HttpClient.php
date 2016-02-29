<?php

namespace Link0\Hp\OfficeJet;

/**
 * Interface HttpClient for handling different kinds of Http clients
 *
 * @package Link0\Hp\OfficeJet
 */
interface HttpClient
{
    /**
     * @param string $method
     * @param string $uri
     * @param null   $body
     *
     * @return string
     */
    public function request($method, $uri, $body = null);
}