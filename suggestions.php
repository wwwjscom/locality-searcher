<?php
    header("Content-Type: text/plain; charset=UTF-8");
   
$userInput=$_GET['userInput'];

$states = array();
//include('../functions.php');
include('functions.php');
openConn();
$query="SELECT p,o,m,t FROM locality WHERE p LIKE \"$userInput%\" OR o LIKE \"$userInput%\" OR m LIKE \"$userInput%\" OR t LIKE \"$userInput%\"";
$query_result=mysql_query($query);
while($x = mysql_fetch_array($query_result))
{
	foreach($x as $val)
	{
//		$val = urlencode($val);
		if(!in_array($val,$states))
			array_push($states,$val);
	}
}
mysql_close();
		

    $suggestions = array();

    
    if (strlen($userInput) > 1){
    
        $userInputLC = strtolower($userInput);


        for ($i=0; $i < count($states); $i++) { 

            $stateLC = strtolower($states[$i]);
           
            $result = strpos($stateLC, $userInputLC);
            if ($result !== false && $result == 0) {
                array_push($suggestions, $userInput.substr($states[$i], strlen($userInput)));
            } 
        }
    }    
?>
[<?php
		$max = count($suggestions);
		if($max>10)
			$max = 10;

    for ($i = 0; $i < $max;$i++) {
        if ($i > 0) {
            echo ",";
        }

				if(strlen($suggestions[$i])>20)
				{
			//		$suggestions[$i]="".substr($suggestions[$i],0,15)."...";
        	echo "\"".substr($suggestions[$i],0,20)."...\"";
			//		echo "\"".$suggestions[$i]."\"";
				}
				else
	        echo "\"".$suggestions[$i]."\"";
        
    }
?>]
