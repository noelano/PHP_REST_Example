<?php
class xmlView
{
	private $model, $controller, $slimApp;

	public function __construct($controller, $model, $slimApp) {
		$this->controller = $controller;
		$this->model = $model;
		$this->slimApp = $slimApp;		
	}

	public function output(){
		//prepare xml response
		$xmlResponse = new SimpleXMLElement('<?xml version="1.0"?><data></data>');
		$response = $this->model->apiResponse;
		$this->array_to_xml($response, $xmlResponse);	

		$this->slimApp->response->write($xmlResponse->asXML());
	}
	
	/**
	 * 
	 * Recursive function to loop over nested arrays and convert entire content to xml ...
	 * @param array $data
	 * @param SimpleXMLElement $xml_data
	 */
	private function array_to_xml( $data, &$xml_data ) {
	    foreach( $data as $key => $value ) {
	        if( is_array($value) ) {
	            if( is_numeric($key) ){
	                $key = 'record'.$key; //dealing with <0/>..<n/> issues
	            }
	            $subnode = $xml_data->addChild($key);
	            $this->array_to_xml($value, $subnode);
	        } else {
	        	// Add element, making sure to handles special characters that would give xml error
	            $xml_data->addChild("$key",htmlspecialchars("$value"));
	        }
	    }
	}
}
?>