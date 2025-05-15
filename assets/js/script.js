$(document).ready(function() {
    var table = $('#inventoryTable').DataTable({
        responsive: true,
        ajax: {
            url: 'api/inventory_data?action=get_all_items',
            dataSrc: 'data'
        },
        columns: [
            { data: null, render: function(data, type, row, meta) { return meta.row + 1; } },
            { data: 'kode_barang' },
            { data: 'nama_barang' },
            { data: 'jumlah_barang' },
            { data: 'satuan_barang' },
            { 
                data: 'harga_beli',
                render: function(data, type, row) {
                    return formatCurrency(data);
                }
            },
            { 
                data: 'status_barang',
                render: function(data, type, row) {
                    if (data == 1) {
                        return '<span class="badge bg-success">Available</span>';
                    } else {
                        return '<span class="badge bg-danger">Not Available</span>';
                    }
                }
            },
            { 
                data: null,
                orderable: false,
                render: function(data, type, row) {
                    // Disable Use button if item is not available (status = 0)
                    var useButtonClass = row.status_barang == 0 ? 'disabled' : '';
                    
                    return `
                        <div class="action-buttons">
                            <button class="btn btn-sm btn-warning btn-use ${useButtonClass}" data-id="${row.id_barang}" ${useButtonClass}>
                                <i class="fas fa-minus-circle"></i> Use
                            </button>
                            <button class="btn btn-sm btn-success btn-add-qty" data-id="${row.id_barang}">
                                <i class="fas fa-plus-circle"></i> Qty
                            </button>
                            <button class="btn btn-sm btn-primary btn-edit" data-id="${row.id_barang}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-danger btn-delete" data-id="${row.id_barang}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ],
        order: [[0, 'asc']],
        dom: "<'row w-100'<'col-auto me-auto me-xl-1'l><'col-auto mx-auto order-3 order-xl-0'f><'col-auto text-end custom-button-container'>>" +
             "<'row w-100'<'col-sm-12'tr>>" +
             "<'row w-100'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        language: {
            search: "Search",
            searchPlaceholder: "Search by item code, item name, quantity, unit, price, or status..."
        },
        initComplete: function() {
            $('.custom-button-container').html(
                '<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addItemModal">' +
                '<i class="fas fa-plus"></i> <span class="d-none d-lg-inline">Add</span> New Item</button>'
            );
        }
    });

    function formatCurrency(value) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(value);
    }

    
    // Real-time currency formatting for price inputs
    function setupCurrencyFormatting() {
        function formatInputAsCurrency(input) {
            var caretPos = input.selectionStart;
            var originalLength = input.value.length;
            
            var numericValue = input.value.replace(/[^\d]/g, '');
            
            if (numericValue) {
                var formattedValue = formatCurrency(parseInt(numericValue));
                input.value = formattedValue;
                
                var newLength = input.value.length;
                var posDiff = newLength - originalLength;
                caretPos += posDiff;
                
                if (caretPos >= 0) {
                    input.setSelectionRange(caretPos, caretPos);
                }
            } else {
                input.value = '';
            }
        }
        
        $('#harga_beli').on('input', function() {
            formatInputAsCurrency(this);
        });
        
        $('#edit_harga_beli').on('input', function() {
            formatInputAsCurrency(this);
        });
        
        $('#addItemForm').on('submit', function(e) {
            var formattedPrice = $('#harga_beli').val();
            var numericPrice = formattedPrice.replace(/[^\d]/g, '');
            $('#harga_beli').val(numericPrice);
        });
        
        $('#editItemForm').on('submit', function(e) {
            var formattedPrice = $('#edit_harga_beli').val();
            var numericPrice = formattedPrice.replace(/[^\d]/g, '');
            $('#edit_harga_beli').val(numericPrice);
        });
    }
    
    // Initialize currency formatting
    setupCurrencyFormatting();

    function showAutoCloseAlert(icon, title, message) {
        Swal.fire({
            icon: icon,
            title: title,
            text: message,
            timer: 1000,
            showConfirmButton: false,
            timerProgressBar: true
        });
    }

    // Add Item Form Submit
    $('#addItemForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: 'api/inventory_data?action=add_item',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#addItemModal').modal('hide');
                    $('#addItemForm')[0].reset();
                    
                    showAutoCloseAlert('success', 'Success', response.message);
                    
                    table.ajax.reload(null, false);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while processing your request.'
                });
            }
        });
    });

    // Get Item Data for Edit
    $(document).on('click', '.btn-edit', function() {
        var id = $(this).data('id');
        
        $.ajax({
            url: 'api/inventory_data?action=get_item&id=' + id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var item = response.data;
                    
                    $('#edit_id_barang').val(item.id_barang);
                    $('#edit_kode_barang').val(item.kode_barang);
                    $('#edit_nama_barang').val(item.nama_barang);
                    $('#edit_jumlah_barang').val(item.jumlah_barang);
                    $('#edit_satuan_barang').val(item.satuan_barang);
                    
                    if (item.jumlah_barang == 0) {
                        $('#edit_status_available').prop('disabled', true);
                        $('#edit_status_not_available').prop('checked', true);
                    } else {
                        $('#edit_status_available').prop('disabled', false);
                    }

                    $('#edit_harga_beli').val(formatCurrency(item.harga_beli));
                    
                    if (item.status_barang == 1) {
                        $('#edit_status_available').prop('checked', true);
                    } else {
                        $('#edit_status_not_available').prop('checked', true);
                    }
                    
                    $('#editItemModal').modal('show');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while retrieving item data.'
                });
            }
        });
    });

    // Update Item Form Submit
    $('#editItemForm').on('submit', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: 'api/inventory_data?action=update_item',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#editItemModal').modal('hide');
                    
                    showAutoCloseAlert('success', 'Success', response.message);
                    
                    table.ajax.reload(null, false);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while updating the item.'
                });
            }
        });
    });

    // Delete Item
    $(document).on('click', '.btn-delete', function() {
        var id = $(this).data('id');
        
        Swal.fire({
            title: 'Confirm Deletion',
            text: 'Are you sure you want to delete this item? This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'api/inventory_data?action=delete_item',
                    type: 'POST',
                    data: { id: id },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            showAutoCloseAlert('success', 'Deleted!', response.message);
                            
                            table.ajax.reload(null, false);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An error occurred while deleting the item.'
                        });
                    }
                });
            }
        });
    });

    // Use Item Button Click
    $(document).on('click', '.btn-use', function() {
        var id = $(this).data('id');
        
        $.ajax({
            url: 'api/inventory_data?action=get_item&id=' + id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var item = response.data;
                    
                    $('#use_id_barang').val(item.id_barang);
                    $('#use_nama_barang').text(item.nama_barang);
                    $('#use_current_stock').text(item.jumlah_barang);
                    $('.use_satuan_barang').text(item.satuan_barang);
                    $('#use_quantity').attr('max', item.jumlah_barang);
                    
                    $('#useItemModal').modal('show');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while retrieving item data.'
                });
            }
        });
    });

    // Use Item Form Submit
    $('#useItemForm').on('submit', function(e) {
        e.preventDefault();
        
        var id = $('#use_id_barang').val();
        var quantity = $('#use_quantity').val();
        var current = $('#use_current_stock').text();
        
        if (parseInt(quantity) > parseInt(current)) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'You cannot use more than the available quantity.'
            });
            return;
        }
        
        $.ajax({
            url: 'api/inventory_data?action=use_item',
            type: 'POST',
            data: { id_barang: id, use_quantity: quantity },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#useItemModal').modal('hide');
                    $('#useItemForm')[0].reset();
                    
                    showAutoCloseAlert('success', 'Success', response.message);
                    
                    table.ajax.reload(null, false);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while processing your request.'
                });
            }
        });
    });

    // Add Quantity Button Click
    $(document).on('click', '.btn-add-qty', function() {
        var id = $(this).data('id');
        
        $.ajax({
            url: 'api/inventory_data?action=get_item&id=' + id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    var item = response.data;
                    
                    $('#addqty_id_barang').val(item.id_barang);
                    $('#addqty_nama_barang').text(item.nama_barang);
                    $('#addqty_current_stock').text(item.jumlah_barang);
                    $('.addqty_satuan_barang').text(item.satuan_barang);
                    
                    $('#addQuantityModal').modal('show');
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while retrieving item data.'
                });
            }
        });
    });

    // Add Quantity Form Submit
    $('#addQuantityForm').on('submit', function(e) {
        e.preventDefault();
        
        var id = $('#addqty_id_barang').val();
        var quantity = $('#add_quantity').val();
        
        $.ajax({
            url: 'api/inventory_data?action=add_quantity',
            type: 'POST',
            data: { id_barang: id, add_quantity: quantity },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#addQuantityModal').modal('hide');
                    $('#addQuantityForm')[0].reset();
                    
                    showAutoCloseAlert('success', 'Success', response.message);
                    
                    table.ajax.reload(null, false);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred while processing your request.'
                });
            }
        });
    });

    $('.modal').on('hidden.bs.modal', function() {
        $(this).find('form')[0].reset();
    });

    // Add event handler for quantity change in Add form
    $('#jumlah_barang').on('input', function() {
        var quantity = parseInt($(this).val()) || 0;
        
        if (quantity <= 0) {
            $('#status_available').prop('disabled', true);
            $('#status_not_available').prop('checked', true);
        } else {
            $('#status_available').prop('disabled', false);
        }
    });
    
    // Add event handler for quantity change in Edit form
    $('#edit_jumlah_barang').on('input', function() {
        var quantity = parseInt($(this).val()) || 0;
        
        if (quantity <= 0) {
            $('#edit_status_available').prop('disabled', true);
            $('#edit_status_not_available').prop('checked', true);
        } else {
            $('#edit_status_available').prop('disabled', false);
        }
    });
}); 