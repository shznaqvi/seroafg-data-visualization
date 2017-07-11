<?php
/* $server = the IP address or network name of the server
 * $userName = the user to log into the database with
 * $password = the database account password
 * $databaseName = the name of the database to pull data from
 * table structure - colum1 is cas: has text/description - column2 is data has the value
 */
$con = mysqli_connect('localhost','app','abcd1234','seroafgh') ;


mysqli_query($con, "INSERT INTO `seroafgh`.`del_proc`
(`temp`)
VALUES
(
'dashboard');
") or die("Query fail: " . mysqli_error());
//mysqli_fetch_assoc($sp));

// write your SQL query here (you may use parameters from $_GET or $_POST if you need them)
$query = mysqli_query($con, "SELECT provna, sum(Total) Total, Sum(grp1) grp1, sum(grp2) grp2, sum(Im) Im, sum(Opv) Opv, sum(IPV) IPV, sum(NoIPV) NoIPV, sum(sgrp1) sgrp1, sum(sgrp2) sgrp2 FROM seroafgh.seroafg_summary group by provna;")or die(mysql_error());

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
    array('label' => 'Provience', 'type' => 'string'),
    array('label' => 'Total Enrollment', 'type' => 'number'),
    array('label' => 'Months(6-11)', 'type' => 'number'),
    array('label' => 'Months(36-48)', 'type' => 'number'),
    array('label' => 'Zero RI', 'type' => 'number'),
    array('label' => 'Zero OPV', 'type' => 'number'),
    array('label' => 'Rcvd. IPV', 'type' => 'number'),
    array('label' => 'No IPV', 'type' => 'number'),
    array('label' => 'Sample(6-11)', 'type' => 'number'),
    array('label' => 'Sample(36-48)', 'type' => 'number')
 
	
);



$rows = array();

while($r = mysqli_fetch_assoc($query)) {
	$temp = array();
			$temp[] = array('v'=> $r['provna']);
			$temp[] = array('v' => (int) $r['Total']); 
			$temp[] = array('v' => (int) $r['grp1']); 
			$temp[] = array('v' => (int) $r['grp2']); 
			$temp[] = array('v' => (int) $r['Im']); 
			$temp[] = array('v' => (int) $r['Opv']); 
			$temp[] = array('v' => (int) $r['IPV']); 
			$temp[] = array('v' => (int) $r['NoIPV']); 
			$temp[] = array('v' => (int) $r['sgrp1']); 
			$temp[] = array('v' => (int) $r['sgrp2']); 
		
		
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