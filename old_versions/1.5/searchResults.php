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
				<div class=IRLogo><a href="http://ir.iit.edu" target="_blank"><img src="img/IR.gif" border="0"></a></div>
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
	<b><a href="index.php?destroy=true">New Search</a></b><br><br>
<?
include("sug_func.php");
$query=$_GET['query'];//Query entered by user
$checkQueryResultIsOK = checkQueryIsOK($query);//Passes query to func to see if its valid.
if($_GET['query']==null )//User didn't enter a query.
	echo "You must enter a query.";
else if(strlen($query)>100)//Users query is over 100 chars.
	echo "Sorry, your query is too long.  It must be shorter than 100 characters.";
else if($checkQueryResultIsOK==-1)//Users query contains invalid chars (see the functions.php file to view invalid chars)
	echo "Your query contains invalid characters. Please check your query and try again.";
else if($checkQueryResultIsOK==1)//Users query is OK, time to do stuff to it. 
{

	//Check to see if user query EXACTLY matches any Provinces
	$Pquery_result=queryDB('p','p',$_GET['query'].", ");
	$Pquery_num=mysql_num_rows($Pquery_result);
	//If it does, display the EXACT matches.
	if($Pquery_num>0)
	{
		$tmp = mysql_result($Pquery_result,$i,p);
		echo "We found the province <a href=\"index.php?p_drop=$tmp&destroy=true\">$tmp</a>";
	}

	//Check to see if user query EXACTLY matches any Districts
	$Oquery_result=queryDB('o','o',$_GET['query'].", ");
	$Oquery_num=mysql_num_rows($Oquery_result);
	//If it does, display the EXACT matches.
	if($Oquery_num>0)
	{
		$tmp = mysql_result($Oquery_result,$i,o);
		echo "We found district <a href=\"index.php?o_drop=$tmp&destroy=true\">$tmp</a>";
	}

	//Check to see if user query EXACTLY matches any Municipality
	$Mquery_result=queryDB('m','m',$_GET['query'].", ");
	$Mquery_num=mysql_num_rows($Mquery_result);
	//If it does, display the EXACT matches.
	if($Mquery_num>0)
	{
		$tmp = mysql_result($Mquery_result,$i,m);
		echo "We found municipality <a href=\"index.php?m_drop=$tmp&destroy=true\">$tmp</a>";
	}

	//Check to see if user query EXACTLY matches any Towns
	$Tquery_result=queryDB('t','t',$_GET['query'].", ");
	$Tquery_num=mysql_num_rows($Tquery_result);
	//If it does, display the EXACT matches.
	if($Tquery_num>0)
	{
		$tmp = mysql_result($Tquery_result,$i,id);
		echo "We found town <a href=\"index.php?t_drop=$tmp&destroy=true\">$tmp</a>";
	}

	/* If no EXACT matches to the users query were found, and the query is less than 4 chars, tell them we cannot help any further. */
	if(($Pquery_num+$Oquery_num+$Mquery_num+$Tquery_num==0) && strlen($_GET['query'])<4)
		echo "Sorry, your query was not found and we only support searching help for words 4 letter or longer.";
	/* Otherwise, if no EXACT matches were found, kick in assisted searching.  Try to find similar results to the users query */
	else if($Pquery_num+$Oquery_num+$Mquery_num+$Tquery_num==0)
	{
		/* ET stands for Error Threshold...this is set by the user in the drop-down menu when they enter a query.  
		 * The closer to 0 the ET becomes, the more accurately the user believes they spelled the word.
		 * See the function displaySearcher in functions.php for the numeric values.
		 * A higher ET should return more results, while a lower one should return less.
		 */
		$et = $_GET['et'];
		echo "Nothing in our database matches \"".$_GET['query']."\"<br /><br />Trying to Help...<br>";
		/* Start executing the search assisting methods */
		$list1=method1($_GET['query'],$et);
		$list3=method3($_GET['query'],$et);
		$list4=method4($_GET['query'],$et);
		$list5=method5($_GET['query'],$et);
		$list6=method6($_GET['query'],$et);
		$list7=method7($_GET['query'],$et);
	
		/* Merge all of the returned results into one array */
		$finalArray=array_merge($list1,$list3,$list4,$list5,$list6,$list7);

		/* If empty, nothing was found */
		if(sizeof($finalArray)==0)
			echo "Sorry, nothing similar was found either";
		else {
			$origArray=$finalArray;
			$origLen=count($origArray);
			$finalArray=(array_count_values($finalArray));
			arsort($finalArray);//Sort the array
//	reset($finalArray);
			echo "<br><br><ul>";
			/* Iterate through all of the results */
			while(list($key, $val) = each($finalArray))
			{
				/* Divide how many times the current result was found by the the number of results found.
				 * Then round the value and * it by 100 to make it a percent. */
				$percent= $val/$origLen; 
				$percent=round($percent*100);
				$tmp = findType($key);//Figures out what type (ie. Prov, Dist, etc) the current array value is
				if($tmp == 'p' || $tmp == 'o' || $tmp == 'm')
				{
					//Makes the output look better
					if($tmp == 'p')
						$type = "Province";
					if($tmp == 'o')
						$type = "District";
					if($tmp == 'm')
						$type = "Municipality";
					//If we have a 100% match, then we only found one matching result.
					if($percent==100)
						echo "<li>$type <a href='index.php?".$tmp."_drop=$key&destroy=true'>".$key."</a> is the only close match we found.<br>";
					//Otherwise display the current result with the percentage that the user meant this word.
					else
						echo "<li>$type <a href='index.php?".$tmp."_drop=$key&destroy=true'>".$key."</a> is ".$percent."% is likely to be your choice.<br>";
				}	else
				//Does the same as above, except for townships.
					if($percent==100)
						echo "<li>Town <a href='index.php?t_drop=$tmp&destroy=true'>".$key."</a> is the only close match we found.<br>";
					else
						echo "<li>Town <a href='index.php?t_drop=$tmp&destroy=true'>".$key."</a> is ".$percent."% likely to be your choice.<br>";
			}
			echo "</ul>";
		}
	}
}

?>
<br><br><br><br>
If your word was not found, increase the spelling accuracy drop-down menu and search again below.
<br>
<? displaySearcher(); ?>
</div> <!-- Close query_content style -->
<? footer(); ?>
</div> <!-- Close main style -->
</body>
</html>
