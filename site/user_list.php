<?php

include('user.php');
class User_list
{
    private $datafile;
    private $separator;
    private $users = array();

    function __construct($datafile = "txtFiles/users.txt", $separator = ":-:")
    {
        $this->datafile = $datafile;
        $this->separator = $separator;

        $this->get_file();
    }
    //---------------------------------------------------------------// 
    //                          update file
    //---------------------------------------------------------------//
    private function update_file($array_to_update)
    {
        $data = '';
        for($i=0; $i < count($array_to_update); $i++ )
        {
                $data .= implode($this->separator, $array_to_update[$i]);
                $data .= "\n";
        }
        if($fh = fopen($this->datafile, "w+"))
        {
            fwrite($fh, $data);
            fclose($fh);
            return TRUE;
        }
        return FALSE;
    }

    private function get_file()
    {
        if($data=file($this->datafile))
        {
           foreach($data as $k=>$v)
           {
                $record = explode($this->separator, trim($v));
                $this->users[]= new User(
                            $record[0], 
                            $record[1], 
                            $record[2], 
                            $record[3]
                );
            }    
        }
        return FALSE;
    }

    //---------------------------------------------------------------//
    //                          user get
    //---------------------------------------------------------------//
    public function get_users()
    {
        if(!empty($this->users))
        {
            $user_list = array();

            foreach($this->users as $user)
            {
                $user_list[] = $user->get_user();
            }
            return $user_list;   
        }
        return FALSE;
    }

    public function get_user($userid)
    {
        if(!empty($this->users))
        {
            foreach($this->users as $user)
            {
                if($user->get_user_id() == $userid)
                {
                    return $user->get_user();
                }
            }  
        }
        return FALSE;
    }
    //---------------------------------------------------------------//
    //                          user get
    //---------------------------------------------------------------//
    public function put_user($userid, $nickname, $password, $privilege)
    {
        $this->users[] = new User($userid, $nickname, $password, $privilege);
    }

    //---------------------------------------------------------------//
    //                          user exists
    //---------------------------------------------------------------//
    public function user_exists($userid)
    {
        if(!empty($this->users))
        {
            foreach($this->users as $user)
            {
                if($user->get_user_id() == $userid)
                {
                    return TRUE;
                }
            }
        }
        return FALSE;
    }
    //---------------------------------------------------------------//
    //                          user delete
    //---------------------------------------------------------------//
    public function delete_user($userid)
    {
        if(!empty($this->users))
        {
            $user_list=array();
            foreach($this->users as $user)
            {
                if(!$this->user_exists($userid))
                {
                    $user_list[]= $user->get_user();
                }
            }
            $this->users = $user_list;
            $this->update_file($user_list);
        }
        return FALSE;
    }
    //---------------------------------------------------------------//
    //                          user perm check
    //---------------------------------------------------------------//
    public function perm_check()
    {
        $user = $this->get_user($_SESSION['userid']);
        if($_SESSION['privilege']==$user['privilege'])
        {
            return TRUE;
        }
        $_SESSION['privilege'] = $user['privilege'];
        
        return FALSE;
    }

    //---------------------------------------------------------------//
    //                          user perm change
    //---------------------------------------------------------------//
    public function change_perm($userid)
    {
        $user_to_check = $this->get_user($userid);
        if($user_to_check['privilege'] == "admin")
        {
            $user_to_check['privilege'] = "user";
        } else
        {
            $user_to_check['privilege'] = "admin";
        }
        if(!empty($this->users))
        {
            $user_list = array();
            foreach($this->users as $user)
            {
                if($user->get_user_id() == $userid)
                {  
                    $user_list[] = $user->set_user(
                                                    $user_to_check['userid'],
                                                    $user_to_check['nickname'],
                                                    $user_to_check['pass'],
                                                    $user_to_check['privilege']
                    );
                }
                else
                {
                    $user_list[] = $user;
                }
            }  
            $this->users = $user_list;
            $this->update_file($user_list);
        }
        return FALSE;
    }

    public function usr_pass_check($userid ,$pass)
    {
        if(!empty($this->users))
        {
            foreach($this->users as $user)
            {
                if($user->user_pass_check($pass)) {
                    return TRUE;
                }
            }
        }
        return FALSE;
    }
}
?>