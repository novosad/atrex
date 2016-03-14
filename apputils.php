<?php

//This script is used only for retrieving information from the ZC installation that requires it to be initialized
include('../includes/configure.php'); //pull in the osCommerce configuration information
//$lcwd = getcwd();
//chdir(DIR_FS_CATALOG);
//include('includes/application_top.php');
//include('includes/classes/payment.php');

define('DEBUG_MODE', 'NO');

function ComparePassword()
{
    if (DEBUG_MODE == 'YES') {
        return true;
    }
    $PasswordHash = ""; //set to default value
    if (isset($_POST['DBPassword'])) $PasswordHash = $_POST['DBPassword'];
    $LocalHash = strtoupper(md5(DB_SERVER_PASSWORD));
    if ($LocalHash <> $PasswordHash) return false;
    return true;
}

function GetPaymentMethods()
{
    $payment = new payment;

    $selection_array = array();
    if (is_array($payment->modules)) {
        reset($payment->modules);
        while (list(, $value) = each($payment->modules)) {
            $class = substr($value, 0, strrpos($value, '.'));
            $payment->selected_module = $class;
            $payment2 = new payment($class);
            $selection_array[] = $payment2->paymentClass->title;
        }
    }

    $xml = '<?xml version="1.0"?><table>';
    $field_name = 'payment_method';
    foreach ($selection_array as $value) {
        $xml .= "<row>";
        $value = htmlspecialchars($value);
        $xml .= "<$field_name>";
        $xml .= "$value";
        $xml .= "</$field_name>";
        $xml .= "</row>";
    }
    $xml .= "</table>";

    echo chr(10);
    echo "<IMPORT_DATA>";
    echo $xml;
    echo "</IMPORT_DATA>";
    echo chr(10);
    return;

    echo chr(10);
    echo "<IMPORT_DATA>";
    echo "payment_method";
    echo chr(30);

    foreach ($selection_array as $value) {
        echo chr(10);
        echo $value;
        echo chr(30);
    }

    echo "</IMPORT_DATA>";
    echo chr(10);

}

// *** Beginning of script
if (ComparePassword() == false) {
    die("Invalid Password");
}

$Action = ""; //set to default value to do nothing
if (isset($_GET['Action'])) $Action = $_GET['Action'];

if (DEBUG_MODE == 'YES') {
    $Action = "GetPaymentMethods";
}

//get status values
if ($Action == "GetPaymentMethods") GetPaymentMethods();

chdir($lcwd);


?>