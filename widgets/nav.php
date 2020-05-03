<?php
require_once('phpconfig/userses.php');
?>


<div class="topnav">
    <a href="about.php" class="lang" key="about-menu"></a>
    <a href="documentation.php" class="lang" key="doc-menu"></a>
    <a href="team.php" class="lang" key="team-menu"></a>
    <img src="img/sk-icon.png" width="30px height:30px" class="translate" id="sk">
    <img src="img/en-icon.png" width="30px height:30px" class="translate" id="en">



    <?php if (isLogged()) : ?>
        <a href="#" style="float:right" class="lang act" key="user-menu"></a>
        <a style="float:right" href="?action=logout" class="lang" key="signout-menu"></a>
    <?php else : ?>
        <a style="float:right" class="act lang" key="login-menu" href="index.php"></a>
        <a style="float:right" href="registration.php" class="lang" key="register-menu"></a>
    <?php endif; ?>
</div>



<?php if (isLogged()) : ?>
    <div class="hopnav">
        <a href="#" class="lang" key="stats-menu" id="statsBut"></a>
        <a href="#" class="lang" key="set-menu" id="setBut"></a>
        <div class="dropdown">
            <button href="#" class="dropbtn">
                <span class="lang" key="sim-menu"></span><i class="fa fa-caret-down"></i>
            </button>
            <div class="dropdown-content">
                <a href="kyvadlo.php" class="lang" key="s2"></a>
                <a href="gulocka.php" class="lang" key="s3"></a>
                <a href="tlmenie.php" class="lang" key="s1"></a>
                <a href="lietadlo.php" class="lang" key="s4"></a>
            </div>
        </div>
        <a style="float:right"><?php echo $_SESSION["login"]; ?></a>
        <img style="float:right" src="img/profileicon.png" width="40px" height="40px">
        <a href="#" style="float:right" class="lang act" key="user-menu"></a>
    </div>

<?php endif; ?>