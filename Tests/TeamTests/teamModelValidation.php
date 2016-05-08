<?php
/**
 * @author Noel
 * Validation of team model functionality
 */

require_once('../SimpleTest/simpletest/autorun.php');

class teamModelValidationClass extends UnitTestCase {
	private $Model;
	private $PlaceholderID;
	public function setUp(){
		require_once "../Slim/Slim.php";
		Slim\Slim::registerAutoloader ();

		$app = new \Slim\Slim ();
		require_once('../app/models/teamModel.php');
		$this->Model = new TeamModel();
	}
	
	public function tearDown(){
		$this->Model = NULL;
	}
	
	public function testTeamInsertion(){
		$team = array("name" => "Test Team United", "colour" => "red", "division" => 1, "position" => 5);
		$this->PlaceholderID = $this->Model->createNewTeam($team);
		$this->assertTrue($this->PlaceholderID);
		
		$team["division"] = "xyz";
		$this->assertFalse($this->Model->createNewTeam($team));
		
		$team["position"] = array(1,2,3);
		$team["division"] = 1;
		$this->assertFalse($this->Model->createNewTeam($team));
		
		$team["position"] = 8;
		$team["name"] = 1234;
		$this->assertFalse($this->Model->createNewTeam($team));
		
		$team["name"] = str_repeat("x", 70);	// Max length is 50
		$this->assertFalse($this->Model->createNewTeam($team));
		
		$team = array();
		$this->assertFalse($this->Model->createNewTeam($team));

	}
	
	public function testTeamRetrieval(){
		$team = array(0 => array("id" => $this->PlaceholderID, "name" => "Test Team United", "colour" => "red", "division" => 1, "position" => 5));
		$this->assertEqual($this->Model->getTeam($this->PlaceholderID), $team);
		$this->assertFalse($this->Model->getTeam("abcd"));
		$this->assertFalse($this->Model->getTeam($this->PlaceholderID + 1));
	}
	
	public function testTeamUpdate(){
		$update = array("name" => "Test Team Wanderers", "colour" => "red", "division" => 1, "position" => 5);
		$this->assertTrue($this->Model->updateTeam($this->PlaceholderID, $update));
		$update["id"] = $this->PlaceholderID;
		$team = array(0 => $update);
		$this->assertEqual($team, $this->Model->getTeam($this->PlaceholderID));
		
		$this->assertFalse($this->Model->updateTeam("not_an_id", $update));
		
		$update["colour"] = array("a", 3);
		$this->assertFalse($this->Model->updateTeam($this->PlaceholderID, $update));
	}
	
	public function testTeamSearch(){
		$this->assertTrue($this->Model->searchTeam("Wanderers"));
		$this->assertTrue($this->Model->searchTeam("red"));
		$this->assertFalse($this->Model->searchTeam("xraerqwzxdfaw"));
	}
	
	public function testTeamDeletion(){
		$this->assertTrue($this->Model->deleteTeam($this->PlaceholderID));
		$this->assertFalse($this->Model->getTeam($this->PlaceholderID));
	}
		
}
?>