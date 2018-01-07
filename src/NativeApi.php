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
     * @var \mrcnpdlk\Weather\NativeApi[]
     */
    protected static $instances = [];
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
        $calledClass                     = get_called_class();
        static::$instances[$calledClass] = new $calledClass($oClient);

        return static::$instances[$calledClass];
    }

    /**
     * @return static
     * @throws \mrcnpdlk\Weather\Exception
     */
    public static function getInstance()
    {
        $calledClass = get_called_class();

        if (!isset(static::$instances[$calledClass])) {
            throw new Exception(sprintf('First call CREATE method for %s!', $calledClass));
        }

        return static::$instances[$calledClass];
    }
}
