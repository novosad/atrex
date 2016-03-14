<?php

include_once('defines.php'); //pull in the local configuration
include_once('commondb.php'); //pull in the base database functions
include_once('../includes/configure.php'); //pull in the shopping cart configuration information

if (CART_TYPE == 'OSC') {
    include_once('../includes/functions/general.php'); //pull in the osCommerce general functions
    include_once('../includes/functions/password_funcs.php'); //pull in the osCommerce password functions
}

include_once('../includes/database_tables.php'); //pull in the osCommerce table name information
@include_once 'user_events.php'; //pull in any custom import functions

define('DEBUG_MODE', 'NO');

$new_cats = 0;
$updated_cats = 0;
$new_mfrs = 0;
$updated_mfrs = 0;
$new_codes = 0;
$updated_codes = 0;
$new_customers = 0;
$updated_customers = 0;

$size_category = 32;
$size_stockcode = 64;
$size_category_description = 32;
$size_lastname = 32;
$size_add1 = 64;
$size_add2 = 32;
$size_model = 32;
$size_mfr = 32;
$default_collation = '';
$category_levels = 2;

$last_time_limit = time();
$ScriptRunStartTime = microtime_float();

$language_array = array(); //create the language array and make it global

$standard_sql_mode;
$custom_sql_mode;


class msQueryBuilder
{
    /*////////////// DEFINE THE VARIABLES THAT ARE USED WITHIN THE CLASS //////////////*/
    var $leftdata = array();
    var $rightdata = array();
    var $querytype;
    var $msQueryBuilder;

    /*////////////// DEFINE CONSTRUCTOR FUNCTION //////////////*/
    function msQueryBuilder($querytype)
    {
        $this->$querytype = $this->set_querytype($querytype);
    }

    /*////////////// DEFINE METHOD FUNCTIONS //////////////*/
    function set_querytype($value)
    {
        return $this->querytype = $value;
    }

    function add($leftdata, $rightdata)
    {
        $arraysize = sizeof($this->leftdata);
        $this->leftdata[$arraysize] = $leftdata;
        $this->rightdata[$arraysize] = $rightdata;
    }

    function buildinsert()
    {
        $arraysize = sizeof($this->leftdata);
        $fieldnames = '';
        $valuenames = '';
        $separator = '';


        for ($i = 0; $i < $arraysize; $i++) {
            if ($i > 0) {
                $separator = ', ';
            } else {
                $separator = '';
            }
            $fieldnames = $fieldnames . $separator . $this->leftdata[$i];
            $valuenames = $valuenames . $separator . $this->rightdata[$i];
        }
        return '(' . $fieldnames . ') values(' . $valuenames . ')';
    }

    function buildselectinsert()
    {
        $arraysize = sizeof($this->leftdata);
        $fieldnames = '';
        $valuenames = '';
        $separator = '';


        for ($i = 0; $i < $arraysize; $i++) {
            if ($i > 0) {
                $separator = ', ';
            } else {
                $separator = '';
            }
            $fieldnames = $fieldnames . $separator . $this->leftdata[$i];
            $valuenames = $valuenames . $separator . $this->rightdata[$i];
        }
        return '(' . $fieldnames . ') select ' . $valuenames;
    }

    function buildupdate()
    {
        $arraysize = sizeof($this->leftdata);
        $fieldnames = '';
        $valuenames = '';
        $separator = '';

        for ($i = 0; $i < $arraysize; $i++) {
            if ($i > 0) {
                $separator = ', ';
            } else {
                $separator = '';
            }
            $valuenames = $valuenames . $separator . $this->leftdata[$i] . ' = ' . $this->rightdata[$i];
        }
        return $valuenames;
    }

    function build()
    {
        $result = '';
        switch ($this->querytype) {
            case 'INSERT':
                $result = $this->buildinsert();
                break;
            case 'SELECT_INSERT':
                $result = $this->buildselectinsert();
                break;
            case 'UPDATE':
                $result = $this->buildupdate();
                break;
        }
        return $result;
    }


}

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}


function reset_time_limit()
{
    global $last_time_limit; //make sure that you are working with the global entries
    $timeout_seconds = 60; //set the number of seconds before timeout
    $time_diff = time() - $last_time_limit; //get time difference in seconds
    if ($time_diff > 10) {
        set_time_limit($timeout_seconds); //try to prevent timeout with another x number of seconds
        $last_time_limit = time();
    }
}

function zen_rand($min = null, $max = null)
{
    static $seeded;

    if (!$seeded) {
        mt_srand((double)microtime() * 1000000);
        $seeded = true;
    }

    if (isset($min) && isset($max)) {
        if ($min >= $max) {
            return $min;
        } else {
            return mt_rand($min, $max);
        }
    } else {
        return mt_rand();
    }
}

function zen_encrypt_password($plain)
{
    $password = '';

    for ($i = 0; $i < 10; $i++) {
        $password .= zen_rand();
    }

    $salt = substr(md5($password), 0, 2);

    $password = md5($salt . $plain) . ':' . $salt;

    return $password;
}

function SetSQLModeVariables($db)
{
    global $standard_sql_mode;
    global $custom_sql_mode;

    $query_string = "SELECT @@SESSION.sql_mode;";
    $default_query = new query($db, $query_string);
    $default_query->fetch_row();
    $standard_sql_mode = $default_query->row_data[0];
    $custom_sql_mode = $standard_sql_mode;

    $custom_sql_mode = str_replace('STRICT_TRANS_TABLES,', '', $custom_sql_mode);
    $custom_sql_mode = str_replace('STRICT_ALL_TABLES,', '', $custom_sql_mode);
    $custom_sql_mode = str_replace('STRICT_TRANS_TABLES', '', $custom_sql_mode);
    $custom_sql_mode = str_replace('STRICT_ALL_TABLES', '', $custom_sql_mode);
}

function SetSQLMode($db, $mode)
{
    $query_string = "SET @@SESSION.sql_mode = '$mode'";
    $default_query = new query($db, $query_string);
}

function quote_smart($value)
{
    // Stripslashes
    if (get_magic_quotes_gpc()) {
        $value = stripslashes($value);
    }
    // Quote if not a number or a numeric string
    if (!is_numeric($value)) {
        $value = "'" . mysql_real_escape_string($value) . "'";
    }
    return $value;
}

//Get a clean image file name
function CleanImageName($image_name)
{
    $result = str_replace(chr(92), 'a', $image_name); //backslash
    $result = str_replace('/', 'b', $result);
    $result = str_replace('*', 'c', $result);
    $result = str_replace('?', 'd', $result);
    $result = str_replace('"', 'e', $result);
    $result = str_replace('<', 'f', $result);
    $result = str_replace('>', 'g', $result);
    $result = str_replace('|', 'h', $result);
    $result = str_replace(chr(39), 'i', $result); //single quote
    $result = str_replace('#', 'j', $result);
    $result = str_replace('&', 'k', $result);
    $result = str_replace('+', 'm', $result);
    $result = str_replace('$', 'n', $result);
    $result = str_replace('%', 'o', $result);
    $result = str_replace('!', 'p', $result);
    $result = str_replace('`', 'q', $result);
    $result = str_replace('{', 'r', $result);
    $result = str_replace('}', 's', $result);
    $result = str_replace('=', 't', $result);
    $result = str_replace(':', 'u', $result);
    $result = str_replace('@', 'v', $result);
    $result = str_replace(' ', '_', $result);

    return $result;
}

function PopulateLanguageArray($db)
{
    global $language_array; //make sure that you are working with the global entries
    $language_query = new query($db, "select language_id from " . TABLE_LANGUAGES);
    for ($i = 0; $i < $language_query->numrows; $i++) { //populate the language ID array
        $language_query->fetch_row();
        $language_array[$i] = $language_query->row_data[0]; //get the languageID
    }
}

function DisplayStatus($aStatus)
{
    global $ScriptRunStartTime;
    if (strlen($aStatus) > 0) {
        echo $aStatus;
    }
    $ScriptElapsedTime = microtime_float() - $ScriptRunStartTime;
    printf("  Elapsed Time: %.2f Seconds", $ScriptElapsedTime);
    echo '<br>';
    echo chr(10);
    flush();
    reset_time_limit();
}

function PreventTimeout()
{
    echo ".";
    flush();
    reset_time_limit();
}

function AddTrailingPathDelimiter($aPath)
{
    $aPath = Trim($aPath);
    $l = strlen($aPath);
    if ($aPath[$l - 1] <> DIRECTORY_SEPARATOR) {
        $aPath = $aPath . DIRECTORY_SEPARATOR;
    }
    return $aPath;
}

function GetImportFilePath($aFileName)
{
    $LocalFileName = AddTrailingPathDelimiter(dirname(__FILE__)) . $aFileName;
    if (file_exists($LocalFileName)) {
        return $LocalFileName;
    } //use the file in the same folder as the import script first - allows for debugging

    $DIR_FS_CATALOG = AddTrailingPathDelimiter(DIR_FS_CATALOG);

    $LocalFileName = AddTrailingPathDelimiter(DIR_FS_CATALOG) . 'atrex/' . $aFileName;
    if (file_exists($LocalFileName)) {
        return $LocalFileName;
    } //try catalog file plus atrex directory

    $LocalFileName = $DIR_FS_CATALOG . $aFileName;
    if (file_exists($LocalFileName)) {
        return $LocalFileName;
    } //try catalog file directly

    $LocalFileName = AddTrailingPathDelimiter(dirname(__FILE__)) . $aFileName;
    return $LocalFileName;  //last ditch effort, return it if the previous entries don't match
}

//Get a file pointer to the file specified. Return null if not sucessfull
function GetImportFile($aFileName)
{
    $LocalFileName = GetImportFilePath($aFileName);
    $fp = null;
    if (file_exists($LocalFileName)) {
        $fp = fopen($LocalFileName, "r") or die("Cannot open file");
    } else {
        echo "Export file does not exist at $LocalFileName";
        return $fp;
    }
    return $fp;
}

function CloseImportFile($import_file)
{
    fclose($import_file);
    unset($import_file);
}

function EmptyImportFile($aFileName)
{
    $LocalFileName = GetImportFilePath($aFileName);
    if (file_exists($LocalFileName)) {
        if (unlink($aFileName) == false) {
            DisplayStatus("Unable to delete import file $aFileName.  Will attempt to truncate");
        }
    }
    if (file_exists($LocalFileName) && is_writeable($aFileName)) {
        $fp = null;
        $fp = fopen($LocalFileName, "wb");
        fwrite($fp, '');
        fclose($fp);
    }

}


//Get an array of the import file
function GetImportLineArray($import_file)
{
    $line = fgets($import_file);
    $line = str_replace(chr(10), '', $line); //strip out CR
    $line = str_replace(chr(13), '', $line); //strip out LF
    $vals = Explode(Chr(30), $line);
    return $vals;
}


