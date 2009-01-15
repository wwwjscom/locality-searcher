<?

function openConn() {
	$username="ushmm";
	$password="ushmm"; 
	$database="ushmm";

	mysql_connect(localhost,$username,$password);
	@mysql_select_db($database) or die("Error");
}


/*
 * $cat: The field to select from
 * $prev_cat: Used when we need to use the WHERE statement
 * $term: The row selected by the user
 * $selectID: Boolean; if true it changes the select statement below -- Selects the ID instead of the row data
*/
function display($cat, $prev_cat, $term, $selectID) {
	openConn();

	if($term!=null)//Term is only set when we need use a WHERE...term is the what was selected prev 
		#$query="SELECT id,$cat FROM geo_locale WHERE $prev_cat = \"$term\" ORDER BY $cat";
//		$query="SELECT id,$cat FROM test WHERE $prev_cat = \"$term\" ORDER BY $cat";
	switch($prev_cat)
	{
		case 'p':
			$query="SELECT o FROM locality WHERE p = '$term' ORDER BY $cat";
		break;
			
		case 'o':
			$p=$_SESSION['p_drop'];
			$query="SELECT m FROM locality WHERE p = '$p' AND o = '$term' ORDER BY $cat";
		break;

		case 'm':
			$p = $_SESSION['p_drop'];
			$o = $_SESSION['o_drop'];
			$query="SELECT t FROM locality WHERE p = '$p' AND o = '$o' AND m = '$term' ORDER BY $cat";
		break;
	}
	else
		$query="SELECT $cat FROM locality ORDER BY $cat";

	$query_result=mysql_query($query);
	$query_num=mysql_num_rows($query_result);
	mysql_close();

	$chosenList = array();//NOT NEEDED?

	echo "<SELECT NAME='".$cat."_drop'>";
	echo "<OPTION VALUE='NoNotMe'>";
	$next=null;//Setting next to null as to display first row found
	if($selectID=="no")//If selecting the row info, not the id
	{
		for($i=0;$i<$query_num;$i++)
		{
			$dis = mysql_result($query_result,$i,$cat);

			if($dis == "X")
				$dis = "No Jewish Census Information";
				

			if(!in_array($dis,$chosenList) && $dis !=  "")
			{
				array_push($chosenList,$dis);
				echo "<OPTION VALUE='".trim(mysql_result($query_result,$i,$cat),",")."'>".trim($dis,", ")."";//Trim off the , at the end of the data
			}
			if($i+1<$query_num)//If not at the end of the rows, select the next to prevent duplicated
				$next = mysql_result($query_result,$i+1,$cat);
		}	
	}
	//DEBUG
	//THIS WHOLE IF IS DEPRECATED???
	else if($selectID=="yes") //SELECT ID Instead of the cat name (Used when we are selecting the final result and need to pull up the matching id in reel_locale
	{
		for($i=0;$i<$query_num;$i++)
		{
			$dis = mysql_result($query_result,$i,$cat);

			if($dis == "X")
				$dis = "No Jewish Census Information";

			if(!in_array($dis,$chosenList) && $dis !=  "")
			{
				array_push($chosenList,$dis);
				echo "<OPTION VALUE='".trim(mysql_result($query_result,$i,id),",")."'>".trim($dis,", ")."";
			}
		}
	}
	echo"</SELECT>";
}


/* Simple footer echo */
function footer() 
{
	echo <<<END
	<div class="footer">
	Developed by Jason Soo and Jordan Wilberding. &nbsp;Courtesy of the IIT IR Laboratory.<br>
	E-Mail problems to <a class="footer" href="mailto:ushmm@ir.iit.edu">ushmm@ir.iit.edu</a>
	</div>
END;
}

/*
 * This is used when the user uses the search function.
 * It figures out what the user selected (ie. Prov or Dist, etc)
 * And then presets all fields before that to "Preset by Sercher"
 */
