<?
include("functions.php");

/*
 * Querys the DB.
 */
function queryDB($x,$y,$z)
{
	openConn();
	$query="SELECT $x FROM geo_locale WHERE $y LIKE \"$z\"";
	$query_result=mysql_query($query);
	mysql_close();
	return $query_result;
}

function showResults($query_num,$query_result,$field)
{
	$chosenList = array();
	for($i=0;$i<$query_num;$i++)
	{
		$dis = mysql_result($query_result,$i,$field);
		if(!in_array($dis,$chosenList))
		{
			array_push($chosenList,$dis);
		}
	}
	return $chosenList;
}


/*
 * Used to figure out the type (ie. Prov, Dist, etc) of a given value
*/
function findType($x)
{
	openConn();

	$query_p="SELECT * FROM geo_locale WHERE p = \"$x\"";
	$query_o="SELECT * FROM geo_locale WHERE o = \"$x\"";
	$query_m="SELECT * FROM geo_locale WHERE m = \"$x\"";
	$query_t="SELECT id FROM geo_locale WHERE t = \"$x\"";

	$p_result=mysql_query($query_p);
	$p_rows=mysql_num_rows($p_result);

	$o_result=mysql_query($query_o);
	$o_rows=mysql_num_rows($o_result);

	$m_result=mysql_query($query_m);
	$m_rows=mysql_num_rows($m_result);

	$t_result=mysql_query($query_t);
	$t_rows=mysql_num_rows($t_result);
	mysql_close();

	if($p_rows>0)
		return "p";
	if($o_rows>0)
		return "o";
	if($m_rows>0)
		return "m";
	if($t_rows>0)
		return mysql_result($t_result,0,id);

}


/*
 * This function cuts off one letter at a time from the start and end of the search term...
 * It then re-searches using the new term.  It continues to do so until the ET is reached,
 * Or the term has become too small to cut off more letters.
 * Example:
 * %Slovakia%
 * %lovaki%
 * %ovak%
 * etc
*/
function method1($query,$et)
{

	$newstr = $query;
	$len = strlen($query);
	
	for($i=0;$i<$et;$i++)
		//Prevents running the function is the query is too short, or the ET has been rearched.
		if(strlen($newstr)>3 && ($query_num<1 || $et>$i))
		{
			$newstr = substr_replace(substr_replace($newstr, '', 0,1), '', -1,$len);	
			$newstr = "%$newstr%";

			$Pquery_result=queryDB('p','p',"$newstr");
			$Pquery_num=mysql_num_rows($Pquery_result);

			$Oquery_result=queryDB('o','o',$newstr);
			$Oquery_num=mysql_num_rows($Oquery_result);

			$Mquery_result=queryDB('m','m',$newstr);
			$Mquery_num=mysql_num_rows($Mquery_result);

			$Tquery_result=queryDB('t','t',$newstr);
			$Tquery_num=mysql_num_rows($Tquery_result);

			$list1=array();
			$list2=array();
			$list3=array();
			$list4=array();

			if($Pquery_num>0)
				$list1 = showResults($Pquery_num,$Pquery_result,'p');
			else if($Oquery_num>0)
				$list2=showResults($Oquery_num,$Oquery_result,'o');
			else if($Mquery_num>0)
				$list3=showResults($Mquery_num,$Mquery_result,'m');
			else if($Tquery_num>0)
				$list4=showResults($Tquery_num,$Tquery_result,'t');

		}else{
			echo "Error Threshold Reached";
		}
		return array_merge($list1,$list2,$list3,$list4);
}

/*
 * This function replaces the middle of the search term with %'s
 * MySQL views %'s "match anything".  The function then re-searches
 * The database using the new query until either the ET is reached,
 * Or until the query is too short to continue dividing.
 * Example:
 * %Slovakia%
 * %Slov%kia%
 * %Slo%ia%
 * etc
 */
