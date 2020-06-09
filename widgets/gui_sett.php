     <?php

        require_once('config.php');
        require_once('phpconfig/userses.php');
        require_once('statistics.php');
        ?>

     <?php

$login = $_SESSION["login"];
$accessType = $_SESSION["accessType"];
$apikey = $_SESSION["apikey"];

        $user = $login;
        $key = get_api_key($conn, $login);

        if (isset($_POST["action"])) {
            if ($_POST["action"] == "regenerate_key") {
                regenerate_api_key($conn, $user);

            } 
            else if ($_POST['action'] == "regenerate_password") {
                if ($_POST['old_one'] == $_POST['old_two']) {
                    if (check_password($conn, $user,$_POST['old_one'])) {
                        change_password($conn, $user, $_POST['new_passwd']);
                    }
                }
            }
        }

        /*
        if (isset($_GET['action']) && $_GET['action'] == "regenerate_key") {
            echo "reg - key";
            regenerate_api_key($conn, $user);
        }
        */





        $user = getUser();
        $key = get_api_key($conn, $user);

        //if (isset($_GET['action']) && $_GET['action'] == 'regenerate_password') {
        //  if (hash('sha256',$_GET['old_one']) == hash('sha256',$_GET['old_two'])) {
        // if (check_password($conn, $_SESSION['login'],$_GET['old_one']) == true) {
        //  change_password($conn, $login,$_GET['new_passwd']);
        // }
        //}
        //}
        ?>


<div style=" width: 100%;
    border: 1px solid black;
    overflow: hidden;background-color:white;">
     <?php if (isLogged() && isRegistered()) : ?>
         <button style="margin-left:20px" id="showpromptpasswd" class="lang" key="showpromptpasswd"></button>
         <button style="margin-left:20px" id="hidepasswd" class="lang" key="hide_but"></button>

             <form action="?action=regenerate_password" method="post">
             <div id="passwd-change">

                 <input type="password" placeholder="old password" name="old_one" id="old_one">
                 <input type="password" placeholder="old2" name="old_two" id="old_two">
                 <input type="password" placeholder="new password" name="new_passwd" id="new_passwd">
                 <input type="submit">
             </div>
             </form>
         <br>
         <button style="margin-left:20px" id="showemailchange" class="lang" key="showemailchange" style="clear:both;"></button>
         <button style="margin-left:20px" id="hideemail" class="lang" key="hide_but"></button>
         <form action="?action=change-email" method="post">
         <div id="email-change">
             <input type="text" placeholder="old email">
             <input type="text" placeholder="new email">
             <input type="submit">
         </div>
        </form>
        
         <br>
         <button style="margin-left:20px" id="showkey" class="lang" key="showkey">Show api key</button>
         <button style="margin-left:20px" id="hidekey" class="lang" key="hide_but"></button>

         <form action="?action=regenerate_key" method="post">
             <button type="submit" style="text-align:left;margin-left:40px" id="reg-key" style="clear:both;">Regenarate apikey</button>
         </form>

         <div id="api-key">
             <div style="text-align: left;margin-left:40px;">
                 <input type="text" value="<?php echo $key ?>" id="myKey" size="35"> <img style="vertical-align: middle;" src="img/copytoclip.png" width="20px" height="20px" onclick="copytoClipboard()">
             </div>
         </div>
     <?php elseif (isLogged()) : ?>
         <br>
         <button style="margin-left:20px" id="showkey" class="lang" key="showkey">Show api key</button>
         <button style="margin-left:20px" id="hidekey" class="lang" key="hide_but"></button>

         <form action="?action=regenerate_key" method="post">
             <button type="submit" style="text-align:left;margin-left:40px" id="reg-key" style="clear:both;">Regenarate apikey</button>
         </form>


         <div id="api-key">

             <div style="text-align: left;margin-left:40px;">
                 <input type="text" value="<?php echo $key ?>" id="myKey" size="35"> <img style="vertical-align: middle;" src="img/copytoclip.png" width="20px" height="20px" onclick="copytoClipboard()">


             </div>
         </div>
</div>


     <?php endif; ?>