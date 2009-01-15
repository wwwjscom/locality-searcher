<?
session_start();
include("functions.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
<head>
<title>Search Results</title>
<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="main">
        <div class="masthead">
        <a href="index.php?destroy=true"><img src="img/masthead.png" border="0"></a>
        </div>
<div class="query_content">
<br><br>
<br><br>
<?
openConn();
$id = $_SESSION['t_drop'];
$query="SELECT * from reel_locale WHERE id = $id";
$query_result=mysql_query($query);
$num=mysql_num_rows($query_result);
mysql_close();

echo mysql_result($query_result,0,h)."<br>";
?>
<br>
<b><a href="index.php?destroy=true">Search again</a></b>
<br><br>
<br><br>
</div>
<? footer(); ?>
</div>
</body>
</html>
