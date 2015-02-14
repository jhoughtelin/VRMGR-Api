<?php
/**
 * @file get_all_properties.php
 * @project VRMGR-API
 * @author Josh Houghtelin <josh@findsomehelp.com>
 * @created 2/13/15 6:09 PM
 */

require __DIR__ . "/../vendor/autoload.php";

$vrmgr = new Gueststream\VRP\PropertyManagementSoftware\VRMGR(
    'http://www.aaoceanfront.com/VRMWebServiceOSTest/VRMWebServiceOSDev.asmx?wsdl'
);

$all_properties = $vrmgr->getAllProperties();

print_r($all_properties);