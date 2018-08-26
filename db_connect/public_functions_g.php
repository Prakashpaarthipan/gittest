<?php
session_start();
error_reporting(0);

function isJson($string) {
	json_decode($string);
	return (json_last_error() == JSON_ERROR_NONE);
}

function select_query($sqlqry_select) {
	// $sqlqry_select = 'http://tcstextile.in/tcs_service/TCSservice.asmx/App_TRN_List?TRN=A';
	$curl = curl_init($sqlqry_select);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$curl_response = curl_exec($curl);
	if ($curl_response === false) {
		$info = curl_getinfo($curl);
		curl_close($curl);
		die('error occured during curl exec. Additioanl info: ' . var_export($info));
	}
	curl_close($curl);
	
	$respxpl = explode('<string xmlns="http://tempuri.org/">', $curl_response);
	$respxplode = explode('</string>', $respxpl[1]);

	$jsonverify = isJson($respxplode[0]);
	$mainarray = json_decode($respxplode[0], true);
	
	if(count($mainarray[0]) == 0) { $innerarray[0] = $mainarray; }
	else { $innerarray = $mainarray; }
	return $innerarray;
}

/* This is for trandata DB */
/* Select Query Started */
//print_r(select_query_encrypted("select trmcode, trmname, trmdesc, trmstat, deleted from srm_terms_detail where trmcode = 1"));
//echo '@@'.$sqlqry_select;
function select_query_encrypted($sqlqry_select)
{
	if($_SESSION["dbassign"] == '') {
		include("config.php");
		//$connect_db = oci_connect($exp_tcs[0], $exp_tcs[1], $exp_tcs[2]);
	}
	elseif($_SESSION['username1'] != '' and $_SESSION['password1'] != '' and $_SESSION['host_db1'] != '') {
		$connect_db = oci_connect($_SESSION['username1'], $_SESSION['password1'], $_SESSION['host_db1']);
	}
	
	try {
		$parseqry = oci_parse($connect_db,$sqlqry_select); // Parse the connection and DB.
		oci_execute($parseqry); // Execute the query
		$res_qry = array();
		while(($row_qry = oci_fetch_array($parseqry, OCI_BOTH)) != false) {
			//print_r($row_qry);
			$res_qry[] = $row_qry;
		}
		return $res_qry;
	} 
	catch(Exception $e) {
		?>
			<script>window.location="tcsitadmin/index.php";</script>
		<?
		//echo 'Message: ' .$e->getMessage();
	}
}

function select_testquery_encrypted($sqlqry_testselect)
{
	if($_SESSION["dbassign"] == '') {
		include("config.php");
		//$tcstest_connect_db = oci_connect($exp_tcs[0], $exp_tcs[1], $exp_tcs[2]);
	}
	elseif($_SESSION['tcstest_username'] != '' and $_SESSION['tcstest_password'] != '' and $_SESSION['tcstest_host_db'] != '') {
		$tcstest_connect_db = oci_connect($_SESSION['tcstest_username'], $_SESSION['tcstest_password'], $_SESSION['tcstest_host_db']);
	}
	
	try {
		$test_parseqry = oci_parse($tcstest_connect_db,$sqlqry_testselect); // Parse the connection and DB.
		oci_execute($test_parseqry); // Execute the query
		$res_testqry = array();
		while(($row_testqry = oci_fetch_array($test_parseqry, OCI_BOTH)) != false) {
			//print_r($row_testqry);
			$res_testqry[] = $row_testqry;
		}
		return $res_testqry;
	} 
	catch(Exception $e) {
		?>
			<script>window.location="tcsitadmin/index.php";</script>
		<?
		//echo 'Message: ' .$e->getMessage();
	}
}
/* Select Query Completed */

/* This is for trandata DB */
/* Select Query Started 
//print_r(select_query("select trmcode, trmname, trmdesc, trmstat, deleted from srm_terms_detail where trmcode = 1"));
//echo '@@'.$sqlqry_select;
function select_query($sqlqry_select)
{
	if($_SESSION["dbassign"] == '') {
		include("config.php");
		//$connect_db = oci_connect($exp_tcs[0], $exp_tcs[1], $exp_tcs[2], 'AL32UTF8');
	}
	elseif($_SESSION['username1'] != '' and $_SESSION['password1'] != '' and $_SESSION['host_db1'] != '') {
		$connect_db = oci_connect($_SESSION['username1'], $_SESSION['password1'], $_SESSION['host_db1'], 'AL32UTF8');
	}
	
	try {
		$parseqry = oci_parse($connect_db,$sqlqry_select); // Parse the connection and DB.
		oci_execute($parseqry); // Execute the query
		$res_qry = array();
		while(($row_qry = oci_fetch_array($parseqry, OCI_BOTH)) != false) {
			//print_r($row_qry);
			$res_qry[] = $row_qry;
		}
		return $res_qry;
	} 
	catch(Exception $e) {
		?>
			<script>window.location="tcsitadmin/index.php";</script>
		<?
		//echo 'Message: ' .$e->getMessage();
	}
}
/* Select Query Completed */

/* Execute Procedure Started */
/* $procedure_name="proceudre_name";
$field_value=array();
$field_value['reqcode'] = 5;	
$field_value['reqname'] = 'Req 5';
$field_value['reqdeli'] = '01-DEC-14'; 
echo exec_procedure_query($field_value, $procedure_name); */
function exec_procedure_query($field_values, $procedure_names)
{
	if($_SESSION["dbassign"] == '') {
		include("config.php");
		//$connect_db = oci_connect($exp_tcs[0], $exp_tcs[1], $exp_tcs[2]);
	}
	elseif($_SESSION['username1'] != '' and $_SESSION['password1'] != '' and $_SESSION['host_db1'] != '') {
		$connect_db = oci_connect($_SESSION['username1'], $_SESSION['password1'], $_SESSION['host_db1'], 'AL32UTF8');
	}
	
	try {
		$key_value = array_keys($field_values);
		$org_value = array_values($field_values);
		$key_values = array_keys($field_values);
		$kvl = ''; $kyvl = '';
		
		for($ii = 0; $ii < count($field_values); $ii++) {
			$kvl .= $key_values[$ii].", ";
			$kyvl .= ":".$key_values[$ii].", ";
			$kkvlg[] = ":".$key_values[$ii]."";
			//$kyvl .= " '".$org_value[$ii]."', ";
			$kkvl[] = "".$key_values[$ii]."";
			
			/* if($key_values[$ii] == 'PO_Y') {
				$var_numb[] = '7';
			}
			if($key_values[$ii] == 'PO_NO') {
				$var_numb[] = '10';
			}
			if($key_values[$ii] == 'GRP_SNO_') {
				$var_numb[] = '5';
			}
			if($key_values[$ii] == 'FR_SIZ') {
				$var_numb[] = '5';
			}
			if($key_values[$ii] == 'TO_SIZE') {
				$var_numb[] = '5';
			} */
		}
		
		$kyvl = rtrim($kyvl, ", ");
		$kvl = rtrim($kvl, ", ");
		
		$sql_exec_procedure ="begin ".$procedure_names."(".$kyvl."); end;";
		$res_exec_procedure=oci_parse($connect_db, $sql_exec_procedure);
		
		for($ij = 0; $ij < count($field_values); $ij++) {
			//echo "<br>==".$kkvlg[$ij]."==".$org_value[$ij]."==";
			oci_bind_by_name($res_exec_procedure, $kkvlg[$ij], $org_value[$ij]); // or die('Error binding string');
		}
		$res_exec_procedure=oci_execute($res_exec_procedure); // or die ('Cannot Execute statment');
		
		if($res_exec_procedure) {
			return '1';
		}
		else {
			return '0';
		}
	} 
	catch(Exception $e) {
		?>
			<script>window.location="tcsitadmin/index.php";</script>
		<?
		//echo 'Message: ' .$e->getMessage();
	}
}

