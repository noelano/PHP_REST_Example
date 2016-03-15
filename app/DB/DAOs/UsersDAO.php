<?php
/**
 * @author Luca
 * definition of the User DAO (database access object)
 */
class UsersDAO {
	private $dbManager;
	function UsersDAO($DBMngr) {
		$this->dbManager = $DBMngr;
	}
	function getUsers($id = null) {
		// Get the user specified by id or return all users

		if ($id){
			$sql = "SELECT * FROM users WHERE id = :id";
			$stmt = $this->dbManager->prepareQuery($sql);
			$this->dbManager->bindValue($stmt, ':id', $id, PDO::PARAM_INT);
			
			$this->dbManager->executeQuery($stmt);
		
			$result = $this->dbManager->fetchResults($stmt);
		
			return $result;			
		} else {
			$sql = "SELECT * ";
			$sql .= "FROM users ";
			$sql .= "ORDER BY users.name; ";
		
			$stmt = $this->dbManager->prepareQuery($sql);
		
			$this->dbManager->executeQuery($stmt);
		
			$result = $this->dbManager->fetchResults($stmt);
		
			return $result;
		}
	}
	
	function getUserById($id){
		$sql = "SELECT * FROM users WHERE id = :id";
		$stmt = $this->dbManager->prepareQuery($sql);
		$this->dbManager->bindValue($stmt, ':id', $id, PDO::PARAM_INT);
			
		$this->dbManager->executeQuery($stmt);
		
		$result = $this->dbManager->fetchResults($stmt);
		
		return $result;			
	}
	
	function insertUser($parametersArray) {
		//create an INSERT INTO sql statement (reads the parametersArray - this contains the fields submitted in the HTML5 form)
		
		//execute the query
		$sql = "INSERT INTO ";
		$sql .= "users (name, surname, email, password) ";
		$sql .= "VALUES (:name, :surname, :email, :password)";
		
		$stmt = $this->dbManager->prepareQuery($sql);

		$this->dbManager->bindValue($stmt, ':name', $parametersArray[0], PDO::PARAM_STR);
		$this->dbManager->bindValue($stmt, ':surname', $parametersArray[1], PDO::PARAM_STR);
		$this->dbManager->bindValue($stmt, ':email', $parametersArray[2], PDO::PARAM_STR);
		$this->dbManager->bindValue($stmt, ':password', $parametersArray[3], PDO::PARAM_INT);
		
		// Dump SQL fro debugging
		//$this->dbManager->debugSQL($stmt);
		
		$this->dbManager->executeQuery($stmt);
	}
	
	function updateUser($id, $parametersArray) {
		// Update the user specified by id with values in $parameterArray
		
		$sql = "UPDATE USERS ";
		$sql .= "SET name = :name, surname = :surname, email = :email, password = :password ";
		$sql .= "WHERE ID = :id";
		$stmt = $this->dbManager->prepareQuery($sql);
		
		$this->dbManager->bindValue($stmt, ':id', $id, PDO::PARAM_INT);
		$this->dbManager->bindValue($stmt, ':name', $parametersArray[0], PDO::PARAM_STR);
		$this->dbManager->bindValue($stmt, ':surname', $parametersArray[1], PDO::PARAM_STR);
		$this->dbManager->bindValue($stmt, ':email', $parametersArray[2], PDO::PARAM_STR);
		$this->dbManager->bindValue($stmt, ':password', $parametersArray[3], PDO::PARAM_STR);
		
		$this->dbManager->executeQuery($stmt);
	}
	
	function deleteUser($id) {
		// Delete the user specified by ID
		
		$sql = "DELETE FROM users WHERE id = :id";
		$stmt = $this->dbManager->prepareQuery($sql);
		$this->dbManager->bindValue($stmt, ':id', $id, PDO::PARAM_INT);
		
		$this->dbManager->executeQuery($stmt);
		
	}
}
?>
