<?php
// ?s are the indicators of PHP code!
header("Access-Control-Allow-Origin: *"); // Allow all origins
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Allow GET and POST methods
header("Access-Control-Allow-Headers: Content-Type"); // Allow Content-Type header

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


// select a row of stimuli URLs from the database, retrieve it and send it to HTML, so the ps can see the stimuli 
$row_used = 1;
$row_id = 0;
while ($row_used==1) { // while ($row_used==1); change to if for testing 
    $query = "SELECT * FROM en_wordpairs_stim ORDER BY RAND() LIMIT 1"; // select a random row that hasn't been shown to ps
    // $query = "SELECT * FROM en_wordpairs_stim WHERE id=1"; // used for testing
    $returned = mysqli_query($connect, $query);
    $result = mysqli_fetch_object($returned);
    
    $row_used = $result->used;
    $row_id = $result->id;

    if ($row_used==0) { // 0; change to 1 for testing
        // comment out for testing
        // update the used column once the row of stimuli URLs is selected for a ps
        $query = "UPDATE en_wordpairs_stim SET used=1 WHERE id='$row_id'";
        mysqli_query($connect, $query);  

        // Initialize empty arrays to store only words without column names
        $words1 = [];
        $words2 = [];

        // Loop through the columns from COL1 to COL80 (first block) and store only the words
        for ($i = 1; $i <= 160; $i++) {
            $colName = "COL " . $i; // Construct column name dynamically
            $words1[] = $result->$colName; // Add word to the words array (no column name)
        }

        // Loop through the columns from COL81 to COL160 (second block) and store only the words
        for ($i = 161; $i <= 240; $i++) {
            $colName = "COL " . $i; // Construct column name dynamically
            $words2[] = $result->$colName; // Add word to the words array (no column name)
        }
    }
}

// Now close the connection
mysqli_close($connect);

// Create an object to hold the columns (words without the column names)
$myObj->col1 = $words1; 
$myObj->col2 = $words2;

// Encode the object to JSON
$myJSON = json_encode($myObj);

// Output the JSON to the client-side (HTML)
echo $myJSON; // Push myJSON to HTML, where myObj will be accessed
?>