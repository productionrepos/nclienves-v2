<?php
// require(__DIR__ . "/Config.class.php");

class FlowApi {
	
	protected $apiKey;
	protected $secretKey;
	
	
	public function __construct() {
		// $this->apiKey = getenv("flow_APIKEY");
		// $this->secretKey = getenv("flow_SECRETKEY");
		$this->apiKey = "3FCFF277-D0F0-45FE-821F-8F963B64L7B1";
		$this->secretKey = "88efefedfe738f4a77e19cbe48a804c5461af01d";
		// $this->apiKey = Config::get("APIKEY");
		// $this->secretKey = Config::get("SECRETKEY");
	}
	/**
	 * Funcion que invoca un servicio del Api de Flow
	 * @param string $service Nombre del servicio a ser invocado
	 * @param array $params datos a ser enviados
	 * @param string $method metodo http a utilizar
	 * @return string en formato JSON
	 * @throws Exception
	 */
	public function send( $service, $params, $method = "GET") {
		$method = strtoupper($method);
		$url = "https://www.flow.cl/api" . "/" . $service;
		// $url = Config::get("APIURL") . "/" . $service;
		$params = array("apiKey" => $this->apiKey) + $params;
		$params["s"] = $this->sign($params);
		if($method == "GET") {
			$response = $this->httpGet($url, $params);
		} else {
			$response = $this->httpPost($url, $params);
		}
		
		if(isset($response["info"])) {
			$code = $response["info"]["http_code"];
			if (!in_array($code, array("200", "400", "401"))) {
				throw new Exception("Unexpected error occurred. HTTP_CODE: " .$code , $code);
			}
		}
		$body = json_decode($response["output"], true);
		return $body;
	}
	
	/**
	 * Funcion para setear el apiKey y secretKey y no usar los de la configuracion
	 * @param string $apiKey apiKey del cliente
	 * @param string $secretKey secretKey del cliente 
	 */
	public function setKeys($apiKey, $secretKey) {
		$this->apiKey = $apiKey;
		$this->secretKey = $secretKey;
	}
	
	
	/**
	 * Funcion que firma los parametros
	 * @param string $params Parametros a firmar
	 * @return string de firma
	 * @throws Exception
	 */
	private function sign($params) {
		$keys = array_keys($params);
		sort($keys);
		$toSign = "";
		foreach ($keys as $key) {
			$toSign .= $key . $params[$key];
		}
		if(!function_exists("hash_hmac")) {
			throw new Exception("function hash_hmac not exist", 1);
		}
		return hash_hmac('sha256', $toSign , $this->secretKey);
	}
	
	
	/**
	 * Funcion que hace el llamado via http GET
	 * @param string $url url a invocar
	 * @param array $params los datos a enviar
	 * @return array el resultado de la llamada
	 * @throws Exception
	 */
	private function httpGet($url, $params) {
		$url = $url . "?" . http_build_query($params);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$output = curl_exec($ch);
		if($output === false) {
			$error = curl_error($ch);
			throw new Exception($error, 1);
		}
		$info = curl_getinfo($ch);
		curl_close($ch);
		return array("output" =>$output, "info" => $info);
	}
	
	/**
	 * Funcion que hace el llamado via http POST
	 * @param string $url url a invocar
	 * @param array $params los datos a enviar
	 * @return array el resultado de la llamada
	 * @throws Exception
	 */
	private function httpPost($url, $params ) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		$output = curl_exec($ch);
		if($output === false) {
			$error = curl_error($ch);
			throw new Exception($error, 1);
		}
		$info = curl_getinfo($ch);
		curl_close($ch);
		return array("output" =>$output, "info" => $info);
	}
	
	
}
