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

<head>
<title>U.S.H.M.M. Searcher</title>

        <script type="text/javascript" src="autosuggest2.js"></script>
        <script type="text/javascript" src="remotesuggestions.js"></script>
        <link rel="stylesheet" type="text/css" href="autosuggest.css" />        
        <script type="text/javascript">
            window.onload = function () {
                var oTextbox = new AutoSuggestControl(document.getElementById("query"), new RemoteStateSuggestions());        
                var oTextbox = new AutoSuggestControl(document.getElementById("txt1"), new RemoteStateSuggestions());        
            }
				</script>
</head>

<body bgcolor="grey">
<input type="text" id="txt1">
<div class="main">
        <div class="masthead">
				<div class=IRLogo><a href="http://ir.iit.edu" target="_blank"><img src="img/IR.gif" alt="IR Logo" border="0"></a></div>
        <a href="index.php?destroy=true"><img src="img/masthead.png" alt="USHMM Search Page Header"  border="0"></a>
        </div>
        <div class="query_content">
        <br /><br /><br />

<table border="0" align="center" width="642" cellpadding="0" cellspacing="0">
<tr>
	<td align="center">
	</td>
</tr>
<tr>
	<b><a href="<? echo $_SERVER['PHP_SELF'] ?>?destroy=true">New Search</a></b><br><br>
	Please select a Province from the drop-down menu, or enter your search
	<br />
</tr>
<tr>
	<td align="left">
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
		Province:
		<br />
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
		//display(t,m,$_SESSION['m_drop'],yes); 
		display(t,m,$_SESSION['m_drop'],no); 
		?>
		<br>
		<br>
		<input type="submit" value="Search" name="Done">
		</form>
		<?
	}

	?>
	
	</td>
	<td align="right">
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
