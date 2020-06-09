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
$page = "pendulum.php";
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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/p5.js/1.0.0/p5.js"></script>
    <script type="text/javascript" src="js/dict.js"></script>
    <script type="text/javascript" src="js/settings.js"></script>
    <meta name_t="viewport" content="width=device-width, initial-scale=1.0">
    <meta name_t="keywords" content="html, css">
    <meta name_t="author" content="Alica Ondreakova">

    <script src="p5.js"></script>

    <script>

        var outputData;
        var varr;
        var myp5;

        $(document).ready(function () {
            $("#submit").click(function () {
                varr = $("#r").val();
                var temp = parseFloat(varr)
                if (temp >= -15 && temp <= 15) {

                    $.ajax({
                        type: 'GET',
                        url: "api/pendulum?r="+varr,
                        dataType: "text",
                        success: function (response) {
                            var data = JSON.parse(response);
                            outputGrapf(data);
                            outputData = JSON.parse(response);
                            $('#sketch-holder').html("");
                            myp5 = new p5(sketch2, document.getElementById('sketch-holder'));
                        }
                    });
                }
            });
        });

        var trace = {
            x: [],
            y: [],
            mode: 'line',
            name: 'Poloha kyvadla',
            marker: {color: 'rgb(0, 0, 0)'}
        };
        var trace2 = {
            x: [],
            y: [],
            mode: 'line',
            name: 'Uhol',
            marker: {color: 'rgb(216,0,12)'}
        };

        var config = {
            showlegend: true,
            responsive: true,
        };

        function outputGrapf(data) {
            if (data != null) {
                trace.y = (data.y);
                trace.x = (data.x);
                trace2.y = (data.angle);
                trace2.x = (data.x);
                graf();
            }
        }

        function graf() {

            data = [trace, trace2];

            Plotly.newPlot("chart", data,  config, {
                displayModeBar: false, alignContent: false,
                scrollZoom: false, cursor: false
            });
        }

        var sketch2 = function(p) {
            p.i = 0;
            p.x = 0;
            p.array_angle = [];
            p.array_poloha = [];
            p.kyvadlo = p.loadImage('img/pendulum.png');

            p.setup = function() {
                p.createCanvas(1000, 600);
                p.array_angle = (outputData.angle);
                p.array_poloha = (outputData.y);
                p.angleMode(p.DEGREES);
            };

            p.draw = function() {
                p.background(0);
                p.i = p.i +1;

                if(p.i == p.array_angle.size || isNaN(p.array_angle[p.i] * 180/p.PI)) {
                    p.i = 0;
                }
                p.stroke(255);
                p.line(0,p.height * (2/3),p.width,p.height * (2/3));
                p.translate(500 + parseInt(p.array_poloha[p.i])*5, 395);

                p.rect(-70,-10,140,30);
                p.rotate( p.array_angle[p.i] * 180/p.PI);
                p.image(p.kyvadlo, -12, -110, 23, 98);
            };
        };



    </script>

</head>

<div class="background_1">
    <?php require_once('widgets/nav.php'); ?>
</div>
<style>
    .checkbox{
        margin-left: 1%;
    }
    .grid-container{
        text-align: center;
    }
</style>
<body>
<div class="grid-container">
    <h1 class="lang" key="pend-h1"></h1>
    <p class="lang" key="val"></p>

    <div class="grid-item">
        <div id="form" class="pendulum" style="margin: auto; width: 235px; font-size: 25px"><br>
            <form action="javascript:void(0);">
                <label for="r (rad)">r: </label>
                <input style="font-size: 25px" id="r" name="r" type="number" step="" min="-15" max="15" required>
                <button style="font-size: 25px" id="submit" class="lang" key="set"></button>
            </form>
        </div>

        <br>

        <div class ="toAllign">
            <label class="leftAlign lang" for="vehicle1" key="animation"> </label><br>
            <input type="checkbox" id="vehicle1" name="Animácia" value="animation" onclick="myFunction(1)" checked><br>
            <label class="leftAlign lang" for="vehicle2" key="chart"> </label><br>
            <input type="checkbox" id="vehicle2" name="Graf" value="graph" onclick="myFunction(2)" checked>
        </div>

        <div id="sketch-holder" style="margin: auto; width: 1000px"></div><br>

    </div>

    <div class="grid-item">


        <br>

        <br><br>
        <div id="chart" class="klapka" style="margin: auto; width: 1000px"></div><br>

    </div>
</div>



<script>

    function myFunction(num) {
        var animation = document.getElementById("vehicle1");

        var graph = document.getElementById("vehicle2");

        if(num === 1){
            if(animation.checked){

                document.getElementById("sketch-holder").style.display = "block";
            }
            else{

                document.getElementById("sketch-holder").style.display = "none";
            }
        }else if (num === 2 ){
            if(graph.checked){

                document.getElementById("chart").style.display = "block";
            }
            else{

                document.getElementById("chart").style.display = "none";
            }
        }
    }
</script>

</body>
</html>