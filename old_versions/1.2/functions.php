<?

function openConn() {
	$username="root";
//	$password="sa"; 
	$password="ja8son";
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
		$query="SELECT id,$cat FROM geo_locale WHERE $prev_cat = \"$term\"";
	else
		$query="SELECT id,$cat FROM geo_locale";

	$query_result=mysql_query($query);
	$query_num=mysql_num_rows($query_result);
	mysql_close();

	$chosenList = array();

	echo "<SELECT NAME='".$cat."_drop'>";
	echo "<OPTION VALUE='NoNotMe'>";
	$next=null;//Setting next to null as to display first row found
	if($selectID=="no")//If selecting the row info, not the id
	{
		for($i=0;$i<$query_num;$i++)
		{
			$dis = mysql_result($query_result,$i,$cat);
			if($dis == "") //If selection is blank replace with (Unknown)
				$dis = "(Unknown)";
			if(!in_array($dis,$chosenList)  || $dis ==  "(Unknown)")
			{
				array_push($chosenList,$dis);
				echo "<OPTION VALUE='".trim(mysql_result($query_result,$i,$cat),",")."'>".trim($dis,", ")."";//Trim off the , at the end of the data
			}
			if($i+1<$query_num)//If not at the end of the rows, select the next to prevent duplicated
				$next = mysql_result($query_result,$i+1,$cat);
		}	
	}
	else if($selectID=="yes") //SELECT ID Instead of the cat name (Used when we are selecting the final result and need to pull up the matching id in reel_locale
	{
		for($i=0;$i<$query_num;$i++)
		{
			$dis = mysql_result($query_result,$i,$cat);
			if($dis == "")
				$dis = "(Unknown)";
			if(!in_array($dis,$chosenList)  || $dis ==  "(Unknown)")
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
	echo "<div class=\"footer\">";
	echo "(C) ...";
	echo "</div>";
}