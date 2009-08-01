<?

/** 
 * The Matix Framework PHP5 for PHP Developers 
 * Author: Dom1n1k
 * Licence: GNU
 */

/** 
 * Including the base class which executes another ...
 */
 
if (!include_once('./base/basic.php'))
	die ('Base Class Including error');
	
/**
 * The Dom1n1k Framework Basic Executing Method 
 */
 
try {

	$oBasic = new basic;
	$oBasic -> setController($oBasic->getRouter());
	
}

/** 
 * Catching Exceptions 
 */
 
catch (Exception $o) {

	echo $o->getMessage();

}
	
	
?>