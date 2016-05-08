<?php
/**
 * @author Noel
 * Master test suite to call all other tests
 */

require_once 'TeamTests/teamModelValidation.php';
require_once 'PlayerTests/playerModelValidation.php';
require_once 'UserTests/userModelValidation.php';

new teamModelValidationClass();
new playerModelValidationClass();
new userModelValidationClass();
?>