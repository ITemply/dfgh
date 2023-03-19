<?php
session_start();
date_default_timezone_set('US/Eastern');
if(isset($_GET['logout'])){    
	
    $logout_message = "<div class='msgln'><span class='left-info'>User <b class='user-name-left'>". $_SESSION['name'] ."</b> has left the chat session.</span><br></div>";
    file_put_contents("log.html", $logout_message, FILE_APPEND | LOCK_EX);
	
	session_destroy();
	header("Location: index.php");
}
if(isset($_POST['enter'])){
    if($_POST['name'] != ""){
      $_SESSION['name'] = stripslashes(htmlspecialchars($_POST['name']));
    } else {
      echo '<center><span class="error">Please input a username.</span></center>';
    }
}
function loginForm(){
    echo 
    '<div id="loginform"> 
<p>Please enter a username to continue!</p> 
<form action="index.php" method="post"> 
<label for="name">Username: </label> 
<input type="text" name="name" id="inputname" /> 
<input type="submit" name="enter" id="enter" value="Enter" /> 
</form>
</div>';
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Chat App</title>
        <meta name="description" content="Chat App" />
        <link rel="stylesheet" href="style.css" />
    </head>
    <body>
    <?php
    if(!isset($_SESSION['name'])){
      loginForm();
    }
    else {
      if($_SESSION['name'] == 'Admin') {
        echo "<script>input=prompt('Enter Admin Password')
              if(input == 'AdminPassword'){
              } else {
                window.location = 'https://modified-chat-app.itemply.repl.co/'
              };</script>";
      }
      $prevsessions = file_get_contents("sessions.txt");
      file_put_contents("sessions.txt", $prevsessions.$_SESSION['name'].' | '.date("g:i A").' | '.session_id().' |
');
    ?>
        <div id="wrapper">
            <div id="menu">
                <p class="welcome">Your Username: <b><?php echo $_SESSION['name']; ?></b></p>
                <p class="logout"><a id="exit" href="#">Exit Chat</a></p>
            </div>
            <div id="chatbox">
            <?php
            if(file_exists("log.html") && filesize("log.html") > 0){
                $contents = file_get_contents("log.html");          
                echo $contents;
            }
            ?>
            </div>
            <form name="message" action="">
                <input name="usermsg" type="text" id="usermsg" />
                <input name="submitmsg" type="submit" id="submitmsg" value="Send" />
            </form>
        </div>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                $("#submitmsg").click(function () {
                    var clientmsg = $("#usermsg").val();
                    $.post("post.php", { text: clientmsg });
                    $("#usermsg").val("");
                    return false;
                });
                function loadLog() {
                    var oldscrollHeight = $("#chatbox")[0].scrollHeight - 20;
                    $.ajax({
                        url: "log.html",
                        cache: false,
                        success: function (html) {
                            $("#chatbox").html(html);
                            var newscrollHeight = $("#chatbox")[0].scrollHeight - 20;
                            if(newscrollHeight > oldscrollHeight){
                                $("#chatbox").animate({ scrollTop: newscrollHeight }, 'normal');
                            }	
                        }
                    });
                }
                setInterval (loadLog, 500);
                $("#exit").click(function () {
                    var exit = confirm("Are you sure you want to end the session?");
                    if (exit == true) {
                    window.location = "index.php?logout=true";
                    }
                });
            });
        </script>
    </body>
</html>
<?php
}
?>