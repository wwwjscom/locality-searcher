<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en-US" xml:lang="en-US">
<head>
<title>U.S.H.M.M. Searcher</title>
<link rel="stylesheet" type="text/css" href="style.css">
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>search</title>
</head>

<body bgcolor="grey">

<div class="main">
        <div class="masthead">
        <a href="index.php?destroy=true"><img src="img/masthead.png" border="0"></a>
        </div>
        <div class="query_content">
        <br /><br /><br />

<table border="0" align="center" width="642" cellpadding="0" cellspacing="0">
<tr>
	<td align="center">
	</td>
</tr>
<tr>
	<td align="left">
	<b><a href="index.php?destroy=true">Start Over</a></b><br><br>
<?
include("sug_func.php");

if($_GET['query']==null)
	echo "You must enter a query.";
else {

	$Pquery_result=queryDB('p','p',$_GET['query'].", ");
	$Pquery_num=mysql_num_rows($Pquery_result);
	if($Pquery_num>0)
	{
		$tmp = mysql_result($Pquery_result,$i,p);
		echo "We found <a href=\"index.php?p_drop=$tmp&destroy=true\">$tmp</a>";
	}

	$Oquery_result=queryDB('o','o',$_GET['query'].", ");
	$Oquery_num=mysql_num_rows($Oquery_result);
	if($Oquery_num>0)
	{
		$tmp = mysql_result($Oquery_result,$i,o);
		echo "We found <a href=\"index.php?o_drop=$tmp&destroy=true\">$tmp</a>";
	}

	$Mquery_result=queryDB('m','m',$_GET['query'].", ");
	$Mquery_num=mysql_num_rows($Mquery_result);
	if($Mquery_num>0)
	{
		$tmp = mysql_result($Mquery_result,$i,m);
		echo "We found <a href=\"index.php?m_drop=$tmp&destroy=true\">$tmp</a>";
	}

	$Tquery_result=queryDB('t','t',$_GET['query'].", ");
	$Tquery_num=mysql_num_rows($Tquery_result);
	if($Tquery_num>0)
	{
		$tmp = mysql_result($Tquery_result,$i,id);
		echo "We found <a href=\"index.php?t_drop=$tmp&destroy=true\">$tmp</a>";
	}

if(strlen($_GET['query'])<4)
	echo "Sorry, your query was not found and we only support searching help for words 4 letter or longer.";
else if($Pquery_num+$Oquery_num+$Mquery_num+$Tquery_num==0)
{
	$et = $_GET['et'];
	echo "Nothing in our database matches ".$_GET['query']."<br>Trying to Help...<br>";
	$list1=method1($_GET['query'],$et);
	$list3=method3($_GET['query'],$et);
	$list4=method4($_GET['query'],$et);
	$list5=method5($_GET['query'],$et);
	$list6=method6($_GET['query'],$et);
	$list7=method7($_GET['query'],$et);

	$finalArray=array_merge($list1,$list3,$list4,$list5,$list6,$list7);

	if(sizeof($finalArray)==0)
		echo "Sorry, nothing similar was found either";
	else {
	$origArray=$finalArray;
	$origLen=count($origArray);
	$finalArray=(array_count_values($finalArray));
	arsort($finalArray);
//	reset($finalArray);
	echo "<br><br><ul>";
	while(list($key, $val) = each($finalArray))
	{
		$percent= $val/$origLen;
		$percent=round($percent*100);
		$tmp = findType($key);
		if($tmp == 'p' || $tmp == 'o' || $tmp == 'm')
		{
			if($tmp == 'p')
				$type = "Province";
			if($tmp == 'o')
				$type = "District";
			if($tmp == 'm')
				$type = "Municipality";
			echo "<li>$type <a href='index.php?".$tmp."_drop=$key&destroy=true'>".$key."</a> is ".$percent."% is likely to be your choice.<br>";
		}	else
			echo "<li>Town <a href='index.php?t_drop=$tmp&destroy=true'>".$key."</a> is ".$percent."% likely to be your choice.<br>";
	}
	echo "</ul>";
	}
}
}
?>
<br><br><br><br>
You may change your search settings if you couldn't find your word.
<br><br>
<? displaySearcher(); ?>
</div> <!-- Close query_content style -->
<? footer(); ?>
</div> <!-- Close main style -->
</body>
</html>
