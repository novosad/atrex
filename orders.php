<?php

include_once('defines.php'); //pull in the local configuration
include_once('commondb.php'); //pull in the base database functionss
include_once('../includes/configure.php'); //pull in the osCommerce configuration information
include_once('../includes/database_tables.php'); //pull in the osCommerce table name information

define('DEBUG_MODE', 'NO');
define('RETURN_XML', 'YES');

function DisplayStatus($aStatus)
{
    if (strlen($aStatus) > 0) {
        echo $aStatus;
    }
    echo '<br>';
    flush();
}


function GetQueryFieldOffset($q, $field_name)
{
    $field_offset = -1;
    for ($field_count = 0; $field_count < $q->numfields; $field_count++) {
        $q->field_info($field_count);
        $temp_val = $q->field_name;
        if ($temp_val == $field_name) {
            $field_offset = $field_count;
            break;
        }
    }
    return $field_offset;
}

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

function ResultsToXML($q)
{
//    $direct_types = array("int", "decimal");
    $xml = '<?xml version="1.0"?><table>';
    for ($n = 0; $n < $q->numrows; $n++) {
        $q->fetch_row();
        $xml .= "<row>";
        for ($field_count = 0; $field_count < $q->numfields; $field_count++) {
            $q->field_info($field_count);
            $field_name = $q->field_name;
            $field_type = $q->field_type;
            $value = htmlspecialchars($q->row_data[$field_count]);
            $xml .= "<$field_name>";
            $xml .= "$value";
            $xml .= "</$q->field_name>";
        }
        $xml .= "</row>";
    }
    $xml .= "</table>";
    return $xml;
}

function ReturnXML($q)
{
    $xml = ResultsToXML($q);
    echo chr(10);
    echo "<IMPORT_DATA>";
    echo $xml;
    echo "</IMPORT_DATA>";
    echo chr(10);
}


