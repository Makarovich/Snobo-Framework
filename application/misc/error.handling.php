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


/* 
* @name Error handling!
* @desc NULL 
* @author Cobe Makarov 
*/ 

if(!defined('SNOBO')){die('Direct Loading Fobidden');} 

//Write out our error
function writeError($error_number, $error_message, $error_file, $error_line)
{
    global $snobo;
    
    //OBV: The administrator doesn't want any errors shown.
    if ($snobo->configuration['system']['environment'] == 0)
    {
        return;
    }
    
    $output = new simpleTemplate('error');

    $output->replace('title', $error_number);
    $output->replace('error', $error_message);
    $output->replace('file', $error_file);
    $output->replace('line', $error_line);

    die($output->result());    
}
?>