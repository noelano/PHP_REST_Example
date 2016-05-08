<?php
require_once "../app/DB/pdoDbManager.php";
require_once "../app/DB/DAO/PlayersDAO.php";
require_once "../app/models/Validation.php";
class PlayerModel {
	private $PlayersDAO;
	private $dbmanager;
	public $apiResponse;
	private $validationSuite;
	public function __construct() {
		$this->dbmanager = new pdoDbManager ();
		$this->PlayersDAO = new PlayersDAO ( $this->dbmanager );
		$this->dbmanager->openConnection ();
		$this->validationSuite = new Validation ();
	}
	
	public function getPlayer($playerID = null) {
		if ($playerID){
			if (is_numeric ( $playerID ))
				return ($this->PlayersDAO->get ( $playerID ));
			return false;
		} else {
			return ($this->PlayersDAO->get ());
		}
	}
	
	public function getPlayerAndTeam($playerID = null) {
		if ($playerID){
			if (is_numeric ( $playerID ))
				return ($this->PlayersDAO->getPlayerAndTeam( $playerID ));
			return false;
		} else {
			return ($this->PlayersDAO->getPlayerAndTeam());
		}
	}
	
	public function createNewPlayer($newPlayer) {
		// validation of the values of the new player
		
		// compulsory values
		if (! empty ( $newPlayer ["name"] ) && ! empty ( $newPlayer ["surname"] ) && ! empty ( $newPlayer ["team"] ) && ! empty ( $newPlayer ["position"] ) && ! empty ( $newPlayer ["points"] ) && ! empty ( $newPlayer ["cost"] ) && ! empty ( $newPlayer ["selected_by"] )) {
			
			if (($this->validationSuite->isLengthStringValid ( $newPlayer ["name"], TABLE_PLAYER_NAME_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $newPlayer ["surname"], TABLE_PLAYER_SURNAME_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $newPlayer ["team"], TABLE_PLAYER_TEAM_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $newPlayer ["position"], TABLE_PLAYER_POSITION_LENGTH )) && is_numeric( $newPlayer["cost"]) && is_numeric( $newPlayer["points"]) && is_numeric( $newPlayer["selected_by"])) {
				if ($newId = $this->PlayersDAO->insert ( $newPlayer ))
					return ($newId);
			}
		}
		// if validation fails or insertion fails
		return (false);
	}
	
	public function updatePlayer($playerID, $playerNewRepresentation) {
		// compulsory values
		if (! empty ( $playerNewRepresentation ["name"] ) && ! empty ( $playerNewRepresentation ["surname"] ) && ! empty ( $playerNewRepresentation ["team"] ) && ! empty ( $playerNewRepresentation ["position"] ) && ! empty ( $playerNewRepresentation ["points"] ) && ! empty ( $playerNewRepresentation ["cost"] ) && ! empty ( $playerNewRepresentation ["selected_by"] )) {
			/*
			 * the model knows the representation of a user in the database and this is: name: varchar(25) surname: varchar(25) email: varchar(50) password: varchar(40)
			 */
			if (($this->validationSuite->isLengthStringValid ( $playerNewRepresentation ["name"], TABLE_PLAYER_NAME_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $playerNewRepresentation ["surname"], TABLE_PLAYER_SURNAME_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $playerNewRepresentation ["team"], TABLE_PLAYER_TEAM_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $playerNewRepresentation ["position"], TABLE_PLAYER_POSITION_LENGTH ))  && is_numeric( $playerNewRepresentation["cost"]) && is_numeric( $playerNewRepresentation["points"]) && is_numeric( $playerNewRepresentation["selected_by"])) {
				if ($rows = $this->PlayersDAO->update( $playerNewRepresentation, $playerID ))
					return ($rows);
			}
		}
		
		// if validation fails or insertion fails
		return (false);
	}
	
	public function searchPlayer($string) {
		// Check search string 
		
		if (gettype($string) == "string"){
			if ($rows = $this->PlayersDAO->search($string)){
				return ($rows);
			}
		}
		return (false);
	}
	
	public function deletePlayer($playerID) {
		// check user id is present and delete specified user
		
		if ($playerID){
			if ($rows = $this->PlayersDAO->delete($playerID)){
				return ($rows);
			}
		}
		
		return (false);
	}
	
	public function __destruct() {
		$this->PlayersDAO = null;
		$this->dbmanager->closeConnection ();
	}
}
?>