<?
include("functions.php");

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
					echo "<br>===== $field ====";
					echo "<ul>";
					$chosenList = array();
					for($i=0;$i<$query_num;$i++)
					{
						$dis = mysql_result($query_result,$i,$field);
						if(!in_array($dis,$chosenList))
						{
							array_push($chosenList,$dis);
							echo "<li>Perhaps you meant ".$dis."?";
						}
					}
					echo "</ul>";
					return $chosenList;
}


/* $et: Error Threshold */
function method1($query,$et)
{

	$newstr = $query;
	$len = strlen($query);
	/*
	DEBUG
	echo $len;
		echo "<br>";
	echo $query;
	*/
	
	if($len>=5)
		for($i=0;$i<$et;$i++)
			if(strlen($newstr)>3 && ($query_num<1 || $et>$i))
			{
				$newstr = substr_replace(substr_replace($newstr, '', 0,1), '', -1,$len);	
				$newstr = "%$newstr%";
				//echo $newstr; DEBUG

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
				{
					$list1 = showResults($Pquery_num,$Pquery_result,'p');
				}else if($Oquery_num>0)
				{
					$list2=showResults($Oquery_num,$Oquery_result,'o');
				}else if($Mquery_num>0)
				{
					$list3=showResults($Mquery_num,$Mquery_result,'m');
				}else if($Tquery_num>0)
				{
					$list4=showResults($Tquery_num,$Tquery_result,'t');
				}
			}else if(strlen($newstr)<=3){
				echo "<br>Too Short";
			}else{
				echo "Error Threshold Reached";
			}
			return array_merge($list1,$list2,$list3,$list4);
}




function method3($query,$et)
{

	$newstr = $query;
	$len = strlen($query);
	$origLen = $len;
	/*
	DEBUG
	echo $len;
		echo "<br>";
	echo $query;
	*/
	
	$newstr = "$newstr, ";
	if($len>=5)
		for($i=0;$i<$et;$i++)
			if(strlen($newstr)>3 && ($query_num<1 || $et>$i))
			{
				//$newstr = substr_replace($newstr, '%', $len/2,($len/2)+1);	
//				$newstr = substr_replace($newstr, '%', ($len/2),1);	
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
				{
					$list1=showResults($Pquery_num,$Pquery_result,'p');
				}else if($Oquery_num>0)
				{
					$list2=showResults($Oquery_num,$Oquery_result,'o');
				}else if($Mquery_num>0)
				{
					$list3=showResults($Mquery_num,$Mquery_result,'m');
				}else if($Tquery_num>0)
				{
					$list4=showResults($Tquery_num,$Tquery_result,'t');
				}
			}else if(strlen($newstr)<=3){
				echo "<br>Too Short";
			}else{
				echo "Error Threshold Reached";
			}
			return array_merge($list1,$list2,$list3,$list4);
}







function method4($query,$et)
{

	$newstr = $query;
	$len = strlen($query);
	/*
	DEBUG
	echo $len;
		echo "<br>";
	echo $query;
	*/
	
	if($len>=5)
			if(strlen($newstr)>3 && ($query_num<1 || $et>$i))
			{
				$newstr = substr_replace($newstr, '%', 0,$len/2);	
				$newstr = "$newstr, ";

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
				{
					$list1=showResults($Pquery_num,$Pquery_result,'p');
				}else if($Oquery_num>0)
				{
					$list2=showResults($Oquery_num,$Oquery_result,'o');
				}else if($Mquery_num>0)
				{
					$list3=showResults($Mquery_num,$Mquery_result,'m');
				}else if($Tquery_num>0)
				{
					$list4=showResults($Tquery_num,$Tquery_result,'t');
				}
			}else if(strlen($newstr)<=3){
				echo "<br>Too Short";
			}else{
				echo "Error Threshold Reached";
			}
			return array_merge($list1,$list2,$list3,$list4);
}



function method5($query,$et)
{

	$newstr = $query;
	$len = strlen($query);
	/*
	DEBUG
	echo $len;
		echo "<br>";
	echo $query;
	*/
	
	if($len>=5)
			if(strlen($newstr)>3 && ($query_num<1 || $et>$i))
			{
				$newstr = substr_replace($newstr, '%', $len/2,$len);	
				$newstr = "$newstr";

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
				{
					$list1=showResults($Pquery_num,$Pquery_result,'p');
				}else if($Oquery_num>0)
				{
					$list2=showResults($Oquery_num,$Oquery_result,'o');
				}else if($Mquery_num>0)
				{
					$list3=showResults($Mquery_num,$Mquery_result,'m');
				}else if($Tquery_num>0)
				{
					$list4=showResults($Tquery_num,$Tquery_result,'t');
				}
			}else if(strlen($newstr)<=3){
				echo "<br>Too Short";
			}else{
				echo "Error Threshold Reached";
			}
			return array_merge($list1,$list2,$list3,$list4);
}








function method6($query,$et)
{

	$newstr = $query;
	$len = strlen($query);
	/*
	DEBUG
	echo $len;
		echo "<br>";
	echo $query;
	*/
	
	if($len>=5)
			if(strlen($newstr)>3 && ($query_num<1 || $et>$i))
			{
				$newstr = substr_replace($newstr, '%', 1,$len-2);	
				$newstr = "$newstr, ";

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
				{
					$list1=showResults($Pquery_num,$Pquery_result,'p');
				}else if($Oquery_num>0)
				{
					$list2=showResults($Oquery_num,$Oquery_result,'o');
				}else if($Mquery_num>0)
				{
					$list3=showResults($Mquery_num,$Mquery_result,'m');
				}else if($Tquery_num>0)
				{
					$list4=showResults($Tquery_num,$Tquery_result,'t');
				}
			}else if(strlen($newstr)<=3){
				echo "<br>Too Short";
			}else{
				echo "Error Threshold Reached";
			}
			return array_merge($list1,$list2,$list3,$list4);
}





function method7($query,$et)
{

	$newstr = $query;
	$len = strlen($query);
	/*
	DEBUG
	echo $len;
		echo "<br>";
	echo $query;
	*/
	
	if($len>=5)
			if(strlen($newstr)>3 && ($query_num<1 || $et>$i))
			{
				$newstr = substr_replace($newstr, '%', 2,$len-4);	
				$newstr = "$newstr, ";

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
				{
					$list1=showResults($Pquery_num,$Pquery_result,'p');
				}else if($Oquery_num>0)
				{
					$list2=showResults($Oquery_num,$Oquery_result,'o');
				}else if($Mquery_num>0)
				{
					$list3=showResults($Mquery_num,$Mquery_result,'m');
				}else if($Tquery_num>0)
				{
					$list4=showResults($Tquery_num,$Tquery_result,'t');
				}
			}else if(strlen($newstr)<=3){
				echo "<br>Too Short";
			}else{
				echo "Error Threshold Reached";
			}
			return array_merge($list1,$list2,$list3,$list4);
}
?>
