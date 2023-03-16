<?php
// Add plugin menu
add_action('admin_menu', 'my_plugin_menu');

function my_plugin_menu() {
    add_menu_page('My Plugin', 'My Plugin', 'manage_options', 'my-plugin', 'my_plugin_page');
}

function my_plugin_page() {
    ?>
    <h1>Import Orders</h1>
    <form method="post" enctype="multipart/form-data">
        <label for="csv_file">CSV file:</label>
        <input type="file" name="csv_file" id="csv_file"><br>
        <label for="partial_refund_ids">Partial refund IDs (comma separated):</label>
        <input type="text" name="partial_refund_ids" id="partial_refund_ids"><br>
        <input type="submit" name="submit" value="Import Orders">
    </form>
    <?php
}

// Import orders function
function import_orders($orders, $partial_refund_ids = array()) {
    foreach ($orders as $order) {
        // Get order details
        $order_id = $order['id'];
        $order_status = $order['status'];
        $order_total = $order['total'];
        $order_currency = $order['currency'];
        $order_date = $order['date'];

        // Create new order
        $new_order = wc_create_order(array(
            'status' => $order_status,
            'customer_id' => get_current_user_id(),
            'created_via' => 'import',
            'date_created' => $order_date,
            'currency' => $order_currency,
            'prices_include_tax' => true,
        ));

        // Add order items
        foreach ($order['items'] as $item) {
            $product_id = $item['product_id'];
            $product = wc_get_product($product_id);
            $item_name = $product->get_name();
            $item_qty = $item['quantity'];
            $item_price = $product->get_price();
            $item_total = $item_qty * $item_price;
            $new_order->add_product($product, $item_qty, array(
                'subtotal' => $item_total,
                'total' => $item_total,
                'subtotal_tax' => 0,
                'tax' => 0,
                'tax_class' => '',
            ));
        }

        // Set order total
        $new_order->set_total($order_total, 'total');

        // Save order
        $new_order->save();

        // Partial refund
        if (in_array($order_id, $partial_refund_ids)) {
            $refund_amount = $order_total * 0.5; // 50% refund
            $refund_reason = 'Customer request';
            $refund_id = $new_order->add_refund(array(
                'amount' => $refund_amount,
                'reason' => $refund_reason,
                'refunded_by' => get_current_user_id(),
            ));

            if (is_wp_error($refund_id)) {
                // Handle error
            }
        }
    }
}

// Upload CSV file function
function upload_csv_file() {
    $file = $_FILES['csv_file'];

    if (empty($file)) {
        // Handle error
    }

    $file_path = $file['tmp_name'];

    if (empty($file_path)) {
        // Handle error
    }

    $orders = array();

    if (($handle = fopen($file_path, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 100, ",")) !== FALSE) {
            $order = array(
                'id' => $data[0],
                'status' => $data[1],
                'total' => $data[2],
                'currency' => $data[3],
                'date' => $data[4],
                'items' => array(),
            );

            $items = explode("|", $data[5]);

            foreach ($items as $item) {
                $item_data = explode("x", $item);
                $order['items'][] = array(
                    'product_id' => $item_data[0],
                    'quantity' => $item_data[1],
                );
            }

            $orders[] = $order;
        }

        fclose($handle);
    }

    // Get partial refund IDs from GUI
    $partial_refund

?>