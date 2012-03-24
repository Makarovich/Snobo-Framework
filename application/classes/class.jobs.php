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
* @name "Steve" Jobs Class 
* @desc NULL 
* @author Cobe Makarov 
*/ 

if(!defined('SNOBO')){die('Direct Loading Fobidden');} 

class Jobs
{ 
   //The jobs that have been cached at the start.
    private $cachedJobs;
    
    //When the class is first constructed.
    public function __construct()
    {
        //
    }
    
    //Start all the actions
    public function start()
    {
        global $snobo;
        
        //Grab all the jobs within the database..
        $jobs = $snobo->database->prepare('SELECT * FROM sulake_jobs WHERE `binary` = ?')
                ->bindParameters(array(1))->execute();
        
        while ($j = $jobs->fetchArray())
        {
           $this->run($j);
        }
        
    }
    
    private function run($job)
    {
        global $snobo;
        
        //If our variable isn't array, send it back.
        if (!is_array($job))
        {
            return;
        }
        
        //If it isn't the correct time, send it back.
        if (($job['last'] + $job['interval']) > time())
        {
            return;   
        }
        
        //Give our system authorization
        $snobo->job_authorization[$job['id']] = true;
        
        //Include the job file
        include './application/jobs/'.$job['file'];
        
        //Take our system authorization, to avoid exploitation.
        $snobo->job_authorization[$job['id']] = false;
 
        //Update our last_time
        $snobo->database->prepare('UPDATE sulake_jobs SET last = ? WHERE id = ?')->bindParameters(array(time(), $job['id']))->execute();
    }
} 
?>