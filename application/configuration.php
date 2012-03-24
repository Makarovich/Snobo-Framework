<?php 
/*-------------------------------------------- 
* PROJECT NAME - PROJECT MOTTO
* BUILT ON SNOBO FRAMEWORK
* -------------------------------------------- 
* COPYRIGHT YEAR AUTHOR
* SNOBO COPYRIGHT 2012 COBE MAKAROV
* -------------------------------------------- 
* SNOBO FRAEMWORK RELEASED UNDER THE GNU PUBLIC 
* LICENSE V3. NONE OF THE DEVELOPERS ARE 
* AFFILIATED WITH THE SERVER(S) RAN WITH ANY 
* WEB APPLICATION BUILT UPON SNOBO FRAMEWORK
* -------------------------------------------- 
* @author: AUTHOR
* @framework-author: Cobe Makarov 
* --------------------------------------------*/ 

if(!defined('SNOBO')){die('Direct Loading Fobidden');} 

//Define our variable as an array.
$_snoboConfig = array();

//Database variables
$_snoboConfig['database']['host'] = "localhost";
$_snoboConfig['database']['user'] = "root";
$_snoboConfig['database']['password'] = "lol123";
$_snoboConfig['database']['name'] = "mcd";

//System variables
$_snoboConfig['system']['name'] = "Snobo";
$_snoboConfig['system']['tagline'] = "R.I.P Blowfis...";
$_snoboConfig['system']['environment'] = "2";
$_snoboConfig['system']['secret_quote'] = "snobolovesblowfis";
$_snoboConfig['system']['site_path'] = "http://localhost/";
//If a user is using a mobile device, do you want the system to override whatever style is being used?
$_snoboConfig['system']['mobile_override'] = true; 

?>