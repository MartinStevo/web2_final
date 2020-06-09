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
$page = "suspension.php";
insert_page($conn,$page,$login);
?>

<!DOCTYPE html>
<html lang="sk">

<head>
<title>Alica Ondreakova Page</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/login.css">
    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
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
                if (temp >= -100 && temp <= 100) {
                    $.ajax({
                        type: 'GET',
                        url: "api/vehicle?r=" + varr,
                        dataType: "text",
                        success: function (response) {
                            var data = JSON.parse(response);
                            outputGrapf(data);
                            outputData = data;
                            $('#sketch-holder').html("");
                            myp5 = new p5(sketch, document.getElementById('sketch-holder'));
                        }
                    });
                }
            });
        });

        var wheelTrace = {
            x: [],
            y: [],
            mode: 'line',
            name: 'Koleso',
            marker: {line: {color: 'rgb(0, 1, 0)'}}
        };
        var carTrace = {
            x: [],
            y: [],
            mode: 'line',
            name: 'Karoseria',
            marker: {line: {color: 'rgb(1, 0, 0)'}}
        };

        var config = {
            showlegend: true,
            responsive: true,
            //dragmode: 'pan'
        };

        function outputGrapf(data) {
            if (data != null) {
                wheelTrace.y = (data.y);
                wheelTrace.x = (data.x);
                carTrace.x = data.x;
                carTrace.y = data.angle;
                graf();
            }
        }

        function graf() {

            data = [wheelTrace, carTrace];
            Plotly.newPlot("chart", data, config, {
                displayModeBar: false, alignContent: false,
                scrollZoom: false, cursor: false
            });

        }

        var sketch = function (p) {
            p.x_wheel = 500;
            p.y_wheel = 550;
            p.x_car = 350;
            p.y_car = 250;
            p.array_wheel = [];
            p.array_car = [];
            p.wheel_r = 100;
            p.wheel_fill = p.color(255, 255, 255);
            p.wheel_stroke = p.color(100, 100, 100);
            p.wheel_stroke_weight = 20;
            p.car_height = 170;
            p.car_width = 400;
            p.car_fill = p.color(255, 150, 150);
            p.car_stroke = p.color(255, 255, 255);
            p.car_stroke_weight = 5;
            p.connector_stroke_weight = 20;
            p.connector_stroke = p.color(200, 255, 200);
            p.col = p.color(255, 255, 128);
            p.bg = p.loadImage('img/lab.jpg');

            p.setup = function () {
                p.createCanvas(1000, 800);
                p.array_wheel = (outputData.y);
                p.array_car = (outputData.angle);
                p.x = p.x_home + p.ball_constant + p.temp * 8.5;
                p.y = p.y_home;
                p.x_tyc = p.x_home;
                p.y_tyc = p.y_home;

            };

            p.i = 0;
            p.draw = function () {
                p.background(p.bg);

                var y_wheel_current = p.y_wheel - parseFloat(p.array_wheel[p.i]); 
                
                // Draw wheel
                p.fill(p.wheel_fill);
                p.strokeWeight(p.wheel_stroke_weight);
                p.stroke(p.wheel_stroke);
                p.ellipse(p.x_wheel, y_wheel_current, p.wheel_r, p.wheel_r);


                var y_car_current = p.y_car - parseFloat(p.array_car[p.i]);
                var y_car_top = y_car_current - p.car_height / 2.0;
                var y_car_bot = y_car_current + p.car_height / 2.0;
                var x_car_left = p.x_car - p.car_height / 2.0;
                var x_car_right = p.x_car + p.car_height / 2.0;

                // Draw connector
                p.strokeWeight(p.connector_stroke_weight);
                p.stroke(p.connector_stroke);
                p.line(p.x_wheel, y_car_current, p.x_wheel, y_wheel_current);

                // Draw car
                p.fill(p.car_fill);
                p.strokeWeight(p.car_stroke_weight);
                p.stroke(p.car_stroke);
                p.rect(x_car_left, y_car_top, p.car_width, p.car_height);

                //p.rect(20, 20, 60, 60);
                //p.ellipse(60, 60, 100, 100);
                
                
                
                p.i++;
                if (p.i == 501) p.i = 0;
/*
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
*/
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

    <?php echo "<p class='lang' key='suspension-text'></p>"; ?>
    <form action="javascript:void(0);" style="text-align: center"><br>

        <label for="r">r: </label>
        <input style="font-size: 25px" id="r" name="r" type="number" step="0.01" min="-100" max="100" required>
        <button style="font-size: 25px" id="submit">OK</button><br><br>

        <label for="checkboxgraf"><?php echo "<p class='lang' key='chart' style='display: inline'></p>"; ?></label>
        <input type="checkbox" id="checkboxgraf" name="checkboxgraf" style="vertical-align: bottom; width: 25px; height: 25px" checked>

        <label for="checkboxanim"><?php echo "<p class='lang' key='animation' style='display: inline'></p>"; ?></label>
        <input type="checkbox" id="checkboxanim" name="checkboxanim" style="vertical-align: bottom; width: 25px; height: 25px" checked><br>

    </form>
</div>
<br>
<div id="chart" style="margin: auto; width: 1000px"></div>
<br>
<div id="sketch-holder" style="margin: auto; width: 1000px"></div>
<br>
</body>
</html>