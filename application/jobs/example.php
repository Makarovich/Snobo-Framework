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

//Define our cron job id.
define('JOB_ID', 1);

//Make sure the system is allowed to run
if (!$snobo->job_authorization[JOB_ID])
{
    return;
}

//Run the cron.
//$sulake->database->prepare('UPDATE users SET credits = ?')->bindParameters(array(0))->execute();
?>