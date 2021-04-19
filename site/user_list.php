<?php

class User_list
{
    private $datafile;
    private $separator;
    private $users = array();

    function __construct($datafile = "txtFiles/users.txt", $separator = ":-:")
    {
        $this->datafile = $datafile;
        $this->separator = $separator;

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

    public function user_exists($userid)
    {
        if(!empty($this->users))
        {
            foreach($this->users as $user)

                if($user->get_user_id() == $userid)
                {
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    public function delete_user($userid)
    {
        if($data=file($this->datafile))
        {
            $users = array();
            foreach($data as $k=>$v)
            {
                $record = explode($this->separator, trim($v));
                if($record[0] == bin2hex($userid))
                {  
                } else
                {
                    $users[]=array( 
                        "userid"      => $record[0],
                        "nickname"    => $record[1],
                        "pass"        => $record[2],
                        "privilege"   => $record[3]
                    );
                }
                
            }  
            $this->update_file($users);
        }
        return FALSE;
    }

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
}
?>