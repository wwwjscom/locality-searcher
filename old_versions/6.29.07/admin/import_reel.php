<?php
session_start();
include("admin_functions.php");
checkLoggedIn();
disHeader();
openConn();
$row = 1;
$handle = fopen("".$_POST['filename']."", "r");
while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
	$num = count($data);
  echo "<p> $num fields in line $row: <br /></p>\n";
  $row++;
  for ($c=0; $c < $num; $c=$c+2) {
		$query = "INSERT INTO reel_locale (id,h) VALUES(\"".$data[$c]."\",\"".$data[$c+1]."\")";
		echo $query."<br>";
		mysql_query($query);
		if(mysql_error())
			echo "mysql error";
		else
			echo "INSERTED!";
	}
}
fclose($handle);

mysql_close();
?> 
