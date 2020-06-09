<?php
require_once('phpconfig/userses.php');
require_once('config.php');
?>



<?php
$login = $_SESSION["login"];
$accessType = $_SESSION["accessType"];
$apikey = $_SESSION["apikey"];

function send_page_stats() {

    $to = "alicaondreakova@gmail.com";
    $subject = "My subject";
    $txt = "Hello world!";
    $headers = "From: alicaondreakova@gmail.com" . "\r\n" .
        "CC: somebodyelse@example.com";

    mail($to,$subject,$txt,$headers);

}

send_page_stats();
function show_profile_usage_stats($conn, $login, $accessType)
{

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
    return $qresult;
}
?>

<?php

function show_page_stats($conn)
{
    $pendulum = 0;
    $ball = 0;
    $aircraft = 0;
    $suspension = 0;
    $login = $_SESSION["login"];
    $accessType = $_SESSION["accessType"];

    $pageh = $conn->prepare("SELECT * FROM Statistics");
    if (!$pageh) {
        die("Db error: " . $conn->error);
    }

    if (!$pageh->execute()) {
        die("Db error: " . $pageh->error);
    }

    $qresult = $pageh->get_result();
    while ($row = $qresult->fetch_assoc()) {
        if ($row['page'] == 'ball.php') {
            $ball += 1;
        } else if ($row['page'] == 'suspension.php') {
            $suspension += 1;
        } else if ($row['page'] == 'airplane.php') {
            $aircraft += 1;
        } else if ($row['page'] == 'pendulum.php') {
            $pendulum += 1;
        }
    }
    if (!$qresult) {
        echo $conn->error;
    }
    return compact('ball', 'suspension', 'aircraft', 'pendulum');
}

function show_user_queries($conn, $apikey)
{
    $stmt = $conn->prepare("SELECT date,success,error,query,form FROM Queries WHERE apikey=?");
    if (!$stmt) {
        die("Db error: " . $conn->error);
    }
    $stmt->bind_param('s', $apikey);
    if (!$stmt->execute()) {
        die("Db error: " . $stmt->error);
    }

    $qresult = $stmt->get_result();
    if (!$qresult) {
        echo $conn->error;
    }
    return $qresult;
}


?>
<?php if (isLogged()) : ?>

    <div style=" width: auto;
    border: 1px solid black;
    overflow: hidden;background-color:white;">
        <div id="dif-info" >

            <p id="login-history" class="stats lang" key="log-hist" style="margin-left:10px;"></p>


            <form method="post" action="send_stats_pdf.php">
                <button style="margin:10px" id="sentlogin" class="stats lang" key="send_but"></button>
            </form>


            <form method="post" action="generate_pdf.php">
                <button style="margin:10px" type="submit" id="pdf-login" name="generate_pdf" class="lang" key="pdf" formtarget="_blank"></button>
            </form>

            <button style="margin:10px" id="showlogin" class="stats lang" key="show_but"></button>
            <button style="margin:10px" id="hidelogin" class="stats lang" key="hide_but"></button>
            <table id="showloginhistory" class="stats" style="margin-left:20px;">
                <tr>
                    <th>Time</th>
                </tr>
                <?php
                $qresult = show_profile_usage_stats($conn, $login, $accessType);
                while ($row = $qresult->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo date("d. m. Y H:i:s", strtotime($row['time'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>


        </div>

        <div style="overflow: hidden;">

            <p id="query-hist" class="stats lang" key="query-hist" style="margin-left:10px;"></p>

            <form method="post" action="cas_to_pdf.php">
                <button style="margin:10px" type="submit" id="pdf-queries" name="generate_pdf" class="lang stats" key="pdf" formtarget="_blank"></button>
            </form>

            <form method="post" action="exportcsv.php">
                <button style="margin:10px" type="submit" id="csv-queries" name="generate_csv" class="lang stats" key="csv-exp" formtarget="_blank"></button>
            </form>


            <button style="margin:10px" id="showquery" class="stats lang" key="show_but"></button>
            <button style="margin:10px" id="hidequery" class="stats lang" key="hide_but"></button>
            <table id="showuserqueries" class="stats" style="margin-left:20px;">
                <tr>
                    <th>Query</th>
                    <th>Form</th>
                    <th>Date</th>
                    <th>Success</th>
                    <th>Error</th>
                </tr>

                <?php
                $qresult = show_user_queries($conn, $apikey);
                while ($row = $qresult->fetch_assoc()) : ?>
                    <tr>
                        <td><?php echo $row["query"]; ?></td>
                        <td><?php echo $row["form"]; ?></td>
                        <td><?php echo $row["date"]; ?></td>
                        <td><?php echo $row["success"]; ?></td>
                        <td><?php echo $row["error"]; ?></td>




                    </tr>
                <?php endwhile; ?>

            </table>


        </div>

        <div style="overflow: inherit;">
            <p id="page-hist" class="stats lang" key="page-hist" style="margin-left:10px;"></p>
            <button style="margin:10px" id="showpage" class="stats lang" key="show_but"></button>
            <button style="margin:10px" id="hidepage" class="stats lang" key="hide_but"></button>
            <table id="show-page-history" class="stats" style="margin-left:20px;">
                <tr>
                    <th>Page</th>
                    <th>Number of visits</th>
                </tr>
                <?php
                $pageresult = show_page_stats($conn);
                $plane = $pageresult['aircraft'];
                $pendulum = $pageresult['pendulum'];
                $suspension = $pageresult['suspension'];;
                $ball = $pageresult['ball'];
                ?>
                <tr>
                    <td><?php echo "plane.php" ?></td>
                    <td><?php echo $plane ?></td>

                </tr>
                <tr>
                    <td><?php echo "pendulum.php" ?></td>
                    <td><?php echo $pendulum ?></td>

                </tr>
                <tr>
                    <td><?php echo "suspension.php" ?></td>
                    <td><?php echo $suspension ?></td>

                </tr>

                <tr>
                    <td><?php echo "ball.php" ?></td>
                    <td><?php echo $ball ?></td>

                </tr>
            </table>



        </div>
    </div>
<?php endif; ?>