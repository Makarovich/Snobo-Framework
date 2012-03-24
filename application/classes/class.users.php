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
* @name Users Class 
* @desc NULL 
* @author Cobe Makarov 
*/ 

if(!defined('SNOBO')){die('Direct Loading Fobidden');} 

class Users
{ 
    //The email this occurance is working with.
    var $master_email = null;
    
    //How many accounts we need to catch
    var $account_num = 0;
    
    //When a user is authenicated
    public function authenicate($email, $password)
    {
        global $snobo;
        
        $result = $snobo->database->prepare('SELECT * FROM users WHERE email = ? AND password = ?')
                ->bindParameters(array($email, $password))->execute();
                
        if($result->num_rows() == 1)
        {
            while($user_array = $result->fetchArray())
            {
                $_SESSION['master_email'] = $user_array['email'];
                $_SESSION['account_num'] = $user_array['accounts'];
            }
            
            $snobo->redirect('characters');
        }
        else
        {
            $_SESSION['error'] = 'There isnt\'t an account associated with that e-mail address.';
            $snobo->redirect('index');
        }
    }
    
    //Grab all the users associated with this email
    public function grabUsers()
    {
        global $snobo;
        
        $users = $snobo->database->prepare('SELECT * FROM users WHERE mail = ? LIMIT '.$_SESSION['account_num'])
                ->bindParameters(array($_SESSION['master_email']))->execute();
        
        return $users;
    }
} 
?>