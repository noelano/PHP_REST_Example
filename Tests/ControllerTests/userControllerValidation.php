<?php
/**
 * @author Noel
 * Validation of user controller functionality
 * NB: This test is not to be used.
 * There seems to be no easy way to manually set the slim request body.
 * The simplest would probably be to create a mock slim object
 */

require_once('../SimpleTest/simpletest/autorun.php');

class userControllerValidationClass extends UnitTestCase {
	private $Model;
	private $Controller;
	private $app;
	private $PlaceholderID;		// This is to enable the deletion of all records inserted by the test
	public function setUp(){
		require_once "../Slim/Slim.php";
		Slim\Slim::registerAutoloader ();

		$this->app = new \Slim\Slim ();
		require_once('../app/models/userModel.php');
		$this->Model = new UserModel();
	}
	
	public function tearDown(){
		$this->Model = NULL;
	}
	
	private function controllerSetup($action, $parameters){
		// Used to prep the controller for each test
		require_once('../app/controllers/userController.php');
		$this->Controller = new UserController ( $this ->Model, "User", $action, $this->app, $parameters );
	}
	
	public function testUserInsertion(){
		$user = json_encode(array("user_name" => "test", "name" => "test", "surname" => "testtest", "email" => "Test@123.com", "password" => "4444"));
		$action = ACTION_CREATE_RECORD;
		$params = NULL;
		$this->app->request->setBody($user);
		$this->controllerSetup($action, $params);
		
		$this->assertEqual($this->Model->apiResponse, json_encode(array("message" => "Resource has been created", "id" => "21")));

	}
			
}
?>