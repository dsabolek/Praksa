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
$p1 = "SELECT `order_item_id`, `order_id`, `product_id`, `date_created`, `product_gross_revenue` FROM `wp_wc_order_product_lookup`";
$p2 = "SELECT `order_item_name`, `order_item_id` FROM `wp_woocommerce_order_items`";
$p3 = "SELECT `status`, `customer_id`, `order_id` FROM `wp_wc_order_stats` ";
$ispis = $p1 . ' ' . $p2 . ' ' . $p3;
$result = $conn->query($ispis);

$n_dok = "exportNarudzi.csv";
$file = fopen($n_dok, 'w');
$header = array( ‘id narudžbe’, ‘datum narudžbe’, ‘podaci o kupcu’, ‘podaci o dostavi’, 
‘podaci o plaćanju’, ‘status narudzbe’, ‘podaci o proizvodima’);
fputcsv($file, $header);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $id_narudžbe = $row['order_id'];
        $id_proizvoda = $row['product_id'];
        $podaci_proizvoda = $row['order_item_name'];
        $datum_narudžbe = $row['order_date'];
        $status_narudzbe = $row['status'];
        $kreirano = $row['date_created'];
        $id_kupac = $row['customer_id'];
        $cijena = $row['product_gross_revenue'];

        $podaci = array($id_narudžbe, $id_proizvoda, $podaci_proizvoda, $datum_narudžbe, $status_narudzbe, $kreirano, $id_kupac, $cijena);
        fputcsv($file, $podaci);
    }
}

fclose($file);

// Download the CSV file
header('Content-Type: application/csv');
header('Content-Disposition: attachment; filename="' . $n_dok . '";');

readfile($n_dok);

// Close database connection
$conn->close();
?>
