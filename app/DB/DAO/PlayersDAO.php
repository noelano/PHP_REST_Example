<?php
/**
 * @author Noel
 * definition of the Player DAO (database access object)
 */
class PlayersDAO {
	private $dbManager;
	function PlayersDAO($DBMngr) {
		$this->dbManager = $DBMngr;
	}
	
	public function get($id = null) {
		$sql = "SELECT * ";
		$sql .= "FROM players ";
		if ($id != null)
			$sql .= "WHERE players.id=? ";
		$sql .= "ORDER BY players.name ";
		
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $id, $this->dbManager->INT_TYPE );
		$this->dbManager->executeQuery ( $stmt );
		$rows = $this->dbManager->fetchResults ( $stmt );
		
		return ($rows);
	}
	
	public function insert($parametersArray) {
		// insertion assumes that all the required parameters are defined and set
		$sql = "INSERT INTO players (name, surname, position, team, cost, points, selected_by) ";
		$sql .= "VALUES (?,?,?,?,?,?, ?) ";
		
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $parametersArray ["name"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 2, $parametersArray ["surname"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 3, $parametersArray ["position"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 4, $parametersArray ["team"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 5, $parametersArray ["cost"], $this->dbManager->INT_TYPE );
		$this->dbManager->bindValue ( $stmt, 6, $parametersArray ["points"], $this->dbManager->INT_TYPE );
		$this->dbManager->bindValue ( $stmt, 7, $parametersArray ["selected_by"], $this->dbManager->INT_TYPE );
		
		$this->dbManager->executeQuery ( $stmt );
		
		return ($this->dbManager->getLastInsertedID ());
	}
	
	public function update($parametersArray, $playerID) {
		// Update the player specified by id with values in $parameterArray
		
		$sql = "UPDATE players ";
		$sql .= "SET name = ?, surname = ?, position = ?, team = ?, cost = ?, points = ?, selected_by = ? ";
		$sql .= "WHERE id = ?";
		
		$stmt = $this->dbManager->prepareQuery($sql);

		$this->dbManager->bindValue ( $stmt, 1, $parametersArray ["name"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 2, $parametersArray ["surname"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 3, $parametersArray ["position"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 4, $parametersArray ["team"], $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue ( $stmt, 5, $parametersArray ["cost"], $this->dbManager->INT_TYPE );
		$this->dbManager->bindValue ( $stmt, 6, $parametersArray ["points"], $this->dbManager->INT_TYPE );
		$this->dbManager->bindValue ( $stmt, 7, $parametersArray ["selected_by"], $this->dbManager->INT_TYPE );
		$this->dbManager->bindValue($stmt, 8, $playerID, $this->dbManager->INT_TYPE );
		
		$this->dbManager->executeQuery($stmt);
		
		return ($this->dbManager->getNumberOfAffectedRows($stmt));

	}
	
	public function delete($playerID) {
		// Delete the player specified by ID
		
		$sql = "DELETE FROM players WHERE id = ?";
		$stmt = $this->dbManager->prepareQuery($sql);
		$this->dbManager->bindValue($stmt, 1, $playerID, $this->dbManager->INT_TYPE );
		
		$this->dbManager->executeQuery($stmt);
		
		return ($this->dbManager->getNumberOfAffectedRows($stmt));
	}
	
	public function search($str) {
		// Identify players by searching on the name, surname, team or position
		$sql = "SELECT * FROM players WHERE (";
		$sql .= "name like ? ";
		$sql .= "OR surname like ? ";
		$sql .= "OR team like ? ";
		$sql .= "OR position like ?)";
		$stmt = $this->dbManager->prepareQuery($sql);
		
		$this->dbManager->bindValue($stmt, 1, '%'.$str.'%', $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue($stmt, 2, '%'.$str.'%', $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue($stmt, 3, '%'.$str.'%', $this->dbManager->STRING_TYPE );
		$this->dbManager->bindValue($stmt, 4, '%'.$str.'%', $this->dbManager->STRING_TYPE );
		
		$this->dbManager->executeQuery($stmt);
		$rows = $this->dbManager->fetchResults ( $stmt );
		
		return ($rows);
	}
	
	/**
	 * 
	 * Join the team information to the player records and return all data
	 * @param int $id
	 * @return array $rows
	 */
	public function getPlayerAndTeam($id = null) {
		$sql = "SELECT a.id as id, a.name as name, surname, a.position as position, cost, points, selected_by, b.name as team, colour, division, b.position as league_standing ";
		$sql .= "FROM players a INNER JOIN teams b ";
		$sql .= "ON a.team = b.name ";
		if ($id != null)
			$sql .= "WHERE a.id=? ";
		$sql .= "ORDER BY surname ";
		
		$stmt = $this->dbManager->prepareQuery ( $sql );
		$this->dbManager->bindValue ( $stmt, 1, $id, $this->dbManager->INT_TYPE );
		$this->dbManager->executeQuery ( $stmt );
		$rows = $this->dbManager->fetchResults ( $stmt );
		
		return ($rows);
	}
}
?>
