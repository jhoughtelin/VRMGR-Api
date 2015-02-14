<?php
/**
 * @file get_property.php
 * @project VRMGR-API
 * @author Josh Houghtelin <josh@findsomehelp.com>
 * @created 2/14/15 12:00 PM
 */


require __DIR__ . "/../vendor/autoload.php";

$vrmgr = new Gueststream\VRP\PropertyManagementSoftware\VRMGR(
    'http://www.aaoceanfront.com/VRMWebServiceOSTest/VRMWebServiceOSDev.asmx?wsdl'
);

$property = $vrmgr->getProperty(152);

print_r($property);