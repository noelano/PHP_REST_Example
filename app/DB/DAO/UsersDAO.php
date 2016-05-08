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
	public function get($id = null) {
		$sql = "SELECT * ";
		$sql .= "FROM users ";
		if ($id != null)
			$sql .= "WHERE users.id=? ";
		$sql .= "ORDER BY users.name ";
		
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $id, $this->dbManager->INT_TYPE );
		$this->dbManager->executeQuery ( $stmt );
		$rows = $this->dbManager->fetchResults ( $stmt );
		
		return ($rows);
	}
	public function insert($parametersArray) {
		// insertion assumes that all the required parameters are defined and set
		$sql = "INSERT INTO users (user_name, name, surname, email, password) ";
		$sql .= "VALUES (?,?,?,?,?) ";
		
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $parametersArray ["user_name"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 2, $parametersArray ["name"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 3, $parametersArray ["surname"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 4, $parametersArray ["email"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 5, $parametersArray ["password"], $this->dbManager->STRING_TYPE );
		$this->dbManager->executeQuery ( $stmt );
		
		return ($this->dbManager->getLastInsertedID ());
	}
	public function update($parametersArray, $userID) {
		// Update the user specified by id with values in $parameterArray
		
		$sql = "UPDATE users ";
		$sql .= "SET user_name = ?, name = ?, surname = ?, email = ?, password = ? ";
		$sql .= "WHERE id = ?";
		$stmt = $this->dbManager->prepareQuery($sql);
		
		$this->dbManager->bindValue($stmt, 1, $parametersArray["user_name"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue($stmt, 2, $parametersArray["name"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue($stmt, 3, $parametersArray["surname"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue($stmt, 4, $parametersArray["email"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue($stmt, 5, $parametersArray["password"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue($stmt, 6, $userID, $this->dbManager->INT_TYPE );
		
		$this->dbManager->executeQuery($stmt);
		
		return ($this->dbManager->getNumberOfAffectedRows($stmt));
	}
	public function delete($userID) {
		// Delete the user specified by ID
		
		$sql = "DELETE FROM users WHERE id = ?";
		$stmt = $this->dbManager->prepareQuery($sql);
		$this->dbManager->bindValue($stmt, 1, $userID, $this->dbManager->INT_TYPE );
		
		$this->dbManager->executeQuery($stmt);
		
		return ($this->dbManager->getNumberOfAffectedRows($stmt));
	}
	public function search($str) {
		// Identify user based on the specified name, surname or email
		$sql = "SELECT * FROM users WHERE (";
		$sql .= "user_name like ? ";
		$sql .= "OR name like ? ";
		$sql .= "OR surname like ? ";
		$sql .= "OR email like ?)";
		$stmt = $this->dbManager->prepareQuery($sql);
		
		$this->dbManager->bindValue($stmt, 1, '%'.$str.'%', $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue($stmt, 2, '%'.$str.'%', $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue($stmt, 3, '%'.$str.'%', $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue($stmt, 4, '%'.$str.'%', $this->dbManager->STRING_TYPE );
		
		$this->dbManager->executeQuery($stmt);
		$rows = $this->dbManager->fetchResults ( $stmt );
		
		return ($rows);
	}
	
	public function getUserByName($name) {
		// For use in the authentication function
		$sql = "SELECT * FROM users WHERE (";
		$sql .= "user_name like ?)";
		
		$stmt = $this->dbManager->prepareQuery($sql);
		
		$this->dbManager->bindValue($stmt, 1, $name, $this->dbManager->STRING_TYPE );
		
		$this->dbManager->executeQuery($stmt);
		$rows = $this->dbManager->fetchResults ( $stmt );
		
		return ($rows);
	}
}
?>
