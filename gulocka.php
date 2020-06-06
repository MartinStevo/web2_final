<?php
require_once('config.php');
require_once('statistics.php'); ?>
<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: index.php?login=none");
} else if (isset($_GET["action"])) {
    if ($_GET["action"] == "logout") {
        session_unset();
        header("Location: index.php?login=loggedOut");
    }
}
$login = $_SESSION["login"];
$page = "ball.php";
insert_page($conn, $page, $login);
?>

<!DOCTYPE html>
<html lang="sk">

<head>
    <title>Alica Ondreakova Page</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="jquery-3.4.1.min.js"></script>
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/1.0.0/p5.js"></script>
    <script type="text/javascript" src="js/dict.js"></script>
    <script type="text/javascript" src="js/settings.js"></script>
    <meta name_t="viewport" content="width=device-width, initial-scale=1.0">
    <meta name_t="keywords" content="html, css">
    <meta name_t="author" content="Alica Ondreakova">

    <script src="p5.js"></script>
    <script src="p5.sound.js"></script>
    <script src="sketch.js"></script>
    <script>

        var outputData;
        var varr;
        var myp5;

        $(document).ready(function () {
            $("#submit").click(function () {
                varr = $("#r").val();
                var temp = parseFloat(varr)
                if (temp >= 0 && temp <= 100) {
                    $.ajax({
                        type: 'GET',
                        url: "api/ball?r=" + varr,
                        dataType: "text",
                        success: function (response) {
                            var data = JSON.parse(response);
                            outputGrapf(data);
                            outputData = JSON.parse(response);
                            $('#sketch-holder').html("");
                            myp5 = new p5(sketch, document.getElementById('sketch-holder'));
                        }
                    });
                }
            });
        });

        var trace = {
            x: [],
            y: [],
            mode: 'line',
            name: 'Poloha guličky',
            marker: {color: 'rgb(0, 0, 0)'}
        };

        var config = {
            showlegend: true,
            responsive: true,
            //dragmode: 'pan'
        };

        function outputGrapf(data) {
            if (data != null) {
                trace.y = (data.y);
                trace.x = (data.x);
                graf();
            }
        }

        function graf() {

            data = [trace];
            Plotly.newPlot("chart", data, config, {
                displayModeBar: false, alignContent: false,
                scrollZoom: false, cursor: false
            });

        }

        var sketch = function (p) {
            p.x_home = 30;
            p.y_home = 215;
            p.x_tyc = 30;
            p.y_tyc = 215;
            p.ball_constant = 45;
            p.temp = parseInt(varr);
            p.x = 30;
            p.y = 190;
            p.array_x = [];
            p.array_y = [];
            p.r = 25;
            p.col = p.color(255, 255, 128);
            p.bg = p.loadImage('img/lab.jpg');

            p.setup = function () {
                p.createCanvas(1000, 400);
                p.array_x = (outputData.y);
                p.array_y = (outputData.angle);
                p.x = p.x_home + p.ball_constant + p.temp * 8.5;
                p.y = p.y_home;
                p.x_tyc = p.x_home;
                p.y_tyc = p.y_home;

            };

            p.i = 0;
            p.draw = function () {
                p.background(p.bg);
                p.fill(p.col);

                p.x = p.x_home + p.ball_constant + p.array_x[p.i] * 8.5;
                p.y = p.y_home + p.tan(p.array_y[p.i]) * p.array_x[p.i] * 8.5;
                p.ellipse(p.x, p.y, p.r * 2, p.r * 2);
                console.log(p.tan(p.array_y[p.i]));
                console.log(p.tan(p.array_x));
                p.i++;
                if (p.i == 501) {
                    console.log(varr);
                    p.i = 0;
                    p.x = p.x_home + p.ball_constant + p.temp * 8.5;
                    p.y = p.y_home;
                }

                p.rotate(p.array_y[p.i]);
                p.rect(p.x_tyc, p.y_tyc + 25, 940, 20, 0, 20, 20, 0);
                drawStojan();

            };

            function drawStojan() {

                p.rotate(-p.array_y[p.i]);
                p.rect(20, 70, 20, 300, 5, 5, 0, 0);
            }
        };


    </script>
    <script>
        $(document).ready(function(){
            $('#checkboxgraf').change(function () {
                $('#chart').toggle();
            });
            $('#checkboxanim').change(function () {
                $('#sketch-holder').toggle();
            });
        });
    </script>

</head>

<div class="background_1">
    <?php require_once('widgets/nav.php'); ?>
</div>



<body>
<div id="form" style="font-size: 25px; text-align: center"><br>

    Zadajte novú pozíciu guličky [0-100cm]
    <form action="javascript:void(0);" style="text-align: center"><br>

        <label for="r">r: </label>
        <input style="font-size: 25px" id="r" name="r" type="number" step="0.01" min="0.01" max="100" required>
        <button style="font-size: 25px" id="submit">OK</button><br><br>

        <label for="checkboxgraf">Graf: </label><input type="checkbox" id="checkboxgraf" name="checkboxgraf" style="vertical-align: bottom; width: 25px; height: 25px" checked>
        <label for="checkboxanim">Animácia: </label><input type="checkbox" id="checkboxanim" name="checkboxanim" style="vertical-align: bottom; width: 25px; height: 25px" checked><br>

    </form>
</div>
<br>
<div id="chart" style="margin: auto; width: 1000px"></div>
<br>
<div id="sketch-holder" style="margin: auto; width: 1000px"></div>
<br>
</body>
</html>