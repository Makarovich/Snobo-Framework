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
* @name Database Class 
* @desc NULL 
* @author Cobe Makarov 
*/ 

if(!defined('SNOBO')){die('Direct Loading Fobidden');} 

class Database  
{ 
    //The variable with the value of the mysqli class 
    var $link; 
     
    //The page queryCount
    var $queryCount;
    
    //The variable that holds the mysqli stmt class data 
    private $stmt; 
     
    //The database variables 
    private $name; 
    private $host; 
    private $user; 
    private $password; 
     
    //If the database is connected 
    private $connected; 
     
    //Our class's query
    private $classQuery;
    
    //When the class is first constructed.. 
    public function __construct($database) 
    { 
        //Open the database connection 
        $this->connect($database); 
    } 
         
    //Open database connection 
    private function connect($database) 
    { 
        global $snobo; 
         
        //If we're already connected, send it back 
        if ($this->connected) 
		{
            return; 
		}
         
        //Automatically set our private variables 
        foreach ($database as $key => $value) 
        { 
            $this->$key = $value; 
        } 
         
        //Open a new instance of the mysqli class 
        $this->link = new mysqli($this->host, $this->user, $this->password, $this->name); 
         
        //If there's an error, display it 
        if ($this->link->connect_error)     
        {
            trigger_error($this->link->connect->errno); 
            return; 
        }
         
             
        //Tell the system we are now connected 
        $this->connected = true; 
    } 
     
    //Close database connection 
    public function disconnect() 
    { 
        //Close the connection 
        $this->link->close(); 
         
        //Tell the system we are now dis-connected 
        $this->connected = false; 
    } 
     
    ############################## MISC Functions ################################## 

    //Secures a variable 
    public function secure($variable) 
    { 
        return $this->link->real_escape_string($variable); 
    } 
     
    ############################## QUERY Functions ################################## 
     
    //Step 1 - Prepare the query 
    public function prepare($query) 
    { 
        global $snobo; 
        
        //Set our classQuery
        $this->classQuery = $query;
        
        if (!$this->stmt = $this->link->prepare($query)) 
        { 
            trigger_error($this->stmt->error); 
            return; 
        } 
         
        return $this; 
    } 
     
    //Step 2 - Bind the parameters 
    //@credits : Jos Piek(60%)
    public function bindParameters($params) 
    { 
        global $snobo;
        
        //Our types for the parameters
        $paramTypes = ''; 
        
        //Split all the of the params. 
        foreach($params as $key => $value) 
        {           
            //Set the types
            $paramTypes .= $snobo->getType($value); 
        } 
        
        //Fill our arguments variable with an array of the parameter types
        $arguments = array($paramTypes); 

        //Make sure we have the correct parameters
        $this->retrieveParams($params, $arguments); 

        //Bind the parameters
        call_user_func_array(array($this->stmt, 'bind_param'), $arguments);
        
        return $this; 
    } 
     
    //Step 3 - Retrieve correct parameters
    //@credits : Jos Piek
    private function retrieveParams(array &$array, array &$out)
    {
        //Make sure the system is at a usuable version
        if (strnatcmp(phpversion(),'5.3') >= 0) 
        { 
            foreach($array as $key => $value) 
            { 
                $out[] =& $array[$key]; 
            } 
        } 
        else 
        { 
            $out = $array; 
        } 
    }
	
    //Step 4 - Execute the query 
    public function execute() 
    { 
        if(!$this->stmt->execute()) 
        { 
            return $this->stmt->error; 
        } 
        
        $this->queryCount++;
        
        return new STMT($this->stmt); 
    } 
} 

//STMT Class 
class STMT 
{ 
    //Our STMT variable 
    private $stmt; 

    //Is our stmt associated yet?
    private $assoc = false;
    
    //If the stmt is an array, this is where the rows are.
    private $rows = array();
    
    //When the class is first constructed..
    public function __construct($stmt) 
    { 
        $this->stmt = $stmt; 

        mysqli_stmt_store_result($stmt); 
    } 

    public function num_rows() 
    { 
        return $this->stmt->num_rows(); 
    } 
     
    public function result() 
    { 
        $this->stmt->bind_result($res); 
         
        $this->stmt->fetch(); 
         
        return $res; 
    } 
    
    //Get the fields of our stmt if it's an array
    //@credits : Jos Piek
    private function stmt_assoc(&$stmt, array &$out)
    {
        $data = mysqli_stmt_result_metadata($stmt);
        
        $fields = array($this->stmt);
        
        $out = array();
        
        while ($field = mysqli_fetch_field($data))
        {
            $fields[] =& $out[$field->name];
        }
        
        call_user_func_array('mysqli_stmt_bind_result', $fields);
    }
    
    //Fetch the STMT array
    //@credit : Jos Piek
    public function fetchArray()
    {
        if (!$this->assoc)
        {
            $this->assoc = true;
            
            $this->stmt_assoc($this->stmt, $this->rows);
        }
        
        if (!$this->stmt->fetch())
        {
            $this->assoc = false;

            $this->rows = array();
        }
        
        $data = array();
        
        foreach ($this->rows as $key => $value)
        {
            $data[$key] = $value;
        }

        return ($this->assoc) ? $data : false;
        
    }
    
    public function close()
    {
        $this->stmt->close();
    }
} 
?>