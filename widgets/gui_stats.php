<?php
        require_once('phpconfig/userses.php');
        require_once('config.php');
?>



        <?php
            $login = $_SESSION["login"];
            $accessType = $_SESSION["accessType"];

            $stmt = $conn->prepare("SELECT time FROM Prihlasenia WHERE login=? AND accessType=? ORDER BY time DESC");
            if (!$stmt) {
                die("Db error: " . $conn->error);
            }
            $stmt->bind_param('ss', $login, $accessType);
            if (!$stmt->execute()) {
                die("Db error: " . $stmt->error);
            }

            $qresult = $stmt->get_result();
            if (!$qresult) {
                echo $conn->error;
            }
            ?>
                    <?php if (isLogged()) : ?>

                       
         <p id="login-history" class="stats" style="margin-left:20px;">Your login history:</p>
         <button id="showlogin" class="stats lang" key="show_but"></button>
         <button id="hidelogin" class="stats lang" key="hide_but"></button>

         <form method="post" action="send_stats_pdf.php">
         <button id="sentlogin" class="stats lang" key="send_but"></button>
         </form>
     

         <form method="post" action="generate_pdf.php" >
        <button  type="submit" id="pdf-login" name="generate_pdf" class="lang" key="pdf" formtarget="_blank"></button>
        </form>
                        
         <table id="showloginhistory" class="stats" style="margin-left:20px;">
             <tr>
                 <th>Time</th>
             </tr>
             <?php while ($row = $qresult->fetch_assoc()) : ?>
                 <tr>
                     <td><?php echo date("d. m. Y H:i:s", strtotime($row['time'])); ?></td>
                 </tr>
             <?php endwhile; ?>
         </table>
         <?php endif; ?>


