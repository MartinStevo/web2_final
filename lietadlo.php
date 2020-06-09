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
insert_page($conn,$page,$login);
?>

<!DOCTYPE html>
<html lang="sk">

<head>
<title>JR Plane API</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/login.css">
    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/dict.js"></script>
    <script type="text/javascript" src="js/settings.js"></script>
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
    <meta name_t="viewport" content="width=device-width, initial-scale=1.0">
    <meta name_t="keywords" content="html, css">
    <meta name_t="author" content="Jakub Rajčok">
    <style>

    </style>
    <script src="p5.js"></script>
    <!--Plane Animation-->
    <script>
        var outputData;
        var varr;
        var myp5;

        $(document).ready(function () {
            $("#submit").click(function () {
                varr = $("#r").val();
                var temp = parseFloat(varr)
                if (temp >= -1.6 && temp <= 1.6) {
                    $.ajax({
                        type: 'GET',
                        url: "api/plane?r=" + varr,
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
            name: 'Uhol klapky',
            marker: {color: 'rgb(0, 0, 0)'}
        };
        var trace2 = {
            x: [],
            y: [],
            mode: 'line',
            name: 'xxx',
            marker: {color: 'rgb(125, 125, 125)'}
        };
        var layout = {
            showlegend: true,
            responsive: true,
            title: {
                text:'Náklon zadnej klapky lietadla',
                font: {
                    family: 'Courier New, monospace',
                    size: 24
                },
                xref: 'paper',
                x: 0.05,
            },
            xaxis: {
                title: {
                    text: 'Uhol (rad)',
                    font: {
                        family: 'Courier New, monospace',
                        size: 18,
                        color: '#7f7f7f'
                    }
                },
            },
            yaxis: {
                title: {
                    text: 'Čas (sec)',
                    font: {
                        family: 'Courier New, monospace',
                        size: 18,
                        color: '#7f7f7f'
                    }
                }
            }
        };

        function outputGrapf(data) {
            var cropNo = 400;
            if (data != null ) {
                trace.x = (data.x);
                trace.y = (data.angle);

                trace2.x = (data.angle);
                trace2.y = (data.y);
                graf();
            }
        }

        function graf() {
            data = [trace /*,trace2*/];
            Plotly.newPlot("chart", data, layout, {
                displayModeBar: false, alignContent: false,
                scrollZoom: false, cursor: false
            });
        }

        var sketch = function(p) {
            p.i = 0;
            var plus = false;
            var minus = false;
            var inc = 0.1;
            var x1 = 0;
            var x2;
            var scrollSpeed = 2;
            p.array_angle = [];

            //p.bg = p.loadImage('img/sky.jpg');
            p.bg = p.loadImage('img/sky2.jpg');
            p.img = p.loadImage('img/planeImg2.png');

            p.setup = function() {
                p.createCanvas(1000, 600);
                x2 = p.width;
                p.array_angle = (outputData.angle);
                p.angleMode(p.DEGREES);
            };

            p.draw = function() {
                p.image(p.bg, x1, 0, 0);
                p.image(p.bg, x2, 0, p.width, p.height);

                x1 -= scrollSpeed;
                x2 -= scrollSpeed;

                if (x1 < -1 * p.width)
                    x1 = p.width;
                if (x2 < -1 * p.width)
                    x2 = p.width;


                if( plus == true && p.i < 0){
                    inc = -1*inc;
                    p.i = 0;
                }
                if(minus == true && p.i > 0){
                    inc = -1*inc;
                    p.i = 0;
                }
                if(varr * 180/p.PI > 0){
                    plus = true;
                    p.i = p.i + inc;
                    if( p.i >= varr * 180/p.PI ){
                        inc = -1*inc;
                        reached = true;
                    }
                }else if(varr * 180/p.PI < 0){
                    minus = true;
                    p.i = p.i - inc;
                    if( p.i <= varr * 180/p.PI ){
                        inc = -1*inc;
                        reached = true;
                    }
                }

                p.translate(p.width / 3, p.height / 3);
                p.rotate(p.i);
                p.image(p.img, 10, 140, 225, 100);

            };
        };

    </script>
    <!--Klapka Animation-->
    <script>
        var outputData;var varr;var myp5;

        $(document).ready(function () {
            $("#submit").click(function () {
                varr = $("#r").val();
                var temp = parseFloat(varr)
                if (temp >= -1.6 && temp <= 1.6) {
                    $.ajax({
                        type: 'GET',
                        url: "api/plane?r=" + varr,
                        dataType: "text",
                        success: function (response) {
                            var data = JSON.parse(response);
                            outputData = JSON.parse(response);
                            $('#sketch-holder2').html("");
                            myp5 = new p5(sketch2, document.getElementById('sketch-holder2'));
                        }
                    });
                }
            });
        });

        var sketch2 = function(p) {
            p.i = 0;
            p.array_angle = [];
            p.bg = p.loadImage('img/sky3.jpg');
            p.klapka = p.loadImage('img/sky3add.png');

            p.setup = function() {
                p.createCanvas(1000, 600);
                p.array_angle = (outputData.angle);
                p.angleMode(p.DEGREES);
            };

            p.draw = function() {
                p.background(p.bg);
                p.i++;

                p.translate(p.width / 2.1 , p.height / 1.695);

                if(p.i == p.array_angle.size || isNaN(p.array_angle[p.i] * 180/p.PI))
                    p.i=0;
                p.rotate( p.array_angle[p.i] * 180/p.PI);
                p.image(p.klapka, 0, 0, 98, 23);
            };

        };

    </script>
</head>
<style>
    .checkbox{
        margin-left: 1%;
    }
    .grid-container{
        text-align: center;
    }
</style>
<div class="background_1">
<?php require_once('widgets/nav.php'); ?>
</div>

<body>
<div class="grid-container">
    <h1 class="lang" key="plane-1"></h1>

    <p class="plane lang" key="plane-sim"></p>

    <div class="grid-item">
    <div id="form" class="plane" style="margin: auto; width: 235px; font-size: 25px"><br>
        <form action="javascript:void(0);">
            <label for="r (rad)">r: </label><input style="font-size: 25px" id="r" name="r" type="number" step="0.01" min="-1.6" max="1.6" required>
            <button style="font-size: 25px" id="submit" class="lang" key="set"></button>
        </form></div><br>
        <div class ="toAllign">
            <label class="leftAlign lang" for="vehicle1" key="animation"></label><br>
        <input type="checkbox" id="vehicle1" name="Animácia" value="animation" onclick="myFunction(1)" checked><br>
        </div>
        <div id="sketch-holder" style="margin: auto; width: 1000px"></div><br>
    </div>

    <div class="grid-item">
    <h1 class="klapka lang" key="plane-2"></h1>
    <p class="klapka lang" key="rear-flap"></p>
        <label class="leftAlign lang" for="vehicle2" key="chart"></label><br>
        <input type="checkbox" id="vehicle2" name="Graf" value="graph" onclick="myFunction(2)" checked>
        <br>
        <label class="leftAlign lang" for="vehicle3" key="an-to-chart"></label><br>
        <input type="checkbox" id="vehicle3" name="animacia2" value="graph" onclick="myFunction(3)" checked>
        <br><br>
    <div id="chart" class="klapka" style="margin: auto; width: 1000px"></div><br>
    <div id="sketch-holder2" style="margin: auto; width: 1000px"></div><br>
    </div>
</div>
<script>
    //Get the button
    var mybutton = document.getElementById("myBtn");

    // When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function() {scrollFunction()};

    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            mybutton.style.display = "block";
        } else {
            mybutton.style.display = "none";
        }
    }

    // When the user clicks on the button, scroll to the top of the document
    function topFunction() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }
</script>
<button onclick="topFunction()" id="myBtn" title="Go to top">↑</button>
<script>

    function myFunction(num) {
        var animation = document.getElementById("vehicle1");
        var animation2 = document.getElementById("vehicle3");
        var graph = document.getElementById("vehicle2");

        if(num === 1){
            if(animation.checked){
                //document.getElementById("sketch-holder").style.visibility = "visible";
                document.getElementById("sketch-holder").style.display = "block";
            }
            else{
                //document.getElementById("sketch-holder").style.visibility = "hidden";
                document.getElementById("sketch-holder").style.display = "none";
            }
        }else if (num === 2 ){
            if(graph.checked){
                //document.getElementById("chart").style.visibility = "visible";
                document.getElementById("chart").style.display = "block";
            }
            else{
                //document.getElementById("chart").style.visibility = "hidden";
                document.getElementById("chart").style.display = "none";
            }
        }else if( num === 3){
            if(animation2.checked){
                //document.getElementById("sketch-holder2").style.visibility = "visible";
                document.getElementById("sketch-holder2").style.display = "block";
            }
            else{
                //document.getElementById("sketch-holder2").style.visibility = "hidden";
                document.getElementById("sketch-holder2").style.display = "none";
            }
        }
    }
</script>

</body>
</html>