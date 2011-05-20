<?php
/********************************************
*
*	Filename:	login.php
*	Author:		Ahmet Oguz Mermerkaya
*	E-mail:		ahmetmermerkaya@hotmail.com
*	Begin:		Thursday, May 19, 2011  10:08
*
*********************************************/

define("IN_PHP", true);

define("WEB_ADDRESS", 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']));

require_once("includes/config.php");
$dbc = NULL;  // database connectivity;
$out = NULL;

if (isset($_REQUEST['action']) && !empty($_REQUEST['action'])) 
{
	$action = $_REQUEST['action'];	
	if (strpos($action, WEB_CLIENT_ACTION_PREFIX) === 0)
	{
		$dbc = getMySQLOperator($dbc, $dbHost,$dbUsername,$dbPassword,$dbName);
		$wcm = new WebClientManager($dbc, WEB_CLIENT_ACTION_PREFIX, STAFF_TRACKER_TABLE_PREFIX, 
									ELEMENT_COUNT_IN_LIST_PAGE, ELEMENT_COUNT_IN_LOCATIONS_PAGE,
									ELEMENT_COUNT_IN_PHOTO_PAGE);
		$tdo = new TempDataStoreOperator();
		$wcm->setTempDataStoreOperator($tdo);
		$auth = new AuthenticateManager($dbc, $tdo, STAFF_TRACKER_TABLE_PREFIX);

		$usermanager = new UserManager($dbc, STAFF_TRACKER_TABLE_PREFIX);			
		$wcm->setUserManager($usermanager);
		
				
		$wcm->setAuthenticator($auth);
		$wcm->setImageRelatedVars(UPLOAD_DIRECTORY, MISSING_IMAGE, IMAGE_HANDLER);
		
		$out = $wcm->process($_REQUEST);

		if ($auth !== NULL && ($userId = $auth->getUserId()) !== null){
			 $_SESSION["userId"] = $userId;
		}		
	}
	else if (strpos($action, DEVICE_ACTION_PREFIX) === 0)
	{
		$dbc = getMySQLOperator($dbc, $dbHost,$dbUsername,$dbPassword,$dbName);
		$dm = new DeviceManager($dbc, DEVICE_ACTION_PREFIX, STAFF_TRACKER_TABLE_PREFIX, 
								GPS_MIN_DATA_SENT_INTERVAL, GPS_MIN_DISTANCE_INTERVAL);
		$dm->setUploadPath(UPLOAD_DIRECTORY);
		
		$usermanager = new UserManager($dbc, STAFF_TRACKER_TABLE_PREFIX);			
		$dm->setUserManager($usermanager);
		
		
		$out = $dm->process($_REQUEST);
	}
}
else {	
	$dbc = getMySQLOperator($dbc, $dbHost,$dbUsername,$dbPassword,$dbName);
	$tdo = new TempDataStoreOperator();
	$auth = new AuthenticateManager($dbc, $tdo, STAFF_TRACKER_TABLE_PREFIX);
	
	$out .= DisplayOperator::getLoginPage($_SERVER['PHP_SELF'], $_SERVER['PHP_SELF'], LANGUAGE);
}

echo $out;
//error_log($out, 3, "log.txt");
///////////////////////////////////////////////////
function getMySQLOperator($dbc, $dbHost,$dbUsername,$dbPassword,$dbName ){	
	if ($dbc == NULL) {
		require_once('classes/MySQLOperator.php');
		$dbc = new MySQLOperator($dbHost,$dbUsername,$dbPassword,$dbName);
	}
	return $dbc;
}
function __autoload($class_name) {
    require_once dirname(__FILE__) .'/classes/' . $class_name . '.php';
}

?>