function method3($query,$et)
{

	$newstr = $query;
	$len = strlen($query);
	$origLen = $len;
	
	$newstr = "$newstr, ";//REQURED due to the format of the current database...all entries include a , and space at the end...for some reason...
	for($i=0;$i<$et;$i++)
		//Prevents running the function is the query is too short, or the ET has been rearched.
		if(strlen($newstr)>3 && ($query_num<1 || $et>$i))
		{
			$newstr = substr_replace($newstr, '%', ($len/2),2);	
			if($i%2==0)
				$len = $len+1;
			else
				$len = ($origLen/2)-1;

			$Pquery_result=queryDB('p','p',"$newstr");
			$Pquery_num=mysql_num_rows($Pquery_result);

			$Oquery_result=queryDB('o','o',$newstr);
			$Oquery_num=mysql_num_rows($Oquery_result);

			$Mquery_result=queryDB('m','m',$newstr);
			$Mquery_num=mysql_num_rows($Mquery_result);

			$Tquery_result=queryDB('t','t',$newstr);
			$Tquery_num=mysql_num_rows($Tquery_result);

			$list1=array();
			$list2=array();
			$list3=array();
			$list4=array();

			if($Pquery_num>0)
				$list1=showResults($Pquery_num,$Pquery_result,'p');
			else if($Oquery_num>0)
				$list2=showResults($Oquery_num,$Oquery_result,'o');
			else if($Mquery_num>0)
				$list3=showResults($Mquery_num,$Mquery_result,'m');
			else if($Tquery_num>0)
				$list4=showResults($Tquery_num,$Tquery_result,'t');

		}else{
			echo "Error Threshold Reached";
		}
		return array_merge($list1,$list2,$list3,$list4);
}

/*
 * This function divides the query in 1/2 and cuts off the front 1/2.
 * It only adds %'s to the BEGINING of the word.  The END of the word
 * MUST remain untouched due to the way the database it set up.
 * Exmaple:
 * %Slovakia%
 * %akia,     <-- COMMA and SPACE must be at the END!!
 */
function method4($query,$et)
{

	$newstr = $query;
	$len = strlen($query);
	
	if(strlen($newstr)>3 && ($query_num<1 || $et>$i))
	{
		$newstr = substr_replace($newstr, '%', 0,$len/2);	
		$newstr = "$newstr, ";//REQURED due to the format of the current database...all entries include a , and space at the end...for some reason...

		$Pquery_result=queryDB('p','p',"$newstr");
		$Pquery_num=mysql_num_rows($Pquery_result);

		$Oquery_result=queryDB('o','o',$newstr);
		$Oquery_num=mysql_num_rows($Oquery_result);

		$Mquery_result=queryDB('m','m',$newstr);
		$Mquery_num=mysql_num_rows($Mquery_result);

		$Tquery_result=queryDB('t','t',$newstr);
		$Tquery_num=mysql_num_rows($Tquery_result);

		$list1=array();
		$list2=array();
		$list3=array();
		$list4=array();

		if($Pquery_num>0)
			$list1=showResults($Pquery_num,$Pquery_result,'p');
		else if($Oquery_num>0)
			$list2=showResults($Oquery_num,$Oquery_result,'o');
		else if($Mquery_num>0)
			$list3=showResults($Mquery_num,$Mquery_result,'m');
		else if($Tquery_num>0)
			$list4=showResults($Tquery_num,$Tquery_result,'t');

	}else{
		echo "Error Threshold Reached";
	}

	return array_merge($list1,$list2,$list3,$list4);
}

/*
 * Same as above function, but keeps the latter 1/2 of the query.
 * However, a percent SHOULD be put at the end of the query and NOT
 * at the begining of the query.
 * Example:
 * %Slovakia%
 * Slov%
 */
function method5($query,$et)
{

	$newstr = $query;
	$len = strlen($query);
	
	if(strlen($newstr)>3 && ($query_num<1 || $et>$i))
	{
		$newstr = substr_replace($newstr, '%', $len/2,$len);	
		$newstr = "$newstr, ";//REQURED due to the format of the current database...all entries include a , and space at the end...for some reason...

		$Pquery_result=queryDB('p','p',"$newstr");
		$Pquery_num=mysql_num_rows($Pquery_result);

		$Oquery_result=queryDB('o','o',$newstr);
		$Oquery_num=mysql_num_rows($Oquery_result);

		$Mquery_result=queryDB('m','m',$newstr);
		$Mquery_num=mysql_num_rows($Mquery_result);

		$Tquery_result=queryDB('t','t',$newstr);
		$Tquery_num=mysql_num_rows($Tquery_result);

		$list1=array();
		$list2=array();
		$list3=array();
		$list4=array();

		if($Pquery_num>0)
			$list1=showResults($Pquery_num,$Pquery_result,'p');
		else if($Oquery_num>0)
			$list2=showResults($Oquery_num,$Oquery_result,'o');
		else if($Mquery_num>0)
			$list3=showResults($Mquery_num,$Mquery_result,'m');
		else if($Tquery_num>0)
			$list4=showResults($Tquery_num,$Tquery_result,'t');

	}else{
		echo "Error Threshold Reached";
	}

	return array_merge($list1,$list2,$list3,$list4);
}

