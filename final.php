
<form method="post" action="exportcsv.php" enctype="multipart/form-data" >
    <p>Please select what you want to download:</p>

    <input type="radio" id="loc1" name="dbtarget" value="User" >
    <label for="loc1">User</label><br>
    <input type="radio" id="loc3" name="dbtarget" value="Queries">
    <label for="loc3">Queries</label><br>
    <input type="radio" id="loc4" name="dbtarget" value="Statistics">
    <label for="loc4">Statistics</label><br>
    <input type="radio" id="loc5" name="dbtarget" value="Registracia">
    <label for="loc5">Registracia</label><br>


    <input type="radio" id="loc2" name="dbtarget" value="Prihlasenia">
    <label for="loc2">Prihlasenia</label><br>

    <br>
    <input type="submit" value="Submit">
</form>


