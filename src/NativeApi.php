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
final class NativeApi
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
     * NativeApi constructor.
     *
     * @param \mrcnpdlk\Weather\Client $oClient
     */
    protected function __construct(Client $oClient)
    {
        $this->oClient = $oClient;
    }

    /**
     * @param \mrcnpdlk\Weather\Client $oClient
     *
     * @return \mrcnpdlk\Weather\NativeApi
     */
    public static function create(Client $oClient): NativeApi
    {
        static::$instance = new static($oClient);

        return static::$instance;
    }

    /**
     * @return \mrcnpdlk\Weather\NativeApi
     * @throws \mrcnpdlk\Weather\Exception
     */
    public static function getInstance(): NativeApi
    {
        if (!isset(static::$instance)) {
            throw new Exception(sprintf('First call CREATE method!'));
        }

        return static::$instance;
    }
}
