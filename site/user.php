<?php
class User 
{
    private $userid;
    private $nickname;
    private $password;
    private $privilege;

    //---------------------------------------------------------------//
    //                      class constructor
    //---------------------------------------------------------------//
    function __construct($userid, $nickname, $password, $privilege)
    {
        $this->userid;
        $this->nickname;
        $this->password;
        $this->privilege;
    }

    //---------------------------------------------------------------//
    //                           user get
    //---------------------------------------------------------------//
    public function get_user()
    {
        $user=array( 
                    "userid"      => hex2bin($this->userid),
                    "nickname"    => hex2bin($this->nickname),
                    "pass"        => md5($this->password),
                    "privilege"   => hex2bin($this->privilege)
        );
        return $user;
    }
    
    //---------------------------------------------------------------//
    //                           user put
    //---------------------------------------------------------------//
    public function set_user($userid, $nickname, $password, $privilege = "user")
    {
        $this->userid = bin2hex($userid);
        $this->nickname = bin2hex($nickname);
        $this->password = md5($password),
        $this->privilege = bin2hex($privilege);
        
        $user=array( 
            "userid"      => hex2bin($this->userid),
            "nickname"    => hex2bin($this->nickname),
            "pass"        => md5($this->password),
            "privilege"   => hex2bin($this->privilege)
        );
        return $user;
    }

    //---------------------------------------------------------------//
    //                           user pass check
    //---------------------------------------------------------------//
    public function user_pass_check($pass)
    {
        if($this->password == md5($pass))
        {
            return TRUE;
        }  
        return FALSE;
    }


}

?>