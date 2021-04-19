<?php

class User_list
{
    private $datafile;
    private $separator;

    function __construct($datafile = "txtFiles/users.txt", $separator = ":-:")
    {
        $this->datafile = $datafile;
        $this->separator = $separator;
    }

    //users get
    public function get_users()
    {
       if($data=file($this->datafile))
       {
          $users=array();
          foreach($data as $k=>$v)
          {
            $record = explode($this->separator, trim($v));
            $users[]=array( 
                "userid"  => hex2bin($record[0]),
                "nickname"   => hex2bin($record[1]),
                "pass"     => $record[2],
                "privilege"   => hex2bin($record[3])
            );
          }
          return $users;   
       } else
       {
          return FALSE;
       }
    }
    public function user_exists($userid)
    {
        if($data=file($this->datafile))
        {
            $post=array();
            foreach($data as $k=>$v)
            {
                $record = explode( $this->separator, trim($v));
                if($record[0] == bin2hex($userid))
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
            $data = '';
            for($i=0; $i < count($users); $i++ )
        {
                $data .= implode($this->separator, $users[$i]);
                $data .= "\n";
        }
        if( $fh = fopen($this->datafile, "w+" ))
        {
                fwrite($fh, $data);
                fclose($fh);
                return TRUE;
        }
        }
        return FALSE;
    }
}
?>