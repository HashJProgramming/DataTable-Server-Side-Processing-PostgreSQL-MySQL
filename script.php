<?php
 
// This is a simple example of how to use the SSP_PGSQL class to get data from a PostgreSQL database
$table = 'users';
// Table's primary key
$primaryKey = 'id';
 
// Columns to display
$columns = array(
    array( 'db' => 'firstname', 'dt' => 0 ),
    array( 'db' => 'lastname',  'dt' => 1 ),
    array( 'db' => 'middlename',   'dt' => 2 ),
    array( 'db' => 'suffix',     'dt' => 3 ),
    array( 'db' => 'phone',     'dt' => 4)
);
 
// SQL server connection information (PostgreSQL) 
// edit the following lines to match your database settings
$sql_details = array(
    'user' => 'postgres',
    'pass' => 'hash',
    'db'   => 'test_db',
    'host' => 'localhost',
    'port' => '5432',
    'charset' => 'utf8'
);
 
// Include the SSP_PGSQL class
require( 'ssp.class.posgres.php' );

//  SSO_PGSQL::complex is used for complex queries
// echo json_encode(
//     SSP_PGSQL::complex( $_GET, $sql_details, $table, $primaryKey, $columns )
// );

// SSO_PGSQL::simple is used for simple queries
echo json_encode(
    SSP_PGSQL::simple( $_GET, $sql_details, $table, $primaryKey, $columns )
);