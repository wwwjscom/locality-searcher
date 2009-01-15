<?
/* Start/Continue Session..MUST BE THE FIRST LINE!!! */
session_start();
/* Destroy Session if the user asked to start over */
if(isset($_GET['destroy'])) 
{
	session_destroy();
	session_start();
}

/* The the last form has been filled out, forward them to the results page */
/* This is checked in the header b/c you can only auto-forward in header */
//if($_GET['Done']=="Search") 
//{
	/* Double check to see if a town was selected */
	if(isset($_GET['t_drop']) && $_GET['t_drop']!="NoNotMe")
		$_SESSION['t_drop'] = $_GET['t_drop'];
	/* If the session is ok, then forward them to the results page */
	if(isset($_SESSION['t_drop']))
		header('Location: results.php');
//}
include("functions.php"); 
checkSet();
?>

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
	<td>
	<b><a href="<? echo $_SERVER['PHP_SELF'] ?>?destroy=true">Start Over</a></b><br><br>
	Welcome to the U.S.H.M.M. Searching Tool! You have two ways to get a reuslt.  Either make 4 selections from the list on the left, or search using the box on the right.  If you are unsure how to spell your word exactly our searching tool will attempt to help you find it and list possiable results.<br><br>
	</td>
</tr>
<tr>
	<td align="center">
	<? 
/* ---------------------------------- 
 * BEGIN FORM SECTION FOR PROV SELECTION
 * ----------------------------------
 */	

	if((!isset($_SESSION['p_drop']) && !isset($_GET['p_drop'])) || $_GET['p_drop'] == "NoNotMe") {
		/* If entry isn't valid */
		if($_GET['p_drop'] == "NoNotMe")
			echo "<font color=\"red\"><b>Please select a valid entry.</b></font><br><br>";
		?>
		<form action="<? echo $_SERVER['PHP_SELF']?>" method="get">
		Please begin by selecting a Province: <br>
		
		<? 
		/* display(category_letter, previous_category_letter, previous_selected_term, select_ID) -- More documention found in the function.php file */
		display(p,null,null,no); 
		?>
		<br>
		<br>
		<input type="submit" name="prov" value="Next (1/4)">
		</form>
		<?
	}
	/* If the user has made a valid selection, Set the session var AND print out their past selections, seperated by arrows */
		if(isset($_GET['p_drop']) && $_GET['p_drop'] != "NoNotMe")
		{
			$_SESSION['p_drop']=$_GET['p_drop'];
			echo "<i>You have selected: ".$_SESSION['p_drop']."</i><br><br>";
		}

/* ---------------------------------- 
 * BEGIN FORM SECTION FOR O SELECTION
 * ----------------------------------
 */	

 if((!isset($_SESSION['o_drop']) && !isset($_GET['o_drop']) && isset($_SESSION['p_drop'])) || $_GET['o_drop'] == "NoNotMe") {
		/* If entry isn't valid */
		if($_GET['o_drop'] == "NoNotMe")
			echo "<font color=\"red\"><b>Please select a valid entry.</b></font><br><br>";
	?>

		<form action="<? echo $_SERVER['PHP_SELF'] ?>" method="get">
		District:<br>
		<?
		/* display(category_letter, previous_category_letter, previous_selected_term, select_ID) -- More documention found in the function.php file */
		display(o,p,$_SESSION['p_drop'],no); 
		?>
		<br>
		<br>
		<input type="Submit" value="Next (2/4)">
		</form>
		<?
	}
	/* If the user has made a valid selection, print out their past selections, seperated by arrows */
	if(isset($_GET['o_drop']) && $_GET['o_drop'] != "NoNotMe")	
	{
		$_SESSION['o_drop'] = $_GET['o_drop'];
		echo "<i>You have selected: ".$_SESSION['p_drop']." -> ". $_SESSION['o_drop']."</i><br><br>";
	}
		

/* ---------------------------------- 
 * BEGIN FORM SECTION FOR MUNICIPALITY SELECTION
 * ----------------------------------
 */	


 if((!isset($_SESSION['m_drop']) && !isset($_GET['m_drop']) && isset($_SESSION['o_drop'])) || $_GET['m_drop'] == "NoNotMe") {
		/* If entry isn't valid */
		if($_GET['m_drop'] == "NoNotMe")
			echo "<font color=\"red\"><b>Please select a valid entry.</b></font><br><br>";
		?>
		<form action="<? echo $_SERVER['PHP_SELF'] ?>" method="get">
		Municipality:<br>
		<? 
		/* display(category_letter, previous_category_letter, previous_selected_term, select_ID) -- More documention found in the function.php file */
		display(m,o,$_SESSION['o_drop'],no); 
		?>
		<br>
		<br>
		<input type="submit" value="Next (3/4)">
		</form>
		<?
	}
	/* If the user has made a valid selection, print out their past selections, seperated by arrows */
	if(isset($_GET['m_drop']) && $_GET['m_drop'] != "NoNotMe") 
	{
		$_SESSION['m_drop'] = $_GET['m_drop'];
		echo "<i>You have selected: ".$_SESSION['p_drop']." -> ". $_SESSION['o_drop']." -> ".$_SESSION['m_drop']."</i><br><br>";
	}


/* ---------------------------------- 
 * BEGIN FORM SECTION FOR TOWN SELECTION
 * ----------------------------------
 */	

	if(!isset($_SESSION['t_drop']) && !isset($_GET['t_drop']) && isset($_SESSION['m_drop']) || $_GET['t_drop'] == "NoNotMe") {
		/* If entry isn't valid */
		if($_GET['t_drop'] == "NoNotMe")
			echo "<font color=\"red\"><b>Please select a valid entry.</b></font><br><br>";
		?>
		<form action="<? echo $_SERVER['PHP_SELF'] ?>" method="get">
		Township:<br>
		<? 
		/* display(category_letter, previous_category_letter, previous_selected_term, select_ID) -- More documention found in the function.php file */
		display(t,m,$_SESSION['m_drop'],yes); 
		?>
		<br>
		<br>
		<input type="submit" value="Search" name="Done">
		</form>
		<?
	}

	?>
	
	</td>
	<td>
	<? displaySearcher(); ?>
	</td>
</tr>
</table>
<br><br>
</div> <!-- Close query_content style -->
<? footer(); ?>
</div> <!-- Close main style -->
</body>
</html>