function GetArrayFieldOffset($field_array, $field_name)
{
    $field_offset = -1;
    for ($field_count = 0; $field_count < count($field_array); $field_count++) {
        $temp_val = strtolower($field_array[$field_count]);
        if ($temp_val == $field_name) {
            $field_offset = $field_count;
            break;
        }
    }
    return $field_offset;
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

function GetFieldValue($vals, $offset, $default)
{
    if (($offset >= 0) && ($offset <= count($vals) - 1)) {
        $value = addslashes($vals[$offset]);
    } else {
        $value = $default;
    }
    return $value;
}

function GetRawFieldValue($vals, $offset, $default)
{
    if (($offset >= 0) && ($offset <= count($vals) - 1)) {
        $value = $vals[$offset];
    } else {
        $value = $default;
    }
    return $value;
}

function CheckFieldInfo($db, $table_name, $field_name, $expected_type)
{
    //show index form products
    $sql = "show columns from $table_name like '$field_name'";
    $q = new query($db, $sql);
    if ($q->numrows == 0) {  //can't find the column, add it
        DisplayStatus("Altering $table_name table.  Adding field $field_name...");
        $sql = "alter table $table_name add $field_name $expected_type";
        new query($db, $sql);
    } else {
        $q->fetch_row();
        $field_offset = GetQueryFieldOffset($q, 'Type');
        $field_type = $q->row_data[$field_offset];
        if (strncmp($field_type, $expected_type, strlen($expected_type)) <> 0) {
            DisplayStatus("Altering $table_name table.  Updating field $field_name data type...");
            $sql = "alter table $table_name change $field_name $field_name $expected_type";
            new query($db, $sql);
        }
    }   //end of else
}

function GetFieldLength($db, $table_name, $field_name)
{
    //show index form products
    $sql = "show columns from $table_name like '$field_name'";
    $q = new query($db, $sql);
    if ($q->numrows == 0) {  //can't find the column, add it
        return 256;
    } else {
        $q->fetch_row();
        $field_offset = GetQueryFieldOffset($q, 'Type');
        $field_type = $q->row_data[$field_offset];
        $start_pos = strpos($field_type, '(', 0);
        $end_pos = strpos($field_type, ')', 0);
        return (int)substr($field_type, $start_pos + 1, $end_pos - 1);
    }   //end of else
}


function GetIndexFields($q, $index_name)
{
    $result = '';
    $key_offset = GetQueryFieldOffset($q, 'Key_name');
    $column_offset = GetQueryFieldOffset($q, 'Column_name');
    for ($n = 0; $n < $q->numrows; $n++) {
        $q->fetch_row();
        $temp_val = strtolower($q->row_data[$key_offset]);
        if ($temp_val == $index_name) {
            if (strlen($result) > 0) {
                $result = $result . ',';
            }
            $result = $result . $q->row_data[$column_offset];
        }
    }
    return $result;
}

function CheckIndexInfo($db, $table_name, $index_name, $index_def)
{
    $sql = "show index from $table_name";
    $q = new query($db, $sql);

    $index_fields = GetIndexFields($q, $index_name);

    if ($index_fields == '') { //can't find the index, add it
        DisplayStatus("Altering $table_name table.  Adding index $index_name...");
        $sql = "alter table $table_name add index $index_name ($index_def)";
        new query($db, $sql);
    } else {
        if ($index_fields <> $index_def) {
            DisplayStatus("Altering $table_name table.  Updating index $index_name...");
            $sql = "alter table $table_name drop index $index_name, add index $index_name ($index_def)";
            new query($db, $sql);
        }
    }   //end of else
}

function CheckStructure($db)
//This function will check the structure of the osCommerce tables, altering the tables if necessary
//If the atrex stock code field is not present, it will add the field and a corresponding index
//If the field does exist, it will check to make sure that it is the correct length.
{
    global $size_category;
    global $size_stockcode;
    global $size_model;

//Check the products table
    DisplayStatus('Checking "Products" table structure...');
    CheckFieldInfo($db, TABLE_PRODUCTS, 'atrex_stockcode', "varchar($size_stockcode)");
    CheckFieldInfo($db, TABLE_PRODUCTS, 'atrex_live', 'tinyint');
    CheckFieldInfo($db, TABLE_PRODUCTS, 'atrex_discount1', 'decimal(15,4)');
    CheckFieldInfo($db, TABLE_PRODUCTS, 'atrex_discount2', 'decimal(15,4)');
    CheckFieldInfo($db, TABLE_PRODUCTS, 'atrex_discount3', 'decimal(15,4)');
    CheckFieldInfo($db, TABLE_PRODUCTS, 'atrex_discount4', 'decimal(15,4)');
    CheckFieldInfo($db, TABLE_PRODUCTS, 'atrex_discount5', 'decimal(15,4)');
    CheckFieldInfo($db, TABLE_PRODUCTS, 'atrex_list', 'decimal(15,4)');
    CheckFieldInfo($db, TABLE_PRODUCTS, 'atrex_cat_id', 'int(11)');
    CheckIndexInfo($db, TABLE_PRODUCTS, 'idx_atrex_stockcode', 'atrex_stockcode');
    CheckFieldInfo($db, TABLE_ORDERS, 'cc_avs_respcode', "varchar(2)");
    CheckFieldInfo($db, TABLE_ORDERS, 'cc_cvv_respcode', "varchar(1)");
    CheckFieldInfo($db, TABLE_ORDERS, 'cc_edc_number', "varchar(32)");


//Check the customer table
    DisplayStatus('Checking "Customers" table structure...');
    CheckFieldInfo($db, TABLE_CUSTOMERS, 'atrex_custnum', 'int');
    CheckFieldInfo($db, TABLE_CUSTOMERS, 'atrex_custguid', 'varchar(38)');
    CheckFieldInfo($db, TABLE_CUSTOMERS, 'atrex_live', 'tinyint');
    CheckFieldInfo($db, TABLE_CUSTOMERS, 'atrex_pricing', 'tinyint');
    CheckIndexInfo($db, TABLE_CUSTOMERS, 'idx_atrex_custguid', 'atrex_custguid');


//Check the specials table
    CheckFieldInfo($db, TABLE_SPECIALS, 'atrex_recid', 'int');
    CheckFieldInfo($db, TABLE_SPECIALS, 'atrex_discount1', 'decimal(15,4)');
    CheckFieldInfo($db, TABLE_SPECIALS, 'atrex_discount2', 'decimal(15,4)');
    CheckFieldInfo($db, TABLE_SPECIALS, 'atrex_discount3', 'decimal(15,4)');
    CheckFieldInfo($db, TABLE_SPECIALS, 'atrex_discount4', 'decimal(15,4)');
    CheckFieldInfo($db, TABLE_SPECIALS, 'atrex_discount5', 'decimal(15,4)');
}

function BoolStrToBit($bool)
{
    $result = 0;
    $bool = Trim($bool);
    if (strlen($bool) > 0) {
        $bool = strtoupper($bool);
        if ($bool[0] == 'T') {
            $result = 1;
        }
    }
    return $result;
}

function DeleteTempCatTable($db)
{
//delete the existing table if it exists
    DisplayStatus('Deleting temporary category table...');
    new query($db, "drop table if exists tmpcat");
    DisplayStatus('Deleting temporary category table (complete)');
}

function CreateTempCatTable($db)
//this function will create a temporary category table for import linking - reads from osc tables
{
    global $default_collation;
    global $category_levels;
    global $size_category;

    DeleteTempCatTable($db); //always delete the table before recreating it.

    DisplayStatus('Creating temporary category table...');

    $query_string =
        "create table tmpcat (
    cat1_id integer,
    cat2_id integer,
    cat3_id integer,
    cat4_id integer,
    cat1 varchar($size_category) default '',
    cat2 varchar($size_category) default '',
    cat3 varchar($size_category) default '',
    cat4 varchar($size_category) default '',
    category_id integer,
    catlevel integer
    ) $default_collation";
    new query($db, $query_string); //Create the table
    CheckIndexInfo($db, 'tmpcat', 'idx_cat_id', 'category_id'); //needed here for the insertion level matching


    DisplayStatus('Populating temporary category table...');

//insert first cat1 level
    $insert_query_string =
        "insert into tmpcat (cat1_id, cat1, category_id, catlevel)
    select c.category_id, cd.name, c.category_id, 1 as CatLevel from " . TABLE_CATEGORIES . " c
    join " . TABLE_CATEGORIES_DESCRIPTION . " cd on c.category_id = cd.category_id
    where c.parent_id = 0";
    new query($db, $insert_query_string); //Create the table

    $insert_query_string =
        "insert into tmpcat (cat1_id, cat2_id, cat1, cat2, category_id, catlevel)
    select tc.cat1_id, c.category_id, tc.cat1, cd.name, c.category_id, 2 as CatLevel from " . TABLE_CATEGORIES . " c
    join " . TABLE_CATEGORIES_DESCRIPTION . " cd on c.category_id = cd.category_id
    join tmpcat tc on c.parent_id = tc.category_id and tc.CatLevel = 1";
    new query($db, $insert_query_string); //Create the table

    if ($category_levels >= 3) {
        $insert_query_string =
            "insert into tmpcat (cat1_id, cat2_id, cat3_id, cat1, cat2, cat3, category_id, catlevel)
    select tc.cat1_id, tc.cat2_id, c.category_id, tc.cat1, tc.cat2, cd.name, c.category_id, 3 as CatLevel from " . TABLE_CATEGORIES . " c
    join " . TABLE_CATEGORIES_DESCRIPTION . " cd on c.category_id = cd.category_id
    join tmpcat tc on c.parent_id = tc.category_id and tc.CatLevel = 2";
        new query($db, $insert_query_string); //Create the table
    }

    if ($category_levels >= 4) {
        $insert_query_string =
            "insert into tmpcat (cat1_id, cat2_id, cat3_id, cat4_id, cat1, cat2, cat3, cat4, category_id, catlevel)
    select tc.cat1_id, tc.cat2_id, tc.cat3_id, c.category_id, tc.cat1, tc.cat2, tc.cat3, cd.name, c.category_id, 4 as CatLevel from " . TABLE_CATEGORIES . " c
    join " . TABLE_CATEGORIES_DESCRIPTION . " cd on c.category_id = cd.category_id
    join tmpcat tc on c.parent_id = tc.category_id and tc.CatLevel = 3";
        new query($db, $insert_query_string); //Create the table
    }

    CheckIndexInfo($db, 'tmpcat', 'idx_cat1', 'cat1');
    CheckIndexInfo($db, 'tmpcat', 'idx_cat2', 'cat2');
    CheckIndexInfo($db, 'tmpcat', 'idx_cat3', 'cat3');
    CheckIndexInfo($db, 'tmpcat', 'idx_cat4', 'cat4');

    DisplayStatus('Creating temporary category table (completed)');
}

function DeleteTempInventoryImportTable($db)
{
    DisplayStatus('Deleting temporary inventory import table...');
//delete the existing table if it exists
    new query($db, "drop table if exists tmpcode");
    DisplayStatus('Deleting temporary inventory import table (completed)');
}

function DeleteTempInventorySaleImportTable($db)
{
    DisplayStatus('Deleting temporary inventory sale import table...');
//delete the existing table if it exists
    new query($db, "drop table if exists tmpsale");
    DisplayStatus('Deleting temporary inventory sale import table (completed)');
}

