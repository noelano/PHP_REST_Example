<?php
/**
 * @author Noel
 * definition of the Team DAO (database access object)
 */
class TeamsDAO {
	private $dbManager;
	function TeamsDAO($DBMngr) {
		$this->dbManager = $DBMngr;
	}
	public function get($id = null) {
		$sql = "SELECT * ";
		$sql .= "FROM teams ";
		if ($id != null)
			$sql .= "WHERE teams.id=? ";
		$sql .= "ORDER BY teams.name ";
		
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $id, $this->dbManager->INT_TYPE );
		$this->dbManager->executeQuery ( $stmt );
		$rows = $this->dbManager->fetchResults ( $stmt );
		
		return ($rows);
	}
	public function insert($parametersArray) {
		// insertion assumes that all the required parameters are defined and set
		$sql = "INSERT INTO teams (name, colour, division, position) ";
		$sql .= "VALUES (?,?,?,?) ";
		
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $parametersArray ["name"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 2, $parametersArray ["colour"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 3, $parametersArray ["division"], $this->dbManager->INT_TYPE );
		$this->dbManager->bindValue ( $stmt, 4, $parametersArray ["position"], $this->dbManager->INT_TYPE );
		
		$this->dbManager->executeQuery ( $stmt );
		
		return ($this->dbManager->getLastInsertedID ());
	}
	public function update($parametersArray, $teamID) {
		// Update the team specified by id with values in $parameterArray
		
		$sql = "UPDATE teams ";
		$sql .= "SET name = ?, colour = ?, division = ?, position = ? ";
		$sql .= "WHERE id = ?";
		
		$stmt = $this->dbManager->prepareQuery($sql);

		$this->dbManager->bindValue ( $stmt, 1, $parametersArray ["name"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 2, $parametersArray ["colour"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 3, $parametersArray ["division"], $this->dbManager->INT_TYPE );
		$this->dbManager->bindValue ( $stmt, 4, $parametersArray ["position"], $this->dbManager->INT_TYPE );
		$this->dbManager->bindValue($stmt, 5, $teamID, $this->dbManager->INT_TYPE );
		
		$this->dbManager->executeQuery($stmt);
		
		return ($this->dbManager->getNumberOfAffectedRows($stmt));

	}
	public function delete($teamID) {
		// Delete the team specified by ID
		
		$sql = "DELETE FROM teams WHERE id = ?";
		$stmt = $this->dbManager->prepareQuery($sql);
		$this->dbManager->bindValue($stmt, 1, $teamID, $this->dbManager->INT_TYPE );
		
		$this->dbManager->executeQuery($stmt);
		
		return ($this->dbManager->getNumberOfAffectedRows($stmt));
	}
	public function search($str) {
		// Identify team based on the specified name or colour
		$sql = "SELECT * FROM teams WHERE (";
		$sql .= "name like ? ";
		$sql .= "OR colour like ?)";
		$stmt = $this->dbManager->prepareQuery($sql);
		
		$this->dbManager->bindValue($stmt, 1, '%'.$str.'%', $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue($stmt, 2, '%'.$str.'%', $this->dbManager->STRING_TYPE );
		
		$this->dbManager->executeQuery($stmt);
		$rows = $this->dbManager->fetchResults ( $stmt );
		
		return ($rows);
	}
}
?>
