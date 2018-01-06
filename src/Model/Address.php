<?php
/**
 * Created by Marcin.
 * Date: 06.01.2018
 * Time: 20:42
 */

namespace mrcnpdlk\Weather\Model;


class Address
{
    /**
     * @var string
     */
    public $countryCode;
    /**
     * Województwo
     *
     * @var string
     */
    public $province;
    /**
     * Powiat
     *
     * @var string
     */
    //public $district;
    /**
     * Gmina
     *
     * @var string
     */
    public $commune;
    /**
     * Miasto/miescowość/wieś
     *
     * @var string
     */
    public $place;
    /**
     * @var string
     */
    public $neighbourhood;
    /**
     * @var string
     */
    public $street;
    /**
     * @var string
     */
    public $homeNr;
    /**
     * @var string
     */
    public $postalCode;

    public function __construct(\stdClass $oData = null)
    {
        if ($oData) {
            $this->countryCode   = $oData->country_code ?? null;
            $this->province      = $oData->state ?? null;
            $this->commune       = $oData->county ?? null;
            $this->place         = $oData->village ?? $oData->town ?? $oData->city ?? null;
            $this->neighbourhood = $oData->neighbourhood ?? null;
            $this->street        = $oData->road ?? null;
            $this->homeNr        = $oData->house_number ?? null;
            $this->postalCode    = $oData->postcode ?? null;
        }
    }
}