function GetFieldSizes($db)
{
    global $size_category;
    global $size_stockcode;
    global $size_category_description;
    global $size_lastname;
    global $size_add1;
    global $size_add2;
    global $size_model;
    global $size_mfr;

    $import_file = GetImportFile("atxcode.txt");
    if ($import_file <> null) {

        $line = fgets($import_file);
        if (strpos($line, '~v3') !== false) { //check for the v3 indicator in the first line.  Retrieve field sizes if it is
            $columns = GetImportLineArray($import_file); //get next line which is the column names
            $sizes = GetImportLineArray($import_file); //get next line which is the column names

            $offset = GetArrayFieldOffset($columns, "stockcode");
            if ($offset <> -1) {
                $size_stockcode = $sizes[$offset];
            }

            $offset = GetArrayFieldOffset($columns, "category");
            if ($offset <> -1) {
                $size_category = min($sizes[$offset], GetFieldLength($db, TABLE_CATEGORIES_DESCRIPTION, 'name'));
            }

            $offset = GetArrayFieldOffset($columns, "cat1descr");
            if ($offset <> -1) {
                $size_category_description = $sizes[$offset];
            }

            $offset = GetArrayFieldOffset($columns, "model");
            if ($offset <> -1) {
                $size_model = min($sizes[$offset], GetFieldLength($db, TABLE_PRODUCTS, 'model'));
            }

            $offset = GetArrayFieldOffset($columns, "mfr");
            if ($offset <> -1) {
                $size_mfr = min($sizes[$offset], GetFieldLength($db, TABLE_MANUFACTURERS, 'name'));
            }
        }

        CloseImportFile($import_file);
        unset($import_file);
    }

    $import_file = GetImportFile("atxcust.txt");
    if ($import_file <> null) {

        $line = fgets($import_file);
        if (strpos($line, '~v3') !== false) { //check for the v3 indicator in the first line.  Retrieve field sizes if it is
            $columns = GetImportLineArray($import_file); //get next line which is the column names
            $sizes = GetImportLineArray($import_file); //get next line which is the column names

            $offset = GetArrayFieldOffset($columns, "lastname");
            if ($offset <> -1) {
                $size_lastname = min($sizes[$offset], GetFieldLength($db, TABLE_ADDRESS_BOOK, 'entry_lastname'));
            }

            $offset = GetArrayFieldOffset($columns, "add1");
            if ($offset <> -1) {
                $size_add1 = min($sizes[$offset], GetFieldLength($db, TABLE_ADDRESS_BOOK, 'entry_street_address'));
            }

            $offset = GetArrayFieldOffset($columns, "add2");
            if ($offset <> -1) {
                $size_add2 = min($sizes[$offset], GetFieldLength($db, TABLE_ADDRESS_BOOK, 'entry_suburb'));
            }
        }

        CloseImportFile($import_file);
        unset($import_file);
    }

}

function CreateTempInventorySaleImportTable($db)
{
    global $size_stockcode;
    global $default_collation;
    $update_count = 0;
    DeleteTempInventorySaleImportTable($db);
    DisplayStatus('Creating temporary inventory sale import table...');
    $query_string =
        "create table tmpsale
    (product_id int default 0, sale_id integer default 0, recid int default 0, begdate date, enddate date, stockcode varchar($size_stockcode), price numeric(15, 4) default 0.00, list numeric(15, 4) default 0.00, discount1 numeric(15, 4) default 0.00, discount2 numeric(15, 4) default 0.00, discount3 numeric(15, 4) default 0.00, discount4 numeric(15, 4) default 0.00, discount5 numeric(15, 4) default 0.00 )  $default_collation";
    new query($db, $query_string);

    DisplayStatus('Creating temporary inventory import sale table indexes...');
    CheckIndexInfo($db, 'tmpsale', 'idx_stockcode', 'stockcode');
    CheckIndexInfo($db, 'tmpsale', 'idx_product_id', 'product_id');
    CheckIndexInfo($db, 'tmpsale', 'idx_sale_id', 'sale_id');

    DisplayStatus('Inserting temporary inventory sale table items...');

    $import_file = GetImportFile("atxsale.txt");
    if ($import_file == null) return;

    $line = fgets($import_file);
    if (strpos($line, '~v3') === false) { //check for the v3 indicator in the first line.
        fseek($import_file, 0); //reset to beginning of the file
        $vals = GetImportLineArray($import_file);
    } else {
        $vals = GetImportLineArray($import_file); //get the field descriptions
        $line = fgets($import_file); //skip the field sizes
    }

    $stockcode_offset = GetArrayFieldOffset($vals, "stockcode");
    $price_offset = GetArrayFieldOffset($vals, "price");
    $discount1_offset = GetArrayFieldOffset($vals, "discount1");
    $discount2_offset = GetArrayFieldOffset($vals, "discount2");
    $discount3_offset = GetArrayFieldOffset($vals, "discount3");
    $discount4_offset = GetArrayFieldOffset($vals, "discount4");
    $discount5_offset = GetArrayFieldOffset($vals, "discount5");
    $recid_offset = GetArrayFieldOffset($vals, "recid");
    $begdate_offset = GetArrayFieldOffset($vals, "begdate");
    $enddate_offset = GetArrayFieldOffset($vals, "enddate");
    $list_offset = GetArrayFieldOffset($vals, "list");

    if ($import_file != null) {
        new query($db, 'START TRANSACTION');
        while (!feof($import_file)) {

            $vals = GetImportLineArray($import_file);

            if ($vals[0] == '') {
                PreventTimeout();
                continue;
            }

            $stockcode = GetFieldValue($vals, $stockcode_offset, '');
            $price = GetFieldValue($vals, $price_offset, '0');
            $list = GetFieldValue($vals, $list_offset, '0');
            $discount1 = GetFieldValue($vals, $discount1_offset, '0');
            $discount2 = GetFieldValue($vals, $discount2_offset, '0');
            $discount3 = GetFieldValue($vals, $discount3_offset, '0');
            $discount4 = GetFieldValue($vals, $discount4_offset, '0');
            $discount5 = GetFieldValue($vals, $discount5_offset, '0');
            $recid = GetFieldValue($vals, $recid_offset, '0');
            $begdate = GetFieldValue($vals, $begdate_offset, '1899-12-31');
            $enddate = GetFieldValue($vals, $enddate_offset, '1899-12-31');

            if ($stockcode == '') {
                PreventTimeout();
                continue;
            }

            $insert_query_string =
                "insert into tmpsale (recid, stockcode, price, list, discount1, discount2, discount3, discount4, discount5, begdate, enddate)
      Values( $recid, '$stockcode', $price, $list, $discount1, $discount2, $discount3, $discount4, $discount5, '$begdate', '$enddate')";

            new query($db, $insert_query_string);
            $update_count++;
            if (fmod($update_count, 100) == 0) {
                PreventTimeout();
            }
            if (fmod($update_count, 500) == 0) {
                new query($db, 'COMMIT');
                new query($db, 'START TRANSACTION');
                DisplayStatus('CreateTempInventorySaleImportTable - ' . $update_count);
            }
        }

//ZenCart sale items expire ON the end date, not at the end of it.  Add 1 to all of the end dates imported into the temp table
        $query_string = 'update tmpsale set enddate = enddate + INTERVAL 1 DAY';
        new query($db, $query_string);

        new query($db, 'COMMIT');
        CloseImportFile($import_file);
        unset($import_file);
        DisplayStatus('Inserting temporary inventory sale import table items (complete) - ' . $update_count . ' Total Records');
    }

}

function GetProductStatusUpdateOption($db)
{
    $query = "select value from " . TABLE_CONFIGURATION . " where " . TABLE_CONFIGURATION . ".key = 'category_status'";
    $q = new query($db, $query);
    $q->fetch_row();
    $value = $q->row_data[0]; //get the value

    if ($value == '1') {
        $RetVal = '1';
    } else {
        $RetVal = 'available > 0';
    }
    return $RetVal;
}

