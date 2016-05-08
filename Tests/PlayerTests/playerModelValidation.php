<?php
/**
 * @author Noel
 * Validation of player model functionality
 */

require_once('../SimpleTest/simpletest/autorun.php');

class playerModelValidationClass extends UnitTestCase {
	private $Model;
	private $PlaceholderID;	// Will be used for later deletion of inserted player
	public function setUp(){
		require_once "../Slim/Slim.php";
		Slim\Slim::registerAutoloader ();

		$app = new \Slim\Slim ();
		require_once('../app/models/playerModel.php');
		$this->Model = new PlayerModel();
	}
	
	public function tearDown(){
		$this->Model = NULL;
	}
	
	/**
	 * Test to check player insertion
	 * 
	 */
	public function testPlayerInsertion(){
		$player = array("name" => "Jimmy", "surname" => "McGee", "position" => "Defender", "team" => "Arsenal", "cost" => 10, "points" => 10, "selected_by" => 11);
		// Record the ID of the inserted userr so that subsequent tests can use it
		$this->PlaceholderID = $this->Model->createNewPlayer($player);
		$this->assertTrue($this->PlaceholderID);
		
		$player["cost"] = "one";
		$this->assertFalse($this->Model->createNewPlayer($player));
		
		$player["cost"] = array(1);
		$this->assertFalse($this->Model->createNewPlayer($player));
		
		$player = array();
		$this->assertFalse($this->Model->createNewPlayer($player));

	}
	
	public function testPlayerRetrieval(){
		$player = array(0 => array("id" => $this->PlaceholderID, "name" => "Jimmy", "surname" => "McGee", "position" => "Defender", "team" => "Arsenal", "cost" => 10, "points" => 10, "selected_by" => 11));
		$this->assertEqual($this->Model->getPlayer($this->PlaceholderID), $player);
		$this->assertFalse($this->Model->getPlayer("abcd"));
		$this->assertFalse($this->Model->getPlayer($this->PlaceholderID + 1));
	}
	
	public function testPlayerUpdate(){
		$update = array("name" => "Johny", "surname" => "McGoo", "position" => "Defender", "team" => "Arsenal", "cost" => 9, "points" => 7, "selected_by" => 2);
		$this->assertTrue($this->Model->updatePlayer($this->PlaceholderID, $update));
		$update["id"] = $this->PlaceholderID;
		// The model returns the player inside an array
		$player = array(0 => $update);
		$this->assertEqual($player, $this->Model->getPlayer($this->PlaceholderID));
		
		$update["name"] = str_repeat("A", 80);	// Max length is 25
		$this->assertFalse($this->Model->updatePlayer($this->PlaceholderID, $update));
		
		$update["name"] = 12345;
		$this->assertFalse($this->Model->updatePlayer($this->PlaceholderID, $update));
	}
	
	public function testPlayerSearch(){
		$this->assertTrue($this->Model->searchPlayer("McGoo"));
		$this->assertTrue($this->Model->searchPlayer("Johny"));
		$this->assertFalse($this->Model->searchPlayer("xraerqwzxdfaw"));
	}
	
	public function testPlayerDeletion(){
		$this->assertTrue($this->Model->deletePlayer($this->PlaceholderID));
		$this->assertFalse($this->Model->getPlayer($this->PlaceholderID));
	}
	
}
?>