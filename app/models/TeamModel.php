<?php
require_once '../app/conf/config.inc.php';
require_once "../app/DB/pdoDbManager.php";
require_once "../app/DB/DAO/TeamsDAO.php";
require_once "../app/Models/Validation.php";
class TeamModel {
	private $TeamsDAO; // list of DAOs used by this model
	private $dbmanager; // dbmanager
	public $apiResponse; // api response
	private $validationSuite; // contains functions for validating inputs
	public function __construct() {
		$this->dbmanager = new pdoDbManager ();
		$this->TeamsDAO = new TeamsDAO ( $this->dbmanager );
		$this->dbmanager->openConnection ();
		$this->validationSuite = new Validation ();
	}
	public function getTeam($teamID = null) {
		if ($teamID){
			if (is_numeric ( $teamID ))
				return ($this->TeamsDAO->get ( $teamID ));
			return false;
		} else {
			return ($this->TeamsDAO->get ());
		}
	}
	
	public function createNewTeam($newTeam) {
		// validation of the values of the new team
		
		// compulsory values
		if (! empty ( $newTeam ["name"] ) && ! empty ( $newTeam ["colour"] ) && ! empty ( $newTeam ["division"] ) && ! empty ( $newTeam ["position"] )) {
			
			if (($this->validationSuite->isLengthStringValid ( $newTeam ["name"], TABLE_TEAM_NAME_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $newTeam ["colour"], TABLE_TEAM_COLOUR_LENGTH )) && is_numeric($newTeam["position"]) && is_numeric($newTeam["division"]) ) {
				if ($newId = $this->TeamsDAO->insert ( $newTeam ))
					return ($newId);
			}
		}
		// if validation fails or insertion fails
		return (false);
	}
	public function updateTeam($teamID, $teamNewRepresentation) {
		// compulsory values
		if (! empty ( $teamNewRepresentation ["name"] ) && ! empty ( $teamNewRepresentation ["colour"] ) && ! empty ( $teamNewRepresentation ["division"] ) && ! empty ( $teamNewRepresentation ["position"] )) {

			if (($this->validationSuite->isLengthStringValid ( $teamNewRepresentation ["name"], TABLE_TEAM_NAME_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $teamNewRepresentation ["colour"], TABLE_TEAM_COLOUR_LENGTH )) && is_numeric($teamNewRepresentation["position"]) && is_numeric($teamNewRepresentation["division"])) {	
				if ($rows = $this->TeamsDAO->update( $teamNewRepresentation, $teamID ))
					return ($rows);
			}
		}
		
		// if validation fails or insertion fails
		return (false);
	}
	public function searchTeam($string) {
		// Check search string 
		
		if (gettype($string) == "string"){
			if ($rows = $this->TeamsDAO->search($string)){
				return ($rows);
			}
		}
		return (false);
	}
	
	public function deleteTeam($teamID) {
		// check team id is present and delete specified team
		
		if ($teamID){
			if ($rows = $this->TeamsDAO->delete($teamID)){
				return ($rows);
			}
		}
		
		return (false);
	}
	public function __destruct() {
		$this->TeamsDAO = null;
		$this->dbmanager->closeConnection ();
	}
}
?>