function CreateTempInventoryImportTable($db)
{
    global $size_category;
    global $size_stockcode;
    global $size_category_description;
    global $size_model;
    global $size_mfr;
    global $default_collation;
    global $category_levels;

    $update_count = 0;
    DeleteTempInventoryImportTable($db);
    DisplayStatus('');
    DisplayStatus('Creating temporary inventory import table...');
    $query_string =
        "create table tmpcode
    (id int default 0,
     cat_id integer default 0,
     newind tinyint default 0,
     mfr_id integer default 0,
     stockcode varchar($size_stockcode),
     productname varchar(128),
     imagename varchar(64),
     imagecount integer default 0,
     model varchar($size_model),
     cat1 varchar($size_category),
     cat2 varchar($size_category),
     cat3 varchar($size_category),
     cat4 varchar($size_category),
     price numeric(15, 4) default 0.00,
     weight numeric(15,4) default 0.00,
     available integer default 0,
     mfr varchar($size_mfr),
     hasimage tinyint default 0,
     descr text,
     taxable tinyint default 0,
     tax_class_id integer default 0,
     list numeric(15, 4) default 0.00,
     discount1 numeric(15, 4) default 0.00,
     discount2 numeric(15, 4) default 0.00,
     discount3 numeric(15, 4) default 0.00,
     discount4 numeric(15, 4) default 0.00,
     discount5 numeric(15, 4) default 0.00,
     url varchar(128),
     keywords varchar(128),
     freeshipping tinyint default 0,
     cat1descr varchar($size_category_description),
     cat2descr varchar($size_category_description),
     diml integer default 0,
     dimw integer default 0,
     dimh integer default 0,
     dimuom varchar(2),
     cat3descr varchar($size_category_description),
     cat4descr varchar($size_category_description)
     )  $default_collation";
    new query($db, $query_string);

    DisplayStatus('Inserting temporary inventory import table items...');

    $DIR_WS_IMAGES_ATREX = "catalog/";

    $import_file = GetImportFile("atxcode.txt");
    if ($import_file == null) return;

    $line = fgets($import_file);
    if (strpos($line, '~v3') === false) { //check for the v3 indicator in the first line.
        fseek($import_file, 0); //reset to beginning of the file
        $vals = GetImportLineArray($import_file);
    } else {
        $vals = GetImportLineArray($import_file); //get the field descriptions
        $line = fgets($import_file); //skip the field sizes
    }

    $stockcode_offset = GetArrayFieldOffset($vals, "stockcode");
    $productname_offset = GetArrayFieldOffset($vals, "productname");
    $descr_offset = GetArrayFieldOffset($vals, "descr");
    $model_offset = GetArrayFieldOffset($vals, "model");
    $mfr_offset = GetArrayFieldOffset($vals, "mfr");
    $cat1_offset = GetArrayFieldOffset($vals, "category");
    $cat2_offset = GetArrayFieldOffset($vals, "subcat");
    $cat3_offset = GetArrayFieldOffset($vals, "subcat2");
    $cat4_offset = GetArrayFieldOffset($vals, "subcat3");
    $cat1descr_offset = GetArrayFieldOffset($vals, "cat1descr");
    $cat2descr_offset = GetArrayFieldOffset($vals, "cat2descr");
    $cat3descr_offset = GetArrayFieldOffset($vals, "cat3descr");
    $cat4descr_offset = GetArrayFieldOffset($vals, "cat4descr");
    $price_offset = GetArrayFieldOffset($vals, "price");
    $list_offset = GetArrayFieldOffset($vals, "list");
    $weight_offset = GetArrayFieldOffset($vals, "weight");
    $available_offset = GetArrayFieldOffset($vals, "available");
    $has_image_offset = GetArrayFieldOffset($vals, "hasimage");
    $taxable_offset = GetArrayFieldOffset($vals, "taxable");
    $discount1_offset = GetArrayFieldOffset($vals, "discount1");
    $discount2_offset = GetArrayFieldOffset($vals, "discount2");
    $discount3_offset = GetArrayFieldOffset($vals, "discount3");
    $discount4_offset = GetArrayFieldOffset($vals, "discount4");
    $discount5_offset = GetArrayFieldOffset($vals, "discount5");
    $url_offset = GetArrayFieldOffset($vals, "url");
    $keywords_offset = GetArrayFieldOffset($vals, "onlinekeywords");
    $freeshipping_offset = GetArrayFieldOffset($vals, "freeshipping");
    $imagecount_offset = GetArrayFieldOffset($vals, "imagecount");
    $diml_offset = GetArrayFieldOffset($vals, "diml");
    $dimw_offset = GetArrayFieldOffset($vals, "dimw");
    $dimh_offset = GetArrayFieldOffset($vals, "dimh");
    $dimuom_offset = GetArrayFieldOffset($vals, "dimuom");

    $default_tax_class_id = 0;
    $discount4 = '0'; //set default values for discount 4 and 5 in case they are not present
    $discount5 = '0';
    $keywords = '';
    $freeshipping = '0';
    $cat1descr = '';
    $cat2descr = '';
    $cat3descr = '';
    $cat4descr = '';
    $diml = '0';
    $dimw = '0';
    $dimh = '0';
    $dimuom = '';

    $qb = new msQueryBuilder('INSERT');
    $qb->add('stockcode', '\'$stockcode\'');
    $qb->add('productname', '\'$productname\'');
    $qb->add('imagename', '\'$image_name\'');
    $qb->add('descr', '\'$descr\'');
    $qb->add('model', '\'$model\'');
    $qb->add('cat1', '\'$cat1\'');
    $qb->add('cat2', '\'$cat2\'');
    $qb->add('price', '$price');
    $qb->add('list', '$list');
    $qb->add('weight', '$weight');
    $qb->add('available', '$available');
    $qb->add('mfr', '\'$mfr\'');
    $qb->add('hasimage', '$has_image');
    $qb->add('taxable', '$taxable');
    $qb->add('tax_class_id', '$default_tax_class_id');
    $qb->add('discount1', '$discount1');
    $qb->add('discount2', '$discount2');
    $qb->add('discount3', '$discount3');
    $qb->add('url', '\'$url\'');
    $qb->add('imagecount', '$imagecount');

//insert conditional fields from here down
    if ($discount4_offset <> -1) {
        $qb->add('discount4', '$discount4');
        $qb->add('discount5', '$discount5');
    }
    if ($keywords_offset <> -1) {
        $qb->add('keywords', '\'$keywords\'');
        $qb->add('freeshipping', '$freeshipping');
    }
    if ($cat1descr_offset <> -1) {
        $qb->add('cat1descr', '\'$cat1descr\'');
        $qb->add('cat2descr', '\'$cat2descr\'');
        $qb->add('cat3descr', '\'$cat3descr\'');
        $qb->add('cat4descr', '\'$cat4descr\'');
    }
    if ($diml_offset <> -1) {
        $qb->add('diml', '$diml');
        $qb->add('dimw', '$dimw');
        $qb->add('dimh', '$dimh');
        $qb->add('dimuom', '\'$dimuom\'');
    }
    if ($cat3_offset <> -1) {
        $category_levels = 3;
        $qb->add('cat3', '\'$cat3\'');
    }
    if ($cat4_offset <> -1) {
        $category_levels = 4;
        $qb->add('cat4', '\'$cat4\'');
    }

    $_insert_query_string = 'insert into tmpcode ' . $qb->build();

    if ($import_file != null) {
        new query($db, 'START TRANSACTION');
        while (!feof($import_file)) {

            $vals = GetImportLineArray($import_file);

            if ($vals[0] == '') {
                PreventTimeout();
                continue;
            }

            $stockcode = GetFieldValue($vals, $stockcode_offset, '');
            $raw_image_name = GetRawFieldValue($vals, $stockcode_offset, ''); //use raw stock code without slashed characters
            $productname = GetFieldValue($vals, $productname_offset, '');
            $descr = GetFieldValue($vals, $descr_offset, '');
            $model = GetFieldValue($vals, $model_offset, '');
            $mfr = GetFieldValue($vals, $mfr_offset, '');
            $cat1 = GetFieldValue($vals, $cat1_offset, '');
            $cat2 = GetFieldValue($vals, $cat2_offset, '');
            $cat3 = GetFieldValue($vals, $cat3_offset, '');
            $cat4 = GetFieldValue($vals, $cat4_offset, '');
            $price = GetFieldValue($vals, $price_offset, '0');
            $weight = GetFieldValue($vals, $weight_offset, '0');
            $available = GetFieldValue($vals, $available_offset, '0');
            $has_image = GetFieldValue($vals, $has_image_offset, '0');
            $taxable = GetFieldValue($vals, $taxable_offset, '0');
            $list = GetFieldValue($vals, $list_offset, '0');
            $discount1 = GetFieldValue($vals, $discount1_offset, '0');
            $discount2 = GetFieldValue($vals, $discount2_offset, '0');
            $discount3 = GetFieldValue($vals, $discount3_offset, '0');
            $discount4 = GetFieldValue($vals, $discount4_offset, '0');
            $discount5 = GetFieldValue($vals, $discount5_offset, '0');
            if ($keywords_offset <> -1) {
                $keywords = GetFieldValue($vals, $keywords_offset, '');
                $freeshipping = GetFieldValue($vals, $freeshipping_offset, '0');
            }
            $url = GetFieldValue($vals, $url_offset, '');
            if ($cat1descr_offset <> -1) {
                $cat1descr = GetFieldValue($vals, $cat1descr_offset, '');
                $cat2descr = GetFieldValue($vals, $cat2descr_offset, '');
                $cat3descr = GetFieldValue($vals, $cat3descr_offset, '');
                $cat4descr = GetFieldValue($vals, $cat4descr_offset, '');
            }

            if ($diml_offset <> -1) {
                $diml = GetFieldValue($vals, $diml_offset, '0');
                $dimw = GetFieldValue($vals, $dimw_offset, '0');
                $dimh = GetFieldValue($vals, $dimh_offset, '0');
                $dimuom = GetFieldValue($vals, $dimuom_offset, '');
            }

            if ($stockcode == '' or $cat1 == '') { //prevent blank stock code or blank base category
                PreventTimeout();
                continue;
            }
            if ($available == null) {
                PreventTimeout();
                continue;
            }

            if ($cat2 == '') {
                $cat3 = '';
            } //prevent bad category chain
            if ($cat3 == '') {
                $cat4 = '';
            } //prevent bad category chain

            if ($has_image) {
                $imagecount = 1;
                $clean_image_name = CleanImageName($raw_image_name);
                $image_name = "$DIR_WS_IMAGES_ATREX$clean_image_name.jpg";
                $image_name = strtolower($image_name);
                if ($imagecount_offset <> -1) {
                    $imagecount = GetFieldValue($vals, $imagecount_offset, '');
                }
            } else {
                $imagecount = 0;
                $image_name = '';
            }

            $insert_query_string = ''; //declare it so it doesn't throw a new variable flag
            eval("\$insert_query_string = \"$_insert_query_string\";"); //evaluate and convert variables into values
            new query($db, $insert_query_string);
            $update_count++;
            if (fmod($update_count, 100) == 0) {
                PreventTimeout();
            }
            if (fmod($update_count, 500) == 0) {
                new query($db, 'COMMIT');
                new query($db, 'START TRANSACTION');
                DisplayStatus('CreateTempInventoryImportTable - ' . $update_count);
            }
        }
        new query($db, 'COMMIT');
        CloseImportFile($import_file);
        unset($import_file);

        DisplayStatus('Creating temporary inventory import table indexes...');
        CheckIndexInfo($db, 'tmpcode', 'idx_stockcode', 'stockcode');
        CheckIndexInfo($db, 'tmpcode', 'idx_cat1', 'cat1');
        CheckIndexInfo($db, 'tmpcode', 'idx_cat2', 'cat2');
        CheckIndexInfo($db, 'tmpcode', 'idx_cat3', 'cat3');
        CheckIndexInfo($db, 'tmpcode', 'idx_cat4', 'cat4');
        CheckIndexInfo($db, 'tmpcode', 'idx_id', 'id');
        CheckIndexInfo($db, 'tmpcode', 'idx_cat_id', 'cat_id');
        CheckIndexInfo($db, 'tmpcode', 'idx_mfr', 'mfr');

        DisplayStatus('Inserting temporary inventory import table items (complete) - ' . $update_count . ' Total Records');
    }

}

function UpdateTempInventoryTableNewItemIDs($db)
{
    DisplayStatus('Updating temporary inventory table new product IDs...');
//now match the category entries with the correct category id
    $update_product_id_string =
        "update tmpcode tc, " . TABLE_PRODUCTS . " p
      set tc.id = p.product_id
   where tc.stockcode = p.atrex_stockcode and tc.id = 0";
    new query($db, $update_product_id_string);
    DisplayStatus('Updating temporary inventory table new product IDs (complete)');
}


function UpdateTempInventoryTableReferences($db)
{
    global $category_levels;

//now match the category entries with the correct category id
    DisplayStatus('Updating temporary inventory table references (Category IDs)');
    new query($db, 'START TRANSACTION');
    $update_cat_id_string =
        "update tmpcode tc, tmpcat cat
      set tc.cat_id = cat.category_id
   where tc.cat1 = cat.cat1 and tc.cat2 = cat.cat2";

    if ($category_levels >= 3) {
        $update_cat_id_string = $update_cat_id_string . " and tc.cat3 = cat.cat3";
    }
    if ($category_levels >= 4) {
        $update_cat_id_string = $update_cat_id_string . " and tc.cat4 = cat.cat4";
    }

    new query($db, $update_cat_id_string);
    new query($db, 'COMMIT');
    PreventTimeout();


    DisplayStatus('Updating temporary inventory table references (Product ID)');
    new query($db, 'START TRANSACTION');
//now match the category entries with the correct product id
    $update_product_id_string =
        "update tmpcode tc, " . TABLE_PRODUCTS . " p
      set tc.id = p.product_id
   where tc.stockcode = p.atrex_stockcode";
    new query($db, $update_product_id_string);
    new query($db, 'COMMIT');
    PreventTimeout();


    DisplayStatus('Updating temporary inventory table references (Manufacturer ID)');
    new query($db, 'START TRANSACTION');
    $update_mfr_id_string =
        "update tmpcode tc, " . TABLE_MANUFACTURERS . " m
      set tc.mfr_id = m.manufacturer_id
   where tc.mfr = m.name";
    new query($db, $update_mfr_id_string);
    new query($db, 'COMMIT');

    $taxable_tax_class = 'Taxable Goods'; //set a default value in case it isn't passed
    if (isset($_POST['TaxableZCTaxClass'])) $taxable_tax_class = $_POST['TaxableZCTaxClass'];
    $query_string = "select tax_class_id from " . TABLE_TAX_CLASS . " where title = '$taxable_tax_class'";
    $default_query = new query($db, $query_string);
    $default_query->fetch_row();
    $default_tax_class_id = (int)$default_query->row_data[0];

    DisplayStatus('Updating temporary inventory table references (Tax Class ID)');
    new query($db, 'START TRANSACTION');
    $update_tax_class_id_string =
        "update tmpcode tc
      set tc.tax_class_id = $default_tax_class_id
   where tc.taxable = 1";
    new query($db, $update_tax_class_id_string);
    new query($db, 'COMMIT');
    PreventTimeout();
    DisplayStatus('Updating temporary inventory table references (complete)');
}

function UpdateTempInventorySaleReferences($db)
{
    DisplayStatus('Updating temporary inventory sale references (Product ID)');
    new query($db, 'START TRANSACTION');
//now match the category entries with the correct product id
    $update_product_id_string =
        "update tmpsale ts, " . TABLE_PRODUCTS . " p
      set ts.product_id = p.product_id
   where ts.stockcode = p.atrex_stockcode";
    new query($db, $update_product_id_string);
    new query($db, 'COMMIT');
    PreventTimeout();

    DisplayStatus('Updating temporary inventory sale references (Sale ID)');
    new query($db, 'START TRANSACTION');
//now match the category entries with the correct product id
    $update_product_id_string =
        "update tmpsale ts, " . TABLE_SPECIALS . " p
      set ts.sale_id = p.review_id
   where ts.product_id = p.product_id";
    new query($db, $update_product_id_string);
    new query($db, 'COMMIT');
    PreventTimeout();
}

