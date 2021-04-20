<?php

class Posts{
    private $datafile = "txtFiles/wypowiedzi.txt";
    private $separator = ":-:";

    //---------------------------------------------------------------//
    //                        get post
    //---------------------------------------------------------------//
    public function get_post($topicid, $postid)
    {
        if($data=file($this->datafile))
        {
            $post=array();
            foreach($data as $k=>$v)
            {
                $record = explode($this->separator, trim($v));
                if($record[1]==$postid && $record[0]==$topicid){
                    $post=array( 
                        "topicid"       => $record[0],
                        "postid"        => $record[1],
                        "post"          => hex2bin($record[2]),
                        "userid"        => hex2bin($record[3]),
                        "nickname"      => hex2bin($record[4]),
                        "date"          => $record[5]
                    );
                }
            }
            return $post;   
        } else
        {
            return FALSE;
        }
    }
    //---------------------------------------------------------------//
    //                        get posts
    //---------------------------------------------------------------//
    public function get_posts($topicid)
    {
        if($data=file($this->datafile))
        {
            $posts=array();
            foreach($data as $k=>$v)
            {
                $record = explode($this->separator, trim($v));
                if( $record[0]==$topicid ){
                    $posts[]=array( 
                        "topicid"  => $record[0],
                        "postid"   => $record[1],
                        "post"     => hex2bin($record[2]),
                        "userid"   => hex2bin($record[3]),
                        "nickname" => hex2bin($record[4]),
                        "date"     => $record[5]
                    );
                }
            }
            return $posts;   
        } else
        {
            return FALSE;
        }
    }
    //---------------------------------------------------------------//
    //                        put post
    //---------------------------------------------------------------//
    public function put_post($topicid, $post, $userid, $nickname)
    {
        if(is_file($this->datafile))
        {
            $postid = count_posts($topicid) + 1; 
            $data=file($this->datafile);
        }
        $data = implode($this->separator, 
                            array( $topicid,
                                    $postid, 
                                    bin2hex($post), 
                                    bin2hex($userid), 
                                    bin2hex($nickname), 
                                    date("Y-m-d H:i:s") 
                            )
                        );
        if( $fh = fopen($this->datafile, "a+" )){
            fwrite($fh, $data."\n");
            fclose($fh);
            return $postid;
        } else
        {
            return FALSE;
        }                              
    }
    //---------------------------------------------------------------//
    //                        get post
    //---------------------------------------------------------------//
    public function count_posts($topicid)
    {
        $counter = 0;
        if($data=file($this->datafile))
        {
            foreach($data as $k=>$v){
                $record = explode($this->separator, trim($v));
                if( $record[0]== $topicid){
                    $counter++;
                }
            } 
            return $counter;  
        }
        return 0;
    }
    //---------------------------------------------------------------//
    //                        last post
    //---------------------------------------------------------------//
    public function last_post()
    {
        $check = false;
        if($data=file($this->datafile))
        {
            $post = array();
            $size = count($data);
            $record = explode($this->separator, $data[$size - 1]);
            if(!empty($record[5]))  
                return $record[5];
        }
        return "::brak-postów::";
    }
    //---------------------------------------------------------------//
    //                        edit post
    //---------------------------------------------------------------//
    public function edit_post($topicid, $postid, $description, $userid, $nickname)
    {
        if($data=file($this->datafile))
        {
            $posts=array();
            foreach($data as $k=>$v)
            {
                    $record = explode($this->separator, trim($v));
                    if($record[1] == $postid && $record[0] == $topicid && $record[3] == bin2hex($userid))
                    {
                    $posts[]=array(
                        "topicid" => $record[0],
                        "postid" => $record[1],
                        "post" => bin2hex(trim($description)),
                        "userid"=> bin2hex(trim($userid)),
                        "nickname"=> bin2hex(trim($nickname)),
                        "date" => date("Y-m-d H:i:s")
                    );
                    } else
                    {
                    $posts[]=array(
                        "topicid" => $record[0],
                        "postid" => $record[1],
                        "post" => $record[2],
                        "userid"=> $record[3],
                        "nickname"=> $record[4],
                        "date" => $record[5]
                    );
                    }
            }
            $data = '';
            for($i=0; $i < count($posts); $i++ )
            {
                    $data .= implode($this->separator, $posts[$i]);
                    $data .= "\n";
            }
            if( $fh = fopen($this->datafile, "w+" ))
            {
                    fwrite($fh, $data);
                    fclose($fh);
                    return $postid;
            } else
            {
                    return FALSE;
            }
            return $postid; 
        } else
        {
            return FALSE;
        }
    }
    //---------------------------------------------------------------//
    //                        delete post
    //---------------------------------------------------------------//
    public function delete_post($topicid, $postid, $userid)
    {
        if($data=file($this->datafile))
        {
            $posts=array();
            foreach($data as $k=>$v)
            {
                    $record = explode($this->separator, trim($v));
                    if($record[1] === $postid && $record[0] == $topicid && $record[3] == bin2hex($userid))
                    {

                    } else
                    {
                    $posts[]=array(
                        "topicid" => $record[0],
                        "postid" => $record[1],
                        "post" => $record[2],
                        "userid"=> $record[3],
                        "nickname"=> $record[4],
                        "date" => $record[5]
                    );
                    }
            }
            $data = '';
            for($i=0; $i < count($posts); $i++ )
            {
                    $data .= implode($this->separator, $posts[$i]);
                    $data .= "\n";
            }
            if( $fh = fopen($this->datafile, "w+" ))
            {
                    fwrite($fh, $data);
                    fclose($fh);
                    return $postid;
            } else
            {
                    return FALSE;
            };
            
            return $postid; 
        } else
        {
            return FALSE;
        }
    }
    //---------------------------------------------------------------//
    //                        delete posts
    //---------------------------------------------------------------//
    public function delete_posts($topicid)
    {
        if($data=file($this->datafile))
        {
            $posts=array();
            foreach($data as $k=>$v)
            {
                    $record = explode($this->separator, trim($v));
                    if($record[0] == $topicid)
                    {
                    } else
                    {
                    $posts[]=array(
                        "topicid" => $record[0],
                        "postid" => $record[1],
                        "post" => $record[2],
                        "userid"=> $record[3],
                        "nickname"=> $record[4],
                        "date" => $record[5]
                    );
                    }
            }
            $data = '';
            for($i=0; $i < count($posts); $i++ )
            {
                    $data .= implode($this->separator, $posts[$i]);
                    $data .= "\n";
            }
            if( $fh = fopen($this->datafile, "w+" ))
            {
                    fwrite($fh, $data);
                    fclose($fh);
                    return $postid;
            } else
            {
                    return FALSE;
            };
            
            return $postid; 
        } else
        {
            return FALSE;
        }
    }

}
?>