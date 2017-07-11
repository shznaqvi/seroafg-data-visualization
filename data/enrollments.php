<?php
/* $server = the IP address or network name of the server
 * $userName = the user to log into the database with
 * $password = the database account password
 * $databaseName = the name of the database to pull data from
 * table structure - colum1 is cas: has text/description - column2 is data has the value
 */
$con = mysqli_connect('localhost','app','abcd1234', 'seroafgh') ;


mysqli_query($con, "CALL delete_duplicates()");
// write your SQL query here (you may use parameters from $_GET or $_POST if you need them)
$query = mysqli_query($con, "SELECT * FROM seroafgh.enrollments")or die(mysql_error());

$table = array();
$table['cols'] = array(
    /* define your DataTable columns here
     * each column gets its own array
     * syntax of the arrays is:
     * label => column label
     * type => data type of column (string, number, date, datetime, boolean)
     */
    // I assumed your first column is a "string" type
    // and your second column is a "number" type
    // but you can change them if they are not
    array('label' => 'UID', 'type' => 'string'),
    array('label' => 'Study ID', 'type' => 'string'),
    array('label' => 'Study Code', 'type' => 'string'),
    array('label' => 'Child Name', 'type' => 'string'),
    array('label' => 'Father\'s Name', 'type' => 'string'),
    array('label' => 'Mother\'s Name', 'type' => 'string'),
    array('label' => 'Gener', 'type' => 'string')

 
	
);



$rows = array();

while($r = mysqli_fetch_assoc($query)) {
	$temp = array();
			$temp[] = array('v'=> $r['uid']);
			$temp[] = array('v'=> $r['studyid']);
			$temp[] = array('v'=> $r['studycode']);
			$temp[] = array('v'=> $r['mna1']);
			$temp[] = array('v'=> $r['mna2']);
			$temp[] = array('v'=> $r['mna3']);
			$temp[] = array('v'=> $r['mna6']);
	
		
    $rows[] = array('c' => $temp);
}

    mysqli_free_result($result);
	mysqli_close($link);

	$table['rows'] = $rows;
// encode the table as JSON
$jsonTable = json_encode($table);

// set up header; first two prevent IE from caching queries
header('Cache-Control: no-cache, must-revalidate');
header('Content-type: application/json');

// return the JSON data
echo $jsonTable;
?>