function InsertCategoryEntries($db, $q)
{
    global $language_array;

    $_new_cats = 0;
    $newcat_offset = GetQueryFieldOffset($q, 'newcat');
    $newcat_descr_offset = GetQueryFieldOffset($q, 'newdescr');
    $parent_id_offset = GetQueryFieldOffset($q, 'parent_id');

    for ($i = 0; $i < $q->numrows; $i++) {
        $q->fetch_row();
        $new_cat = addslashes($q->row_data[$newcat_offset]); //get the new category name
        $new_cat_descr = addslashes($q->row_data[$newcat_descr_offset]); //get the new category description
        $parent_id = $q->row_data[$parent_id_offset]; //get the parent id

        if ($parent_id == 0) {
            // category
            $insert_query = "insert into " . TABLE_CATEGORIES . " (parent_id, top, " . TABLE_CATEGORIES . ".column, sort_order, status, date_added)
                                                                  values($parent_id, 1, 1, 1, 1, now())";
        } else {
            $insert_query = "insert into " . TABLE_CATEGORIES . " (parent_id, top, " . TABLE_CATEGORIES . ".column, sort_order, status, date_added)
                                                                   values($parent_id, 0, 0, 0, 1, now())";
        }

        //$insert_query = "insert into " . TABLE_CATEGORIES . " (parent_id, sort_order, date_added) values($parent_id, 0, now())";
        new query($db, $insert_query);
        $insert_id = mysql_insert_id();
        $_new_cats++;

        for ($n = 0; $n < count($language_array); $n++) { //insert an entry for every language id
            $language_id = $language_array[$n];
            // category path parent
            if ($parent_id == 0) {
                $insert_path = "insert into " . TABLE_CATEGORIES_PATH . " (category_id, path_id, level)
                                                                  values($insert_id, $insert_id, 0)";
                new query($db, $insert_path);
            } // category path child
            else {
                $insert_path_parent = "insert into " . TABLE_CATEGORIES_PATH . " (category_id, path_id, level)
                                                                  values($insert_id, $parent_id, 0)";
                new query($db, $insert_path_parent);

                $insert_path_child = "insert into " . TABLE_CATEGORIES_PATH . " (category_id, path_id, level)
                                                                  values($insert_id, $insert_id, 1)";
                new query($db, $insert_path_child);
            }
//            if (CART_TYPE == 'OSC') $insert_descr_query = "insert into " . TABLE_CATEGORIES_DESCRIPTION . " (category_id, language_id, name) values($insert_id, $language_id, '$new_cat')";
//            if (CART_TYPE == 'ZC') $insert_descr_query = "insert into " . TABLE_CATEGORIES_DESCRIPTION . " (category_id, language_id, name, description) values($insert_id, $language_id, '$new_cat', '$new_cat_descr')";
            $insert_descr_query = "insert into " . TABLE_CATEGORIES_DESCRIPTION . " (category_id, language_id, name, description)
                                   values($insert_id, $language_id, '$new_cat', '$new_cat_descr')";
            new query($db, $insert_descr_query);

            // category to store
            $insert_category_store = $insert_path = "insert into " . TABLE_CATEGORIES_STORE . " (category_id, store_id)
                                                                  values($insert_id, 0)";
            new query($db, $insert_category_store);
        }
        if (fmod($_new_cats, 100) == 0) PreventTimeout();
    }
    return $_new_cats;
}

function CreateCategoryEntries($db)
{
    global $language_array;
    global $new_cats;
    global $category_levels;

    DisplayStatus('Creating needed level 1 category entries...');
//find missing cat1 entries - tested 2/28/2014
    $missing_cat_string =
        "select distinct tc.cat1 as newcat, tc.cat1descr as newdescr, 0 as parent_id from tmpcode tc
      left outer join tmpcat cats on tc.cat1 = cats.cat1
   where cats.cat1 is null";
    $missing_cat_query = new query($db, $missing_cat_string);

    $new_cats = InsertCategoryEntries($db, $missing_cat_query);

    DisplayStatus('Creating needed level 1 category entries (complete)');
    if ($new_cats > 0) {
        CreateTempCatTable($db); //create temporary product category table for import linking
        $new_cats = 0;
    }

    DisplayStatus('Creating needed level 2 category entries...');
//find missing cat2 entries
    $missing_subcat_string =
        "select distinct tc.cat1, tc.cat2 as newcat, pc.cat1_id as parent_id, tc.cat2descr as newdescr from tmpcode tc
      left outer join tmpcat cats on tc.cat1 = cats.cat1 and tc.cat2 = cats.cat2
      join tmpcat pc on tc.cat1 = pc.cat1
   where tc.cat2 <> '' and cats.cat2 is null";
    $missing_cat_query = new query($db, $missing_subcat_string);

    $new_cats = InsertCategoryEntries($db, $missing_cat_query);

    DisplayStatus('Creating needed level 2 category entries (complete)');
    if ($new_cats > 0) {
        CreateTempCatTable($db); //create temporary product category table for import linking
        $new_cats = 0;
    }

    if ($category_levels >= 3) {
//find missing cat3 entries
        DisplayStatus('Creating needed level 3 category entries...');
        $missing_subcat_string =
            "select distinct tc.cat1, tc.cat2, tc.cat3 as newcat, pc.cat2_id as parent_id, tc.cat3descr as newdescr from tmpcode tc
      left outer join tmpcat cats on tc.cat1 = cats.cat1 and tc.cat2 = cats.cat2 and tc.cat3 = cats.cat3
      join tmpcat pc on tc.cat1 = pc.cat1 and tc.cat2 = pc.cat2
   where tc.cat3 <> '' and cats.cat3 is null";

        $missing_cat_query = new query($db, $missing_subcat_string);

        $new_cats = InsertCategoryEntries($db, $missing_cat_query);

        DisplayStatus('Creating needed level 3 category entries (complete)');
        if ($new_cats > 0) {
            CreateTempCatTable($db); //create temporary product category table for import linking
            $new_cats = 0;
        }
    }

    if ($category_levels >= 4) {
//find missing cat4 entries
        DisplayStatus('Creating needed level 4 category entries...');
        $missing_subcat_string =
            "select distinct tc.cat1, tc.cat2, tc.cat3, tc.cat4 as newcat, pc.cat3_id as parent_id, tc.cat4descr as newdescr from tmpcode tc
      left outer join tmpcat cats on tc.cat1 = cats.cat1 and tc.cat2 = cats.cat2 and tc.cat3 = cats.cat3 and tc.cat4 = cats.cat4
      join tmpcat pc on tc.cat1 = pc.cat1 and tc.cat2 = pc.cat2 and tc.cat3 = pc.cat3
   where tc.cat4 <> '' and cats.cat4 is null";
        $missing_cat_query = new query($db, $missing_subcat_string);

        $new_cats = InsertCategoryEntries($db, $missing_cat_query);

        DisplayStatus('Creating needed level 4 category entries (complete)');
        if ($new_cats > 0) {
            CreateTempCatTable($db); //create temporary product category table for import linking
            $new_cats = 0;
        }
    }

}

function UpdateCategoryDescriptions($db)
{
    global $default_collation;
    if (CART_TYPE == 'OSC') return;

    new query($db, "drop table if exists tmpcat2");

    $query_string =
        "create table tmpcat2  $default_collation

  select distinct tmpcat.cat1_id, tmpcat.cat2_id, tmpcode.cat1descr, tmpcode.cat2descr
    from tmpcat
    join tmpcode on tmpcat.category_id = tmpcode.cat_id";
    new query($db, $query_string);


    $update_cat_descr_string =
        "update " . TABLE_CATEGORIES_DESCRIPTION . " cd, tmpcat2 cat
      set cd.description = cat.cat1descr
   where cd.category_id = cat.cat1_id";
    new query($db, $update_cat_descr_string);
    PreventTimeout();

    $update_cat_descr_string =
        "update " . TABLE_CATEGORIES_DESCRIPTION . " cd, tmpcat2 cat
      set cd.description = cat.cat2descr
   where cd.category_id = cat.cat2_id";
    new query($db, $update_cat_descr_string);
    PreventTimeout();

    new query($db, "drop table if exists tmpcat2");
}


function CreateManufacturerEntries($db)
{
    global $language_array;
    global $new_mfrs;
    DisplayStatus('Creating needed manufacturer entries...');
//find missing mfr entries
    $missing_mfr_string =
        "select distinct tc.mfr from tmpcode tc
      left outer join " . TABLE_MANUFACTURERS . " mfrs on tc.mfr = mfrs.name
where tc.mfr is not null and mfrs.name is null";
    $missing_mfr_query = new query($db, $missing_mfr_string);

    for ($i = 0; $i < $missing_mfr_query->numrows; $i++) {
        $missing_mfr_query->fetch_row();
        $new_mfr = addslashes($missing_mfr_query->row_data[0]);
        $insert_query = "insert into " . TABLE_MANUFACTURERS . " (name) values('$new_mfr')";
        new query($db, $insert_query);
        $new_mfrs++;
        $insert_id = mysql_insert_id();

        for ($n = 0; $n < count($language_array); $n++) { //insert an entry for every language id
            $language_id = $language_array[$n];
            $insert_descr_query = "insert into " . TABLE_MANUFACTURERS_INFO . " (manufacturer_id) values($insert_id)";
            new query($db, $insert_descr_query);
        }
    }
    DisplayStatus('Creating needed manufacturer entries (complete)');
}

function HandleOSCImages($db)
{
    define('TABLE_PRODUCTS_IMAGES', DB_PREFIX . 'product_image');

//delete entries that exceed the number of images for the product
    $delete_query = "delete from " . TABLE_PRODUCTS_IMAGES . " using " . TABLE_PRODUCTS_IMAGES . " join tmpcode tc on " . TABLE_PRODUCTS_IMAGES . ".product_id = tc.id and " . TABLE_PRODUCTS_IMAGES . ".sort_order > (tc.imagecount - 1)";
    new query($db, $delete_query);

    $codes_select_query = 'select id, imagename, imagecount from tmpcode where imagecount > 1'; //process all of the items with multiple images
    $codes = new query($db, $codes_select_query);

    for ($i = 0; $i < $codes->numrows; $i++) {
        $codes->fetch_row();
        $id = $codes->row_data[0];
        $imagename = $codes->row_data[1];
        $imagecount = $codes->row_data[2];

        $q = new query($db, "select max(sort_order) from " . TABLE_PRODUCTS_IMAGES . " where product_id = $id");
        $q->fetch_row();
        $current_max = $q->row_data[0];
        $add_cnt = ($imagecount - 1 - $current_max);

        for ($ii = $current_max + 1; $ii < $imagecount; $ii++) {
            $insert_query = "insert into " . TABLE_PRODUCTS_IMAGES . " (product_id, sort_order) values($id, $ii)";
            new query($db, $insert_query);
        }

        for ($ii = 1; $ii < $imagecount; $ii++) {
            //update the rows to show the proper image name
            $update_imagename = str_replace('.jpg', sprintf('_%02d.jpg', $ii), $imagename);
            $update_query = "update " . TABLE_PRODUCTS_IMAGES . " set image = '$update_imagename' where product_id = $id and sort_order = $ii";
            new query($db, $update_query);
        }

    }

}

// copy images
CopyImages();

function CopyImages()
{
// source directory
    $source = '../images/atrex/';
// upload directory
    $dir_upload = '../upload/image/catalog/';

//open directory
    $dir = opendir($source);

// write directory
    while (($file = readdir($dir)) !== false) {
        if (is_file($source . "/" . $file)) {
            // info file
            $file_info = pathinfo($file);
            // file name
            $filename = $file_info['basename'];
            // copy files
            copy($source . $filename, $dir_upload . $filename);
            // exists file
//            if (file_exists($dir_upload . $filename)) {
//                // no extension
//                $name_file = $file_info['filename'];
//                // new file
//                $new_file = $name_file . '_atrex.' . $file_info['extension'];
//                // copy files
//                copy($source . $filename, $dir_upload . $new_file);
//            } else {
//                // copy files
//                copy($source . $filename, $dir_upload . $filename);
//            }
        }
    }
    closedir($dir);
}

