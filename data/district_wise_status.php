<?php
/* $server = the IP address or network name of the server
 * $userName = the user to log into the database with
 * $password = the database account password
 * $databaseName = the name of the database to pull data from
 * table structure - colum1 is cas: has text/description - column2 is data has the value
 */
$con = mysql_connect('localhost','app','abcd1234') ;

mysql_select_db('seroafgh', $con); 

// write your SQL query here (you may use parameters from $_GET or $_POST if you need them)
$query = mysql_query("select * from seroafg_summary")or die(mysql_error());

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
    array('label' => 'Total Engagement', 'type' => 'number'),
    array('label' => 'Age 0- 11', 'type' => 'number'),
    array('label' => 'Age 36 - 48', 'type' => 'number'),
    array('label' => 'Zero Doses Routine Immunization', 'type' => 'number'),
    array('label' => 'Zero Doses OPV', 'type' => 'number'),
    array('label' => 'Received IPV', 'type' => 'number')
	
);



$rows = array();

while($r = mysql_fetch_assoc($query)) {
	$temp = array();
			$temp[] = array('v'=> $r['provna']);
			$temp[] = array('v' => (int) $r['Total']); 
			$temp[] = array('v' => (int) $r['grp1']); 
			$temp[] = array('v' => (int) $r['grp2']); 
			$temp[] = array('v' => (int) $r['Im']); 
			$temp[] = array('v' => (int) $r['Opv']); 
			$temp[] = array('v' => (int) $r['IPV']); 
		
		
    $rows[] = array('c' => $temp);
}
	$table['rows'] = $rows;
// encode the table as JSON
$jsonTable = json_encode($table);

// set up header; first two prevent IE from caching queries
header('Cache-Control: no-cache, must-revalidate');
header('Content-type: application/json');

// return the JSON data
echo $jsonTable;
?>