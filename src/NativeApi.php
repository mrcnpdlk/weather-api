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

/**
 * Class NativeApi
 *
 * @package mrcnpdlk\Weather
 */
abstract class NativeApi
{
    /**
     * @var \mrcnpdlk\Weather\NativeApi|null
     */
    protected static $instance;
    /**
     * @var Client
     */
    protected $oClient;
    /**
     * @var \JsonMapper
     */
    protected $jsonMapper;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $oLogger;
    /**
     * @var \mrcnpdlk\Psr16Cache\Adapter
     */
    protected $oCacheAdapter;

    /**
     * NativeApi constructor.
     *
     * @param \mrcnpdlk\Weather\Client $oClient
     */
    protected function __construct(Client $oClient)
    {
        $this->oClient       = $oClient;
        $this->jsonMapper    = new \JsonMapper();
        $this->oLogger       = $oClient->getLogger();
        $this->oCacheAdapter = $oClient->getCacheAdapter();
        $this->jsonMapper->setLogger($this->oLogger);
    }

    /**
     * @param \mrcnpdlk\Weather\Client $oClient
     *
     * @return static
     */
    public static function create(Client $oClient)
    {
        static::$instance = new static($oClient);

        return static::$instance;
    }

    /**
     * @return static
     * @throws \mrcnpdlk\Weather\Exception
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            throw new Exception(sprintf('First call CREATE method!'));
        }

        return static::$instance;
    }
}
