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
* @name Snobo Main Class 
* @desc NULL 
* @author Cobe Makarov 
*/ 

if(!defined('SNOBO')){die('Direct Loading Fobidden');} 

class Snobo
{
    //The configuration variable
    var $configuration;
    
    //The class data holder variable
    var $class;
    
    //The variable that holds when that page has started
    var $executionStart;
    
    //Classes that shouldn't be initialized directly, but should still be included.
    private $ignored_classes = array('cache', 'mock');
    
    //Determines whether a certain job is authorized to run.
    var $job_authorization = array();
    
    //Is the installer running..?
    private $installer_running = false;
    
    public function __construct()
    {
        //Define our folder variables
        define('DS', '/');
        define('LB', "\r\n");
        
        //Fill our configuration variable
        $this->handleConfiguration();
        
        //If the installer is running stop us in our tracks.
        if ($this->installer_running)
		{
            break;
		}
        
        //Now let's set our environment
        $this->setEnvironment();
        
        //Let's cache our classes
        $this->initializeClasses();
        
        //Set our custom error handler
        include 'misc/error.handling.php';
        
        set_error_handler('writeError');
        
        //Start the page load!
        $this->executionStart = microtime(true);
    }
    
    //Fills our configuration variable
    private function handleConfiguration()
    {
        //If their configuration file doesn't exist, run the installer
        if (!file_exists('./application/configuration.php'))
        {
            $this->installer_running = true;
            header('Location: install.php');
            return;
        }
        
        //Include the configuration file
        include './application/configuration.php';
        
        //Set our variables
        $this->configuration = $_snoboConfig;
        
    }
    
    //Sets our environment based on the configuration value
    private function setEnvironment()
    {
        switch ($this->configuration['system']['environment'])
        {
            case '0':
                error_reporting(0);
                break;
            
            case '1':
                error_reporting(E_ALL);
                break;
            
            case '2':
                error_reporting(E_ALL ^ E_NOTICE);
                break;
            
            default:
                error_reporting(0);
        }
    }
    
    private function initializeClasses()
    {
        
        //Grab all the files within the classes folder.
        foreach (glob('./application/classes/'.'*.php') as $file)
        {
            include $file;
            
            //Read the proper class name.
            $proper = ucfirst($this->getName($file));
            
            //Get what we'll call it.
            $class = $this->getName($file);
            
            //Ignore em!
            if (in_array($class, $this->ignored_classes))
            {
                continue;
            }
            
            //If it's the database class, we need extra parameters
            if ($class == 'database')
            {
                $this->$class = new $proper($this->configuration['database']);
                continue;
            }
            
            //If not let's just add it in
            $this->$class = new $proper();
            
        } 
        
        //Require our interfaces
        $this->requireInterfaces();
    }
    
    private function requireInterfaces()
    {
        //Grab all the files within the interfaces folder
        foreach (glob('./application/classes/interfaces'.'*.php') as $file)
        {
            //Require them
            require $file;
        }
    }
    
    ############################## MISC Functions ##################################
    
    private function getName($class)
    {
        $periodSplit = explode('.', $class);
        
        return $periodSplit[2];
    }
    
    public function errorCheck()
    {
        if (isset($_SESSION['error']))
        {
            $this->template->setParameter('error', $_SESSION['error']);
            
            //Unset our session variable
            unset($_SESSION['error']);
        }
        else
        {
            $this->template->setParameter('error', 'Welcome to '.$this->configuration['system']['name'].' here you can login or register!');
        }
    }
      
    //Get the type of a variable
    public function getType($var)
    {
        if (is_array($var))
            return 'a';
        
        if (is_bool($var))
            return 'b';
        
        if (is_double($var))
            return 'd';
        
        if (is_numeric($var))
            return 'i';

        if (is_string($var))
            return 's';
        
        
    }
    
    //Hash a variable
    public function hashVariable($var)
    {
        return sha1(md5($var.$this->configuration['system']['secret_quote']));       
    }
    
    //Redirect to a url within the site map.
    public function redirect($url)
    {
        //$externalUrl = false;
        
        if (!strpos($url, '.php'))
        {
            $url .= '.php';
        }
        
        if (!file_exists($url))
        {
            return; //Ehh.. incorrect url so gtfo.
        }
        
        header('Location: '.$this->configuration['system']['site_path'].$url);
    }
    
    //Get the user's operating system
    public function retrieveAgent($agent)
    {
        return $_SERVER['SERVER_SOFTWARE'];
    }
}
?>