<?php
/**
 * @file VRMGR.php
 * @project VRMGR-API
 * @author Josh Houghtelin <josh@findsomehelp.com>
 * @created 2/12/15 12:13 PM
 */

namespace Gueststream\VRP\PropertyManagementSoftware;

/**
 * Virtual Resort Manager API Wrapper
 * @package Gueststream\VRP\PropertyManagementSoftware
 */
class VRMGR
{
    /** @var SoapClient */
    private $client;

    /** @var array Soap Call Params */
    private $parameters = [
        "return_all"     => true,
        "calculate_rent" => false,
        "web_code"       => "all",
        "date_start"     => "",
        "date_end"       => "",
        "amenities"      => "-1",
        "location"       => "-1",
        "proptype"       => "-1",
        "price"          => "",
        "priceEnd"       => "",
        "bedrooms"       => "-1",
        "baths"          => "-1",
        "num_guests"     => "",
        "pets"           => "",
        "smoking"        => "",
        "handicap"       => "",
        "ExactList"      => 1
    ];

    /** @var  SoapSomething Soap Call Result  */
    private $results;

    /** @var  string Last Soap Request */
    private $last_request;

    /** @var  string Last Soap Response */
    private $last_response;

    /** @var  string SoapFault Error Message */
    private $error;

    /**
     * VRMGR Class Construct
     *
     * @param string $api_url
     * @param \SoapClient $client
     */
    public function __construct($api_url = null, \SoapClient $client = null)
    {
        if (!is_null($client)) {
            $this->client = $client;
            return true;
        }

        if (is_null($api_url)) {
            return false;
        }

        $this->client = new \SoapClient($api_url, [
            'trace'       => 1,
            'compression' => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP,
            'features'    => SOAP_SINGLE_ELEMENT_ARRAYS,
            'cache_wsdl'  => WSDL_CACHE_DISK
        ]);

        return true;
    }

    private function call($call, $params = null)
    {
        $parameters = $this->parameters;

        if (!is_null($params)) {
            $parameters = $params;
        }

        try {
            $this->results = $this->client->$call($parameters);
        } catch (SoapFault $soapFault) {
            $this->results = false;
            $this->error   = $soapFault->getMessage();
        }

        $this->last_request  = $this->client->__getLastRequest();
        $this->last_response = $this->client->__getLastResponse();
    }

    /**
     * Get All Properties
     *
     * @return array|bool
     */
    public function getAllProperties()
    {
        $this->call("getWebPropertiesList");
        if($this->results === false) {
            // Throw a fit.. or an exception
            return false;
        }

        $xml_object = new \SimpleXMLElement($this->results->getWebPropertiesListResult->any);
        foreach($xml_object->NewDataSet->WebPropertiesList as $a_property) {
            $properties[] = $a_property;
        }

        return $properties;
    }

    /**
     * Get a single property by Property Code
     *
     * @param $PropertyCode
     *
     * @return mixed
     */
    public function getProperty($PropertyCode)
    {
        $params = $this->parameters;
        $params['product_id'] = $PropertyCode;
        $this->call("getWebPropertiesList",$params);
        $xml_object = new \SimpleXMLElement($this->results->getWebPropertiesListResult->any);
        $property = $xml_object->NewDataSet->WebPropertiesList;
        return $property;
    }

    public function getPropertyRates($PropertyCode)
    {
        $params = ["product_id" => $PropertyCode];
        $this->call("getPropertyRates",$params);
        $xml_object = new \SimpleXMLElement($this->results->getPropertyRatesResult->any);
        print_r($xml_object);
    }

    public function getPropertyAvailability($PropertyCode)
    {
        $this->call("getPropertyCalendar");
    }

    public function getPropertyAmenities($PropertyCode)
    {
        $this->call("getPropertyAmenities");
    }

    public function getPropertyPhotos($PropertyCode)
    {
        $this->call("getProductPictures");
    }

    public function getPropertyBookSettings($PropertyCode)
    {

    }

    public function checkPropertyAvailability($PropertyCode)
    {

    }

    /**
     * Get Last Soap Request
     *
     * @return string
     */
    public function getLastRequest()
    {
        return $this->last_request;
    }

    /**
     * Get Last Soap Response
     *
     * @return string
     */
    public function getLastResponse()
    {
        return $this->last_response;
    }


    private function sanitizePropertyData($property)
    {
        foreach($property as $key => $val) {
            $property->$key = (string) $val;
        }
        print_r($property);
    }
}