function exec_procedure_ksreqquery($field_values, $procedure_names)
{
	if($_SESSION["dbassign"] == '') {
		include("config.php");
	}
	elseif($_SESSION['ksrequsername2'] != '' and $_SESSION['ksreqpassword2'] != '' and $_SESSION['ksreqhost_db2'] != '') {
		$connect_db = oci_connect($_SESSION['ksrequsername2'], $_SESSION['ksreqpassword2'], $_SESSION['ksreqhost_db2'], 'AL32UTF8');
	}
	
	try {
		$key_value = array_keys($field_values);
		$org_value = array_values($field_values);
		$key_values = array_keys($field_values);
		$kvl = ''; $kyvl = '';
		
		for($ii = 0; $ii < count($field_values); $ii++) {
			$kvl .= $key_values[$ii].", ";
			$kyvl .= ":".$key_values[$ii].", ";
			$kkvlg[] = ":".$key_values[$ii]."";
			$kkvl[] = "".$key_values[$ii]."";
		}
		
		$kyvl = rtrim($kyvl, ", ");
		$kvl = rtrim($kvl, ", ");
		
		$sql_exec_procedure ="begin ".$procedure_names."(".$kyvl."); end;";
		$res_exec_procedure=oci_parse($connect_db, $sql_exec_procedure);
		
		for($ij = 0; $ij < count($field_values); $ij++) {
			oci_bind_by_name($res_exec_procedure, $kkvlg[$ij], $org_value[$ij]); // or die('Error binding string');
		}
		$res_exec_procedure=oci_execute($res_exec_procedure); // or die ('Cannot Execute statment');
		
		if($res_exec_procedure) {
			return '1';
		}
		else {
			return '0';
		}
	} 
	catch(Exception $e) {
		echo 'Message: ' .$e->getMessage();
	}
}
/* Execute Procedure Completed */

/* Insert Query Started */
/* $tbl_name="srm_requirement";
$field_value=array();
$field_value['reqcode'] = 5;	
$field_value['reqname'] = 'Req 5';
$field_value['reqamnt'] = '1000';	
$field_value['reqpiec'] = '5';	
$field_value['reqmatr'] = 'Cotton'; 
$field_value['reqqlty'] = 'Fine Cotton'; 
$field_value['reqcmnt'] = 'Cmnt 1'; 
$field_value['reqnote'] = 'Note 1'; 
$field_value['reqstat'] = '1'; 
$field_value['supcode'] = '0'; 
$field_value['reqcmpl'] = '0'; 
$field_value['adduser'] = '14442'; 
$field_value['adddate'] = 'dd-Mon-yyyy HH:MI:SS AM~~'.strtoupper(date('d-M-Y h:i:s A')); 

$field_value['edtuser'] = ''; 
$field_value['edtdate'] = ''; 
$field_value['deleted'] = '0'; 
$field_value['deluser'] = ''; 
$field_value['deldate'] = ''; 
$field_value['reqdeli'] = '01-DEC-14'; 

echo insert_query($field_value, $tbl_name); */
function insert_query($field_values, $tbl_names)
{
	if($_SESSION['dbassign'] == '') {
		include("config.php");
	}
	elseif($_SESSION['username1'] != '' and $_SESSION['password1'] != '' and $_SESSION['host_db1'] != '') {
		$connect_db = oci_connect($_SESSION['username1'], $_SESSION['password1'], $_SESSION['host_db1'], 'AL32UTF8');
	}
	
	try {
		$key_value = array_keys($field_values);
		$org_value = array_values($field_values);
		$key_values = array_keys($field_values);
		
		for($ii = 0; $ii < count($field_values); $ii++) {
			$expld = explode("~~",$org_value[$ii]);
			if($expld[1] != '')
			{
				$kvl .= $key_values[$ii].", ";
				$kyvl .= "to_date(:".$key_values[$ii].", '".$expld[0]."'), ";
				$kkvl[] = "".$key_values[$ii]."";
			} else {
				$kvl .= $key_values[$ii].", ";
				$kyvl .= ":".$key_values[$ii].", ";
				$kkvl[] = "".$key_values[$ii]."";
			}
		}
		$kyvl = rtrim($kyvl, ", ");
		$kvl = rtrim($kvl, ", ");

		$sql_insert ="insert into ".$tbl_names." (".$kvl.") values (".$kyvl.")";
		$res_insert=oci_parse($connect_db,$sql_insert);

		for($ij = 0; $ij < count($field_values); $ij++) {
			$expld = explode("~~",$org_value[$ij]);
			if($expld[1] != '')
			{
				oci_bind_by_name($res_insert, $kkvl[$ij], $expld[1]);
			} else {
				oci_bind_by_name($res_insert, $kkvl[$ij], $org_value[$ij]);
			}
		}
		$res_execute=oci_execute($res_insert);
		
		if($res_execute) {
			return '1';
		}
		else {
			$arr_error = oci_error($res_insert);
			return $arr_error['message'];
		}
	} 
	catch(Exception $e) {
		return 'Message: ' .$e->getMessage();
	}
}

function insert_querys($field_values, $tbl_names)
{
	if($_SESSION['dbassign'] == '') {
		include("config.php");
	}
	elseif($_SESSION['username1'] != '' and $_SESSION['password1'] != '' and $_SESSION['host_db1'] != '') {
		$connect_db = oci_connect($_SESSION['username1'], $_SESSION['password1'], $_SESSION['host_db1'], 'AL32UTF8');
	}
	
	try {
		$key_value = array_keys($field_values);
		$org_value = array_values($field_values);
		$key_values = array_keys($field_values);
		
		for($ii = 0; $ii < count($field_values); $ii++) {
			$expld = explode("~~",$org_value[$ii]);
			if($expld[1] != '')
			{
				$kvl .= $key_values[$ii].", ";
				$kyvl .= "to_date(:".$key_values[$ii].", '".$expld[0]."'), ";
				$kkvl[] = "".$key_values[$ii]."";
			} else {
				$kvl .= $key_values[$ii].", ";
				$kyvl .= ":".$key_values[$ii].", ";
				$kkvl[] = "".$key_values[$ii]."";
			}
		}
		$kyvl = rtrim($kyvl, ", ");
		$kvl = rtrim($kvl, ", ");

		$sql_insert ="insert into ".$tbl_names." (".$kvl.") values (".$kyvl.")";
		$res_insert=oci_parse($connect_db,$sql_insert);

		for($ij = 0; $ij < count($field_values); $ij++) {
			$expld = explode("~~",$org_value[$ij]);
			if($expld[1] != '')
			{
				oci_bind_by_name($res_insert, $kkvl[$ij], $expld[1]);
			} else {
				oci_bind_by_name($res_insert, $kkvl[$ij], $org_value[$ij]);
			}
		}
		$res_execute = oci_execute($res_insert, OCI_NO_AUTO_COMMIT);
		
		if($res_execute) {
			return '1';
		}
		else {
			$arr_error = oci_error($res_insert);
			return $arr_error['message'];
		}
	} 
	catch(Exception $e) {
		return 'Message: ' .$e->getMessage();
	}
}

function commit_query()
{
	if($_SESSION['dbassign'] == '') {
		include("config.php");
	}
	elseif($_SESSION['username1'] != '' and $_SESSION['password1'] != '' and $_SESSION['host_db1'] != '') {
		$connect_db = oci_connect($_SESSION['username1'], $_SESSION['password1'], $_SESSION['host_db1'], 'AL32UTF8');
	}
	
	try {
		$res_execute = oci_commit($connect_db);
		if($res_execute) {
			return '1';
		}
		else {
			return '0';
		}
	} 
	catch(Exception $e) {
		return 'Message: ' .$e->getMessage();
	}
}

function rollback_query()
{
	if($_SESSION['dbassign'] == '') {
		include("config.php");
	}
	elseif($_SESSION['username1'] != '' and $_SESSION['password1'] != '' and $_SESSION['host_db1'] != '') {
		$connect_db = oci_connect($_SESSION['username1'], $_SESSION['password1'], $_SESSION['host_db1'], 'AL32UTF8');
	}
	
	try {
		$res_execute = oci_rollback($connect_db);
		if($res_execute) {
			return '1';
		}
		else {
			return '0';
		}
	} 
	catch(Exception $e) {
		return 'Message: ' .$e->getMessage();
	}
}

/* Insert Query Completed */

/* Update Query Started */
/*$tbl_name="group_reg";
$field_value=array();
$field_value['EMPPWD'] = 'test2';
$field_value['EMPCPWD'] = 'test2';
$where_conditions = 'EMPCODE = 14442';
echo update_query($field_value, $tbl_name, $where_conditions); */
function update_query($field_values, $tbl_names, $where_condition)
{
	if($_SESSION['dbassign'] == '') {
		include("config.php");
	}
	elseif($_SESSION['username1'] != '' and $_SESSION['password1'] != '' and $_SESSION['host_db1'] != '') {
		$connect_db = oci_connect($_SESSION['username1'], $_SESSION['password1'], $_SESSION['host_db1'], 'AL32UTF8');
	}
	
	try{
		$key_value = array_keys($field_values);
		$org_value = array_values($field_values);
		$key_values = array_keys($field_values);
		$org_values = array_values($field_values);
		
		for($ii = 0; $ii < count($field_values); $ii++) {
			$expld = explode("~~",$org_value[$ii]);
			//print_r($expld); echo "<br>";
			if($expld[1] != '')
			{
				$kyvl .= $key_values[$ii]." = to_date('".$expld[1]."', '".$expld[0]."'), ";
			} else {
				$kyvl .= $key_values[$ii]." = '". $org_values[$ii]."', ";	
			}	
		}
		$kyvl = rtrim($kyvl, ", ");
		
		$sql_update = "UPDATE ".$tbl_names." SET ".$kyvl." WHERE ".$where_condition;
		$res_update = oci_parse($connect_db,$sql_update);
		for($ij = 0; $ij < count($field_values); $ij++) {
			$expld = explode("~~",$org_value[$ij]);
			if($expld[1] != '')
			{
				oci_bind_by_name($res_update, $key_value[$ij], $expld[1]);
			} else {
				oci_bind_by_name($res_update, $key_value[$ij], $org_value[$ij]);
			}
		}
		$res_update1=oci_execute($res_update);
		
		if($res_update1) {
			return '1';
		}
		else {
			// return '0';
			$arr_error = oci_error($res_update);
			return $arr_error['message'];
		}
	}
	catch(Exception $e) {
		echo 'Message: ' .$e->getMessage();
	}
}
/* Update Query Completed */