function CreateProductEntries($db)
{
    global $language_array;
    global $new_codes;
    global $updated_codes;
    $update_count = 0;
    $test_min = -1;
    $test_max = -1;
    $AddOnInsertFields = '';
    $AddOnInsertValues = '';
    $AddOnUpdateFields = '';

    if (isset($_GET['startrec'])) $test_min = $_GET['startrec'];
    if (isset($_GET['endrec'])) $test_max = $_GET['endrec'];

    DisplayStatus('');
    DisplayStatus('Creating/Updating product entries...');

//disable any product that has an Atrex stock code that is NOT present in the upload file.
    $status_query = "update " . TABLE_PRODUCTS . " set status = 0, atrex_live = 0 where atrex_stockcode is not null and atrex_stockcode not in (select stockcode from tmpcode)";
    new query($db, $status_query);

    $status = 1;
    $atrex_live = 1;

    if (CART_TYPE == 'ZC') {
//  $AddOnInsertFields = 'master_category_id,';
//  $AddOnInsertValues = 'cat_id,';
//  $AddOnUpdateFields = 'p.master_category_id = tc.cat_id,';
    }

    DisplayStatus('CreateProductEntries - Inserting New Items');
    $StatusValue = GetProductStatusUpdateOption($db);

    $qb = new msQueryBuilder('SELECT_INSERT');
    $qb->add('quantity', 'available');
    $qb->add('model', 'model');
    $qb->add('price', 'price');
    $qb->add('weight', 'weight');
    $qb->add('date_added', 'now()');
    $qb->add('atrex_stockcode', 'stockcode');
    $qb->add('manufacturer_id', 'mfr_id');
    $qb->add('image', 'imagename');
    $qb->add('status', $StatusValue); //>>>
    $qb->add('tax_class_id', 'tax_class_id');
    $qb->add('atrex_discount1', 'discount1');
    $qb->add('atrex_discount2', 'discount2');
    $qb->add('atrex_discount3', 'discount3');
    $qb->add('atrex_discount4', 'discount4');
    $qb->add('atrex_discount5', 'discount5');
    $qb->add('atrex_list', 'list');
    $qb->add('atrex_live', '1');
    $qb->add('atrex_cat_id', 'cat_id');
    $insert_query = "insert into " . TABLE_PRODUCTS . " " .
        $qb->build() .
        " from tmpcode where id = 0";

    $q = new query($db, $insert_query);
    $new_codes = $q->affected_rows;
    PreventTimeout();
    DisplayStatus('CreateProductEntries - Inserting New Items (complete)');

    DisplayStatus('CreateProductEntries - Updating Temp Table with new item ids');
//now match the new entries with the correct product id
    $update_product_id_string =
        "update tmpcode tc, " . TABLE_PRODUCTS . " p
      set tc.id = p.product_id,
      tc.newind = 1
   where  tc.id = 0 and tc.stockcode = p.atrex_stockcode";
    new query($db, $update_product_id_string);
    PreventTimeout();
    DisplayStatus('CreateProductEntries - Updating Temp Table with new item ids (complete)');

    $res_atrex_product = mysql_query("SELECT id, productname, descr FROM tmpcode WHERE newind = 1 ") or die(mysql_query());
    while ($row_atrex_product = mysql_fetch_array($res_atrex_product)) {
        $id_atrex[] = $row_atrex_product['id'];
        $product_atrex[] = $row_atrex_product['productname'];
        $descr_atrex[] = $row_atrex_product['descr'];
    }

    for ($atr = 0; $atr < count($id_atrex); $atr++) {
        $insert_product_desc = "insert into " . TABLE_PRODUCTS_DESCRIPTION . " (product_id, language_id, name, description)
                                                                  values($id_atrex[$atr], 1, '$product_atrex[$atr]', '$descr_atrex[$atr]')";
        new query($db, $insert_product_desc);

        $insert_product_store = "insert into " . TABLE_PRODUCTS_STORE . " (product_id)
                                                                  values($id_atrex[$atr])";
        new query($db, $insert_product_store);
    }

//    for ($n = 0; $n < count($language_array); $n++) { //insert an entry for every language id
//        $language_id = $language_array[$n];
//
//        $insert_query = "insert into " . TABLE_PRODUCTS_DESCRIPTION . " (product_id, language_id, name, description)
//    	select id, $language_id, productname, descr from tmpcode where newind = 0";
//        new query($db, $insert_query);
//
//        // product_to_store
//        $insert_product_store = "insert into " . TABLE_PRODUCTS_STORE . " (product_id)
//    	select id from tmpcode where newind = 0";
//        new query($db, $insert_product_store);
//
//    }

    PreventTimeout();

    DisplayStatus('CreateProductEntries - Updating Existing Items');
//now handle item updates

    $qb = new msQueryBuilder('UPDATE');
    $qb->add('p.quantity', 'tc.available');
    $qb->add('p.model', 'tc.model');
    $qb->add('p.price', 'tc.price');
    $qb->add('p.weight', 'tc.weight');
    $qb->add('p.date_modified', 'now()');
    $qb->add('p.atrex_stockcode', 'tc.stockcode');
    $qb->add('p.image', 'tc.imagename');
    $qb->add('p.atrex_discount1', 'tc.discount1');
    $qb->add('p.atrex_discount2', 'tc.discount2');
    $qb->add('p.atrex_discount3', 'tc.discount3');
    $qb->add('p.atrex_discount4', 'tc.discount4');
    $qb->add('p.atrex_discount5', 'tc.discount5');
    $qb->add('p.atrex_list', 'tc.list');
    $qb->add('p.tax_class_id', 'tc.tax_class_id');
    $qb->add('p.manufacturer_id', 'tc.mfr_id');
    $qb->add('p.atrex_cat_id', 'tc.cat_id');
    $qb->add('p.atrex_live', '1');
    $qb->add('p.status', $StatusValue); //>>>

    $update_query = "update " . TABLE_PRODUCTS . " p, tmpcode tc set " .
        $qb->build() .
        " where tc.newind = 0 and p.product_id = tc.id";

    $old_update_query = "update " . TABLE_PRODUCTS . " p, tmpcode tc set
	     p.quantity = tc.available,
	     p.model = tc.model,
	     p.price = tc.price,
	     p.weight = tc.weight,
	     p.date_modified = now(),
	     p.atrex_stockcode = tc.stockcode,
	     p.image = tc.imagename,
	     p.atrex_discount1 = tc.discount1,
	     p.atrex_discount2 = tc.discount2,
	     p.atrex_discount3 = tc.discount3,
	     p.atrex_discount4 = tc.discount4,
	     p.atrex_discount5 = tc.discount5,
	     p.atrex_list = tc.list,
	     p.atrex_live = 1,
	     p.atrex_cat_id = tc.cat_id,
	     p.tax_class_id = tc.tax_class_id,
	     p.manufacturer_id = tc.mfr_id
	     where tc.newind = 0 and p.product_id = tc.id";

    $q = new query($db, $update_query);
    $updated_codes = $q->affected_rows;
    DisplayStatus('CreateProductEntries - Updating Existing Items (complete)');
    PreventTimeout();

    DisplayStatus('CreateProductEntries - Updating Existing Items Descriptions');
    $update_query = "update " . TABLE_PRODUCTS_DESCRIPTION . " p, tmpcode tc set
	p.name = tc.productname,
	p.description = tc.descr
	where p.product_id = tc.id and p.language_id = 1 and tc.newind = 0";
    new query($db, $update_query);
    DisplayStatus('CreateProductEntries - Updating Existing Items Descriptions (complete)');

    $update_count = $new_codes + $updated_codes;

    DisplayStatus('Creating/Updating product entries (complete) - ' . $update_count . ' Total Records');
}

function UpdateMasterCategory($db)
{
//    $update_count = 0;
//
//    DisplayStatus('Updating Master Categories...');
//
////Not sure shy, but products_last_modified appears to have to be updated
//    $update_query = "update " . TABLE_PRODUCTS . " p set
//         p.master_categories_id = p.atrex_cat_id
//	     where p.atrex_cat_id is not null";
//    $q = new query($db, $update_query);
//    $updated_count = $q->affected_rows;
//    DisplayStatus('Updating Master Categories (complete) - ' . $update_count . ' Total Records');
}

function CreateProductSaleEntries($db)
{
    global $language_array;
    global $new_codes;
    global $updated_codes;
    $update_count = 0;

    DisplayStatus('');
    DisplayStatus('Clearing dead sale entries...');

    $status_query = "delete from " . TABLE_SPECIALS . " where atrex_recid <> 0 and product_id not in (select product_id from tmpsale)";
    new query($db, $status_query);

    DisplayStatus('');
    DisplayStatus('Creating/Updating sale entries...');

    $status = 1;
    $atrex_live = 1;

    DisplayStatus('CreateProductEntries - Inserting New Items');
    $insert_query = "insert into " . TABLE_SPECIALS . "
	(product_id,
	date_added,
	atrex_discount1,
	atrex_discount2,
	atrex_discount3,
	atrex_discount4,
	atrex_discount5,
	status,
	atrex_recid)
	select
	product_id,
    begdate,
    discount1,
    discount2,
    discount3,
    discount4,
    discount5,
    now() between begdate and enddate,
	recid 	from tmpsale where sale_id = 0";
    $q = new query($db, $insert_query);
    $new_codes = $q->affected_rows;
    PreventTimeout();
    DisplayStatus('CreateProductSaleEntries - Inserting New Sale Items (complete)');


    DisplayStatus('CreateProductSaleEntries - Updating Existing Sale Items');
//now handle item updates
    $update_query = "update " . TABLE_SPECIALS . " p, tmpsale ts set
	     p.product_id = ts.product_id,
	     date_modified = now(),
	     p.atrex_discount1 = ts.discount1,
	     p.atrex_discount2 = ts.discount2,
	     p.atrex_discount3 = ts.discount3,
	     p.atrex_discount4 = ts.discount4,
	     p.atrex_discount5 = ts.discount5,
	     p.status = now() between ts.begdate and ts.enddate
	     where p.review_id = ts.sale_id";
    $q = new query($db, $update_query);
    $updated_codes = $q->affected_rows;
    DisplayStatus('CreateProductEntries - Updating Existing Sale Items (complete)');
    PreventTimeout();

    $update_count = $new_codes + $updated_codes;

    DisplayStatus('Creating/Updating sale entries (complete) - ' . $update_count . ' Total Records');
}

function ClearExistingCategoryLinks($db) //deletes all existing links and potential links between products and categories
{
//Delete Existing Category Links
    DisplayStatus('Clearing product category links...');
//Delete the links that were imported previously
    $delete_query = "delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " USING " . TABLE_PRODUCTS_TO_CATEGORIES . " INNER JOIN " . TABLE_PRODUCTS . " p on " . TABLE_PRODUCTS_TO_CATEGORIES . ".product_id = p.product_id and " . TABLE_PRODUCTS_TO_CATEGORIES . ".category_id = p.atrex_cat_id";
    new query($db, $delete_query);

//Delete any links that are going to be inserted
    $delete_query = "delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " USING " . TABLE_PRODUCTS_TO_CATEGORIES . " INNER JOIN tmpcode p on " . TABLE_PRODUCTS_TO_CATEGORIES . ".product_id = p.id and " . TABLE_PRODUCTS_TO_CATEGORIES . ".category_id = p.cat_id";
    new query($db, $delete_query);
}

