<?php
/**
 * @author Noel
 * Validation of user model functionality
 */

require_once('../SimpleTest/simpletest/autorun.php');

class userModelValidationClass extends UnitTestCase {
	private $Model;
	private $PlaceholderID;		// This is to enable the deletion of all records inserted by the test
	public function setUp(){
		require_once "../Slim/Slim.php";
		Slim\Slim::registerAutoloader ();

		$app = new \Slim\Slim ();
		require_once('../app/models/userModel.php');
		$this->Model = new UserModel();
	}
	
	public function tearDown(){
		$this->Model = NULL;
	}
	
	public function testUserInsertion(){
		$user = array("user_name" => "test", "name" => "test", "surname" => "testtest", "email" => "Test@123.com", "password" => "4444");
		$this->PlaceholderID = $this->Model->createNewUser($user);
		$this->assertTrue($this->PlaceholderID);
		
		$user["name"] = "long_name_with_more_than_twenty_five_characters";
		$this->assertFalse($this->Model->createNewUser($user));
		
		$user["name"] = "test";
		$user["user_name"] = array("abcd");
		$this->assertFalse($this->Model->createNewUser($user));
		
		$user["user_name"] = 12345;
		$this->assertFalse($this->Model->createNewUser($user));
		
		$user = array();
		$this->assertFalse($this->Model->createNewUser($user));

	}
	
	public function testUserRetrieval(){
		$user = array(0 => array("id" => $this->PlaceholderID, "user_name" => "test", "name" => "test", "surname" => "testtest", "email" => "Test@123.com", "password" => "4444"));
		$this->assertEqual($this->Model->getUser($this->PlaceholderID), $user);
		$this->assertFalse($this->Model->getUser("abcd"));
		$this->assertFalse($this->Model->getUser($this->PlaceholderID + 1));
		$this->assertFalse($this->Model->getUser(array(123, "ABC")));
	}
	
	public function testUserUpdate(){
		$update = array("user_name" => "tester", "name" => "Mc Test", "surname" => "testtest", "email" => "Test@123.com", "password" => "4444");
		$this->assertTrue($this->Model->updateUser($this->PlaceholderID, $update));
		$update["id"] = $this->PlaceholderID;
		$user = array(0 => $update);
		$this->assertEqual($user, $this->Model->getUser($this->PlaceholderID));
		$update["surname"] = "really_really_really_really_long_string_too_long_for_DB_column";
		$this->assertFalse($this->Model->updateUser($this->PlaceholderID, $update));
		$user = array();
		$this->assertFalse($this->Model->updateUser($this->PlaceholderID, $user));
	}
	
	public function testUserSearch(){
		$this->assertTrue($this->Model->searchUser("tester"));
		$this->assertTrue($this->Model->searchUser("Mc Test"));
		$this->assertFalse($this->Model->searchUser("xraerqwzxdfaw"));
		$user = array(0 => array("id" => $this->PlaceholderID, "user_name" => "tester", "name" => "Mc Test", "surname" => "testtest", "email" => "Test@123.com", "password" => "4444"));
		$this->assertEqual($this->Model->searchUser("testtest"), $user);
	}
	
	public function testUserDeletion(){
		$this->assertTrue($this->Model->deleteUser($this->PlaceholderID));
		$this->assertFalse($this->Model->getUser($this->PlaceholderID));
	}
		
}
?>