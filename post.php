<?php
session_start();
date_default_timezone_set('US/Eastern');
if(isset($_SESSION['name'])){
  if($_SESSION['name'] == 'Admin') {
    $text = $_POST['text'];
    $console == FALSE;
    $slientcommanden == FALSE;
    if (strpos(stripslashes(htmlspecialchars($text)), '/') !== false){
      $args = explode("/", stripslashes(htmlspecialchars($text)));
      if ($args[1] == 'clear') {
        file_put_contents("log.html", "");
      } else if ($args[1] == 'mute') {
        $prevbans = file_get_contents("mutedusers.txt");
        file_put_contents("mutedusers.txt", $prevbans.$args[2].' 
');
      } else if ($args[1] == 'cmds') {
        $console = TRUE;
        $text = 'Commands: 1. /clear | 2. /mute <user> | 3. /unmute <user> | 4. /cmds | 5. /help';
      } else if ($args[1] == 'unmute') {
        $prevbans = file_get_contents("mutedusers.txt");
        file_put_contents("mutedusers.txt", str_replace($args[2], '', $prevbans));
      } else if ($args[1] == 'help') {
        $console = TRUE;
        $text = 'Helpful Tips: 1. To run a command silently, run your command and put a /s at the end of it. | 2. To view a list of commands, run the /cmds command. 3. You can send an image with the image address.';
      }
      if ($args[3] == 's' or $args[2] == 's') {
        $slientcommanden = TRUE;
      }
    }
    if ($console == TRUE) {
      $console = FALSE;
        $text_message = "<div class='msgln'><span class='chat-time'>".date("g:i A")."</span> <b class='console-user-name'>Console</b> ".stripslashes(htmlspecialchars($text))."<br></div>";
      file_put_contents("log.html", $text_message, FILE_APPEND | LOCK_EX);
    } else {
      if (strpos(stripslashes(htmlspecialchars($text)), 'https://') !== false){
        $text_message = "<div class='msgln'><span class='chat-time'>".date("g:i A")."</span> <b class='admin-user-name'>".$_SESSION['name']."</b><a target='_blank' href='".stripslashes(htmlspecialchars($text))."'><img src='".stripslashes(htmlspecialchars($text))."' style='height: 50%; width:50%; border-radius: 10px'></a><br></div>";
      file_put_contents("log.html", $text_message, FILE_APPEND | LOCK_EX);
      } else {
        if ($slientcommanden == TRUE) {
          $slientcommanden = FALSE;
        } else {
          $text_message = "<div class='msgln'><span class='chat-time'>".date("g:i A")."</span> <b class='admin-user-name'>".$_SESSION['name']."</b> ".stripslashes(htmlspecialchars($text))."<br></div>";
        file_put_contents("log.html", $text_message, FILE_APPEND | LOCK_EX);
        }
      }
    }
  } else {
    $data = file_get_contents('mutedusers.txt');
    $text = $_POST['text'];
    if(strpos($data, $_SESSION['name']) !== FALSE) {
	      $text_message = "<div class='msgln'><span class='chat-time'>".date("g:i A")."</span> <b class='user-name'>".$_SESSION['name']."</b>[Text Removed]<br></div>";
        file_put_contents("log.html", $text_message, FILE_APPEND | LOCK_EX);
    } else {
      if (strpos(stripslashes(htmlspecialchars($text)), 'https://') !== false){
         $text_message = "<div class='msgln'><span class='chat-time'>".date("g:i A")."</span> <b class='user-name'>".$_SESSION['name']."</b><a target='_blank' href='".stripslashes(htmlspecialchars($text))."'><img src='".stripslashes(htmlspecialchars($text))."' style='height: 50%; width:50%; border-radius: 10px'></a><br></div>";
      } else {
        $text_message = "<div class='msgln'><span class='chat-time'>".date("g:i A")."</span> <b class='user-name'>".$_SESSION['name']."</b> ".stripslashes(htmlspecialchars($text))."<br></div>";
      file_put_contents("log.html", $text_message, FILE_APPEND | LOCK_EX);
      }
    }
  }
}
?>