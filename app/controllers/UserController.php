<?php
class UserController {
	private $slimApp;
	private $model;
	private $requestBody;
	public function __construct($model, $action = null, $slimApp, $parameteres = null) {
		$this->model = $model;
		$this->slimApp = $slimApp;
		$this->requestBody = json_decode ( $this->slimApp->request->getBody (), true ); // this must contain the representation of the new user
		
		if (! empty ( $parameteres ["id"] ))
			$id = $parameteres ["id"];
		
		switch ($action) {
			case ACTION_GET_USER :
				$this->getUsers ( $id );		// Changed to getUsers to streamline 
				break;
			case ACTION_GET_USERS :
				$this->getUsers ();
				break;
			case ACTION_UPDATE_USER :
				$this->updateUser ( $id, $this->requestBody );
				break;
			case ACTION_CREATE_USER :
				$this->createNewUser ( $this->requestBody );
				break;
			case ACTION_DELETE_USER :
				$this->deleteUser ( $id );
				break;
			case ACTION_SEARCH_USERS :
				$string = $parameteres ["SearchString"];
				$this->searchUsers ( $string );
				break;
			case ACTION_AUTHENTICATE :
				$user = $parameteres["user"];
				$pass = $parameteres["pass"];
				$this->authenticate($user, $pass);
				break;
			case null :
				$this->slimApp->response ()->setStatus ( HTTPSTATUS_BADREQUEST );
				$Message = array (
						GENERAL_MESSAGE_LABEL => GENERAL_CLIENT_ERROR 
				);
				$this->model->apiResponse = $Message;
				break;
		}
	}
	/*
	// Deprecated - removed to refine code
	private function getUsers() {
		$answer = $this->model->getUsers ();
		if ($answer != null) {
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_OK );
			$this->model->apiResponse = $answer;
		} else {
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_NOCONTENT );
			$Message = array (
					GENERAL_MESSAGE_LABEL => GENERAL_NOCONTENT_MESSAGE 
			);
			$this->model->apiResponse = $Message;
		}
	}
	*/
	
	private function getUsers($userID = null) {
		$answer = $this->model->getUsers ( $userID );
		if ($answer != null) {
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_OK );
			$this->model->apiResponse = $answer;
		} else {		
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_NOTFOUND );
			$Message = array (
					GENERAL_MESSAGE_LABEL => GENERAL_NOCONTENT_MESSAGE 
			);
			$this->model->apiResponse = $Message;
		}
	}
	
	private function createNewUser($newUser) {
		if ($newID = $this->model->createNewUser ( $newUser )) {
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_CREATED );
			$Message = array (
					GENERAL_MESSAGE_LABEL => GENERAL_RESOURCE_CREATED,
					"id" => "$newID" 
			);
			$this->model->apiResponse = $Message;
		} else {
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_BADREQUEST );
			$Message = array (
					GENERAL_MESSAGE_LABEL => GENERAL_INVALIDBODY 
			);
			$this->model->apiResponse = $Message;
		}
	}
	private function deleteUser($userId) {
		// delete user by id
		if ($rows = $this->model->deleteUser($userId)) {
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_OK );
			$Message = array (
					GENERAL_MESSAGE_LABEL => GENERAL_RESOURCE_DELETED
			);
			$this->model->apiResponse = $Message;
		} else {
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_NOTFOUND );
			$Message = array (
					GENERAL_MESSAGE_LABEL => GENERAL_NOCONTENT_MESSAGE 
			);
			$this->model->apiResponse = $Message;
		}
	}
	
	private function updateUser($userId, $parameterArray) {
		// Update user (specified by id)
		if ($rows = $this->model->updateUser($userId, $parameterArray)) {
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_OK );
			$Message = array (
					GENERAL_MESSAGE_LABEL => GENERAL_RESOURCE_UPDATED
			);
		} else if ($this->model->getUsers($userId)){
			// If the user exists there must be a problem with the parameters that were passed
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_BADREQUEST );
			$Message = array (
					GENERAL_MESSAGE_LABEL => GENERAL_INVALIDBODY 
			);
		} else {
			// User ID is invalid
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_NOTFOUND );
			$Message = array (
					GENERAL_MESSAGE_LABEL => GENERAL_NOCONTENT_MESSAGE 
			);
		}
		$this->model->apiResponse = $Message;
	}
	private function searchUsers($string) {
		// Search the name, surname and email for matches with the parameter string
		$rows = $this->model->searchUsers($string);
		if ($rows != null) {
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_OK );
			$Message = $rows;
		} else {
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_NOTFOUND );
			$Message = array (
					GENERAL_MESSAGE_LABEL => GENERAL_NOCONTENT_MESSAGE 
			);
		}
		$this->model->apiResponse = $Message;
	}
	
	private function authenticate($user, $pass) {
		// Check that the username and password are present in the DB and correct
		$db_pass = $this->model->getPassByUser($user);
		$this->model->apiResponse = HTTPSTATUS_UNAUTHORIZED;
		
		if ($db_pass) {
			if ($pass == $db_pass){
				$this->slimApp->response ()->setStatus ( HTTPSTATUS_OK );
				$this->model->apiResponse = HTTPSTATUS_OK;
			}
		}		
	}
}
?>