/* Delete Query Started - Delete is not required here. Just update the deleted indicator 'Y' using update_query */
//echo delete_query('delete from group_reg where EMPCODE = 1');
function delete_query($delete_qry) 
{
	if($_SESSION['dbassign'] == '') {
		include("config.php");
	}
	elseif($_SESSION['username1'] != '' and $_SESSION['password1'] != '' and $_SESSION['host_db1'] != '') {
		$connect_db = oci_connect($_SESSION['username1'], $_SESSION['password1'], $_SESSION['host_db1'], 'AL32UTF8');
	}
	
	try {
		$parse_qry = oci_parse($connect_db, $delete_qry);
		$row_qry = oci_execute($parse_qry);
		if($row_qry) {
			return '1';
		}
		else {
			return '0';
		}
	} 
	catch(Exception $e) {
		echo 'Message: ' .$e->getMessage();
	}
}
/* Delete Query Completed - Delete is not required here. Just update the deleted indicator 'Y' using update_query */
/* This is for trandata DB */







/* This is for CRM DB */
/* Select Query Started */
//print_r(select_crmquery("select trmcode, trmname, trmdesc, trmstat, deleted from srm_terms_detail where trmcode = 1"));
function select_crmquery($sqlqry_selectcrm)
{
	if($_SESSION['dbassign'] == '') {
		include("config.php");
	}
	elseif($_SESSION['username2'] != '' and $_SESSION['password2'] != '' and $_SESSION['host_db2'] != '') {
		$connect_crmdb = oci_connect($_SESSION['username2'], $_SESSION['password2'], $_SESSION['host_db2']);
	}
	try {
		$parseqrycrm = oci_parse($connect_crmdb, $sqlqry_selectcrm); // Parse the connection and DB.
		oci_execute($parseqrycrm); // Execute the query
		$res_qrycrm = array();
		while(($row_qrycrm = oci_fetch_array($parseqrycrm, OCI_BOTH)) != false) {
			//print_r($row_qrycrm);
			$res_qrycrm[] = $row_qrycrm;
		}
		return $res_qrycrm;
	} 
	catch(Exception $e) {
		echo 'Message: ' .$e->getMessage();
	}
}
/* Select Query Completed */

/* Insert Query Started */
/* $tbl_name="srm_requirement";
$field_value=array();
$field_value['reqcode'] = 5;	
$field_value['reqname'] = 'Req 5';
$field_value['reqamnt'] = '1000';	
$field_value['reqpiec'] = '5';	
$field_value['reqmatr'] = 'Cotton'; 
$field_value['reqqlty'] = 'Fine Cotton'; 
$field_value['reqcmnt'] = 'Cmnt 1'; 
$field_value['reqnote'] = 'Note 1'; 
$field_value['reqstat'] = '1'; 
$field_value['supcode'] = '0'; 
$field_value['reqcmpl'] = '0'; 
$field_value['adduser'] = '14442'; 
$field_value['adddate'] = '20-OCT-15'; 

$field_value['edtuser'] = ''; 
$field_value['edtdate'] = ''; 
$field_valuecrm['deleted'] = '0'; 
$field_valuecrm['deluser'] = ''; 
$field_valuecrm['deldate'] = ''; 
$field_valuecrm['reqdeli'] = '01-DEC-14'; 

echo insert_crmquery($field_valuecrm, $tbl_name); */
function insert_crmquery($field_valuecrmscrm, $tbl_namescrm)
{
	if($_SESSION['dbassign'] == '') {
		include("config.php");
	}
	elseif($_SESSION['username2'] != '' and $_SESSION['password2'] != '' and $_SESSION['host_db2'] != '') {
		$connect_crmdb = oci_connect($_SESSION['username2'], $_SESSION['password2'], $_SESSION['host_db2']);
	}
	
	try {
		$key_valuecrm = array_keys($field_valuecrmscrm);
		$org_valuecrm = array_values($field_valuecrmscrm);
		$key_valuecrms = array_keys($field_valuecrmscrm);
		
		for($iicrm = 0; $iicrm < count($field_valuecrmscrm); $iicrm++) {
			//$kvlcrm .= $key_valuecrms[$iicrm].", ";
			//$kyvlcrm .= ":".$key_valuecrms[$iicrm].", ";
			//$kkvlcrm[] = "".$key_valuecrms[$iicrm]."";
			
			$expld = explode("~~",$org_valuecrm[$iicrm]);
			if($expld[1] != '')
			{
				$kvlcrm .= $key_valuecrms[$iicrm].", ";
				$kyvlcrm .= "to_date(:".$key_valuecrms[$iicrm].", '".$expld[0]."'), ";
				$kkvlcrm[] = "".$key_valuecrms[$iicrm]."";
			} else {
				$kvlcrm .= $key_valuecrms[$iicrm].", ";
				$kyvlcrm .= ":".$key_valuecrms[$iicrm].", ";
				$kkvlcrm[] = "".$key_valuecrms[$iicrm]."";
			}
		}
		$kyvlcrm = rtrim($kyvlcrm, ", ");
		$kvlcrm = rtrim($kvlcrm, ", ");
		
		$sql_insertcrm ="insert into ".$tbl_namescrm." (".$kvlcrm.") values (".$kyvlcrm.")";
		$res_insertcrm=oci_parse($connect_crmdb,$sql_insertcrm);
		
		for($ij = 0; $ij < count($field_valuecrmscrm); $ij++) {
			$expld = explode("~~",$org_valuecrm[$ij]);
			if($expld[1] != '')
			{
				oci_bind_by_name($res_insertcrm, $kkvlcrm[$ij], $expld[1]);
			} else {
				oci_bind_by_name($res_insertcrm, $kkvlcrm[$ij], $org_valuecrm[$ij]);
			}
			//oci_bind_by_name($res_insertcrm, $kkvlcrm[$ij], $org_valuecrm[$ij]);
		}
		$res_executecrm=oci_execute($res_insertcrm);
		
		if($res_executecrm) {
			return '1';
		}
		else {
			return '0';
		}
	} 
	catch(Exception $e) {
		echo 'Message: ' .$e->getMessage();
	}
}
/* Insert Query Completed */

/* Update Query Started */
/*$tbl_name="group_reg";
$field_valuecrm=array();
$field_valuecrm['EMPPWD'] = 'test2';
$field_valuecrm['EMPCPWD'] = 'test2';
$where_conditioncrms = 'EMPCODE = 14442';
echo update_crmquery($field_valuecrm, $tbl_name, $where_conditioncrms); */
function update_crmquery($field_valuecrmscrm, $tbl_namescrm, $where_conditioncrm)
{
	//include("config.php");
	if($_SESSION['dbassign'] == '') {
		include("config.php");
	}
	elseif($_SESSION['username2'] != '' and $_SESSION['password2'] != '' and $_SESSION['host_db2'] != '') {
		$connect_crmdb = oci_connect($_SESSION['username2'], $_SESSION['password2'], $_SESSION['host_db2']);
	}
	
	try{
		$key_valuecrm = array_keys($field_valuecrmscrm);
		$org_valuecrm = array_values($field_valuecrmscrm);
		$key_valuecrms = array_keys($field_valuecrmscrm);
		$org_valuecrms = array_values($field_valuecrmscrm);
		
		for($iicrm = 0; $iicrm < count($field_valuecrmscrm); $iicrm++) {
			$kyvlcrm .= $key_valuecrms[$iicrm]." = '". $org_valuecrms[$iicrm]."', ";		
		}
		$kyvlcrm = rtrim($kyvlcrm, ", ");
		
		$sql_updatecrm = "UPDATE ".$tbl_namescrm." SET ".$kyvlcrm." WHERE ".$where_conditioncrm;
		$res_updatecrm = oci_parse($connect_crmdb,$sql_updatecrm);
		for($ijcrm = 0; $ijcrm < count($field_valuecrmscrm); $ijcrmcrm++) {
			oci_bind_by_name($res_updatecrm, $key_valuecrm[$ijcrmcrm], $org_valuecrm[$ijcrmcrm]);
		}
		$res_updatecrm=oci_execute($res_updatecrm);
		
		if($res_updatecrm) {
			return '1';
		}
		else {
			return '0';
		}
	}
	catch(Exception $e) {
		echo 'Message: ' .$e->getMessage();
	}
}
/* Update Query Completed */