function CreateCategoryLinks($db) //does a delete and insert for all imported records at once instead of one record at a time
{
    PreventTimeout();
    DisplayStatus('Creating product category links...');
    new query($db, "insert into " . TABLE_PRODUCTS_TO_CATEGORIES . " (product_id, category_id) select id, cat_id from tmpcode where cat_id > 0");

    // get id product and id subcategory
    $resCat1 = mysql_query("SELECT id, cat_id FROM tmpcode ") or die(mysql_error($db));
    while ($rowCat1 = mysql_fetch_array($resCat1)) {
        $id_product[] = $rowCat1['id'];
        $sub_id[] = $rowCat1['cat_id'];
    }

    // connection of category and product
    for ($sub = 0; $sub < count($sub_id); $sub++) {
        $resCatPath = mysql_query("SELECT path_id FROM " . TABLE_CATEGORIES_PATH . " WHERE category_id = '$sub_id[$sub]' AND level = 0 ")
        or die(mysql_query());
        while ($rowCatPath = mysql_fetch_array($resCatPath)) {
            $cat1[$id_product[$sub]] = $rowCatPath['path_id'];
        }
    }

    // insert product in category
    foreach ($cat1 as $key_cat1 => $value_cat1) {
        mysql_query("INSERT INTO " . TABLE_PRODUCTS_TO_CATEGORIES . " (product_id, category_id) VALUES('$key_cat1', '$value_cat1') ")
        or die(mysql_query());
    }

    DisplayStatus('Updating product category links (complete)');
    PreventTimeout();
}

function CreateKeywordEntries($db) //does a delete and insert for all imported records at once instead of one record at a time
{
    global $language_array;

    // new query($db, "delete from " . TABLE_PRODUCTS_DESCRIPTION . " where product_id in (select id from tmpcode)");

    for ($n = 0; $n < count($language_array); $n++) { //insert an entry for every language id
        $language_id = $language_array[$n];
        $insert_query = "insert into " . TABLE_PRODUCTS_DESCRIPTION . " (product_id, language_id) select id, $language_id from tmpcode where keywords <> ''";
        new query($db, $insert_query);
    }
}

function SetFreeShippingFlags($db) //does a delete and insert for all imported records at once instead of one record at a time
{
//    new query($db, 'START TRANSACTION');
//    $update_product =
//        "UPDATE " . TABLE_PRODUCTS . " p, tmpcode tc
//  SET p.product_is_always_free_shipping = tc.freeshipping
//WHERE p.atrex_stockcode = tc.stockcode";
//    new query($db, $update_product);
//    new query($db, 'COMMIT');
    PreventTimeout();
}


//*** Customer Import Functions

function DeleteTempCustomerImportTable($db)
{
    DisplayStatus('Deleting temporary customer import table...');
//delete the existing table if it exists
    new query($db, "drop table if exists tmpcust");
}

function CreateTempCustomerImportTable($db)
{
    global $size_lastname;
    global $size_add1;
    global $size_add2;
    global $default_collation;

    $update_count = 0;
    DeleteTempCustomerImportTable($db);

    DisplayStatus('Creating temporary customer import table...');

    $query_string =
        "create table tmpcust
    (id integer default 0, address_id integer default 0, country_id integer default 0, zone_id integer default 0, custnum integer, custguid varchar(38), lastname varchar($size_lastname), firstname varchar($size_lastname), add1 varchar($size_add1), add2 varchar($size_add2), city varchar(32), state varchar(32), zip varchar(10), country varchar(64),
       phone varchar(32), fax varchar(32), pricing tinyint default 0, email varchar(96), onlinepw varchar(25) ) $default_collation";
    new query($db, $query_string);

    DisplayStatus('Creating temporary customer import table indexes...');
    CheckIndexInfo($db, 'tmpcust', 'idx_custguid', 'custguid');
    CheckIndexInfo($db, 'tmpcust', 'idx_countryid', 'country_id');
    CheckIndexInfo($db, 'tmpcust', 'idx_zoneid', 'zone_id');

    $import_file = GetImportFile("atxcust.txt");
    if ($import_file == null) return;

    $line = fgets($import_file);
    if (strpos($line, '~v3') === false) { //check for the v3 indicator in the first line.
        fseek($import_file, 0); //reset to beginning of the file
        $vals = GetImportLineArray($import_file);
    } else {
        $vals = GetImportLineArray($import_file); //get the field descriptions
        $line = fgets($import_file); //skip the field sizes
    }


    $custnum_offset = GetArrayFieldOffset($vals, "custnum");
    $custguid_offset = GetArrayFieldOffset($vals, "guid");
    $lastname_offset = GetArrayFieldOffset($vals, "lastname");
    $firstname_offset = GetArrayFieldOffset($vals, "firstname");
    $add1_offset = GetArrayFieldOffset($vals, "add1");
    $add2_offset = GetArrayFieldOffset($vals, "add2");
    $city_offset = GetArrayFieldOffset($vals, "city");
    $state_offset = GetArrayFieldOffset($vals, "state");
    $zip_offset = GetArrayFieldOffset($vals, "zip");
    $country_offset = GetArrayFieldOffset($vals, "country");
    $phone_offset = GetArrayFieldOffset($vals, "phone");
    $fax_offset = GetArrayFieldOffset($vals, "fax");
    $pricing_offset = GetArrayFieldOffset($vals, "pricing");
    $email_offset = GetArrayFieldOffset($vals, "email");
    $password_offset = GetArrayFieldOffset($vals, "onlinepw");

    if ($import_file != null) {
        new query($db, 'START TRANSACTION');
        while (!feof($import_file)) {
            $vals = GetImportLineArray($import_file);

            if ($vals[0] == '') continue;

            $custnum = GetFieldValue($vals, $custnum_offset, '0');
            $custguid = GetFieldValue($vals, $custguid_offset, '');
            $lastname = GetFieldValue($vals, $lastname_offset, '');
            $firstname = GetFieldValue($vals, $firstname_offset, '');
            $add1 = GetFieldValue($vals, $add1_offset, '');
            $add2 = GetFieldValue($vals, $add2_offset, '');
            $city = GetFieldValue($vals, $city_offset, '');
            $state = GetFieldValue($vals, $state_offset, '');
            $zip = GetFieldValue($vals, $zip_offset, '');
            $country = GetFieldValue($vals, $country_offset, '');
            $phone = GetFieldValue($vals, $phone_offset, '');
            $fax = GetFieldValue($vals, $fax_offset, '');
            $pricing = GetFieldValue($vals, $pricing_offset, '0');
            $password = GetFieldValue($vals, $password_offset, '');

            //strip down to a single email in case there is more than one in the email field.
            $delimiter_position = strpos($vals[$email_offset], ';');
            if ($delimiter_position === false) $extracted_email = $vals[$email_offset];
            else $extracted_email = substr($vals[$email_offset], 0, $delimiter_position);
            $email = addslashes($extracted_email);

            $insert_query_string =
                "insert into tmpcust (custnum, custguid, lastname, firstname, add1, add2, city, state, zip, country, pricing, email, phone, fax, onlinepw)
      Values( '$custnum', '$custguid', '$lastname', '$firstname', '$add1', '$add2', '$city', '$state', '$zip', '$country', $pricing, '$email', '$phone', '$fax', '$password')";
            new query($db, $insert_query_string);

            $update_count++;
            if (fmod($update_count, 100) == 0) {
                PreventTimeout();
            }
            if (fmod($update_count, 500) == 0) {
                new query($db, 'COMMIT');
                new query($db, 'START TRANSACTION');
                DisplayStatus('CreateTempCustomerImportTable - ' . $update_count);
            }
        }
        new query($db, 'COMMIT');
        CloseImportFile($import_file);
    }

}

function UpdateTempCustomerTableReferences($db)
{
    DisplayStatus('Updating temporary customer table references...');

//    $query_string = "select value from " . TABLE_CONFIGURATION . " where key = 'STORE_COUNTRY'";
//    $default_query = new query($db, $query_string);
//    $default_query->fetch_row();
//    $default_country_id = (int)$default_query->row_data[0];

    DisplayStatus('Status 1');
    $update_customer_id_string =
        "update tmpcust tc, " . TABLE_CUSTOMERS . " c
      set tc.id = c.customer_id, tc.address_id = c.address_id
   where (tc.email = c.email)";
    new query($db, $update_customer_id_string);

    $update_customer_id_string =
        "update tmpcust tc, " . TABLE_CUSTOMERS . " c
      set tc.id = c.customer_id, tc.address_id = c.address_id
   where (tc.id = 0) and tc.custguid = c.atrex_custguid";
    new query($db, $update_customer_id_string);

//    $update_customer_id_string =
//        "update tmpcust tc
//      set tc.country_id = $default_country_id where (tc.country = '')";
//    new query($db, $update_customer_id_string);

    $update_customer_id_string =    //match on country name, iso_2 code, or iso_3 code
        "update tmpcust tc, " . TABLE_COUNTRIES . " c
      set tc.country_id = c.country_id
   where (tc.country_id = 0) and
   ( upper(tc.country) = upper(c.name) or upper(tc.country) = upper(c.iso_code_2) or upper(tc.country) = upper(c.iso_code_3))";
    new query($db, $update_customer_id_string);

    $update_customer_id_string =    //match on state name or code
        "update tmpcust tc, " . TABLE_ZONES . " z
      set tc.zone_id = z.zone_id
   where (tc.country_id = z.country_id) and
   ( upper(tc.state) = upper(z.name) or upper(tc.state) = upper(z.code))";
    new query($db, $update_customer_id_string);
}


function RandomPassword()
{
    $length = 7;
    $key_chars = 'abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    $rand_max = strlen($key_chars) - 1;

    for ($i = 0; $i < $length; $i++) {
        $rand_pos = rand(0, $rand_max);
        $rand_key[] = $key_chars{$rand_pos};
    }

    $rand_pass = implode('', $rand_key);
    return $rand_pass;
}


