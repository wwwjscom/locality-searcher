<?
include("sug_func.php");

				$Pquery_result=queryDB('p','p',$_GET['query'].", ");
				$Pquery_num=mysql_num_rows($Pquery_result);
				if($Pquery_num>0)
//					for($i=0;$i<$Pquery_num;$i++)
						echo "We found ".mysql_result($Pquery_result,$i,p);

				$Oquery_result=queryDB('o','o',$_GET['query'].", ");
				$Oquery_num=mysql_num_rows($Oquery_result);
				if($Oquery_num>0)
//					for($i=0;$i<$Oquery_num;$i++)
						echo "We found ".mysql_result($Oquery_result,$i,o);

				$Mquery_result=queryDB('m','m',$_GET['query'].", ");
				$Mquery_num=mysql_num_rows($Mquery_result);
				if($Mquery_num>0)
//					for($i=0;$i<$Mquery_num;$i++)
						echo "We found ".mysql_result($Mquery_result,$i,m);

				$Tquery_result=queryDB('t','t',$_GET['query'].", ");
				$Tquery_num=mysql_num_rows($Tquery_result);
				if($Tquery_num>0)
//					for($i=0;$i<$Tquery_num;$i++)
						echo "We found ".mysql_result($Tquery_result,$i,t);


if($Pquery_num+$Oquery_num+$Mquery_num+$Tquery_num==0)
{
	echo "NOTHING FOUND<br>Trying to Help...<br>";
	echo "<br>Trying Method 1...<br>";
	$list1=method1($_GET['query'],3);
	echo "<br>Trying Method 3...<br>";
	$list3=method3($_GET['query'],3);
//	print_r($list);
	echo "<br>Trying Method 4...<br>";
	$list4=method4($_GET['query'],3);
	echo "<br>Trying Method 5...<br>";
	$list5=method5($_GET['query'],3);
	echo "<br>Trying Method 6...<br>";
	$list6=method6($_GET['query'],3);
	echo "<br>Trying Method 7...<br>";
	$list7=method7($_GET['query'],3);

	$finalArray=array_merge($list1,$list3,$list4,$list5,$list6,$list7);
	$origArray=$finalArray;
	$origLen=count($origArray);
//	print_r($finalArray);
	$finalArray=(array_count_values($finalArray));
//	print_r($finalArray);
//	echo "<br>";
	arsort($finalArray);
//	print_r($finalArray);
//	echo "<br><br>";
//	print_r(array_values($finalArray));
//	echo "<br><br>";
//	print_r(array_keys($finalArray));
	reset($finalArray);
	echo "<br><br>";
	while(list($key, $val) = each($finalArray))
	{
		/*
		echo "<br>Key: ".$key;
		echo "<br>Val: ".$val;
		*/
		$percent= $val/$origLen;
		$percent=round($percent*100);
		echo $key." is ".$percent."% likely to be your choice.<br>";
	}
}
?>
