     <?php
        require_once('phpconfig/userses.php');
        require_once('config.php');
        ?>

     <?php if (isLogged() && isRegistered()) : ?>
             <button style="margin-left:20px" id="showpromptpasswd" class="lang" key="showpromptpasswd"></button>
             <button style="margin-left:20px" id="hidepasswd" class="lang" key="hide_but"></button>

             <div id="passwd-change">
                 <input type="password" placeholder="old password">
                 <input type="password" placeholder="new password">
                 <input type="password" placeholder="new password">
                 <input type="submit">

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

             <div id="api-key">
                 <div style="text-align: left;margin-left:40px;">
                     <input type="text" value="23921832138127fjkfashjda" id="myKey"> <img style="vertical-align: middle;" src="img/copytoclip.png" width="20px" height="20px" onclick="copytoClipboard()">
                 </div>
             </div>
         <?php elseif (isLogged()) : ?>
            <br>
             <button style="margin-left:20px" id="showkey" class="lang" key="showkey">Show api key</button>
             <button style="margin-left:20px" id="hidekey" class="lang" key="hide_but"></button>
             <div id="api-key">
                 <div style="text-align: left;margin-left:40px;">
                     <input type="text" value="23921832138127fjkfashjda" id="myKey"> <img style="vertical-align: middle;" src="img/copytoclip.png" width="20px" height="20px" onclick="copytoClipboard()">
                 </div>
             </div>

            
            
         <?php endif; ?>


   