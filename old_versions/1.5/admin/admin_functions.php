<?

/*
 */
function checkpw()
{
	if($_POST['pw'] == "xacut")
	{
		$_SESSION['pw'] = "ok";
	}
}

/*
 */
function checkLoggedIn()
{
	checkpw();
	if($_SESSION['pw'] != "ok")
	{
		die("You must login!<br><br>".loginForm()."");
	}
}

/*
 */
function loginForm()
{
echo <<<END
<form action="index.php" method="post">
Password: <input type="password" name="pw">
<br>
<input type="Submit" value="Login">
END;
}

/*
 */
function disHeader()
{
echo <<<END
	<a href="index.php?destroy=true">logout</a> || <a href="upload.php">upload a file</a> || <a href="select_file_to_import.php">Import</a>
	<hr>
END;
}
?>
