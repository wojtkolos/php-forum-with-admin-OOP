<?php
session_start();
include('user_list.php');
include('topics.php');

$path = explode('\\',dirname(__FILE__));
$dirname = array_pop($path);
$username = array_pop($path);
$path = implode('\\',$path);
foreach(scandir( "$path\\$username" ) as $dir) if( is_dir("$path\\$username\\$dir") and $dir!='.' and $dir!='..') $tasks[] = $dir;

$user_list = new User_list();
$post_list = new Posts();
$topic_list = new Topics();

$users = $user_list->get_users();
$check = FALSE;
foreach($users as $user)
{
    if($user['userid'] == "admin")
    {
        $check = TRUE;
    }
}
if(!$check) $user_list->put_user("admin", "admin", "admin", "admin"); 

$postid='';
$cmd = '';
$topic_val='';
$descr_val='';
if(isset($_GET['cmd']))
{
    
    $cmd = $_GET['cmd'];
    if($cmd == 'logout')
    {
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
            );
        }
        session_destroy();
        header("Location: index.php");exit;
    }
    else if($cmd == 'editTopic')
    {
        $topicid = $_GET['top'];
        $toEdit = $topic_list->get_topic($topicid);
        $topic_val = $toEdit['topic'];
        $descr_val = $toEdit['description'];
    } 
    else if($cmd == 'delTopic')
    {
        $topicid = $_GET['top'];
        $topic_list->delete_topic($topicid);
        header("Location: index.php");
    }
    else if($cmd == 'editPost')
    {
        $postid = $_GET['id'];
        $topicid = $_GET['topic'];
        $toEdit = $post_list->get_post($topicid ,$postid);
        $descr_val = $toEdit['post'];
    } 
    else if($cmd == 'delPost')
    {
        $topicid = $_GET['topic'];
        $postid = $_GET['id'];
        $post_list->delete_post($topicid, $postid, $_SESSION['userid']);
    } 
    else if($cmd == 'changeuser')
    {
        $user_list->changePerm($_GET['userid']);

        header("Location: index.php?cmd=userlist");
    } 
    else if($cmd == 'deluser')
    {
        $user_list->deleteUsr($_GET['userid']);
        header("Location: index.php?cmd=userlist");
    }

}

?>
<html>
<head>
    <title>TWWW - User: <?=$username?>, <?=$dirname?></title>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate" >
    <meta http-equiv="Pragma" content="no-cache" >
    <link rel="stylesheet" href="css/style4.css" type="text/css" />
</head>
<body>
    <header>
        <h1 class="logo">
            <?=$dirname?> - WWW i Języki skryptowe
        </h1>
        <h2 class="logo">
                Proste forum
        </h2>
    </header>
    <nav class="nav">
        <ul class="menu">
            <li><a href="<?="/$username"?>" title="Strona początkowa serwisu" target="_self" >home</a></li>
            <?php foreach($tasks as $task){ ?>
            <li><a href="<?="/$username/$task"?>/" title="<?=$task?>" target="_self" ><?=$task?></a></li>
            <?php } ?>
        </ul>
    </nav>
    <section>
        <?php 
        if(isset($_GET['topic']) > 0 && isset($_SESSION['logged']) && $_SESSION['logged'] == TRUE) //show posts
        {
            $user_list->perm_check();
            $topicid = $_GET['topic'];
            include('post_list.php');
        }
        else if(isset($_SESSION['logged']) && $_SESSION['logged'] == TRUE)
        {
            $user_list->perm_check();
            include('topic_list.php');
        } 
        else 
        {
            
            include('login_module.php');
            //header("Location: index.php");
        }
        ?>
    </section>
    <footer>
        Ostatni wpis na forum powstał dnia: <?=$post_list->last_post()?>
    </footer>
</body>
</html>