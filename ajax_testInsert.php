<?
include('lib/config.php');
include("db_connect/public_functions_2.php");
extract($_REQUEST);

$qry = "select * from trandata.userid@tcscentr";
$table = "userid";

$result = select_query($qry);
$qrycol = "select * from ".$table." where rownum=1";
$column = select_testcolumn($qrycol);


foreach ($result as $key => $row) {
	$field = array();
	foreach ($column as $key => $col) {
		$field[$col] = $row[$col];
	}
	/*echo "<pre>";
	print_r($field);
	*/$insert = insert_testquery($field,$table);
	echo $insert;
} 
?>