/* Delete Query Started - Delete is not required here. Just update the deleted indicator 'Y' using update_query */
//echo delete_crmquery('delete from group_reg where EMPCODE = 1');
/*function delete_crmquery($delete_qrycrmcrm) 
{
	if($_SESSION['dbassign'] == '') {
		include("config.php");
	}
	elseif($_SESSION['username2'] != '' and $_SESSION['password2'] != '' and $_SESSION['host_db2'] != '') {
		$connect_crmdb = oci_connect($_SESSION['username2'], $_SESSION['password2'], $_SESSION['host_db2']);
	}
	
	try {
		$parse_qrycrm = oci_parse($connect_crmdb, $delete_qrycrmcrm);
		$row_qrycrm = oci_execute($parse_qrycrm);
		if($row_qrycrm) {
			return '1';
		}
		else {
			return '0';
		}
	} 
	catch(Exception $e) {
		echo 'Message: ' .$e->getMessage();
	}
}*/
/* Delete Query Completed - Delete is not required here. Just update the deleted indicator 'Y' using update_query */
/* This is for CRM DB */






/* This is for trandata KTM DB */
/* Select Query Started */
//print_r(select_ktmquery("select trmcode, trmname, trmdesc, trmstat, deleted from srm_terms_detail where trmcode = 1"));
function select_ktmquery($sqlqry_select)
{
	if($_SESSION["dbassign"] == '') {
		include("config.php");
		//$ktm_connect_db = oci_connect($exp_tcs[0], $exp_tcs[1], $exp_tcs[2]);
	}
	elseif($_SESSION['ktm_username1'] != '' and $_SESSION['ktm_password1'] != '' and $_SESSION['ktm_host_db1'] != '') {
		$ktm_connect_db = oci_connect($_SESSION['ktm_username1'], $_SESSION['ktm_password1'], $_SESSION['ktm_host_db1'], 'AL32UTF8');
	}
	
	try {
		$parseqry = oci_parse($ktm_connect_db,$sqlqry_select); // Parse the connection and DB.
		oci_execute($parseqry); // Execute the query
		$res_qry = array();
		while(($row_qry = oci_fetch_array($parseqry, OCI_BOTH)) != false) {
			//print_r($row_qry);
			$res_qry[] = $row_qry;
		}
		return $res_qry;
	} 
	catch(Exception $e) {
		?>
			<script>window.location="tcsitadmin/index.php";</script>
		<?
		//echo 'Message: ' .$e->getMessage();
	}
}
/* Select Query Completed */


/* Insert Query Started */
/* $tbl_name="srm_requirement";
$field_value=array();
$field_value['reqcode'] = 5;	
$field_value['reqname'] = 'Req 5';
$field_value['reqamnt'] = '1000';	
$field_value['reqpiec'] = '5';	
$field_value['reqmatr'] = 'Cotton'; 
$field_value['reqqlty'] = 'Fine Cotton'; 
$field_value['reqcmnt'] = 'Cmnt 1'; 
$field_value['reqnote'] = 'Note 1'; 
$field_value['reqstat'] = '1'; 
$field_value['supcode'] = '0'; 
$field_value['reqcmpl'] = '0'; 
$field_value['adduser'] = '14442'; 
$field_value['adddate'] = '20-OCT-15'; 

$field_value['edtuser'] = ''; 
$field_value['edtdate'] = ''; 
$field_value['deleted'] = '0'; 
$field_value['deluser'] = ''; 
$field_value['deldate'] = ''; 
$field_value['reqdeli'] = '01-DEC-14'; 

echo insert_ktmquery($field_value, $tbl_name); */
function insert_ktmquery($field_values, $tbl_names)
{
	if($_SESSION['dbassign'] == '') {
		include("config.php");
	}
	elseif($_SESSION['ktm_username1'] != '' and $_SESSION['ktm_password1'] != '' and $_SESSION['ktm_host_db1'] != '') {
		$ktm_connect_db = oci_connect($_SESSION['ktm_username1'], $_SESSION['ktm_password1'], $_SESSION['ktm_host_db1'], 'AL32UTF8');
	}
	
	try {
		$key_value = array_keys($field_values);
		$org_value = array_values($field_values);
		$key_values = array_keys($field_values);
		
		for($ii = 0; $ii < count($field_values); $ii++) {
			$kvl .= $key_values[$ii].", ";
			$kyvl .= ":".$key_values[$ii].", ";
			//$kyvl .= " '".$org_value[$ii]."', ";
			$kkvl[] = "".$key_values[$ii]."";
		}
		$kyvl = rtrim($kyvl, ", ");
		$kvl = rtrim($kvl, ", ");
		
		$sql_insert ="insert into ".$tbl_names." (".$kvl.") values (".$kyvl.")";
		$res_insert=oci_parse($ktm_connect_db,$sql_insert);
		
		for($ij = 0; $ij < count($field_values); $ij++) {
			//echo "<br>==".$kkvl[$ij]."==".$org_value[$ij]."==";
			oci_bind_by_name($res_insert, $kkvl[$ij], $org_value[$ij]);
		}
		$res_execute=oci_execute($res_insert);
		
		if($res_execute) {
			return '1';
		}
		else {
			return '0';
		}
	} 
	catch(Exception $e) {
		echo 'Message: ' .$e->getMessage();
	}
}
/* Insert Query Completed */

/* Update Query Started */
/*$tbl_name="group_reg";
$field_value=array();
$field_value['EMPPWD'] = 'test2';
$field_value['EMPCPWD'] = 'test2';
$where_conditions = 'EMPCODE = 14442';
echo update_ktmquery($field_value, $tbl_name, $where_conditions); */
function update_ktmquery($field_values, $tbl_names, $where_condition)
{
	if($_SESSION['dbassign'] == '') {
		include("config.php");
	}
	elseif($_SESSION['ktm_username1'] != '' and $_SESSION['ktm_password1'] != '' and $_SESSION['ktm_host_db1'] != '') {
		$ktm_connect_db = oci_connect($_SESSION['ktm_username1'], $_SESSION['ktm_password1'], $_SESSION['ktm_host_db1'], 'AL32UTF8');
	}
	
	try{
		$key_value = array_keys($field_values);
		$org_value = array_values($field_values);
		$key_values = array_keys($field_values);
		$org_values = array_values($field_values);
		
		for($ii = 0; $ii < count($field_values); $ii++) {
			$kyvl .= $key_values[$ii]." = '". $org_values[$ii]."', ";		
		}
		$kyvl = rtrim($kyvl, ", ");
		
		$sql_update = "UPDATE ".$tbl_names." SET ".$kyvl." WHERE ".$where_condition;
		$res_update = oci_parse($ktm_connect_db,$sql_update);
		for($ij = 0; $ij < count($field_values); $ij++) {
			oci_bind_by_name($res_update, $key_value[$ij], $org_value[$ij]);
		}
		$res_update=oci_execute($res_update);
		
		if($res_update) {
			return '1';
		}
		else {
			return '0';
		}
	}
	catch(Exception $e) {
		echo 'Message: ' .$e->getMessage();
	}
}
/* Update Query Completed */

/* Delete Query Started - Delete is not required here. Just update the deleted indicator 'Y' using update_query */
//echo delete_ktmquery('delete from group_reg where EMPCODE = 1');
/*function delete_ktmquery($delete_qry) 
{
	if($_SESSION['dbassign'] == '') {
		include("config.php");
	}
	elseif($_SESSION['ktm_username1'] != '' and $_SESSION['ktm_password1'] != '' and $_SESSION['ktm_host_db1'] != '') {
		$ktm_connect_db = oci_connect($_SESSION['ktm_username1'], $_SESSION['ktm_password1'], $_SESSION['ktm_host_db1'], 'AL32UTF8');
	}
	
	try {
		$parse_qry = oci_parse($ktm_connect_db, $delete_qry);
		$row_qry = oci_execute($parse_qry);
		if($row_qry) {
			return '1';
		}
		else {
			return '0';
		}
	} 
	catch(Exception $e) {
		echo 'Message: ' .$e->getMessage();
	}
}*/
/* Delete Query Completed - Delete is not required here. Just update the deleted indicator 'Y' using update_query */
/* This is for KTM DB */





