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
    
$podaci1 = $wpdb->get_results("SELECT order_id, date_created, num_items_sold, net_total, status, customer_id FROM `wp_wc_order_stats` ");
     foreach ($podaci1 as $podaci1);

$podaci2 = $wpdb->get_results("SELECT order_item_id, order_item_name FROM `wp_woocommerce_order_items` ");
    foreach ($podaci2 as $podaci2);
 $rezultat = $podaci1 . ' ' . $podaci2;
      echo $rezultat ->post_title;

$header_args = array( ‘ id narudžbe’, ‘datum narudžbe’, ‘podaci o kupcu’, ‘podaci o dostavi’, 
                    ‘o plaćanju’, ‘status’, ‘podaci o proizvodima’);
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=csv_export.csv');
    $output = fopen( 'php://output', 'w' );
    ob_end_clean();
    fputcsv($output, $header_args);
    exit();

    ?>