function checkSet()
{
	if($_GET['o_drop']!="" && !isset($_SESSION['p_drop']))
	{
		$loc = find_parents($_GET['o_drop'],'o');
		//$_SESSION['p_drop']="Preset by Searcher";
		$_SESSION['p_drop']="$loc[0]";
	}
	if($_GET['m_drop']!="" && !isset($_SESSION['o_drop']))
	{
		$loc = find_parents($_GET['m_drop'],'m');
		$_SESSION['p_drop']="$loc[0]";
		$_SESSION['o_drop']="$loc[1]";
	}
	if($_GET['t_drop']!="" && !isset($_SESSION['m_drop']))
	{
		$loc = find_parents($_GET['t_drop'],'t');
		$_SESSION['p_drop']="$loc[0]";
		$_SESSION['o_drop']="$loc[1]";
		$_SESSION['m_drop']="$loc[2]";
	}

}


function find_parents($term,$type)
{
//	echo "Term: $term<br><br>Type: $type<br><br>";
	openConn();

	switch($type)
	{
		case 'o':
			$query="SELECT p from locality WHERE o = '$term'";
			$query_result=mysql_query($query);
			$num=mysql_num_rows($query_result);
			$loc[0] = mysql_result($query_result,0);
		break;

		case 'm':
			$query="SELECT * from locality WHERE m = '$term'";
			$query_result=mysql_query($query);
			$num=mysql_num_rows($query_result);
			$loc[0] = mysql_result($query_result,0,p);
			$loc[1] = mysql_result($query_result,0,o);
		break;

		case 't':
			$query="SELECT * from locality WHERE t = '$term'";
			$query_result=mysql_query($query);
			$num=mysql_num_rows($query_result);
			$loc[0] = mysql_result($query_result,0,p);
			$loc[1] = mysql_result($query_result,0,o);
			$loc[2] = mysql_result($query_result,0,m);
		break;
	}
			

	mysql_close();
	return $loc;
}

/*
 * Displays a functional serch box for the user to use.
 * Also inclused the selection box asking how accurate they spelled the word.
 */
function displaySearcher()
{
	$q = $_GET['query'];
	echo <<<END
	<br />
	<br />
	Searcher:
	<br>
	<form action="searchResults.php" method="get">
	<input type="text" value="$q" name="query">
	<br>
	Spelling Accuracy:
	<br>
	<SELECT name="et">
	<OPTION value="1">Very Close
	<OPTION value="2">Close
	<OPTION value="3" SELECTED>Somewhat
	<OPTION value="4">Off
	<OPTION value="5">Way Off
	<OPTION value="6">Nowhere Near Correct
	</SELECT>
	<br><br>
	<input type="submit" value="Search">
	</form>
END;
}

/*
 * Checks a search query entered by the user...
 * If it doesn't include any of the following 
 * The queyr is ok, and returns 1...
 * Otherwise the query is invalid and returns -1
 */
function checkQueryIsOK($query)
{
//	echo strpos($query,'%');
	if(strpos($query,'%')!==false)
		return -1;
	if(strpos($query,'_')!==false)
		return -1;
	if(strpos($query,'<')!==false)
		return -1;
	if(strpos($query,'>')!==false)
		return -1;
	if(strpos($query,'*')!==false)
		return -1;
	if(strpos($query,'(')!==false)
		return -1;
	if(strpos($query,')')!==false)
		return -1;
	if(strpos($query,'$')!==false)
		return -1;
	if(strpos($query,'!')!==false)
		return -1;
	if(strpos($query,'@')!==false)
		return -1;
	if(strpos($query,'#')!==false)
		return -1;
	if(strpos($query,'^')!==false)
		return -1;
	if(strpos($query,'=')!==false)
		return -1;
	if(strpos($query,'+')!==false)
		return -1;
	if(strpos($query,'"')!==false)
		return -1;
	if(strpos($query,'`')!==false)
		return -1;
	if(strpos($query,'{')!==false)
		return -1;
	if(strpos($query,'}')!==false)
		return -1;
	if(strpos($query,'[')!==false)
		return -1;
	if(strpos($query,']')!==false)
		return -1;
	if(strpos($query,'/')!==false)
		return -1;
	if(strpos($query,'\\')!==false)
		return -1;
	if(strpos($query,'?')!==false)
		return -1;
	if(strpos($query,';')!==false)
		return -1;
	if(strpos($query,':')!==false)
		return -1;
	else
		return 1;
}
?>
