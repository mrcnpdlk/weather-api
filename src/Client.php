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
    const SERVICE_GIOS_URL = 'http://api.gios.gov.pl/pjp-api/rest';

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
     * Get logger instance
     *
     * @return \Psr\Log\LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->oLogger;
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
