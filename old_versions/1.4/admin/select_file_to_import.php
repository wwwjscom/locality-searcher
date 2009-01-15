<?
session_start();
include("admin_functions.php");
checkLoggedIn();
disHeader();

echo "<form action=\"import_select_db.php\" method=\"post\">";
//Looks into the directory and returns the files, no subdirectories
echo "<select name='yourfiles'>";
//The path to the style directory
$dirpath = "uploads/";
$dh = opendir($dirpath);

while (false !== ($file = readdir($dh))) {
	//Don't list subdirectories
	if (!is_dir("$dirpath/$file")) {
		//Truncate the file extension and capitalize the first letter
		echo "<option name='filename' value='$file'>" . htmlspecialchars(ucfirst(preg_replace('/\..*$/', '', $file))) . '</option>';
	}
}

closedir($dh); 
//Close Select
echo "</select>";
echo "<br><input type=\"Submit\" value=\"Select\">";
echo "</form>";
?>