/* This is for TCS TEST trandata DB */
/* Select Query Started */
//print_r(select_testquery("select trmcode, trmname, trmdesc, trmstat, deleted from srm_terms_detail where trmcode = 1"));
function select_testquery($sqlqry_select)
{
	//include("config.php");
	//echo $_SESSION['tcstest_username'] ."!=".$_SESSION['tcstest_password'] ."!=".$_SESSION['tcstest_host_db'] ."!=";
	if($_SESSION["dbassign"] == '') {
		include("config.php");
		//$tcstest_connect_db = oci_connect($exp_tcs[0], $exp_tcs[1], $exp_tcs[2], 'AL32UTF8');
	}
	elseif($_SESSION['tcstest_username'] != '' and $_SESSION['tcstest_password'] != '' and $_SESSION['tcstest_host_db'] != '')  { 
		$tcstest_connect_db = oci_connect($_SESSION['tcstest_username'], $_SESSION['tcstest_password'], $_SESSION['tcstest_host_db'], 'AL32UTF8');
	}
	
	try {
		$parseqry = oci_parse($tcstest_connect_db,$sqlqry_select); // Parse the connection and DB.
		oci_execute($parseqry); // Execute the query
		$res_qry = array();
		
		while(($row_qry = oci_fetch_array($parseqry, OCI_BOTH)) != false) {
			//print_r($row_qry);
			$res_qry[] = $row_qry;
		}
		
		return $res_qry;
	} 
	catch(Exception $e) {
		?>
			<script>window.location="tcsitadmin/index.php";</script>
		<?
		//echo 'Message: ' .$e->getMessage();
	}
}
/* Select Query Completed */

/* Execute Procedure Started */
/* $procedure_name="proceudre_name";
$field_value=array();
$field_value['reqcode'] = 5;	
$field_value['reqname'] = 'Req 5';
$field_value['reqdeli'] = '01-DEC-14'; 
echo exec_procedure_query($field_value, $procedure_name); */
function exec_procedure_testquery($field_values, $procedure_names)
{
	
	if($_SESSION["dbassign"] == '') {
		include("config.php");
		//$tcstest_connect_db = oci_connect($exp_tcs[0], $exp_tcs[1], $exp_tcs[2], 'AL32UTF8');
	}
	elseif($_SESSION['tcstest_username'] != '' and $_SESSION['tcstest_password'] != '' and $_SESSION['tcstest_host_db'] != '') {
		$tcstest_connect_db = oci_connect($_SESSION['tcstest_username'], $_SESSION['tcstest_password'], $_SESSION['tcstest_host_db'], 'AL32UTF8');
	}
	
	try {
		$key_value = array_keys($field_values);
		$org_value = array_values($field_values);
		$key_values = array_keys($field_values);
		$kvl = ''; $kyvl = '';
		
		for($ii = 0; $ii < count($field_values); $ii++) {
			$kvl .= $key_values[$ii].", ";
			$kyvl .= ":".$key_values[$ii].", ";
			$kkvlg[] = ":".$key_values[$ii]."";
			//$kyvl .= " '".$org_value[$ii]."', ";
			$kkvl[] = "".$key_values[$ii]."";
			
			/* if($key_values[$ii] == 'PO_Y') {
				$var_numb[] = '7';
			}
			if($key_values[$ii] == 'PO_NO') {
				$var_numb[] = '10';
			}
			if($key_values[$ii] == 'GRP_SNO_') {
				$var_numb[] = '5';
			}
			if($key_values[$ii] == 'FR_SIZ') {
				$var_numb[] = '5';
			}
			if($key_values[$ii] == 'TO_SIZE') {
				$var_numb[] = '5';
			} */
		}
		
		$kyvl = rtrim($kyvl, ", ");
		$kvl = rtrim($kvl, ", ");
		
		$sql_exec_procedure ="begin ".$procedure_names."(".$kyvl."); end;";
		$res_exec_procedure=oci_parse($tcstest_connect_db, $sql_exec_procedure);
		
		for($ij = 0; $ij < count($field_values); $ij++) {
			//echo "<br>==".$kkvlg[$ij]."==".$org_value[$ij]."==";
			oci_bind_by_name($res_exec_procedure, $kkvlg[$ij], $org_value[$ij]); // or die('Error binding string');
		}
		$res_exec_procedure=oci_execute($res_exec_procedure); // or die ('Cannot Execute statment');
		
		if($res_exec_procedure) {
			return '1';
		}
		else {
			return '0';
		}
	} 
	catch(Exception $e) {
		?>
			<script>window.location="tcsitadmin/index.php";</script>
		<?
		//echo 'Message: ' .$e->getMessage();
	}
}
/* Execute Procedure Completed */

/* Insert Query Started */
/* $tbl_name="srm_requirement";
$field_value=array();
$field_value['reqcode'] = 5;	
$field_value['reqname'] = 'Req 5';
$field_value['reqamnt'] = '1000';	
$field_value['reqpiec'] = '5';	
$field_value['reqmatr'] = 'Cotton'; 
$field_value['reqqlty'] = 'Fine Cotton'; 
$field_value['reqcmnt'] = 'Cmnt 1'; 
$field_value['reqnote'] = 'Note 1'; 
$field_value['reqstat'] = '1'; 
$field_value['supcode'] = '0'; 
$field_value['reqcmpl'] = '0'; 
$field_value['adduser'] = '14442'; 
$field_value['adddate'] = '20-OCT-15'; 

$field_value['edtuser'] = ''; 
$field_value['edtdate'] = ''; 
$field_value['deleted'] = '0'; 
$field_value['deluser'] = ''; 
$field_value['deldate'] = ''; 
$field_value['reqdeli'] = '01-DEC-14'; 

echo insert_testquery($field_value, $tbl_name); */

function insert_testquery($field_values, $tbl_names)
{	
	//include("config.php");
  // echo $_SESSION['tcstest_username'] ."!=".$_SESSION['tcstest_password'] ."!=".$_SESSION['tcstest_host_db'] ."!=";
	if($_SESSION['dbassign'] == '') {
		include("config.php");
	}
	elseif($_SESSION['tcstest_username'] != '' and $_SESSION['tcstest_password'] != '' and $_SESSION['tcstest_host_db'] != '') {
		$tcstest_connect_db = oci_connect($_SESSION['tcstest_username'], $_SESSION['tcstest_password'], $_SESSION['tcstest_host_db'], 'AL32UTF8');
		
	}
	
	try {
		$key_value = array_keys($field_values);
		$org_value = array_values($field_values);
		$key_values = array_keys($field_values);
		
		for($ii = 0; $ii < count($field_values); $ii++) {
			$expld = explode("~~",$org_value[$ii]);
			if($expld[1] != '')
			{
				$kvl .= $key_values[$ii].", ";
				$kyvl .= "to_date(:".$key_values[$ii].", '".$expld[0]."'), ";
				$kkvl[] = "".$key_values[$ii]."";
			} else {
				$kvl .= $key_values[$ii].", ";
				$kyvl .= ":".$key_values[$ii].", ";
				$kkvl[] = "".$key_values[$ii]."";
			}
		}
		$kyvl = rtrim($kyvl, ", ");
		$kvl = rtrim($kvl, ", ");

		$sql_insert ="insert into ".$tbl_names." (".$kvl.") values (".$kyvl.")";
		$res_insert=oci_parse($tcstest_connect_db,$sql_insert);

		for($ij = 0; $ij < count($field_values); $ij++) {
			$expld = explode("~~",$org_value[$ij]);
			if($expld[1] != '')
			{
				oci_bind_by_name($res_insert, $kkvl[$ij], $expld[1]);
			} else {
				oci_bind_by_name($res_insert, $kkvl[$ij], $org_value[$ij]);
			}
		}
		$res_execute=oci_execute($res_insert);
		
		if($res_execute) {
			return '1';
		}
		else {
			return '0';
		}
	} 
	catch(Exception $e) {
		echo 'Message: ' .$e->getMessage();
	}
}

/* Insert Query Completed */

