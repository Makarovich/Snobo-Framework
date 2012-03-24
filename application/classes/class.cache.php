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
* @name Cache Class 
* @desc NULL 
* @author Cobe Makarov 
*/ 

if(!defined('SNOBO')){die('Direct Loading Fobidden');} 

class Cache
{ 
   //Our class array
    private $array;

    public function __construct($array)
    {
        //The parameter they gave us is NOT an array..
        if (!is_array($array))
        {
            return;
        }
        
        //If it is, set our class array with the values
        $this->array = $array;
    }
    
    //Convert all the array values to the session
    public function intoSession()
    {
        foreach ($this->array as $key => $value)
        {
            $_SESSION[$key] = $value;
        }
    }
    
    //Get a value from the parameter-defined key
    public function recieveValue($key)
    {
        return $this->array[$key];
    }
    
    //Convert all the array values to template parameters
    public function toTPL($name)
    {
       global $snobo;
       
       foreach ($this->array as $key => $value)
       {        
           $snobo->template->setParameter($name.'-'.$key, $value);
       } 
    }
} 
?>