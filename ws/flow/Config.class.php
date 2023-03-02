<?php 
$COMMERCE_CONFIG = array(
	
	"APIKEY" => "3FCFF277-D0F0-45FE-821F-8F963B64L7B1",
	"SECRETKEY" => "88efefedfe738f4a77e19cbe48a804c5461af01d", 
	"APIURL" => "https://www.flow.cl/api",
	// "BASEURL" => "https://app.sendcargo.cl"
	"BASEURL" => $_SERVER['HTTP_HOST']
);

 class Config {
	static function get($name) {
		global $COMMERCE_CONFIG;
		if(!isset($COMMERCE_CONFIG[$name])) {
			throw new Exception("The configuration element thas not exist", 1);
		}
		return $COMMERCE_CONFIG[$name];
	}
}