/* Update Query Started */
/*$tbl_name="group_reg";
$field_value=array();
$field_value['EMPPWD'] = 'test2';
$field_value['EMPCPWD'] = 'test2';
$where_conditions = 'EMPCODE = 14442';
echo update_testquery($field_value, $tbl_name, $where_conditions); */
//include("config.php");
function update_testquery($field_values, $tbl_names, $where_condition)
{
	//include("config.php");
	//echo $_SESSION['tcstest_username'] ."!=".$_SESSION['tcstest_password'] ."!=".$_SESSION['tcstest_host_db'] ."!=";
	if($_SESSION['dbassign'] == '') {
		include("config.php");
	}
	elseif($_SESSION['tcstest_username'] != '' and $_SESSION['tcstest_password'] != '' and $_SESSION['tcstest_host_db'] != '') {
		$tcstest_connect_db = oci_connect($_SESSION['tcstest_username'], $_SESSION['tcstest_password'], $_SESSION['tcstest_host_db'], 'AL32UTF8');
	}
	
	try{
		$key_value = array_keys($field_values);
		$org_value = array_values($field_values);
		$key_values = array_keys($field_values);
		$org_values = array_values($field_values);
		
		for($ii = 0; $ii < count($field_values); $ii++) {
			$expld = explode("~~",$org_value[$ii]);
			//print_r($expld); echo "<br>";
			if($expld[1] != '')
			{
				$kyvl .= $key_values[$ii]." = to_date('".$expld[1]."', '".$expld[0]."'), ";
			} else {
				$kyvl .= $key_values[$ii]." = '". $org_values[$ii]."', ";	
			}	
		}
		$kyvl = rtrim($kyvl, ", ");
		
		$sql_update = "UPDATE ".$tbl_names." SET ".$kyvl." WHERE ".$where_condition;
		$res_update = oci_parse($tcstest_connect_db,$sql_update);
		for($ij = 0; $ij < count($field_values); $ij++) {
			oci_bind_by_name($res_update, $key_value[$ij], $org_value[$ij]);
		}
		$res_update=oci_execute($res_update);
		
		if($res_update) {
			return '1';
		}
		else {
			return '0';
		}
	}
	catch(Exception $e) {
		//echo 'Message: ' .$e->getMessage();
	}
}
/* Update Query Completed */

/* Delete Query Started - Delete is not required here. Just update the deleted indicator 'Y' using update_query */
//echo delete_query('delete from group_reg where EMPCODE = 1');
function delete_testquery($delete_qry) 
{
	if($_SESSION['dbassign'] == '') {
		include("config.php");
	}
	elseif($_SESSION['tcstest_username'] != '' and $_SESSION['tcstest_password'] != '' and $_SESSION['tcstest_host_db'] != '') {
		$tcstest_connect_db = oci_connect($_SESSION['tcstest_username'], $_SESSION['tcstest_password'], $_SESSION['tcstest_host_db'], 'AL32UTF8');
	}
	
	try {
		$parse_qry = oci_parse($tcstest_connect_db, $delete_qry);
		$row_qry = oci_execute($parse_qry);
		if($row_qry) {
			return '1';
		}
		else {
			return '0';
		}
	} 
	catch(Exception $e) {
		echo 'Message: ' .$e->getMessage();
	}
}
/* Delete Query Completed - Delete is not required here. Just update the deleted indicator 'Y' using update_query */
/* This is for Trandata - Test DB */






/* This is for CRM Test DB */
/* Select Query Started */
//print_r(select_crmtestquery("select trmcode, trmname, trmdesc, trmstat, deleted from srm_terms_detail where trmcode = 1"));
function select_crmtestquery($sqlqry_selectcrmtest)
{
	//include("config.php");
	//echo "***".$_SESSION['crmtestusername2'].$_SESSION['crmtestpassword2'].$_SESSION['crmtesthost_db2'];
	if($_SESSION['dbassign'] == '') {
		include("config.php");
	}
	elseif($_SESSION['crmtestusername2'] != '' and $_SESSION['crmtestpassword2'] != '' and $_SESSION['crmtesthost_db2'] != '') {
		$crmtestconnect_crmdb = oci_connect($_SESSION['crmtestusername2'], $_SESSION['crmtestpassword2'], $_SESSION['crmtesthost_db2'], 'AL32UTF8');
	}
	try {
		$parseqrycrmtest = oci_parse($crmtestconnect_crmdb, $sqlqry_selectcrmtest); // Parse the connection and DB.
		oci_execute($parseqrycrmtest); // Execute the query
		$res_qrycrmtest = array();
		while(($row_qrycrmtest = oci_fetch_array($parseqrycrmtest, OCI_BOTH)) != false) {
			//print_r($row_qrycrm);
			$res_qrycrmtest[] = $row_qrycrmtest;
		}
		return $res_qrycrmtest;
	} 
	catch(Exception $e) {
		echo 'Message: ' .$e->getMessage();
	}
}
/* Select Query Completed */

/* Insert Query Started */
/* $tbl_name="srm_requirement";
$field_value=array();
$field_value['reqcode'] = 5;	
$field_value['reqname'] = 'Req 5';
$field_value['reqamnt'] = '1000';	
$field_value['reqpiec'] = '5';	
$field_value['reqmatr'] = 'Cotton'; 
$field_value['reqqlty'] = 'Fine Cotton'; 
$field_value['reqcmnt'] = 'Cmnt 1'; 
$field_value['reqnote'] = 'Note 1'; 
$field_value['reqstat'] = '1'; 
$field_value['supcode'] = '0'; 
$field_value['reqcmpl'] = '0'; 
$field_value['adduser'] = '14442'; 
$field_value['adddate'] = '20-OCT-15'; 

$field_value['edtuser'] = ''; 
$field_value['edtdate'] = ''; 
$field_valuecrm['deleted'] = '0'; 
$field_valuecrm['deluser'] = ''; 
$field_valuecrm['deldate'] = ''; 
$field_valuecrm['reqdeli'] = '01-DEC-14'; 
echo insert_crmtestquery($field_valuecrm, $tbl_name); */
function insert_crmtestquery($field_valuecrmscrmtest, $tbl_namescrmtest)
{
	if($_SESSION['dbassign'] == '') {
		include("config.php");
	}
	elseif($_SESSION['crmtestusername2'] != '' and $_SESSION['crmtestpassword2'] != '' and $_SESSION['crmtesthost_db2'] != '') {
		$crmtestconnect_crmdb = oci_connect($_SESSION['crmtestusername2'], $_SESSION['crmtestpassword2'], $_SESSION['crmtesthost_db2'], 'AL32UTF8');
	}

	try {
		$key_valuecrmtest = array_keys($field_valuecrmscrmtest);
		$org_valuecrmtest = array_values($field_valuecrmscrmtest);
		$key_valuecrmstest = array_keys($field_valuecrmscrmtest);
		for($iicrmtest = 0; $iicrmtest < count($field_valuecrmscrmtest); $iicrmtest++) {
			$expld = explode("~~",$org_valuecrmtest[$iicrmtest]);
			if($expld[1] != '')
			{
				$kvlcrmtest .= $key_valuecrmstest[$iicrmtest].", ";
				$kyvlcrmtest .= "to_date(:".$key_valuecrmstest[$iicrmtest].", '".$expld[0]."'), ";
				$kkvlcrmtest[] = "".$key_valuecrmstest[$iicrmtest]."";
			} else {
				$kvlcrmtest .= $key_valuecrmstest[$iicrmtest].", ";
				$kyvlcrmtest .= ":".$key_valuecrmstest[$iicrmtest].", ";
				$kkvlcrmtest[] = "".$key_valuecrmstest[$iicrmtest]."";
			}

			//$kvlcrmtest .= $key_valuecrmstest[$iicrmtest].", ";
			//$kyvlcrmtest .= ":".$key_valuecrmstest[$iicrmtest].", ";
			//$kkvlcrmtest[] = "".$key_valuecrmstest[$iicrmtest]."";
		}
		$kyvlcrmtest = rtrim($kyvlcrmtest, ", ");
		$kvlcrmtest = rtrim($kvlcrmtest, ", ");
		
		$sql_insertcrmtest ="insert into ".$tbl_namescrmtest." (".$kvlcrmtest.") values (".$kyvlcrmtest.")";
		$res_insertcrmtest=oci_parse($crmtestconnect_crmdb,$sql_insertcrmtest);
		
		for($ij = 0; $ij < count($field_valuecrmscrmtest); $ij++) {
			$expld = explode("~~",$org_valuecrmtest[$ij]);
			if($expld[1] != '')
			{
				oci_bind_by_name($res_insertcrmtest, $kkvlcrmtest[$ij], $expld[1]);
			} else {
				oci_bind_by_name($res_insertcrmtest, $kkvlcrmtest[$ij], $org_valuecrmtest[$ij]);
			}
			//oci_bind_by_name($res_insertcrmtest, $kkvlcrmtest[$ij], $org_valuecrmtest[$ij]);
		}
		$res_executecrmtest=oci_execute($res_insertcrmtest);
		
		if($res_executecrmtest) {
			return '1';
		}
		else {
			return '0';
		}
	} 
	catch(Exception $e) {
		echo 'Message: ' .$e->getMessage();
	}
}
/* Insert Query Completed */

