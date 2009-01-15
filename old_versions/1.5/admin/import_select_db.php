<?
session_start();
include("admin_functions.php");
checkLoggedIn();
disHeader();
?>
<!--
<form method="post" action="import_.php">
<input type="hidden" name="filename" value="<? $_POST['filename']?>">
<select name="adb">
<option name="db" value="geo">Import to geo_locale (the selection DB)</option>
<option name="db" value="reel">Import to reel_locale (the results DB)</option>
</select>
<br>
<i>nput type="submit" value="Go!">
-->

<form method="post">
<input type="test" name="yourfiles" value="<? echo $_POST['yourfiles']?>">
</form>

<a href="import_geo.php?yourfiles=<? echo $_POST['yourfiles']?>">Import file to the geo_locale database (the selection DB)</a><br><br><br>
<a href="import_reel.php?yourfiles=<? echo $_POST['yourfiles']?>">Import file to the reel_locale database (the results DB)</a>
