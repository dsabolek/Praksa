<?php
/*
 * Plugin Name: Export narudžbe
 * Description: Plugin ispisuje narudžbe u csv formatu.
 */
// connect to database
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "local";

$conn = mysqli_connect($servername, $username, $password, $dbname);

// check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$header_args = array( ‘id narudžbe’, ‘datum narudžbe’, ‘podaci o kupcu’, ‘podaci o dostavi’, 
                    ‘podaci o plaćanju’, ‘status’, ‘podaci o proizvodima’);
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=csv_export.csv');
    $output = fopen( 'php://output', 'w' );
    ob_end_clean();
    fputcsv($output, $header_args);
    exit();
    ?>
