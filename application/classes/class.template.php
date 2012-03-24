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
* @name Template Class 
* @desc NULL 
* @author Cobe Makarov 
*/ 

if(!defined('SNOBO')){die('Direct Loading Fobidden');} 

class Template 
{ 
    //What will be shown to the user 
    var $output; 
         
    //The absolute END of the output 
    private $end; 
     
    //All the parameters within the html file 
    private $parameters; 
     
    //All the CSS used within the page 
    private $css; 
         
    //All the Javascript used at the top of the page 
    private $js; 
     
    //Tells the system whether or not it can add to the template 
    private $hasError; 
     
    //Add a template to the output 
    public function addTPL($tpl) 
    { 
        global $snobo; 
         
        if ($this->hasError) 
        { 
            return; 
        } 
         
        if ($tpl == 'footer') 
        { 
            //Footer should be at the end. 
            return; 
        } 
         
        if (!file_exists('./application/views/'.$tpl.'.html')) 
        { 
            trigger_error($tpl. ' does not exist!'); 
            $this->hasError = true;
            return; 
        } 
         
        //Get the source of the template 
        $res = $this->getTPL('./application/views/'.$tpl.'.html'); 
         
        //Add it to the output         
        if (!is_null($this->output)) 
        { 
            $this->output = $this->output.LB.$res; 
        } 
        else 
        { 
            $this->output = $res; 
        } 
    } 
     
    //Add the footer towards the end. 
    public function addFooter() 
    { 
        //Get the source of the template 
        $res = $this->getTPL('./application/views/footer.html'); 
         
        //Add it to the end 
        $this->end = $res.LB.$this->end; 
    } 
     
    //Adds to the css variable which will be put in the header 
    public function addCSS($css) 
    { 
        global $snobo;
        
        $file = './application/views/cascading/'.$css.'.css'; 
        
        if ($snobo->configuration['system']['mobile_override'])
        {
            if ($snobo->retrieveAgent($_SERVER['HTTP_USER_AGENT']) == 'Android' || $snobo->retrieveAgent($_SERVER['HTTP_USER_AGENT']) == 'Blackberry' || $snobo->retrieveAgent($_SERVER['HTTP_USER_AGENT']) ==  'iPhone')
            {          
                $file = './application/views/cascading/mobile-'.$css.'.css';
            }
        }
        
        if (!file_exists($file)) 
        { 
            trigger_error($file. ' does not exist!'); 
            $this->hasError = true;
            return; 
        } 
         
        if (!is_null($this->css)) 
        { 
            $this->css = $this->css.LB.'<link rel="stylesheet" type="text/css" href="'.$file.'" />'; 
        } 
        else 
        { 
            $this->css = '<link rel="stylesheet" type="text/css" href="'.$file.'" />'; 
        } 
    } 
     
    //Adds javascript into the page 
    //@at_end - Should it be put at the end? 
    public function addJavascript($js, $at_end = false) 
    { 
        global $snobo; 
         
        $file = './application/views/javascript/'.$js.'.js'; 
         
        if (!file_exists($file)) 
        { 
            trigger_error($file. ' does not exist!'); 
            $this->hasError = true;
            return; 
        } 
         
        if ($at_end) 
        { 
            if (!is_null($this->end)) 
            { 
                $this->end = $this->end.LB.'<script type="text/javascript" src="'.$file.'" ></script>'; 
            } 
            else 
            { 
                $this->end = '<script type="text/javascript" src="'.$file.'" ></script>'; 
            } 
        } 
        else 
        { 
            if (!is_null($this->js)) 
            { 
                $this->js = $this->js.LB.'<script type="text/javascript" src="'.$file.'" ></script>'; 
            } 
            else 
            { 
                $this->js = '<script type="text/javascript" src="'.$file.'" ></script>'; 
            } 
        } 
    } 
     
    //Retrieves the source of a template 
    private function getTPL($tpl) 
    { 
        ob_start(); 
        include $tpl; 
        $data = ob_get_contents();
        ob_end_clean(); 
        return $data;
    } 
     
    //Appends the template file 
    public function appendTPL($string) 
    { 
        $this->output = $this->output.LB.$string; 
    } 
     
    //Re-writes the whole output to the error template file and then fills the error in! 
    public function displayError($caller, $error) 
    { 
        //Set the parameters for the caller and error 
        $this->setParameter('snobo_system_error', $error); 
        $this->setParameter('snobo_system_caller', $caller); 
         
        //Tell the system not to add anything 
        $this->hasError = true; 
         
        //Set the output 
        $this->output = $this->getTPL('./application/views/error.html'); 
         
        //Display the error 
        $this->publishHTML(); 
    } 
     
    //Publishes the output 
    public function publishHTML() 
    { 
        global $snobo; 
         
        if ($this->hasError)
        {
            return;
        }
        
        if (!is_null($this->end)) 
        { 
            //Let's add the end data 
            $this->output = $this->output.LB.$this->end; 
        } 
         
        //Set all the default javascript files 
        $this->addJavascript('jquery'); 
        $this->addJavascript('jquery-ui.min'); 
         
        //Set all the required parameters 
        $this->setParameter('snobo_system_css', $this->css); //The required CSS 
        $this->setParameter('snobo_system_js', $this->js); //The required CSS 
        $this->setParameter('query_count', $snobo->database->queryCount);
        $this->setParameter('exec_time', round(microtime(true) - $snobo->executionStart, 3)); //The page execution time 
         
        foreach($snobo->configuration['system'] as $key => $value) 
        { 
            $this->SetParameter('snobo_config_'.$key, $value); //All the configuration values 
        } 

        //Print the output while parsing the parameters within the output 
        echo $this->parseParameters($this->output); 
         
        unset($this->output); 
    } 
     
    //Parses all parameters in the output 
    private function parseParameters($str) 
    { 
        return str_replace(array_keys($this->parameter), array_values($this->parameter), $str);  
    } 
     
    //The process of setting a paremeter 
    public function setParameter($key, $value) 
    { 
        //Add it into the param soup! 
        $this->parameter['{'.$key.'}'] = $value;  
    } 
}

class simpleTemplate
{
    //The simpleTemplate content
    private $content;
    
    //When first constructed..
    public function __construct($file)
    {
        $this->content = file_get_contents('./application/views/simple/'.$file.'.html');
        
    }
    
    //Replace a variable within the content
    public function replace($inquiry, $new_value)
    {
        $this->content = str_ireplace('['.$inquiry.']', $new_value, $this->content);
    }
    
    //Return the content
    public function result()
    {
        return $this->content;
    }
}
?>