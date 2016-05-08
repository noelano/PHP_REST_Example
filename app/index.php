<?php
require_once "../Slim/Slim.php";
Slim\Slim::registerAutoloader ();

$app = new \Slim\Slim (); // slim run-time object

require_once "conf/config.inc.php";

function authenticate(\Slim\Route $route){
	$app = \Slim\Slim::getInstance ();
	$parameters = $app->request->headers;
	$action = ACTION_AUTHENTICATE;
	
	require_once 'models/UserModel.php';
	require_once 'controllers/UserController.php';
	$model = new UserModel();
	$controller = new UserController($model, $action, $app, $parameters);
	
	// Authenticate the user and pass
	if ($model->apiResponse == HTTPSTATUS_OK){
		return (true);
	} else {
		$app->halt($model->apiResponse);
		return (false);
	}
}

/**
 * 
 * Parse the header to determine the correct response type
 * Default is json
 * @param slim_app $app
 * @return string
 */
function getResponseFormat($app){
	$responseFormat = $app->request->headers("Accept");
	// Check if the value contains xml (So could be application/xml or application/html+xml etc)
	if (strpos($responseFormat, "xml")!== false) {
		return "xml";
	} else {
		return "json";
	}
}

$app->map ( "/users(/:id)", "authenticate", function ($userID = null) use($app) {
	
	$httpMethod = $app->request->getMethod ();
	$view = getResponseFormat($app) . "View";
	$action = null;
	$parameters ["id"] = $userID; // prepare parameters to be passed to the controller (example: ID)
	
	if (($userID == null) or is_numeric ( $userID )) {
		switch ($httpMethod) {
			case "GET" :
				if ($userID != null)
					$action = ACTION_GET_RECORD;
				else
					$action = ACTION_GET_RECORDS;
				break;
			case "POST" :
				$action = ACTION_CREATE_RECORD;
				break;
			case "PUT" :
				$action = ACTION_UPDATE_RECORD;
				break;
			case "DELETE" :
				$action = ACTION_DELETE_RECORD;
				break;
			default :
		}
	}
	return new loadRunMVCComponents ( "UserModel", "User", "GenericController", $view, $action, $app, $parameters );
} )->via ( "GET", "POST", "PUT", "DELETE" );

$app->map ( "/players(/:id)", "authenticate", function ($playerID = null) use($app) {
	
	$httpMethod = $app->request->getMethod ();
	$view = getResponseFormat($app) . "View";
	$action = null;
	$parameters ["id"] = $playerID;
	
	if (($playerID == null) or is_numeric ( $playerID )) {
		switch ($httpMethod) {
			case "GET" :
				if ($playerID != null)
					$action = ACTION_GET_RECORD;
				else
					$action = ACTION_GET_RECORDS;
				break;
			case "POST" :
				$action = ACTION_CREATE_RECORD;
				break;
			case "PUT" :
				$action = ACTION_UPDATE_RECORD;
				break;
			case "DELETE" :
				$action = ACTION_DELETE_RECORD;
				break;
			default :
		}
	}
	return new loadRunMVCComponents ( "PlayerModel", "Player", "GenericController", $view, $action, $app, $parameters );
} )->via ( "GET", "POST", "PUT", "DELETE" );

$app->map ( "/teams(/:id)", "authenticate", function ($teamID = null) use($app) {
	
	$httpMethod = $app->request->getMethod ();
	$view = getResponseFormat($app) . "View";
	$action = null;
	$parameters ["id"] = $teamID;
	
	if (($teamID == null) or is_numeric ( $teamID )) {
		switch ($httpMethod) {
			case "GET" :
				if ($teamID != null)
					$action = ACTION_GET_RECORD;
				else
					$action = ACTION_GET_RECORDS;
				break;
			case "POST" :
				$action = ACTION_CREATE_RECORD;
				break;
			case "PUT" :
				$action = ACTION_UPDATE_RECORD;
				break;
			case "DELETE" :
				$action = ACTION_DELETE_RECORD;
				break;
			default :
		}
	}
	return new loadRunMVCComponents ( "TeamModel", "Team", "GenericController", $view, $action, $app, $parameters );
} )->via ( "GET", "POST", "PUT", "DELETE" );

$app->map ( "/search(/:recordType(/:SearchString))", "authenticate", function ($recordType = null, $SearchString = null) use($app) {
	/* Two parameters are passed - one to specify the table to be searched (player, team or user)
	   The second is the actual search string
	   Rather than being strict on the table name, case is ignored
	*/
	
	$httpMethod = $app->request->getMethod ();
	$view = getResponseFormat($app) . "View";
	$action = null;
	$parameters ["SearchString"] = $SearchString; // prepare search parameters to be passed to the controller
	$recordType = strtolower($recordType);		// Allow some leniency for cases
	
	// Check the record type that was specified
	if (($recordType == null) or is_string($recordType)) {
		if (in_array($recordType, array("player", "user", "team"))){
			$record = ucwords($recordType);
			$model = $record . "Model";
		} else {
			// For simplicity, default to Player if an incorrect table name is passed
			$record = "Player";
			$model = "PlayerModel";
		}
	}
	
	if (($SearchString == null) or is_string($SearchString)) {
		switch ($httpMethod) {
			case "GET" :
				if ($SearchString != null){
					$action = ACTION_SEARCH_RECORDS;
				} else {
					// If there's no search string, return everything
					$action = ACTION_GET_RECORDS;
				}
				break;
			default :
		}
	}
	return new loadRunMVCComponents ( $model, $record, "GenericController", $view, $action, $app, $parameters );
} )->via ( "GET" );

$app->map ( "/vis", function () use($app) {
	
	/* This location displays a visualisation of player and team details
	 * It's the only location that does not require authentication
	 */
	
	$httpMethod = $app->request->getMethod ();
	$action = null;
	$parameters = null;
	
	switch ($httpMethod) {
		case "GET" : 
			$action = ACTION_GET_RECORDS;
			break;
		default :
	}
	
	return new loadRunMVCComponents ( "PlayerModel", "PlayerAndTeam", "GenericController", "d3View", $action, $app, $parameters );
} )->via ( "GET" );

$app->run ();
class loadRunMVCComponents {
	public $model, $controller, $view;
	public function __construct($modelName, $recordType, $controllerName, $viewName, $action, $app, $parameters = null) {
		include_once "models/" . $modelName . ".php";
		include_once "controllers/" . $controllerName . ".php";
		include_once "views/" . $viewName . ".php";
		
		$model = new $modelName (); // common model
		$controller = new $controllerName ( $model, $recordType, $action, $app, $parameters );
		$view = new $viewName ( $controller, $model, $app, $app->headers ); // common view
		$view->output (); // this returns the response to the requesting client
	}
}

?>