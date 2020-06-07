     <?php
        require_once('phpconfig/userses.php');
        require_once('config.php');
        require_once('statistics.php');
        if (isset($_GET['action']) && $_GET['action'] == 'regenerate_key') {
            regenerate_api_key($conn, $login);
        }
        //if (isset($_GET['action']) && $_GET['action'] == 'regenerate_password') {
          //  if (hash('sha256',$_GET['old_one']) == hash('sha256',$_GET['old_two'])) {
            // if (check_password($conn, $_SESSION['login'],$_GET['old_one']) == true) {
              //  change_password($conn, $login,$_GET['new_passwd']);
           // }
        //}
    //}

        $key = get_api_key($conn, $login);

        ?>

     <?php if (isLogged() && isRegistered()) : ?>
         <button style="margin-left:20px" id="showpromptpasswd" class="lang" key="showpromptpasswd"></button>
         <button style="margin-left:20px" id="hidepasswd" class="lang" key="hide_but"></button>

         <div id="passwd-change">
             <form action="?action=regenerate_password" method="post">
                 <input type="password" placeholder="old password" name="old_one">
                 <input type="password" placeholder="new password" name="old_two">
                 <input type="password" placeholder="new password" name="new_passwd">
                 <input type="submit">
             </form>
         </div>
         <br>
         <button style="margin-left:20px" id="showemailchange" class="lang" key="showemailchange" style="clear:both;"></button>
         <button style="margin-left:20px" id="hideemail" class="lang" key="hide_but"></button>
         <div id="email-change">
             <input type="text" placeholder="old email">
             <input type="text" placeholder="new email">
             <input type="submit">

         </div>
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



     <?php endif; ?>