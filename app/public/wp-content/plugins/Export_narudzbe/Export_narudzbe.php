<?php
/*
 * Plugin Name: Export narudžbe
 * Description: Plugin ispisuje narudžbe u csv formatu.
 */

// Register the menu item
function register_export_narudzbe_menu() {
    add_menu_page(
        'Export Narudžbe',
        'Export Narudžbe',
        'manage_options',
        'export-narudzbe',
        'export_narudzbe_page',
        'dashicons-download',
        30
    );
}
add_action( 'admin_menu', 'register_export_narudzbe_menu' );

// Display the menu page
function export_narudzbe_page(){
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "local";

    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $date_from = isset($_POST['date_from']) ? $_POST['date_from'] : '';
    $date_to = isset($_POST['date_to']) ? $_POST['date_to'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';

    $p1 = "SELECT `order_item_id`, `order_id`, `product_id`, `date_created`, `product_gross_revenue` FROM `wp_wc_order_product_lookup`";
    $p2 = "SELECT `order_item_name`, `order_item_id` FROM `wp_woocommerce_order_items`";
    $p3 = "SELECT `status`, `customer_id`, `order_id` FROM `wp_wc_order_stats` ";

    // add filters
    $filters = array();
    if (!empty($date_from)) {
        $filters[] = "DATE(date_created) >= '" . date('Y-m-d', strtotime($date_from)) . "'";
    }
    if (!empty($date_to)) {
        $filters[] = "DATE(date_created) <= '" . date('Y-m-d', strtotime($date_to)) . "'";
    }
    if (!empty($status)) {
        $filters[] = "status = '" . $status . "'";
    }

    if (!empty($filters)) {
        $ispis = $p1 . ' ' . $p2 . ' ' . $p3 . ' WHERE ' . implode(' AND ', $filters);
    } else {
        $ispis = $p1 . ' ' . $p2 . ' ' . $p3;
    }

    $result = $conn->query($ispis);

    $n_dok = "exportNarudzi.csv";
    $file = fopen($n_dok, 'w');
    $header = array( "id narudzbe", "id proizvoda", "podaci o proizvodu", "datum narudzbe", "status narudzbe", "kreirano", "id kupca", "cijena");
    fputcsv($file, $header);

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $id_narudzbe = $row['order_id'];
            $id_proizvoda = $row['product_id'];
            $podaci_proizvoda = $row['order_item_name'];
            $datum_narudzbe = $row['date_created'];
            $status_narudzbe = $row['status'];
            $kreirano = $row['date_created'];
            $id_kupac = $row['customer_id'];
            $cijena = $row['product_gross_revenue'];

            $podaci = array($id_narudzbe, $id_proizvoda, $podaci_proizvoda, $datum_narudzbe, $status_narudzbe, $kreirano, $id_kupac, $cijena);
            fputcsv($file, $podaci);
        }
    }

    fclose($file);

    // Download the CSV file
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="' . $n_dok . '";');
    header('Pragma: no-cache');
    readfile($n_dok);

    // Close database connection
    $conn->close();
}
?>
