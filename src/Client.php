<?php
/**
 * WEATHER-API
 *
 * Copyright © 2018 pudelek.org.pl
 *
 * @license MIT License (MIT)
 *
 * For the full copyright and license information, please view source file
 * that is bundled with this package in the file LICENSE
 *
 * @author  Marcin Pudełek <marcin@pudelek.org.pl>
 */

namespace mrcnpdlk\Weather;


use mrcnpdlk\Psr16Cache\Adapter;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Psr\SimpleCache\CacheInterface;

/**
 * Class Client
 *
 * @package mrcnpdlk\Weather
 */
class Client
{
    /**
     * Cache handler
     *
     * @var \Psr\SimpleCache\CacheInterface
     */
    private $oCache;
    /**
     * @var \mrcnpdlk\Psr16Cache\Adapter
     */
    private $oCacheAdapter;
    /**
     * Logger handler
     *
     * @var \Psr\Log\LoggerInterface
     */
    private $oLogger;

    /**
     * @var string
     */
    private $sGiosRestUrl = 'http://api.gios.gov.pl/pjp-api/rest';
    /**
     * @var string
     */
    private $sAirlyRestUrl = 'https://airapi.airly.eu/v1';
    /**
     * @var string
     */
    private $sAirlyToken;


    /**
     * Client constructor.
     */
    public function __construct()
    {
        $this->setLoggerInstance();
        $this->setCacheInstance();
    }

    /**
     * @return array
     *
     */
    public function __debugInfo(): array
    {
        return ['Top secret'];
    }

    /**
     * @return string
     */
    public function getAirlyRestUrl(): string
    {
        return $this->sAirlyRestUrl;
    }

    /**
     * @return string
     * @throws \mrcnpdlk\Weather\Exception
     */
    public function getAirlyToken(): string
    {
        if (!$this->sAirlyToken) {
            throw new Exception('Airly token is require but nor set');
        }

        return $this->sAirlyToken;
    }

    /**
     * @return \mrcnpdlk\Psr16Cache\Adapter
     */
    public function getCacheAdapter(): Adapter
    {
        return $this->oCacheAdapter;
    }

    /**
     * @return string
     */
    public function getGiosRestUrl(): string
    {
        return $this->sGiosRestUrl;
    }

    /**
     * Get logger instance
     *
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->oLogger;
    }

    /**
     * @param string $token
     * @param string $url
     *
     * @return \mrcnpdlk\Weather\Client
     */
    public function setAirlyConfig(string $token, string $url = null): Client
    {
        $this->sAirlyToken = $token;
        if ($url) {
            $this->sAirlyRestUrl = rtrim($url, '/');
        }

        return $this;
    }

    /**
     * Setting Cache Adapter
     *
     * @return $this
     */
    private function setCacheAdapter(): Client
    {
        $this->oCacheAdapter = new Adapter($this->oCache, $this->oLogger);

        return $this;
    }

    /**
     * Set Cache handler (PSR-16)
     *
     * @param CacheInterface|null $oCache
     *
     * @return \mrcnpdlk\Weather\Client
     * @see https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-16-simple-cache.md PSR-16
     */
    public function setCacheInstance(CacheInterface $oCache = null): Client
    {
        $this->oCache = $oCache;
        $this->setCacheAdapter();

        return $this;
    }

    /**
     * @param string $url
     *
     * @return \mrcnpdlk\Weather\Client
     */
    public function setGiosRestUrl(string $url): Client
    {
        $this->sGiosRestUrl = rtrim($url, '/');

        return $this;
    }

    /**
     * Set Logger handler (PSR-3)
     *
     * @param LoggerInterface|null $oLogger
     *
     * @return $this
     */
    public function setLoggerInstance(LoggerInterface $oLogger = null): Client
    {
        $this->oLogger = $oLogger ?: new NullLogger();
        $this->setCacheAdapter();

        return $this;
    }

}