/*
 * This function cuts everything out of the middle of the query...
 * Only leaving the first and last letters.  It replaces the
 * chars in the middle of the query wiht a %.  The end of the query
 * Must contain a , and space.
 * Example:
 * Slovakia
 * S%a,    <-- SPACE!!!
 */
function method6($query,$et)
{

	$newstr = $query;
	$len = strlen($query);

	if(strlen($newstr)>3 && ($query_num<1 || $et>$i))
	{
		$newstr = substr_replace($newstr, '%', 1,$len-2);	
		$newstr = "$newstr, ";//REQURED due to the format of the current database...all entries include a , and space at the end...for some reason...

		$Pquery_result=queryDB('p','p',"$newstr");
		$Pquery_num=mysql_num_rows($Pquery_result);

		$Oquery_result=queryDB('o','o',$newstr);
		$Oquery_num=mysql_num_rows($Oquery_result);

		$Mquery_result=queryDB('m','m',$newstr);
		$Mquery_num=mysql_num_rows($Mquery_result);

		$Tquery_result=queryDB('t','t',$newstr);
		$Tquery_num=mysql_num_rows($Tquery_result);

		$list1=array();
		$list2=array();
		$list3=array();
		$list4=array();

		if($Pquery_num>0)
			$list1=showResults($Pquery_num,$Pquery_result,'p');
		else if($Oquery_num>0)
			$list2=showResults($Oquery_num,$Oquery_result,'o');
		else if($Mquery_num>0)
			$list3=showResults($Mquery_num,$Mquery_result,'m');
		else if($Tquery_num>0)
			$list4=showResults($Tquery_num,$Tquery_result,'t');

	}else{
		echo "Error Threshold Reached";
	}

	return array_merge($list1,$list2,$list3,$list4);
}

/*
 * Same as above, but it keeps the last two AND first two
 * chars of the query.
 * Example:
 * Slovakia
 % Sl%ia,  <-- SPACE!!!
 */
function method7($query,$et)
{

	$newstr = $query;
	$len = strlen($query);
	
	if(strlen($newstr)>3 && ($query_num<1 || $et>$i))
	{
		$newstr = substr_replace($newstr, '%', 2,$len-4);	
		$newstr = "$newstr, ";//REQURED due to the format of the current database...all entries include a , and space at the end...for some reason...

		$Pquery_result=queryDB('p','p',"$newstr");
		$Pquery_num=mysql_num_rows($Pquery_result);

		$Oquery_result=queryDB('o','o',$newstr);
		$Oquery_num=mysql_num_rows($Oquery_result);

		$Mquery_result=queryDB('m','m',$newstr);
		$Mquery_num=mysql_num_rows($Mquery_result);

		$Tquery_result=queryDB('t','t',$newstr);
		$Tquery_num=mysql_num_rows($Tquery_result);

		$list1=array();
		$list2=array();
		$list3=array();
		$list4=array();

		if($Pquery_num>0)
			$list1=showResults($Pquery_num,$Pquery_result,'p');
		else if($Oquery_num>0)
			$list2=showResults($Oquery_num,$Oquery_result,'o');
		else if($Mquery_num>0)
			$list3=showResults($Mquery_num,$Mquery_result,'m');
		else if($Tquery_num>0)
			$list4=showResults($Tquery_num,$Tquery_result,'t');

	}else{
		echo "Error Threshold Reached";
	}

	return array_merge($list1,$list2,$list3,$list4);
}
?>
