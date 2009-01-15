<?
/* Start/Continue Session..MUST BE THE FIRST LINE!!! */
session_start();
include("functions.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
<head>
<title>Search Results</title>
<link rel="stylesheet" type="text/css" href="style.css">
<script type="text/javascript" src="ajax/autosuggest2.js"></script>
<script type="text/javascript" src="ajax/remotesuggestions.js"></script>
<link rel="stylesheet" type="text/css" href="ajax/autosuggest.css" />        
</head>
<body>
<div class="main">
        <div class="masthead">
				<div class=IRLogo><a href="http://ir.iit.edu" target="_blank"><img src="img/IR.gif" alt="IR Logo"  border="0"></a></div>
        <a href="index.php?destroy=true"><img src="img/masthead.png" alt="USHMM Search Page Header" border="0"></a>
        </div>
<div class="query_content">
<br><br>
<br><br>
<?
openConn();
$p = $_SESSION['p_drop'];
$o = $_SESSION['o_drop'];
$m = $_SESSION['m_drop'];
$t = $_SESSION['t_drop'];
//$query="SELECT * from test WHERE id = $id";
//$query="SELECT * from test WHERE t = '$id'";
$query="SELECT * from locality WHERE p='$p' AND o='$o' AND m='$m' AND t = '$t'";
$query_result=mysql_query($query);
$num=mysql_num_rows($query_result);
mysql_close();


if($num<1)
{
	echo "Error: Result not found!<br>";
	foreach($_SESSION as $key => $val)
		echo "$key:$val<br>";
}else if($num==1)
		echo mysql_result($query_result,0,h)."<br>";
else {
	echo "Found more than one result:<br>";
	for($i=0;$i<$num;$i++)
		echo mysql_result($query_result,$i,h)."<br>";
}
?>
<br>
<b><a href="index.php?destroy=true">New Search</a></b>
<br><br>
<br><br>
</div>
<? footer(); ?>
</div>
</body>
</html>