/* Update Query Started */
/*$tbl_name="group_reg";
$field_valuecrm=array();
$field_valuecrm['EMPPWD'] = 'test2';
$field_valuecrm['EMPCPWD'] = 'test2';
$where_conditioncrms = 'EMPCODE = 14442';
echo update_crmtestquery($field_valuecrm, $tbl_name, $where_conditioncrms); */
function update_crmtestquery($field_valuecrmscrmtest, $tbl_namescrmtest, $where_conditioncrmtest)
{
	if($_SESSION['dbassign'] == '') {
		include("config.php");
	}
	elseif($_SESSION['crmtestusername2'] != '' and $_SESSION['crmtestpassword2'] != '' and $_SESSION['crmtesthost_db2'] != '') {
		$crmtestconnect_crmdb = oci_connect($_SESSION['crmtestusername2'], $_SESSION['crmtestpassword2'], $_SESSION['crmtesthost_db2'], 'AL32UTF8');
	}
	
	try{
		$key_valuecrmtest = array_keys($field_valuecrmscrmtest);
		$org_valuecrmtest = array_values($field_valuecrmscrmtest);
		$key_valuecrmstest = array_keys($field_valuecrmscrmtest);
		$org_valuecrmstest = array_values($field_valuecrmscrmtest);
		
		for($iicrmtest = 0; $iicrmtest < count($field_valuecrmscrmtest); $iicrmtest++) {
			//$kyvlcrmtest .= $key_valuecrmstest[$iicrmtest]." = '". $org_valuecrmstest[$iicrmtest]."', ";		
			$expld = explode("~~",$org_valuecrmstest[$iicrmtest]);
			//print_r($expld); echo "<br>";
			if($expld[1] != '')
			{
				$kyvlcrmtest .= $key_valuecrmstest[$iicrmtest]." = to_date('".$expld[1]."', '".$expld[0]."'), ";
			} else {
				$kyvlcrmtest .= $key_valuecrmstest[$iicrmtest]." = '". $org_valuecrmstest[$iicrmtest]."', ";	
			}
		}
		$kyvlcrmtest = rtrim($kyvlcrmtest, ", ");
		
		$sql_updatecrmtest = "UPDATE ".$tbl_namescrmtest." SET ".$kyvlcrmtest." WHERE ".$where_conditioncrmtest;
		$res_updatecrmtest = oci_parse($crmtestconnect_crmdb,$sql_updatecrmtest);
		for($ijcrmcrmtest = 0; $ijcrmcrmtest < count($field_valuecrmscrmtest); $ijcrmcrmtest++) {
			//oci_bind_by_name($res_updatecrmtest, $key_valuecrmtest[$ijcrmcrmtest], $org_valuecrmtest[$ijcrmcrmtest]);
			$expld = explode("~~",$org_valuecrmtest[$ijcrmcrmtest]);
			if($expld[1] != '')
			{
				oci_bind_by_name($res_updatecrmtest, $key_valuecrmtest[$ijcrmcrmtest], $expld[1]);
			} else {
				oci_bind_by_name($res_updatecrmtest, $key_valuecrmtest[$ijcrmcrmtest], $org_valuecrmtest[$ijcrmcrmtest]);
			}
		}
		$res_updatecrmtest=oci_execute($res_updatecrmtest);
		
		if($res_updatecrmtest) {
			return '1';
		}
		else {
			return '0';
		}
	}
	catch(Exception $e) {
		echo 'Message: ' .$e->getMessage();
	}
}
/* Update Query Completed */

/* Delete Query Started - Delete is not required here. Just update the deleted indicator 'Y' using update_query */
//echo delete_crmtestquery('delete from group_reg where EMPCODE = 1');
function delete_crmtestquery($delete_qrycrmcrmtest) 
{
	if($_SESSION['dbassign'] == '') {
		include("config.php");
	}
	elseif($_SESSION['crmtestusername2'] != '' and $_SESSION['crmtestpassword2'] != '' and $_SESSION['crmtesthost_db2'] != '') {
		$crmtestconnect_crmdb = oci_connect($_SESSION['crmtestusername2'], $_SESSION['crmtestpassword2'], $_SESSION['crmtesthost_db2'], 'AL32UTF8');
	}
	
	try {
		$parse_qrycrmtest = oci_parse($crmtestconnect_crmdb, $delete_qrycrmcrmtest);
		$row_qrycrmtest = oci_execute($parse_qrycrmtest);
		if($row_qrycrmtest) {
			return '1';
		}
		else {
			return '0';
		}
	} 
	catch(Exception $e) {
		echo 'Message: ' .$e->getMessage();
	}
}
/* Delete Query Completed - Delete is not required here. Just update the deleted indicator 'Y' using update_query */
/* This is for CRM Test DB */







/* This is for KSREQ DB */
/* Select Query Started */
//print_r(select_ksreqquery("select trmcode, trmname, trmdesc, trmstat, deleted from srm_terms_detail where trmcode = 1"));
function select_ksreqquery($sqlqry_selectksreq)
{
	//include("config.php");
	//echo "***".$_SESSION['ksrequsername2'].$_SESSION['ksreqpassword2'].$_SESSION['ksreqhost_db2'];
	if($_SESSION['dbassign'] == '') {
		include("config.php");
	}
	elseif($_SESSION['ksrequsername2'] != '' and $_SESSION['ksreqpassword2'] != '' and $_SESSION['ksreqhost_db2'] != '') {
		$ksreqconnect_crmdb = oci_connect($_SESSION['ksrequsername2'], $_SESSION['ksreqpassword2'], $_SESSION['ksreqhost_db2'], 'AL32UTF8');
	}
	try {
		$parseqryksreq = oci_parse($ksreqconnect_crmdb, $sqlqry_selectksreq); // Parse the connection and DB.
		oci_execute($parseqryksreq); // Execute the query
		$res_qryksreq = array();
		while(($row_qryksreq = oci_fetch_array($parseqryksreq, OCI_BOTH)) != false) {
			//print_r($row_qrycrm);
			$res_qryksreq[] = $row_qryksreq;
		}
		return $res_qryksreq;
	} 
	catch(Exception $e) {
		echo 'Message: ' .$e->getMessage();
	}
}
/* Select Query Completed */

/* Insert Query Started */
/* $tbl_name="srm_requirement";
$field_value=array();
$field_value['reqcode'] = 5;	
$field_value['reqname'] = 'Req 5';
$field_value['reqamnt'] = '1000';	
$field_value['reqpiec'] = '5';	
$field_value['reqmatr'] = 'Cotton'; 
$field_value['reqqlty'] = 'Fine Cotton'; 
$field_value['reqcmnt'] = 'Cmnt 1'; 
$field_value['reqnote'] = 'Note 1'; 
$field_value['reqstat'] = '1'; 
$field_value['supcode'] = '0'; 
$field_value['reqcmpl'] = '0'; 
$field_value['adduser'] = '14442'; 
$field_value['adddate'] = '20-OCT-15'; 

$field_value['edtuser'] = ''; 
$field_value['edtdate'] = ''; 
$field_valuecrm['deleted'] = '0'; 
$field_valuecrm['deluser'] = ''; 
$field_valuecrm['deldate'] = ''; 
$field_valuecrm['reqdeli'] = '01-DEC-14'; 
echo insert_ksreqquery($field_valuecrm, $tbl_name); */
function insert_ksreqquery($field_valuecrmsksreq, $tbl_namesksreq)
{
	if($_SESSION['dbassign'] == '') {
		include("config.php");
	}
	elseif($_SESSION['ksrequsername2'] != '' and $_SESSION['ksreqpassword2'] != '' and $_SESSION['ksreqhost_db2'] != '') {
		$ksreqconnect_crmdb = oci_connect($_SESSION['ksrequsername2'], $_SESSION['ksreqpassword2'], $_SESSION['ksreqhost_db2'], 'AL32UTF8');
	}

	try {
		$key_valueksreq = array_keys($field_valuecrmsksreq);
		$org_valueksreq = array_values($field_valuecrmsksreq);
		$key_valuecrmstest = array_keys($field_valuecrmsksreq);
		for($iiksreq = 0; $iiksreq < count($field_valuecrmsksreq); $iiksreq++) {
			$expld = explode("~~",$org_valueksreq[$iiksreq]);
			if($expld[1] != '')
			{
				$kvlksreq .= $key_valuecrmstest[$iiksreq].", ";
				$kyvlksreq .= "to_date(:".$key_valuecrmstest[$iiksreq].", '".$expld[0]."'), ";
				$kkvlksreq[] = "".$key_valuecrmstest[$iiksreq]."";
			} else {
				$kvlksreq .= $key_valuecrmstest[$iiksreq].", ";
				$kyvlksreq .= ":".$key_valuecrmstest[$iiksreq].", ";
				$kkvlksreq[] = "".$key_valuecrmstest[$iiksreq]."";
			}

			//$kvlksreq .= $key_valuecrmstest[$iiksreq].", ";
			//$kyvlksreq .= ":".$key_valuecrmstest[$iiksreq].", ";
			//$kkvlksreq[] = "".$key_valuecrmstest[$iiksreq]."";
		}
		$kyvlksreq = rtrim($kyvlksreq, ", ");
		$kvlksreq = rtrim($kvlksreq, ", ");
		
		$sql_insertksreq ="insert into ".$tbl_namesksreq." (".$kvlksreq.") values (".$kyvlksreq.")";
		$res_insertksreq=oci_parse($ksreqconnect_crmdb,$sql_insertksreq);
		
		for($ij = 0; $ij < count($field_valuecrmsksreq); $ij++) {
			$expld = explode("~~",$org_valueksreq[$ij]);
			if($expld[1] != '')
			{
				oci_bind_by_name($res_insertksreq, $kkvlksreq[$ij], $expld[1]);
			} else {
				oci_bind_by_name($res_insertksreq, $kkvlksreq[$ij], $org_valueksreq[$ij]);
			}
			//oci_bind_by_name($res_insertksreq, $kkvlksreq[$ij], $org_valueksreq[$ij]);
		}
		$res_executeksreq=oci_execute($res_insertksreq);
		
		if($res_executeksreq) {
			return '1';
		}
		else {
			return '0';
		}
	} 
	catch(Exception $e) {
		echo 'Message: ' .$e->getMessage();
	}
}

