<?php
require_once 'config/database.php';
include 'includes/header.php';
?>

<div class="row mb-4 page-header">
    <div class="col-md-6">
        <h1 class="fs-4">Sparepart Inventory</h1>
    </div>
    <div class="col-md-6 text-end">
    </div>
</div>
<?php
// is the table 'tb_inventory' exists?
$table_check_query = "SHOW TABLES LIKE 'tb_inventory'";
$table_check_result = mysqli_query($conn, $table_check_query);

if (mysqli_num_rows($table_check_result) == 0) {
    echo "Table 'tb_inventory' does not exist. Please run the setup script to create the necessary tables.<br>";
    echo "<a href='./config/setup'>Run Setup Script</a>";
}
?>
<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table id="inventoryTable" class="table table-bordered w-100">
                <thead class="bg-light">
                    <tr>
                        <th>No</th>
                        <th>Item Code</th>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Item Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="addItemModalLabel">Add New Item</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addItemForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="kode_barang" class="form-label">Item Code/Barcode</label>
                        <input type="text" class="form-control" id="kode_barang" name="kode_barang" required placeholder="Enter item code or scan barcode">
                    </div>
                    <div class="mb-3">
                        <label for="nama_barang" class="form-label">Item Name</label>
                        <input type="text" class="form-control" id="nama_barang" name="nama_barang" required placeholder="Enter item name">
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label for="jumlah_barang" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="jumlah_barang" name="jumlah_barang" min="0" required placeholder="Enter initial quantity">
                        </div>
                        <div class="col-6 mb-3">
                            <label for="satuan_barang" class="form-label">Unit</label>
                            <select class="form-select" id="satuan_barang" name="satuan_barang" required>
                                <option value="">Select Unit</option>
                                <option value="pcs">pcs</option>
                                <option value="kg">kg</option>
                                <option value="liter">liter</option>
                                <option value="meter">meter</option>
                                <option value="box">box</option>
                                <option value="set">set</option>
                                <option value="pack">pack</option>
                                <option value="unit">unit</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="harga_beli" class="form-label">Purchase Price</label>
                        <input type="text" class="form-control" id="harga_beli" name="harga_beli" required placeholder="Enter purchase price">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status_barang" id="status_available" value="1" checked>
                                <label class="form-check-label" for="status_available">Available</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status_barang" id="status_not_available" value="0">
                                <label class="form-check-label" for="status_not_available">Not Available</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Item Modal -->
<div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editItemModalLabel">Edit Item</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editItemForm">
                <div class="modal-body">
                    <input type="hidden" id="edit_id_barang" name="id_barang">
                    <div class="mb-3">
                        <label for="edit_kode_barang" class="form-label">Item Code/Barcode</label>
                        <input type="text" class="form-control" id="edit_kode_barang" name="kode_barang" required placeholder="Enter item code or scan barcode">
                    </div>
                    <div class="mb-3">
                        <label for="edit_nama_barang" class="form-label">Item Name</label>
                        <input type="text" class="form-control" id="edit_nama_barang" name="nama_barang" required placeholder="Enter item name">
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label for="edit_jumlah_barang" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="edit_jumlah_barang" name="jumlah_barang" min="0" required placeholder="Enter quantity">
                        </div>
                        <div class="col-6 mb-3">
                            <label for="edit_satuan_barang" class="form-label">Unit</label>
                            <select class="form-select" id="edit_satuan_barang" name="satuan_barang" required>
                                <option value="">Select Unit</option>
                                <option value="pcs">pcs</option>
                                <option value="kg">kg</option>
                                <option value="liter">liter</option>
                                <option value="meter">meter</option>
                                <option value="box">box</option>
                                <option value="set">set</option>
                                <option value="pack">pack</option>
                                <option value="unit">unit</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_harga_beli" class="form-label">Purchase Price</label>
                        <input type="text" class="form-control" id="edit_harga_beli" name="harga_beli" required placeholder="Enter purchase price">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status_barang" id="edit_status_available" value="1">
                                <label class="form-check-label" for="edit_status_available">Available</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status_barang" id="edit_status_not_available" value="0">
                                <label class="form-check-label" for="edit_status_not_available">Not Available</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Use Item Modal -->
<div class="modal fade" id="useItemModal" tabindex="-1" aria-labelledby="useItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title" id="useItemModalLabel">Use Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="useItemForm">
                <div class="modal-body">
                    <input type="hidden" id="use_id_barang" name="id_barang">
                    <p class="fw-bold" id="use_nama_barang"></p>
                    <p>Current Stock: <span id="use_current_stock"></span> <span class="use_satuan_barang"></span></p>
                    <div class="mb-3">
                        <div class="input-group">
                            <label for="use_quantity" class="input-group-text">Quantity to Use</label>
                            <input type="number" class="form-control" id="use_quantity" name="use_quantity" min="1" required placeholder="Enter quantity to use">
                            <span class="input-group-text use_satuan_barang"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Confirm Use</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Quantity Modal -->
<div class="modal fade" id="addQuantityModal" tabindex="-1" aria-labelledby="addQuantityModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="addQuantityModalLabel">Add Quantity</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addQuantityForm">
                <div class="modal-body">
                    <input type="hidden" id="addqty_id_barang" name="id_barang">
                    <p class="fw-bold" id="addqty_nama_barang"></p>
                    <p>Current Stock: <span id="addqty_current_stock"></span> <span class="addqty_satuan_barang"></span></p>
                    <div class="mb-3">
                        <div class="input-group">
                            <label for="add_quantity" class="input-group-text">Quantity to Add</label>
                            <input type="number" class="form-control" id="add_quantity" name="add_quantity" min="1" required placeholder="Enter quantity to add">
                            <span class="input-group-text addqty_satuan_barang"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Add Quantity</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?> 