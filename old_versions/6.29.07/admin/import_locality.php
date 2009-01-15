<?php
session_start();
include("admin_functions.php");
include("../functions.php");
checkLoggedIn();
disHeader();
openConn();
echo $filename;
$row = 1;
$handle = fopen("".$_GET['yourfiles']."", "r");
while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
	$num = count($data);
  echo "<p> $num fields in line $row: <br /></p>\n";
  $row++;
  for ($c=0; $c < $num; $c=$c+5) {
		$query = "INSERT INTO locality (id,p,h,o,m,t) VALUES(\"".$data[$c]."\",\"".$data[$c+1]."\",\"".$data[$c+2]."\",\"".$data[$c+3]."\",\"".$data[$c+4]."\",\"".$data[$c+5]."\")";
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
