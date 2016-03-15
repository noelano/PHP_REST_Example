<?php
/**
 * @author noel
 * Simple form to retrieve user info
 * This will then be added to the DB when submitted
 */

session_start ();

require_once "../Slim/Slim.php";
Slim\Slim::registerAutoloader ();
$app = new \Slim\Slim (); // slim run-time object
require_once "conf/config.inc.php"; // include configuration file

require_once "DB/DAOs/UsersDAO.php";
require_once "DB/pdoDBManager.php";

$app->map ( "/users(/:id)", function ($elementID = null) use($app) {
	$body = $app->request->getBody(); // get the body of the HTTP request (from client)
	$decBody = json_decode ( $body, TRUE ); // this transform the string into an associative array
	$httpMethod = $app->request->getMethod ();

	// initialisations
	$responseBody = null;
	$responseCode = null;
	$userID = null;

	// get the id if one is passed in
	$url = $app->request->getResourceUri();
	$splitUrl = explode('users/', $url);
	if (count($splitUrl) > 1){
		$userID = $splitUrl[1];
	}
	
	// Open DB connection
	$dbmgr = new pdoDbManager;
	$connection = $dbmgr->openConnection();
	$dao = new UsersDAO($dbmgr);

	switch ($httpMethod) {
		case "GET" :
			// Retrieve user based on id
			try{
				if ($userID){
					$result = $dao->getUsers($userID);
				} else {
					$result = $dao->getUsers();
				}
				
				$responseBody = '';
				// Pretty print the response
				foreach ($result as $id => $row){
					foreach ($row as $key=>$value){
						$responseBody .= $key . ': ' . $value . '<br>';
					}
					$responseBody .= '<br>';
				} 
				
				$responseCode = HTTPSTATUS_OK;
				
			} catch (Exception $e) {
				$responseCode = HTTPSTATUS_NOTFOUND;
			}
			break;
		case "POST" :
			// Insert a new user
			
			try {
				// Create the parameter array
				$parameterArray = array();
				foreach ($decBody as $id => $value){
					array_push($parameterArray, $value);
				}
				//Convert password to int
				$parameterArray[3] = intval($parameterArray[3]);

				$dao->insertUser($parameterArray);
				$responseBody = "User inserted";
				$responseCode = HTTPSTATUS_CREATED;
			}catch (Exception $e) {
				$responseCode = HTTPSTATUS_BADREQUEST;
			}
			break;
		case "PUT" :
			// Update user specified by id
			try {				
				if ($userID){
					// Create the parameter array
					$parameterArray = array();
					foreach ($decBody as $id => $value){
						array_push($parameterArray, $value);
					}
					//Convert password to int
					$parameterArray[3] = intval($parameterArray[3]);
					
					$dao -> updateUser($userID, $parameterArray);
					$responseBody = "User Updated";
					$responseCode = HTTPSTATUS_CREATED;
				} else {
					$responseCode = HTTPSTATUS_BADREQUEST;
					break;
				}
			}catch (Exception $e) {
				$responseCode = HTTPSTATUS_BADREQUEST;
			}
			break;

			break;
		case "DELETE" :
			// Delete based on ID
			try{
				if ($userID){
					$result = $dao->deleteUser($userID);
				} else {
					$responseCode = HTTPSTATUS_BADREQUEST;
					break;
				}
				
				$responseBody = 'User Deleted';
				$responseCode = HTTPSTATUS_OK;			
			} catch (Exception $e) {
				$responseCode = HTTPSTATUS_BADREQUEST;
			}
			break;

	}
	
	// return response to client (as a json string)
	if ($responseBody != null)
		//$app->response->write ( json_encode ( $responseBody ) ); // this is the body of the response
		$app->response->write ( $responseBody );
			                                                         
	// TODO:we need to write also the response codes in the headers to send back to the client
	$app->response->status ( $responseCode );
	
	// Close DB connection
	$dbmgr->closeConnection();

} )->via ( "GET", "POST", "PUT", "DELETE" );

$app->run ();
?>
?>