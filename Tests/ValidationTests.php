<?php
/**
 * @author Noel
 * TDD excercise with validation class example
 */
require_once('../SimpleTest/simpletest/autorun.php');

class testValidationClass extends UnitTestCase {
	private $val;
	public function setUp(){
		require_once('../app/Validation.php');
		$this->val = new validation();
	}
	
	public function tearDown(){
		$this->val = NULL;
	}
	
	/**
	 * Test to check for valid emails
	 * 
	 */
	public function testValidEmails(){
		$this->assertTrue($this->val->isEmailValid('test@something.com'));
		$this->assertTrue($this->val->isEmailValid('test@that.ie'));
		$this->assertFalse($this->val->isEmailValid('test@somethingcom'));
		$this->assertFalse($this->val->isEmailValid('no_at.com'));
		$this->assertFalse($this->val->isEmailValid('gibberish'));
		$this->assertFalse($this->val->isEmailValid(1234));
		$this->assertFalse($this->val->isEmailValid(['a', 123, 'address@gmail.com']));
		$this->assertFalse($this->val->isEmailValid(NULL));
		$this->assertFalse($this->val->isEmailValid('test.gmail@com'));
	}
	
	/**
	 * Test for numeric ranges
	 */
	
	public function testNumberInRange(){
		$this->assertTrue($this->val->isNumberInRangeValid(2,1,3));
		$this->assertTrue($this->val->isNumberInRangeValid(2,2,2));
		$this->assertTrue($this->val->isNumberInRangeValid(233446346,10000,3999999999945322999999999999));
		$this->assertFalse($this->val->isNumberInRangeValid(7,2,3));
		$this->assertFalse($this->val->isNumberInRangeValid(1,2,1));
		$this->assertFalse($this->val->isNumberInRangeValid(1,2,'a'));
		$this->assertFalse($this->val->isNumberInRangeValid(1,NULL,'a'));
		$this->assertFalse($this->val->isNumberInRangeValid([3], 4, 5));
		$this->assertTrue($this->val->isNumberInRangeValid(-20,-101,3));
	}
	
	/**
	 * 
	 * Test function to check string lengths
	 */
	public function testStringLength(){
		$this->assertTrue($this->val->isLengthStringValid('abc', 3));
		$this->assertTrue($this->val->isLengthStringValid('abc', 5));
		$this->assertFalse($this->val->isLengthStringValid('abcdef', 3));
		$this->assertFalse($this->val->isLengthStringValid('abcdef', 'gh'));
		$this->assertFalse($this->val->isLengthStringValid(6, 'abc'));
		$this->assertFalse($this->val->isLengthStringValid(['abcdef'], 10));
		$this->assertTrue($this->val->isLengthStringValid('', 0));
		$this->assertFalse($this->val->isLengthStringValid(NULL, -10));
	}
}
?>