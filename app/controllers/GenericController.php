<?php
/**
 * @author Noel
 * Generic controller class
 * This handles all models - the specific model is passed in during instantiation
 * This even has the flexibility to handle the combined player and team request for the data vis
 */
class GenericController {
	private $slimApp;
	private $model;
	private $requestBody;
	private $recordType;
	public function __construct($model, $recordType, $action = null, $slimApp, $parameteres = null) {
		$this->model = $model;
		$this->slimApp = $slimApp;
		$this->recordType = $recordType;
		$this->requestBody = json_decode ( $this->slimApp->request->getBody (), true );
		
		if (! empty ( $parameteres ["id"] ))
			$id = $parameteres ["id"];
		
		switch ($action) {
			case ACTION_GET_RECORD :
				$this->getRecord ( $id ); 
				break;
			case ACTION_GET_RECORDS :
				$this->getRecord ();
				break;
			case ACTION_UPDATE_RECORD :
				$this->updateRecord ( $id, $this->requestBody );
				break;
			case ACTION_CREATE_RECORD :
				$this->createNewRecord ( $this->requestBody );
				break;
			case ACTION_DELETE_RECORD :
				$this->deleteRecord ( $id );
				break;
			case ACTION_SEARCH_RECORDS :
				$string = $parameteres ["SearchString"];
				$this->searchRecords ( $string );
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
	
	private function getRecord($id = null) {
		/*
		 * Since this class handles multiple models, the recordType is used to determine the correct 
		 * method name to call in the model
		 * For example, in PlayerModel we want to call getPlayer()
		 * This is achieved with $this->model->{"get". $this->recordType}
		 */
		$answer = $this->model->{"get". $this->recordType} ( $id );
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
	
	private function createNewRecord($newRecord) {
		if ($newID = $this->model->{"createNew" . $this->recordType} ( $newRecord )) {
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
	
	private function deleteRecord($id) {
		// delete record by id
		if ($rows = $this->model->{"delete" . $this->recordType} ($id)) {
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
	
	private function updateRecord($id, $parameterArray) {
		// Update record (specified by id)
		if ($rows = $this->model->{"update" . $this->recordType} ($id, $parameterArray)) {
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_OK );
			$Message = array (
					GENERAL_MESSAGE_LABEL => GENERAL_RESOURCE_UPDATED
			);
		} else if ($this->model->{"get" . $this->recordType} ($id)){
			// If the record exists there must be a problem with the parameters that were passed
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_BADREQUEST );
			$Message = array (
					GENERAL_MESSAGE_LABEL => GENERAL_INVALIDBODY 
			);
		} else {
			// ID is invalid
			$this->slimApp->response ()->setStatus ( HTTPSTATUS_NOTFOUND );
			$Message = array (
					GENERAL_MESSAGE_LABEL => GENERAL_NOCONTENT_MESSAGE 
			);
		}
		$this->model->apiResponse = $Message;
	}
	
	private function searchRecords($string) {
		$rows = $this->model->{"search" . $this->recordType} ($string);
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
}
?>