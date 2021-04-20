<?php
include('posts.php');

class Topics
{
    private $datafile = "txtFiles/topics.txt";
    private $separator = ":-:";

    //---------------------------------------------------------------//
    //                          get topic
    //---------------------------------------------------------------//
    public function get_topic($topicid)
    {
        if($data=file($this->datafile))
        {
            foreach($data as $k=>$v)
            {
                $record = explode($this->separator, trim($v));
                if($record[0]== $topicid)
                {
                    $topic=array( 
                    "topicid"     => $record[0],
                    "topic"       => hex2bin($record[1]),
                    "description" => hex2bin($record[2]),
                    "userid"      => hex2bin($record[3]),
                    "nickname"    => hex2bin($record[4]),
                    "date"        => $record[5]
                    );
                    return $topic;
                }
            } 
            return FALSE;
        }
    }
    //---------------------------------------------------------------//
    //                          get topics
    //---------------------------------------------------------------//
    public function get_topics()
    {
        if($data=file($this->datafile))
        {
            $topics=array();
            foreach($data as $k=>$v)
            {
                $record = explode($this->separator, trim($v));
                if($record[0]== true)
                {
                    $topics[]=array( 
                    "topicid"     => $record[0],
                    "topic"       => hex2bin($record[1]),
                    "description" => hex2bin($record[2]),
                    "userid"      => hex2bin($record[3]),
                    "nickname"    => hex2bin($record[4]),
                    "date"        => $record[5]
                    );
                }
            } 
            return $topics;  
        }
    }
    //---------------------------------------------------------------//
    //                          put topic
    //---------------------------------------------------------------//
    public function put_topic($topic, $description, $userid, $nickname)
    {
        if(is_file($this->datafile))
        {
            $data=file($this->datafile);
            $record = explode( $this->separator, trim(array_pop($data))); 
            $topicid = (int)$record[0]+1;
        } else
        {
            $topicid = 1;
        }  
        $data = implode( $this->separator, 
                            array( $topicid, 
                                bin2hex($topic),
                                bin2hex($description), 
                                bin2hex($userid), 
                                bin2hex($nickname), 
                                date("Y-m-d H:i:s")
                            )
                        ); 
        if( $fh = fopen( $this->datafile, "a+" ))
        {
            fwrite($fh, $data."\n");
            fclose($fh);
            return $topicid;
        } else
        {
            return FALSE;
        }                               
    }  
    //---------------------------------------------------------------//
    //                          get topic
    //---------------------------------------------------------------//
    public function edit_topic($topicid, $topic, $description, $userid, $nickname)
    {
        if($data=file($this->datafile))
        {
            $topics=array();
            foreach($data as $k=>$v)
            {
                    $record = explode($this->separator, trim($v));
                    if($record[0] == $topicid)
                    {
                    $topics[]=array(
                        "topicid"     => $record[0],
                        "topic"       => bin2hex($topic),
                        "description" => bin2hex($description),
                        "userid"      => bin2hex($userid),
                        "nickname"    => bin2hex($nickname),
                        "date"        => date("Y-m-d H:i:s")
                    );
                    } else
                    {
                    $topics[]=array(
                        "topicid"     => $record[0],
                        "topic"       => $record[1],
                        "description" => $record[2],
                        "userid"      => $record[3],
                        "nickname"    => $record[4],
                        "date"        => $record[5]
                    );
                    }
            }
        $data = '';
        for($i=0; $i < count($topics); $i++ )
        {
                $data .= implode($this->separator, $topics[$i]);
                $data .= "\n";
        }
        if( $fh = fopen( $this->datafile, "w+" ))
        {
                fwrite($fh, $data);
                fclose($fh);
                return $topicid;
        } else
        {
                return FALSE;
        }
            return $topicid; 
        }
        return FALSE;
    }
    //------------------------------------------------------------------------------
    public function delete_topic($topicid)
    {
        $post_list->delete_posts($topicid);
        if($data=file( $this->datafile))
        {
            $topics=array();
            foreach($data as $k=>$v)
            {
                    $record = explode($this->separator, trim($v));
                    if($record[0] == $topicid)
                    {

                    } else
                    {
                    $topics[]=array(
                        "topicid"     => $record[0],
                        "topic"       => $record[1],
                        "description" => $record[2],
                        "userid"      => $record[3],
                        "nickname"    => $record[4],
                        "date"        => $record[5]
                    );
                    }
            }
            $data = '';
            for($i=0; $i < count($topics); $i++ )
            {
                    $data .= implode($this->separator, $topics[$i]);
                    $data .= "\n";
            }
            if( $fh = fopen( $this->datafile, "w+" ))
            {
                    fwrite($fh, $data);
                    fclose($fh);
                    return TRUE;
            } 
        } else
        {
            return FALSE;
        }
    }
}