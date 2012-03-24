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

error_reporting(0);

if (file_exists('./application/configuration.php'))
{
    exit;
}

if (!isset($_POST['submit']))
{
    echo file_get_contents('./application/views/installer.html'); 
}
else
{
    $foundError = false;
    
    write('Loading the configuration base...');
    $base = file_get_contents('./application/configuration.base'); 

    write('Checking for empty inputs...');
    foreach($_POST as $key => $value)
    {
        if (empty($value))
        {
            write($key.' is empty..', true);
            $foundError = true;
        }
    }
    
    if ($foundError)
    {
       return;
    }
        
    foreach($_POST as $key => $value)
    {
        if ($key == 'submit')
        {
            continue;
        }
        
        if ($key != 'db_password')
        {
            write('Parsing '.$value.' as the '.$key.' in the configuration file...');
        }
        
        if (!strpos($base, '$'.$key))
        {
            write('The configuration base has been tampered with...', true);
            return;
        }
        
        $base = str_ireplace('$'.$key, "'".$value."'", $base);  
    }
    
    write('Checking database...');
   
    if (!(mysqli_connect($_POST['db_host'], $_POST['db_user'], $_POST['db_password'], $_POST['db_name'])))
    {
        write('You have some invalid database details...', true);
        return;
    }
    
    write('There\'s no errors so the configuration will be written now..');
    $fp = fopen('./application/configuration.php', 'w+');
    fputs($fp, $base);
    fclose($fp);
    
    write('Configuration written, delete this file and the .base file to avoid exploitation.');
}

function write($str, $error = false)
{
   if ($error)
   {
       $str = '<font color=red><b>Error:</b></font> '.$str;
   }
   echo $str.'<br>';
}
?>