function insert_ksreqquerys($field_valuecrmsksreq, $tbl_namesksreq)
{
	if($_SESSION['dbassign'] == '') {
		include("config.php");
	}
	elseif($_SESSION['ksrequsername2'] != '' and $_SESSION['ksreqpassword2'] != '' and $_SESSION['ksreqhost_db2'] != '') {
		$ksreqconnect_crmdb = oci_connect($_SESSION['ksrequsername2'], $_SESSION['ksreqpassword2'], $_SESSION['ksreqhost_db2'], 'AL32UTF8');
	}

	try {
		$key_valueksreq = array_keys($field_valuecrmsksreq);
		$org_valueksreq = array_values($field_valuecrmsksreq);
		$key_valuecrmstest = array_keys($field_valuecrmsksreq);
		for($iiksreq = 0; $iiksreq < count($field_valuecrmsksreq); $iiksreq++) {
			$expld = explode("~~",$org_valueksreq[$iiksreq]);
			if($expld[1] != '')
			{
				$kvlksreq .= $key_valuecrmstest[$iiksreq].", ";
				$kyvlksreq .= "to_date(:".$key_valuecrmstest[$iiksreq].", '".$expld[0]."'), ";
				$kkvlksreq[] = "".$key_valuecrmstest[$iiksreq]."";
			} else {
				$kvlksreq .= $key_valuecrmstest[$iiksreq].", ";
				$kyvlksreq .= ":".$key_valuecrmstest[$iiksreq].", ";
				$kkvlksreq[] = "".$key_valuecrmstest[$iiksreq]."";
			}

			//$kvlksreq .= $key_valuecrmstest[$iiksreq].", ";
			//$kyvlksreq .= ":".$key_valuecrmstest[$iiksreq].", ";
			//$kkvlksreq[] = "".$key_valuecrmstest[$iiksreq]."";
		}
		$kyvlksreq = rtrim($kyvlksreq, ", ");
		$kvlksreq = rtrim($kvlksreq, ", ");
		
		$sql_insertksreq ="insert into ".$tbl_namesksreq." (".$kvlksreq.") values (".$kyvlksreq.")";
		$res_insertksreq=oci_parse($ksreqconnect_crmdb,$sql_insertksreq);
		
		for($ij = 0; $ij < count($field_valuecrmsksreq); $ij++) {
			$expld = explode("~~",$org_valueksreq[$ij]);
			if($expld[1] != '')
			{
				oci_bind_by_name($res_insertksreq, $kkvlksreq[$ij], $expld[1]);
			} else {
				oci_bind_by_name($res_insertksreq, $kkvlksreq[$ij], $org_valueksreq[$ij]);
			}
			//oci_bind_by_name($res_insertksreq, $kkvlksreq[$ij], $org_valueksreq[$ij]);
		}
		$res_execute = oci_execute($res_insertksreq);
		
		if($res_execute) {
			return '1';
		}
		else {
			$arr_error = oci_error($res_insertksreq);
			return $arr_error['message'];
		}
	} 
	catch(Exception $e) {
		echo 'Message: ' .$e->getMessage();
	}
}
/* Insert Query Completed */

/* Update Query Started */
/*$tbl_name="group_reg";
$field_valuecrm=array();
$field_valuecrm['EMPPWD'] = 'test2';
$field_valuecrm['EMPCPWD'] = 'test2';
$where_conditioncrms = 'EMPCODE = 14442';
echo update_ksreqquery($field_valuecrm, $tbl_name, $where_conditioncrms); */
function update_ksreqquery($field_valuecrmsksreq, $tbl_namesksreq, $where_conditionksreq)
{
	if($_SESSION['dbassign'] == '') {
		include("config.php");
	}
	elseif($_SESSION['ksrequsername2'] != '' and $_SESSION['ksreqpassword2'] != '' and $_SESSION['ksreqhost_db2'] != '') {
		$ksreqconnect_crmdb = oci_connect($_SESSION['ksrequsername2'], $_SESSION['ksreqpassword2'], $_SESSION['ksreqhost_db2'], 'AL32UTF8');
	}
	
	try{
		$key_valueksreq = array_keys($field_valuecrmsksreq);
		$org_valueksreq = array_values($field_valuecrmsksreq);
		$key_valuecrmstest = array_keys($field_valuecrmsksreq);
		$org_valuecrmstest = array_values($field_valuecrmsksreq);
		
		for($iiksreq = 0; $iiksreq < count($field_valuecrmsksreq); $iiksreq++) {
			//$kyvlksreq .= $key_valuecrmstest[$iiksreq]." = '". $org_valuecrmstest[$iiksreq]."', ";		
			$expld = explode("~~",$org_valuecrmstest[$iiksreq]);
			//print_r($expld); echo "<br>";
			if($expld[1] != '')
			{
				$kyvlksreq .= $key_valuecrmstest[$iiksreq]." = to_date('".$expld[1]."', '".$expld[0]."'), ";
			} else {
				$kyvlksreq .= $key_valuecrmstest[$iiksreq]." = '". $org_valuecrmstest[$iiksreq]."', ";	
			}
		}
		$kyvlksreq = rtrim($kyvlksreq, ", ");
		
		 $sql_updateksreq = "UPDATE ".$tbl_namesksreq." SET ".$kyvlksreq." WHERE ".$where_conditionksreq;
		$res_updateksreq = oci_parse($ksreqconnect_crmdb,$sql_updateksreq);
		for($ijcrmksreq = 0; $ijcrmksreq < count($field_valuecrmsksreq); $ijcrmksreq++) {
			//oci_bind_by_name($res_updateksreq, $key_valueksreq[$ijcrmksreq], $org_valueksreq[$ijcrmksreq]);
			$expld = explode("~~",$org_valueksreq[$ijcrmksreq]);
			if($expld[1] != '')
			{
				oci_bind_by_name($res_updateksreq, $key_valueksreq[$ijcrmksreq], $expld[1]);
			} else {
				oci_bind_by_name($res_updateksreq, $key_valueksreq[$ijcrmksreq], $org_valueksreq[$ijcrmksreq]);
			}
		}
		$res_updateksreq=oci_execute($res_updateksreq);
		
		if($res_updateksreq) {
			return '1';
		}
		else {
			return '0';
		}
	}
	catch(Exception $e) {
		echo 'Message: ' .$e->getMessage();
	}
}
/* Update Query Completed */

/* Delete Query Started - Delete is not required here. Just update the deleted indicator 'Y' using update_query */
//echo delete_ksreqquery('delete from group_reg where EMPCODE = 1');
function delete_ksreqquery($delete_qrycrmksreq) 
{
	if($_SESSION['dbassign'] == '') {
		include("config.php");
	}
	elseif($_SESSION['ksrequsername2'] != '' and $_SESSION['ksreqpassword2'] != '' and $_SESSION['ksreqhost_db2'] != '') {
		$ksreqconnect_crmdb = oci_connect($_SESSION['ksrequsername2'], $_SESSION['ksreqpassword2'], $_SESSION['ksreqhost_db2'], 'AL32UTF8');
	}
	
	try {
		$parse_qryksreq = oci_parse($ksreqconnect_crmdb, $delete_qrycrmksreq);
		$row_qryksreq = oci_execute($parse_qryksreq);
		if($row_qryksreq) {
			return '1';
		}
		else {
			return '0';
		}
	} 
	catch(Exception $e) {
		echo 'Message: ' .$e->getMessage();
	}
}
/* Delete Query Completed - Delete is not required here. Just update the deleted indicator 'Y' using update_query */
/* This is for KSREQ DB */
?>