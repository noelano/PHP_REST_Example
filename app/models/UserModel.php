<?php
require_once "../app/DB/pdoDbManager.php";
require_once "../app/DB/DAO/UsersDAO.php";
require_once "../app/models/Validation.php";
class UserModel {
	private $UsersDAO; // list of DAOs used by this model
	private $dbmanager; // dbmanager
	public $apiResponse; // api response
	private $validationSuite; // contains functions for validating inputs
	public function __construct() {
		$this->dbmanager = new pdoDbManager ();
		$this->UsersDAO = new UsersDAO ( $this->dbmanager );
		$this->dbmanager->openConnection ();
		$this->validationSuite = new Validation ();
	}
	public function getUser($userID = null) {
		if ($userID){
			if (is_numeric ( $userID ))
				return ($this->UsersDAO->get ( $userID ));
			return false;
		} else {
			return ($this->UsersDAO->get ());
		}
	}
	
	public function createNewUser($newUser) {
		// validation of the values of the new user
		
		// compulsory values
		if (! empty ( $newUser ["user_name"] ) && ! empty ( $newUser ["name"] ) && ! empty ( $newUser ["surname"] ) && ! empty ( $newUser ["email"] ) && ! empty ( $newUser ["password"] )) {
			/*
			 * the model knows the representation of a user in the database and this is: name: varchar(25) surname: varchar(25) email: varchar(50) password: varchar(40)
			 */
			
			if (($this->validationSuite->isLengthStringValid ( $newUser ["user_name"], TABLE_USER_USERNAME_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $newUser ["name"], TABLE_USER_NAME_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $newUser ["surname"], TABLE_USER_SURNAME_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $newUser ["email"], TABLE_USER_EMAIL_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $newUser ["password"], TABLE_USER_PASSWORD_LENGTH ))) {
				if ($newId = $this->UsersDAO->insert ( $newUser ))
					return ($newId);
			}
		}
		
		// if validation fails or insertion fails
		return (false);
	}
	public function updateUser($userID, $userNewRepresentation) {
		// compulsory values
		if (! empty ( $userNewRepresentation ["user_name"] ) && ! empty ( $userNewRepresentation ["name"] ) && ! empty ( $userNewRepresentation ["surname"] ) && ! empty ( $userNewRepresentation ["email"] ) && ! empty ( $userNewRepresentation ["password"] )) {
			/*
			 * the model knows the representation of a user in the database and this is: name: varchar(25) surname: varchar(25) email: varchar(50) password: varchar(40)
			 */
			
			if (($this->validationSuite->isLengthStringValid ( $userNewRepresentation["user_name"], TABLE_USER_USERNAME_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $userNewRepresentation["name"], TABLE_USER_NAME_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $userNewRepresentation["surname"], TABLE_USER_SURNAME_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $userNewRepresentation["email"], TABLE_USER_EMAIL_LENGTH )) && ($this->validationSuite->isLengthStringValid ( $userNewRepresentation["password"], TABLE_USER_PASSWORD_LENGTH ))) {
				if ($rows = $this->UsersDAO->update( $userNewRepresentation, $userID ))
					return ($rows);
			}
		}
		
		// if validation fails or insertion fails
		return (false);
	}
	public function searchUser($string) {
		// Check search string 
		
		if (gettype($string) == "string"){
			if ($rows = $this->UsersDAO->search($string)){
				return ($rows);
			}
		}
		return (false);
	}
	
	public function deleteUser($userID) {
		// check user id is present and delete specified user
		
		if ($userID){
			if ($rows = $this->UsersDAO->delete($userID)){
				return ($rows);
			}
		}
		
		return (false);
	}
	
	public function getPassByUser($user) {
		// Retrieve the password from the DB for the specified user
		if ($user){
			if ($rows = $this->UsersDAO->getUserByName($user)){
				return ($rows[0]["password"]);
			}
		}
		return (false);
	}
	
	public function __destruct() {
		$this->UsersDAO = null;
		$this->dbmanager->closeConnection ();
	}
}
?>