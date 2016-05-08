<?php
/**
 * @author Noel
 * View object to display a scatterplot of players by cost and points
 */

class d3View
{
	private $model, $controller, $slimApp;

	public function __construct($controller, $model, $slimApp) {
		require_once "htmlCode.php";
		$this->controller = $controller;
		$this->model = $model;
		$this->slimApp = $slimApp;		
	}

	public function output(){
		//Get the data from the model
		$data = json_encode($this->model->apiResponse);
		//D3 javascript to display this 
		$dataDeclaration = "		
			<script>
			var div = d3.select(\"div.scatter\");
			var data = ";
		$end = "</script>";
		$d3Code = STYLE;
		$d3Code .= HEADING;
		$d3Code .= PLOT;
		$d3Code .= D3_SOURCE;
		$d3Code .= $dataDeclaration . $data . ";";	// Combine the data with wrappers to fit into the d3 code
		$d3Code .= DATACLEANING;
		$d3Code .= D3_CODE;
		$d3Code .= $end;
		$this->slimApp->response->write($d3Code);
	}
}
?>