function GetStatusValues($db)
{
    $q = new query($db, "select order_status_id as orders_status_id, name as orders_status_name
    from " . TABLE_ORDERS_STATUS . " where language_id = 1");

    if (RETURN_XML == 'YES') {
        ReturnXML($q);
        return;
    }

    echo chr(10);
    echo "<IMPORT_DATA>";

    for ($field_count = 0; $field_count < $q->numfields; $field_count++) {
        $q->field_info($field_count);
        echo $q->field_name;
        echo chr(30);
    }

    for ($n = 0; $n < $q->numrows; $n++) {
        echo chr(10);
        $q->fetch_row();
        for ($field_count = 0; $field_count < $q->numfields; $field_count++) {
            echo $q->row_data[$field_count];
            echo chr(30);
        }
    }
    echo "</IMPORT_DATA>";
    echo chr(10);
}

function GetTaxClassValues($db)
{
    $q = new query($db, "select tax_class_id, title as tax_class_title, description as tax_class_description, date_added, date_modified as last_modified
    from " . TABLE_TAX_CLASS);

    if (RETURN_XML == 'YES') {
        ReturnXML($q);
        return;
    }
}

function GetGeoZoneValues($db)
{
    $q = new query($db, "select name as geo_zone_name, description as geo_zone_description from " . TABLE_GEO_ZONES);

    if (RETURN_XML == 'YES') {
        ReturnXML($q);
        return;
    }

    echo chr(10);
    echo "<IMPORT_DATA>";

    for ($field_count = 0; $field_count < $q->numfields; $field_count++) {
        $q->field_info($field_count);
        echo $q->field_name;
        echo chr(30);
    }

    for ($n = 0; $n < $q->numrows; $n++) {
        echo chr(10);
        $q->fetch_row();
        for ($field_count = 0; $field_count < $q->numfields; $field_count++) {
            echo $q->row_data[$field_count];
            echo chr(30);
        }
    }
    echo "</IMPORT_DATA>";
    echo chr(10);
}

function GetOrders($db)
{
    $NewStatus = 1;
    if (isset($_POST['NewStatus'])) $NewStatus = $_POST['NewStatus'];
    $order_query = new query($db, "select order_id as orders_id from " . TABLE_ORDERS . " where order_status_id = $NewStatus");

    if (RETURN_XML == 'YES') {
        ReturnXML($order_query);
        return;
    }

    $id_offset = GetQueryFieldOffset($order_query, "order_id");
    echo chr(10);
    echo "<IMPORT_DATA>";
    for ($i = 0; $i < $order_query->numrows; $i++) {
        $order_query->fetch_row();
        echo $order_query->row_data[$id_offset];
        echo chr(10);
    }
    echo "</IMPORT_DATA>";
    echo chr(10);
}

function GetOrderTotalValue($db, $OrderNum, $ClassID)
{
    $query_string = "select o.value from " . TABLE_ORDERS_TOTAL . " o
where o.order_id = $OrderNum and o.code = '$ClassID'";
    $q = new query($db, $query_string);
    $q->fetch_row();
    if ($q->row_data[0] == null) return 0;
    else return $q->row_data[0];
}

function GetPayPalTransactionID($db, $OrderNum)
{
//    $query_string = "select txn_id from " . TABLE_PAYPAL . " where order_id = $OrderNum and txn_type = 'cart'";
//    $q = new query($db, $query_string);
//    $q->fetch_row();
//    if ($q->row_data[0] == null) return "";
//    else return $q->row_data[0];
    return "";
}

function GetGeoZoneName($db, $OrderNum, $prefix)
{
//    $country_field = $prefix . _country;
//    $state_field = $prefix . _state;

    $country_field = $prefix . _country;
    $state_field = $prefix . _zone;

    $query_string = "select gz.name as geo_zone_name from " . TABLE_GEO_ZONES . " gz
join " . TABLE_ZONES_TO_GEO_ZONES . " ztgz on gz.geo_zone_id = ztgz.geo_zone_id
join " . TABLE_ZONES . " z on ztgz.zone_id = z.zone_id
join " . TABLE_COUNTRIES . " c on ztgz.country_id = c.country_id
join " . TABLE_ORDERS . " o on z.name = o.$state_field and c.name = o.$country_field
where o.order_id = $OrderNum limit 1";
    $q = new query($db, $query_string);
    $q->fetch_row();
    if ($q->row_data[0] == null) return "";
    else return $q->row_data[0];
}


function GetOrderInfo($db)
{
    $OrderNum = 0;
    if (isset($_POST['OrderNum'])) $OrderNum = $_POST['OrderNum'];
    if ($OrderNum == 0) return;

//    $order_shipping = GetOrderTotalValue($db, $OrderNum, 'ot_shipping');
//    $paypal_txnid = GetPayPalTransactionID($db, $OrderNum);
//    $customers_zone_name = GetGeoZoneName($db, $OrderNum, 'customers');
//    $delivery_zone_name = GetGeoZoneName($db, $OrderNum, 'delivery');

    $order_shipping = GetOrderTotalValue($db, $OrderNum, 'shipping');
    $paypal_txnid = GetPayPalTransactionID($db, $OrderNum);
    $customers_zone_name = GetGeoZoneName($db, $OrderNum, 'payment');
    $delivery_zone_name = GetGeoZoneName($db, $OrderNum, 'shipping');

//    $query_string = "select o.*, c.*, replace(os.comments, '\r\n', ' ') as comments,
//$order_shipping as order_shipping,
//'$paypal_txnid' as paypal_txnid,
//'$customers_zone_name' as customers_zone_name,
//'$delivery_zone_name' as delivery_zone_name
//from " . TABLE_ORDERS . " o
//join " . TABLE_CUSTOMERS . " c on o.customers_id = c.customers_id
//join " . TABLE_ORDERS_STATUS_HISTORY . " os on o.orders_id = os.orders_id
//where o.orders_id = $OrderNum limit 1";

    $query_string = "select o.order_id as orders_id, o.customer_id as customers_id,
CONCAT(o.payment_firstname, ' ', o.payment_lastname) as customers_name,
o.payment_company as customers_company, o.payment_address_1 as customers_street_address, o.payment_address_2 as customers_suburb,
o.payment_country as customers_city, o.telephone as customers_telephone,
o.shipping_lastname as delivery_name, o.shipping_company as delivery_company, o.shipping_address_1 as delivery_street_address,
o.shipping_address_2 as delivery_suburb, o.shipping_city as delivery_city, o.shipping_country as delivery_country,
o.shipping_address_format as delivery_address_format_id,
o.payment_lastname as billing_name, o.payment_company as billing_company, o.payment_address_1 as billing_street_address,
o.payment_address_2 as billing_suburb, o.payment_country as billing_country, o.shipping_address_format as billing_address_format_id,
o.payment_method, o.payment_code as payment_module_code, o.shipping_method, o.shipping_code as shipping_module_code,
o.date_added as date_purchased, o.order_status_id as orders_status, o.date_modified as orders_date_finished, o.ip as ip_address,
o.cc_avs_respcode, o.cc_cvv_respcode,
c.firstname as customers_firstname, c.lastname as customers_lastname,
c.date_added as customers_dob, c.email as customers_email_address,
c.custom_field as customers_nick, c.telephone as customers_telephone,
c.fax as customers_fax, c.password as customers_password,
c.newsletter as customers_newsletter,
c.atrex_custnum, c.atrex_custguid, c.atrex_live, c.atrex_pricing,
c.address_id as customers_default_address_id,
replace(os.comment, '\r\n', ' ') as comments,
$order_shipping as order_shipping,
'$paypal_txnid' as paypal_txnid,
'$customers_zone_name' as customers_zone_name,
'$delivery_zone_name' as delivery_zone_name
from " . TABLE_ORDERS . " o
join " . TABLE_CUSTOMERS . " c on o.customer_id = c.customer_id
join " . TABLE_ORDERS_STATUS_HISTORY . " os on o.order_id = os.order_id
where o.order_id = $OrderNum limit 1";

    $q = new query($db, $query_string);

    if (RETURN_XML == 'YES') {
        ReturnXML($q);
        return;
    }

    $q->fetch_row();

    echo chr(10);
    echo "<IMPORT_DATA>";

    for ($field_count = 0; $field_count < $q->numfields; $field_count++) {
        $q->field_info($field_count);
        echo $q->field_name;
        echo chr(30);
    }

    echo chr(10);

    for ($field_count = 0; $field_count < $q->numfields; $field_count++) {
        echo $q->row_data[$field_count];
        echo chr(30);
    }

    echo "</IMPORT_DATA>";
    echo chr(10);
}

function GetOrderTotals($db)
{
    $OrderNum = 0;
    if (isset($_POST['OrderNum'])) $OrderNum = $_POST['OrderNum'];
    if ($OrderNum == 0) return;

    $query_string = "select code as class, title, value from " . TABLE_ORDERS_TOTAL . " where order_id = $OrderNum";

    $q = new query($db, $query_string);

    if (RETURN_XML == 'YES') {
        ReturnXML($q);
        return;
    }

    echo chr(10);
    echo "<IMPORT_DATA>";

    for ($field_count = 0; $field_count < $q->numfields; $field_count++) {
        $q->field_info($field_count);
        echo $q->field_name;
        echo chr(30);
    }

    for ($n = 0; $n < $q->numrows; $n++) {
        echo chr(10);
        $q->fetch_row();
        for ($field_count = 0; $field_count < $q->numfields; $field_count++) {
            echo $q->row_data[$field_count];
            echo chr(30);
        }
    }
    echo "</IMPORT_DATA>";
    echo chr(10);
}


function GetOrderItems($db)
{
    $OrderNum = 0;
    if (isset($_POST['OrderNum'])) $OrderNum = $_POST['OrderNum'];
    if ($OrderNum == 0) return;

    $query_string = "select i.order_product_id as orders_products_id, i.order_id as orders_id, i.quantity as products_quantity,
i.price as products_price, i.total as final_price, i.tax as products_tax,
p.model as products_model, p.manufacturer_id as manufacturers_id, p.atrex_stockcode,
pd.name as products_name, pd.description as products_description, pd.tag as products_url,
m.name  as manufacturers_name
from " . TABLE_ORDERS_PRODUCTS . " i
join " . TABLE_PRODUCTS . " p on i.product_id = p.product_id
left outer join " . TABLE_PRODUCTS_DESCRIPTION . " pd on p.product_id = pd.product_id and pd.language_id = 1
left outer join " . TABLE_MANUFACTURERS . " m on p.manufacturer_id = m.manufacturer_id
where order_id = $OrderNum";

//~~REV 11/09/2015 = moved language ID up to join level

    $q = new query($db, $query_string);

    if (RETURN_XML == 'YES') {
        ReturnXML($q);
        return;
    }

    echo chr(10);
    echo "<IMPORT_DATA>";

    $description_offset = -1;

    for ($field_count = 0; $field_count < $q->numfields; $field_count++) {
        $q->field_info($field_count);
        echo $q->field_name;
        echo chr(30);
        if ($q->field_name == 'description') $description_offset = $field_count;
    }

    for ($n = 0; $n < $q->numrows; $n++) {
        echo chr(10);
        $q->fetch_row();
        for ($field_count = 0; $field_count < $q->numfields; $field_count++) {
            if ($field_count == $description_offset) {
                echo strip_tags($q->row_data[$field_count]);
            } //strip html from description
            else {
                echo $q->row_data[$field_count];
            }
            echo chr(30);
        }
    }
    echo "</IMPORT_DATA>";
    echo chr(10);
}

function SetOrderStatus($db)
{
    $OrderNum = 0;
    if (isset($_POST['OrderNum'])) $OrderNum = $_POST['OrderNum'];
    if ($OrderNum == 0) return;
    echo "Order Number: $OrderNum";
    echo chr(10);

    $NewStatus = 0;
    if (isset($_POST['NewStatus'])) $NewStatus = $_POST['NewStatus'];
    if ($NewStatus == 0) return;
    echo "New Status: $NewStatus";
    echo chr(10);

    $query_string = "select order_status_id as orders_status from " . TABLE_ORDERS . " where order_id = $OrderNum";
    $q = new query($db, $query_string);
    $q->fetch_row();
    $OldStatus = $q->row_data[0];


    if ($OldStatus <> $NewStatus) {
        $query_string = "update " . TABLE_ORDERS . " set order_status_id = $NewStatus where order_id = $OrderNum";
        $q = new query($db, $query_string);
        echo "Set Order Number $OrderNum status to $NewStatus.  Rows Affected: $q->affected_rows";
        echo chr(10);
        $query_string = "insert into " . TABLE_ORDERS_STATUS_HISTORY . " (order_id, order_status_id, date_added, comment) values($OrderNum, $NewStatus, now(), '')";
        $q = new query($db, $query_string);
    }

}

function ClearCCNumber($db)
{
    $OrderNum = 0;
    if (isset($_POST['OrderNum'])) $OrderNum = $_POST['OrderNum'];
    if ($OrderNum == 0) return;
    echo "ClearCCNumber: $OrderNum";
    echo chr(10);

    $query_string = "update " . TABLE_ORDERS . " set cc_edc_number = NULL where order_id = $OrderNum";
    $q = new query($db, $query_string);
}


function TestFunction()
{
}

// *** Beginning of script
if (ComparePassword() == false) {
    die("Invalid Password");
}

$connectID = DB_SERVER_USERNAME;
$connectPW = DB_SERVER_PASSWORD;
$connectionDB = DB_DATABASE;


$Action = "ScriptCheck"; //set to default value
if (isset($_GET['Action'])) $Action = $_GET['Action'];

if (DEBUG_MODE == 'YES') {
//  $Action = "GetOrderInfo";
//  $_POST['OrderNum'] = 47;
//  $_POST['NewStatus'] = 2;
}

$host = DB_SERVER;
$db = new db($host, $connectID, $connectPW);
$db->set_db($connectionDB);

if ($Action == "ScriptCheck") {
    echo chr(10);
    echo "<IMPORT_DATA>";
    echo "Status=OK";
    echo chr(10);
    echo "Script Version=" . SCRIPT_SET_VER;
    echo chr(10);
    echo "Cart Name=" . CART_NAME;
    echo chr(10);
    echo "PHP Version: " . phpversion();
    echo chr(10);
    if (function_exists('mysql_get_server_info')) {
        echo "MySQL Version: " . mysql_get_server_info();
    } else {
        echo "MySQL Version: &lt < 4.0.5";
    }
    echo chr(10);

    echo(sprintf("MySQL Connect Results: (%s): %s", mysql_errno($db->linkID), mysql_error($db->linkID)));
    echo chr(10);

    echo "</IMPORT_DATA>";
    echo chr(10);
}

//get outstanding orders
if ($Action == "GetOrders") GetOrders($db);

//get order information
if ($Action == "GetOrderInfo") GetOrderInfo($db);

//get order totals
if ($Action == "GetOrderTotals") GetOrderTotals($db);

//get order items
if ($Action == "GetOrderItems") GetOrderItems($db);

//update order status
if ($Action == "SetOrderStatus") SetOrderStatus($db);

//get status values
if ($Action == "GetStatusValues") GetStatusValues($db);

//get geo_zone_name values
if ($Action == "GetGeoZoneValues") GetGeoZoneValues($db);

//get tax_class values
if ($Action == "GetTaxClassValues") GetTaxClassValues($db);

//get status values
if ($Action == "ClearCCNumber") ClearCCNumber($db);

//test function
if ($Action == "TestFunction") TestFunction();


$db->close();

/* *** Need to do items
clean up image name to ensure that it can be displayed.
*/
/*
1.05 - Added support for ZenCart.
*/


?>