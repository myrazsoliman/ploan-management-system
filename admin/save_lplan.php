<?php
require_once '../class.php';
$db = new db_class();

if (isset($_POST['save'])) {
	$lplan_month = $_POST['lplan_month'];
	$lplan_interest = $_POST['lplan_interest'];
	$lplan_penalty = $_POST['lplan_penalty'];
	if (isset($_POST['lplan_type'])) {
		$lplan_type = $_POST['lplan_type'];
	} else {
		die("Error: Loan type is not set.");
	}


	$db->save_lplan($lplan_month, $lplan_interest, $lplan_penalty, $lplan_type);
	header("location: loan_plan.php");
}
?>