function CreateCustomerEntries($db)
{
    global $new_customers;
    global $updated_customers;
    $update_count = 0;

    DisplayStatus('Creating/Updating customer entries...');

    $CustomerImportOption = '2'; //set to default value
    if (isset($_POST['CustomerImportOption'])) $CustomerImportOption = $_POST['CustomerImportOption'];

    if ($CustomerImportOption == '0') {
        DisplayStatus('   Customer Import Option set to skip customer import.  Exiting function...');
        return;
    }

    $status_query = "update " . TABLE_CUSTOMERS . " set atrex_live = 0 where atrex_custnum > 0";
    new query($db, $status_query);

//$query_string = "select * from tmpcust";
//$cust_query = new query($db, $query_string);
//DisplayStatus('  +++ Number of Customers in TempCust BEFORE cleanup: ' . $cust_query->numrows );

    $status_query = "update tmpcust set zone_id = -1 where zone_id = 0 and country_id in (select distinct(country_id) from " . TABLE_ZONES . ")";
    new query($db, $status_query);

    $status_query = "delete from tmpcust where country_id = 0 or zone_id = -1"; //no valid address, dump the entries
    new query($db, $status_query);

//$query_string = "select * from tmpcust";
//$cust_query = new query($db, $query_string);
//DisplayStatus('  +++ Number of Customers in TempCust AFTER cleanup: ' . $cust_query->numrows );


//Handle updates
//  locate existing entry and update
//  locate address entry and update

//Inserts
//  Create customer entry
//  Create address book entry
//  Update customer table with address entry id
//  Create Cust Info entry


//handle inserts
    $query_string = "select * from tmpcust";
    $cust_query = new query($db, $query_string);

    $id_offset = GetQueryFieldOffset($cust_query, "id");
    $address_id_offset = GetQueryFieldOffset($cust_query, "address_id");
    $custnum_offset = GetQueryFieldOffset($cust_query, "custnum");
    $custguid_offset = GetQueryFieldOffset($cust_query, "custguid");
    $lastname_offset = GetQueryFieldOffset($cust_query, "lastname");
    $firstname_offset = GetQueryFieldOffset($cust_query, "firstname");
    $add1_offset = GetQueryFieldOffset($cust_query, "add1");
    $add2_offset = GetQueryFieldOffset($cust_query, "add2");
    $city_offset = GetQueryFieldOffset($cust_query, "city");
    $state_offset = GetQueryFieldOffset($cust_query, "state");
    $zip_offset = GetQueryFieldOffset($cust_query, "zip");
    $phone_offset = GetQueryFieldOffset($cust_query, "phone");
    $fax_offset = GetQueryFieldOffset($cust_query, "fax");
    $pricing_offset = GetQueryFieldOffset($cust_query, "pricing");
    $email_offset = GetQueryFieldOffset($cust_query, "email");
    $password_offset = GetQueryFieldOffset($cust_query, "onlinepw");
    $country_id_offset = GetQueryFieldOffset($cust_query, "country_id");
    $zone_id_offset = GetQueryFieldOffset($cust_query, "zone_id");


    for ($i = 0; $i < $cust_query->numrows; $i++) {
        $cust_query->fetch_row();
        $cust_id = $cust_query->row_data[$id_offset]; //get the customer ID
        $address_id = $cust_query->row_data[$address_id_offset]; //get the address ID

        $custnum = addslashes($cust_query->row_data[$custnum_offset]);
        $custguid = addslashes($cust_query->row_data[$custguid_offset]);
        $lastname = addslashes($cust_query->row_data[$lastname_offset]);
        $firstname = addslashes($cust_query->row_data[$firstname_offset]);
        $add1 = addslashes($cust_query->row_data[$add1_offset]);
        $add2 = addslashes($cust_query->row_data[$add2_offset]);
        $city = addslashes($cust_query->row_data[$city_offset]);
        $state = addslashes($cust_query->row_data[$state_offset]);
        $zip = addslashes($cust_query->row_data[$zip_offset]);
        $phone = addslashes($cust_query->row_data[$phone_offset]);
        $fax = addslashes($cust_query->row_data[$fax_offset]);
        $pricing = addslashes($cust_query->row_data[$pricing_offset]);
        $email = addslashes($cust_query->row_data[$email_offset]);
        //$password = addslashes($cust_query->row_data[$password_offset]);
        $password = $cust_query->row_data[$password_offset];
        $country_id = addslashes($cust_query->row_data[$country_id_offset]);
        $zone_id = addslashes($cust_query->row_data[$zone_id_offset]);

        if (($zone_id == 0) or ($country_id == 0)) {  //no valid address, don't import
            continue;
        }

        if ($cust_id <> 0) { //update existing customers
            $update_query = "update " . TABLE_CUSTOMERS . " set
	     lastname = '$lastname',
	     firstname = '$firstname',
	     email = '$email',
	     telephone = '$phone',
	     fax = '$fax',
	     atrex_custnum = $custnum,
	     atrex_custguid = '$custguid',
	     atrex_pricing = $pricing
	     where customer_id = $cust_id";
            new query($db, $update_query);

            $update_query = "update " . TABLE_ADDRESS_BOOK . " set
	     lastname = '$lastname',
	     firstname = '$firstname',
	     address_1 = '$add1',
	     address_2 = '$add2',
	     postcode = '$zip',
	     city = '$city',
	     country_id = $country_id,
	     zone_id = $zone_id
	     where address_id = $address_id";
            new query($db, $update_query);

            if ($password <> '*') {  //should we deal with passwords or not?
                if (CART_TYPE == 'OSC') $password = tep_encrypt_password($password);
                else $password = zen_encrypt_password($password);
                $password = addslashes($password);
                $update_query = "update " . TABLE_CUSTOMERS . " set
	        customers_password = '$password'
	     where customer_id = $cust_id";
                new query($db, $update_query);

            }
            $updated_customers = $updated_customers + 1;
        }  //End up customer update entry
        else {
            if ($CustomerImportOption <> '2') { //if it's not set to option 2, don't insert new customer
                continue;
            }

            if ($password == '*') {  //should we deal with passwords or not?
                if (CART_TYPE == 'OSC') {
                    $password = tep_encrypt_password(RandomPassword());
                } else {
                    $password = zen_encrypt_password(RandomPassword());
                }
            } else {
                if (CART_TYPE == 'OSC') {
                    $password = tep_encrypt_password($password);
                } else {
                    $password = zen_encrypt_password($password);
                }
            }
            $password = addslashes($password);

            // Create customer entry
            $insert_query = "insert into " . TABLE_CUSTOMERS . " (lastname, firstname, email, telephone, fax, password, atrex_custnum, atrex_custguid, atrex_pricing)
         values('$lastname', '$firstname', '$email', '$phone', '$fax', '$password', $custnum, '$custguid', $pricing)";
            new query($db, $insert_query);
            $new_cust_id = mysql_insert_id(); //get the newly inserted item id

            // Create address book entry
            $insert_query = "insert into " . TABLE_ADDRESS_BOOK . " (customer_id, entry_lastname, entry_firstname, entry_street_address, entry_suburb, entry_city, entry_state, entry_postcode, entry_country_id, entry_zone_id)
         values($new_cust_id, '$lastname', '$firstname', '$add1', '$add2', '$city', '$state', '$zip', $country_id, $zone_id)";
            new query($db, $insert_query);
            $new_address_id = mysql_insert_id(); //get the newly inserted item id

            //  Update customer table with address entry id
            $update_query = "update " . TABLE_CUSTOMERS . " set
	     customers_default_address_id = $new_address_id
	     where customer_id = $new_cust_id";
            new query($db, $update_query);

            //  Create Cust Info entry
//            $insert_query = "insert into " . TABLE_CUSTOMERS_INFO . " (customers_info_id, customers_info_number_of_logons, customers_info_date_account_created)
//                            values($new_cust_id, 0, now())";
//            new query($db, $insert_query);
            $new_customers = $new_customers + 1;
        }  //end of insert section

        $update_count++;
        if (fmod($update_count, 100) == 0) {
            PreventTimeout();
        }
        if (fmod($update_count, 500) == 0) {
            DisplayStatus('CreateCustomerEntries - ' . $update_count);
        }

    } //end of query for loop

    DisplayStatus('Creating/Updating customer entries (complete) - ' . $cust_query->numrows . ' Total Records,  ' . $updated_customers . ' Updated, ' . $new_customers . ' Inserted');
} //end of function


function ImportInventory($db) //*** Handle the inventory import process
{

    DisplayStatus('Starting inventory import...');
    CreateTempInventoryImportTable($db); //create the temporary inventory import table - must be first to set category levels
    CreateTempCatTable($db); //create temporary product category table for import linking
    CreateTempInventorySaleImportTable($db); //create the temporary inventory sale import table

    if (function_exists('userevent_inventory_pre_import')) {
        UpdateTempInventoryTableReferences($db); //call after initial table creation to allow the user pre-import functions to do updates based upon existing links.
        userevent_inventory_pre_import($db);
    }

    CreateCategoryEntries($db); //force inventory category/sub-category entries to exist
    CreateManufacturerEntries($db); //force manufacturer entries to exist
    UpdateTempInventoryTableReferences($db); //update the related id fields for inventory matching
    UpdateCategoryDescriptions($db); //update the category descriptions
    UpdateTempInventorySaleReferences($db); //update the related id fields for inventory matching

    ClearExistingCategoryLinks($db); //MUST be done before creating the product entries
    CreateProductEntries($db); //insert/update the product codes
    CreateCategoryLinks($db); //clear all of the atrex category links and then reinsert
    if (CART_TYPE == 'ZC') {
        CreateKeywordEntries($db);
        SetFreeShippingFlags($db);
        UpdateMasterCategory($db);
        CreateProductSaleEntries($db); //insert/update the product codes into ZenCart
    }
    //if (CART_TYPE == 'OSC')
    HandleOSCImages($db); //~~02/20/2013 - added to allow for multiple images in osCommerce

    if (function_exists('userevent_inventory_post_import')) {
        UpdateTempInventoryTableReferences($db); //call again to allow for custom functions to properly link records in the temp table
        userevent_inventory_post_import($db);
    }
    DeleteTempInventoryImportTable($db); //remove the temporary inventory table
    DeleteTempCatTable($db); //remove the temporary product category table
    DeleteTempInventorySaleImportTable($db); //remove the temporary sale table
}

function ImportCustomers($db) //*** Handle the customer import process
{
    DisplayStatus('Starting customer import...');
    CreateTempCustomerImportTable($db); //create the temporary customer import table
    if (function_exists('userevent_customer_pre_import')) {
        userevent_customer_pre_import($db);
    }

    UpdateTempCustomerTableReferences($db);
    CreateCustomerEntries($db); //insert/update the customer records
    if (function_exists('userevent_customer_post_import')) {
        UpdateTempCustomerTableReferences($db); //call again to allow for custom functions to properly link records in the temp table
        userevent_customer_post_import($db);
    }
    DeleteTempCustomerImportTable($db); //remove the temporary customer table
}


function GetCollation($db)
{
    global $default_collation;
    $sql = "show table status like '" . TABLE_PRODUCTS . "'";
    $q = new query($db, $sql);
    $q->fetch_row();
    $field_offset = GetQueryFieldOffset($q, 'Collation');
    if ($field_offset >= 0) {
        $field_collation = $q->row_data[$field_offset];
        $default_collation = " COLLATE = $field_collation ";
    } else {
        $field_collation = "";
        $default_collation = "";
    }
}


function GetScriptInfo()
{
    echo chr(10);
    echo "<IMPORT_DATA>";

    echo "</IMPORT_DATA>";
    echo chr(10);
}

// *** Beginning of script
DisplayStatus('Beginning of Script');
reset_time_limit(); //try to prevent timeout with 5 minute time limit
echo(sprintf("Detected Cart Name: %s", CART_NAME));

$connectID = DB_SERVER_USERNAME;
$connectPW = DB_SERVER_PASSWORD;
$connectionDB = DB_DATABASE;
$host = DB_SERVER;

$Action = ""; //set to default value
if (isset($_POST['Action'])) $Action = $_POST['Action'];

//Get Script Versions
if ($Action == "GetScriptInfo") {
    GetScriptInfo();
    exit();
}

$db = new db($host, $connectID, $connectPW);
$db->set_db($connectionDB);
SetSQLModeVariables($db);

echo(sprintf("MySQL Connect Results: (%s): %s", mysql_errno($db->linkID), mysql_error($db->linkID)));
echo chr(10);

DisplayStatus('Starting import process...');

GetFieldSizes($db);
CheckStructure($db); //checks the structure of the tables and updates if needed
GetCollation($db);

PopulateLanguageArray($db); //do it once and populate global array
SetSQLMode($db, $custom_sql_mode);
ImportInventory($db);
ImportCustomers($db);
SetSQLMode($db, $standard_sql_mode);
$db->close();

if (DEBUG_MODE == 'NO') {
    EmptyImportFile('atxcode.txt'); //delete the file for security reasons
    EmptyImportFile('atxsale.txt'); //delete the file for security reasons
    EmptyImportFile('atxcust.txt'); //delete the file for security reasons
}

DisplayStatus('Import process complete');
DisplayStatus('Script Complete');
DisplayStatus('End of Script');


?>
