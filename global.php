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

ob_start();
session_start();

//Needed for security reasons 
define('SNOBO', null); 

//The root directory
define('ROOT', __DIR__);

//The grand-poo-ba of classes within the Snobo framework!
require('./application/snobo.php'); 

//Initialize the variable for the snobo class 
$snobo = new Snobo(); 

//Set the title 
$snobo->template->SetParameter('title', $snobo->configuration['system']['name'].' - '.PAGE); 

//Check for errors 
$snobo->errorCheck(); 

//Convert all the session variables to template parameters 
$sessionCache = new Cache($_SESSION);

$sessionCache->toTPL('habbo');

//Start running the jobs 
$snobo->jobs->start(); 
?>