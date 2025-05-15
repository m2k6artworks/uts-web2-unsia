<?php
header('Content-Type: application/json');
require_once '../config/database.php';

$request_method = $_SERVER["REQUEST_METHOD"];
$action = isset($_GET['action']) ? $_GET['action'] : '';

switch ($request_method) {
    case 'GET':
        if ($action == 'get_all_items') {
            getAllItems();
        } elseif ($action == 'get_item' && isset($_GET['id'])) {
            getItem($_GET['id']);
        } else {
            response(400, false, 'Invalid action');
        }
        break;
    
    case 'POST':
        if ($action == 'add_item') {
            addItem();
        } elseif ($action == 'update_item') {
            updateItem();
        } elseif ($action == 'delete_item' && isset($_POST['id'])) {
            deleteItem($_POST['id']);
        } elseif ($action == 'use_item' && isset($_POST['id_barang']) && isset($_POST['use_quantity'])) {
            useItem($_POST['id_barang'], $_POST['use_quantity']);
        } elseif ($action == 'add_quantity' && isset($_POST['id_barang']) && isset($_POST['add_quantity'])) {
            addQuantity($_POST['id_barang'], $_POST['add_quantity']);
        } else {
            response(400, false, 'Invalid action');
        }
        break;
    
    default:
        response(405, false, 'Method not allowed');
        break;
}

function getAllItems() {
    global $conn;
    
    $query = "SELECT * FROM tb_inventory ORDER BY id_barang DESC";
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        $items = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = $row;
        }
        response(200, true, 'Items retrieved successfully', $items);
    } else {
        response(500, false, 'Failed to retrieve items: ' . mysqli_error($conn));
    }
}

function getItem($id) {
    global $conn;
    
    $id = mysqli_real_escape_string($conn, $id);
    $query = "SELECT * FROM tb_inventory WHERE id_barang = '$id'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $item = mysqli_fetch_assoc($result);
        response(200, true, 'Item retrieved successfully', $item);
    } else {
        response(404, false, 'Item not found');
    }
}

function addItem() {
    global $conn;
    
    $kode_barang = mysqli_real_escape_string($conn, $_POST['kode_barang']);
    $nama_barang = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $jumlah_barang = (int)$_POST['jumlah_barang'];
    $satuan_barang = mysqli_real_escape_string($conn, $_POST['satuan_barang']);
    $harga_beli = (float)$_POST['harga_beli'];
    $status_barang = $jumlah_barang <= 0 ? 0 : (int)$_POST['status_barang'];
    
    $check_query = "SELECT * FROM tb_inventory WHERE kode_barang = '$kode_barang'";
    $check_result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        response(400, false, 'Item code already exists');
        return;
    }
    
    $query = "INSERT INTO tb_inventory (kode_barang, nama_barang, jumlah_barang, satuan_barang, harga_beli, status_barang) 
              VALUES ('$kode_barang', '$nama_barang', $jumlah_barang, '$satuan_barang', $harga_beli, $status_barang)";
    
    if (mysqli_query($conn, $query)) {
        $new_id = mysqli_insert_id($conn);
        response(201, true, 'Item added successfully', ['id' => $new_id]);
    } else {
        response(500, false, 'Failed to add item: ' . mysqli_error($conn));
    }
}

function updateItem() {
    global $conn;
    
    $id_barang = (int)$_POST['id_barang'];
    $kode_barang = mysqli_real_escape_string($conn, $_POST['kode_barang']);
    $nama_barang = mysqli_real_escape_string($conn, $_POST['nama_barang']);
    $jumlah_barang = (int)$_POST['jumlah_barang'];
    $satuan_barang = mysqli_real_escape_string($conn, $_POST['satuan_barang']);
    $harga_beli = (float)$_POST['harga_beli'];
    $status_barang = $jumlah_barang <= 0 ? 0 : (int)$_POST['status_barang'];
    
    $check_query = "SELECT * FROM tb_inventory WHERE kode_barang = '$kode_barang' AND id_barang != $id_barang";
    $check_result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        response(400, false, 'Item code already exists for another item');
        return;
    }
    
    $query = "UPDATE tb_inventory 
              SET kode_barang = '$kode_barang', 
                  nama_barang = '$nama_barang', 
                  jumlah_barang = $jumlah_barang, 
                  satuan_barang = '$satuan_barang', 
                  harga_beli = $harga_beli, 
                  status_barang = $status_barang 
              WHERE id_barang = $id_barang";
    
    if (mysqli_query($conn, $query)) {
        response(200, true, 'Item updated successfully');
    } else {
        response(500, false, 'Failed to update item: ' . mysqli_error($conn));
    }
}

function deleteItem($id) {
    global $conn;
    
    $id = (int)$id;
    $query = "DELETE FROM tb_inventory WHERE id_barang = $id";
    
    if (mysqli_query($conn, $query)) {
        response(200, true, 'Item deleted successfully');
    } else {
        response(500, false, 'Failed to delete item: ' . mysqli_error($conn));
    }
}

function useItem($id, $quantity) {
    global $conn;
    
    $id = (int)$id;
    $quantity = (int)$quantity;
    
    $query = "SELECT * FROM tb_inventory WHERE id_barang = $id";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $item = mysqli_fetch_assoc($result);
        $current_quantity = $item['jumlah_barang'];
        
        if ($current_quantity < $quantity) {
            response(400, false, 'Not enough quantity available');
            return;
        }
        
        $new_quantity = $current_quantity - $quantity;
        $new_status = ($new_quantity > 0) ? 1 : 0;
        
        $update_query = "UPDATE tb_inventory 
                         SET jumlah_barang = $new_quantity, 
                             status_barang = $new_status 
                         WHERE id_barang = $id";
        
        if (mysqli_query($conn, $update_query)) {
            response(200, true, 'Item quantity updated successfully', ['new_quantity' => $new_quantity, 'status' => $new_status]);
        } else {
            response(500, false, 'Failed to update item quantity: ' . mysqli_error($conn));
        }
    } else {
        response(404, false, 'Item not found');
    }
}

function addQuantity($id, $quantity) {
    global $conn;
    
    $id = (int)$id;
    $quantity = (int)$quantity;
    
    $query = "SELECT * FROM tb_inventory WHERE id_barang = $id";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $item = mysqli_fetch_assoc($result);
        $current_quantity = $item['jumlah_barang'];
        $current_status = $item['status_barang'];
        
        $new_quantity = $current_quantity + $quantity;
        $new_status = ($current_quantity == 0) ? 1 : $current_status;
        
        $update_query = "UPDATE tb_inventory 
                         SET jumlah_barang = $new_quantity, 
                             status_barang = $new_status 
                         WHERE id_barang = $id";
        
        if (mysqli_query($conn, $update_query)) {
            response(200, true, 'Item quantity updated successfully', ['new_quantity' => $new_quantity, 'status' => $new_status]);
        } else {
            response(500, false, 'Failed to update item quantity: ' . mysqli_error($conn));
        }
    } else {
        response(404, false, 'Item not found');
    }
}

function response($status_code, $success, $message, $data = null) {
    http_response_code($status_code);
    
    $response = [
        'success' => $success,
        'message' => $message
    ];
    
    if ($data !== null) {
        $response['data'] = $data;
    }
    
    echo json_encode($response);
    exit;
} 