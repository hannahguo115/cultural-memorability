<html>
<?php
// ?s are the indicators of PHP code!
	
	/* 
    Coded by Hannah Guo, 2025.
    */

    header('Content-type: application/json; charset=utf-8');

	// Here's the login data for your online database. Many web hosts have this as a feature and your university may allow it to! I personally use Ionos (though I have not tried other hosts so I cannot vouch for them either way). It allows me to create and maintain an unlimited number of MySQL databases on my web server. I then interact with it using this PHP script uploaded to my website.
	// Note: these will have to be changed if you create a new database on your server
	$host_name = "db5017500590.hosting-data.io";
	$database = "dbs14033317";
	$user_name = "dbu372116";
	$password = "Tow3weld@";

	// Connect to the database
	$connect = mysqli_connect($host_name, $user_name, $password, $database);

	global $message;
	$message = "";

	// Check the connection, and return an error if there is an issue.
	if(mysqli_connect_errno()){
		$message = 'There was a connection error: ' . mysqli_connect_error();
	}

	// Clean up the data to prevent security risks.
	// Note: added 'ENT_QUOTES' here because quotes were breaking the code for some reason
	// Quotes get spitted out as '&#039;' 
	function testinput($data){
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data, ENT_QUOTES);
		return $data;
	}

	// You can use this to get the IP address of your participant, if you want general location information
	function get_client_ip() {
        $ipaddress = '';
		// Essentially, different browsers have different syntax for IP address, so this is trying all of them.
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
	}

// send ps data from HTML to PHP
$d1 = testinput($_POST["wordseqout"]);
$d2 = testinput($_POST["timeseqout"]);
$d3 = testinput($_POST["probeseqout"]);
$d4 = testinput($_POST["recallseqout"]);
$d5 = testinput($_POST["mathquestionout"]);
$d6 = testinput($_POST["mathanswerout"]);

$d7 = testinput($_POST["cultureansout"]);
$d8 = testinput($_POST["prolificidHidden"]);
$d9 = testinput($_POST["smoothHidden"]);
$d10 = testinput($_POST["smoothdetailsHidden"]);
$d11 = testinput($_POST["genderHidden"]);
$d12 = testinput($_POST["ethnicityHidden"]);
$d13 = testinput($_POST["raceHidden"]);
$d14 = testinput($_POST["ageHidden"]);
$d15 = testinput($_POST["languageHidden"]);
$d16 = testinput($_POST["otherlanguageHidden"]);
$d17 = testinput($_POST["educationHidden"]);
$d18 = testinput($_POST["writewordsdownHidden"]);
$d19 = testinput($_POST["loadtimeout"]);

// Insert ps data into the database. 
// The first set of items in () are the names of the columns in the MySQL database. The second set of items (after VALUES) are the values we just extracted that we're uploading into each of the columns. We're essentially creating a new row in the database.
$sql = "INSERT INTO en_wordpairs_data (wordseq, timeseq, probeseq, recallseq, mathquestion, mathanswer,
cultureans, prolificid, smooth, smoothdetails, 
gender, ethnicity, race, age, nativelanguage, otherlanguage, education, writewords,loadtime)
VALUES ('$d1','$d2','$d3','$d4','$d5','$d6','$d7','$d8','$d9','$d10','$d11','$d12','$d13','$d14','$d15','$d16','$d17','$d18','$d19')";
mysqli_query($connect, $sql); // debug: delete this line, otherwise there would be 2 rows of identical data being recorded to memory_av table for each ps--bc the next line (102) does the same thing

// Now close the connection
mysqli_close($connect); 

?>
<title>Experiment Complete</title>
<center>
<br><br>
<div style='font-family:helvetica;font-size:24px'>Thank you!</div>
<br><br>
</center>


</html>