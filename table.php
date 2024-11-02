<?php
include 'connect.php';
include 'sidebar.php';
$errorMessages = [];

if (!empty($errorMessages)) {
    file_put_contents('error_log.txt', implode("\n", $errorMessages), FILE_APPEND);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="table.css">
    <title>Table</title>
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
</head>

<body>
    <!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Import Products</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="importForm" enctype="multipart/form-data" method="POST" action="import.php">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="file">Choose Excel File</label>
                        <input type="file" class="form-control" id="file" name="file" accept=".xlsx, .xls">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="productmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered custom-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="productname">Product Name</label>
                        <input type="text" class="form-control" id="productname" placeholder="Insert Product Name">
                    </div>
                    <div class="form-group">
                        <label for="type">Product Type:</label>
                        <select id="type" class="form-control">
                            <option value="" disabled selected>Select a product type</option>
                            <option value="Beverages">Beverages</option>
                            <option value="Alcoholic Beverages">Alcoholic Beverages</option>
                            <option value="Snacks">Snacks</option>
                            <option value="Tobacco/Cigarettes">Tobacco/Cigarettes</option>
                            <option value="Sweets">Sweets</option>
                            <option value="Dairy">Dairy</option>
                            <option value="Canned Good">Canned Good</option>
                            <option value="Pastry">Pastry</option>
                            <option value="Medication">Medication</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" class="form-control" id="quantity" placeholder="Insert the Amount of Product" min="1">
                    </div>
                    <div class="form-group">
                        <label for="exp">Expiration</label>
                        <input type="date" class="form-control" id="exp" placeholder="Insert Expiration Date" min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="file" class="form-control" id="image" placeholder="Insert Product Image">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning" onclick="addproduct()">Insert</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered custom-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="editproductname">Product Name</label>
                        <input type="text" class="form-control" id="editproductname" placeholder="Insert Product Name">
                    </div>
                    <div class="form-group">
                        <label for="edittype">Product Type:</label>
                        <select id="edittype" class="form-control">
                            <option value="Beverages">Beverages</option>
                            <option value="Alcoholic Beverages">Alcoholic Beverages</option>
                            <option value="Snacks">Snacks</option>
                            <option value="Tobacco/Cigarettes">Tobacco/Cigarettes</option>
                            <option value="Sweets">Sweets</option>
                            <option value="Dairy">Dairy</option>
                            <option value="Canned Good">Canned Good</option>
                            <option value="Pastry">Pastry</option>
                            <option value="Medication">Medication</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editquantity">Quantity</label>
                        <input type="number" class="form-control" id="editquantity" placeholder="Insert the Amount of Product" min="1">
                    </div>
                    <div class="form-group">
                        <label for="editexp">Expiration</label>
                        <input type="date" class="form-control" id="editexp" placeholder="Insert Expiration Date" min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="form-group">
                        <label for="editimage">Image</label>
                        <input type="file" class="form-control" id="editimage" placeholder="Insert Product Image">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning" onclick="showUpdateConfirmation()">Update</button>
                    <input type="hidden" id="hiddendata">
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Update Modal -->
    <div class="modal fade" id="confirmUpdateModal" tabindex="-1" role="dialog" aria-labelledby="confirmUpdateTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered custom-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmUpdateTitle">Confirm Update</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to update this item?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning" id="confirmUpdateButton">Update</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Delete Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered custom-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteTitle">Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this item?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteButton">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter/Sort Modal -->
    <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered custom-modal" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Filter/Sort Products</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="sort">Sort by</label>
                        <select id="sort" class="form-control">
                            <option value="id">Default</option>
                            <option value="asc">Name Ascending(A-Z)</option>
                            <option value="desc">Name Descending(Z-A)</option>
                            <option value="high">Highest Quantity</option>
                            <option value="low">Lowest Quantity</option>
                            <option value="farthest">Closest Expiry Date</option>
                            <option value="closest">Farthest Expiry Date</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="type-sort">Sort by Product Type:</label>
                        <select id="type-sort" class="form-control">
                            <option value="All">All</option>
                            <option value="Beverages">Beverages</option>
                            <option value="Alcoholic Beverages">Alcoholic Beverages</option>
                            <option value="Snacks">Snacks</option>
                            <option value="Tobacco/Cigarettes">Tobacco/Cigarettes</option>
                            <option value="Sweets">Sweets</option>
                            <option value="Dairy">Dairy</option>
                            <option value="Canned Good">Canned Good</option>
                            <option value="Pastry">Pastry</option>
                            <option value="Medication">Medication</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="revertFilters" class="btn btn-warning" data-dismiss="modal">Revert</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="apply-filters">Apply Filters</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container my-3">
        <h1 class="text-center">Inventory</h1>
        <div class="d-flex justify-content-between align-items-center my-4">
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#productmodal">Add Product</button>
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#filterModal">Filter/Sort</button>
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#importModal">Import Products</button>
            <button type="button" class="btn btn-success" onclick="exportProducts()">Export Products</button>
            <input type="text" id="searchInput" class="search-input" placeholder="Search..." onkeyup="filterTable()">
        </div>
        <div id="displaytable"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function exportProducts() {
    window.location.href = 'export.php'; // Redirect to export script
}

        
        $(document).ready(function () {
            displayData();
        });

        function displayData(page = 1) {
            $.ajax({
                url: "display.php?page=" + page, // Pass the page number
                type: 'post',
                data: { displaySend: "true" },
                success: function (data) {
                    $('#displaytable').html(data);
                    $('#displaytable img').each(function() {
                        if ($(this).attr('src') === '') {
                            $(this).attr('src', 'C:/xampp/htdocs/no-image.jpg');
                            $(this).attr('alt', 'No image available');
                        }
                    });
                }
            });
        }

        function loadPage(page) {
            displayData(page); // Call displayData with the selected page
        }

        function isNonNegative(number) {
            return number >= 1;
        }

        function isFutureDate(date) {
            return date >= new Date().toISOString().split('T')[0];
        }

        function addproduct() {
            var productname = $('#productname').val();
            var quantity = $('#quantity').val();
            var expiration = $('#exp').val();
            var type = $('#type').val();

            if (!isNonNegative(quantity)) {
                alert("Quantity cannot be negative or zero!");
                return;
            }
            if (!isFutureDate(expiration)) {
                alert("Expiration date cannot be in the past!");
                return;
            }

            var formData = new FormData();
            formData.append('productSend', productname);
            formData.append('quantitySend', quantity);
            formData.append('expirationSend', expiration);
            formData.append('typeSend', type);

            var fileInput = $('#image')[0];
            if (fileInput.files.length > 0) {
                var file = fileInput.files[0];
                formData.append('imageSend', file, file.name);
            } else {
                console.error("No file selected.");
                return;
            }

            $.ajax({
                url: "insert.php",
                type: 'post',
                data: formData,
                processData: false,
                contentType: false,
                success: function () {
                    displayData(); // Refresh data after successful insert
                },
                error: function (xhr, status, error) {
                    console.error("Error inserting product:", error);
                    alert("Failed to add product. Please try again.");
                },
                complete: function () {
                    $('#productmodal').modal('hide');
                    $('#productname').val('');
                    $('#type').val('');
                    $('#quantity').val('');
                    $('#exp').val('');
                    $('#image').val('');
                    $('.modal-backdrop').remove();
                }
            });
        }

        let itemToDelete = null;

        function deleteline(deleteval) {
            itemToDelete = deleteval; 
            $('#confirmDeleteModal').modal('show');
        }

        $('#confirmDeleteButton').on('click', function () {
            if (itemToDelete) {
                $.ajax({
                    url: "delete.php",
                    type: 'post',
                    data: { deletedata: itemToDelete },
                    success: function () {
                        displayData(); 
                        $('#confirmDeleteModal').modal('hide'); 
                    }
                });
            }
        });

        function editline(editval) {
            $('#hiddendata').val(editval);
            $.post("edit.php", { editval: editval }, function (data) {
                var userid = JSON.parse(data);
                $('#editproductname').val(userid.productname);
                $('#editquantity').val(userid.quantity);
                $('#editexp').val(userid.exp);
                $('#edittype').val(userid.type);
                $('#editimage').val(userid.image);
            });
            $('#editmodal').modal("show");
        }

        function showUpdateConfirmation() {
            $('#confirmUpdateModal').modal('show');
        }

        $('#confirmUpdateButton').on('click', function () {
            updateproduct(); 
            $('#confirmUpdateModal').modal('hide'); 
        });

        function updateproduct() {
            var editproductname = $('#editproductname').val();
            var editquantity = $('#editquantity').val();
            var editexp = $('#editexp').val();
            var edittype = $('#edittype').val();
            var hiddendata = $('#hiddendata').val();

            if (!isNonNegative(editquantity)) {
                alert("Quantity cannot be negative or zero!");
                return;
            }
            if (!isFutureDate(editexp)) {
                alert("Expiration date cannot be in the past!");
                return;
            }

            $.post("edit.php", {
                editproductname: editproductname,
                editquantity: editquantity,
                editexp: editexp,
                edittype: edittype,
                hiddendata: hiddendata
            }, function () {
                $('#editmodal').modal("hide");
                displayData();
            });
        }

        function resetFilters() {
            $('#sort').val('id'); // Default to ID sorting
            $('#type-sort').val('All'); // Reset to show all types
            $('#expiration-sort').val('id'); // Reset expiration sort to default

            // Optionally, refresh the displayed data to reflect the default state
            displayData();
        }

        $(document).ready(function () {
            displayData();

            // Attach click event to the revert button
            $('#revertFilters').on('click', function() {
                resetFilters(); // Call reset function when clicked
            });
        });

        $('#apply-filters').on('click', function() {
            const sort = $('#sort').val();
            const typeSort = $('#type-sort').val();
            const expirationSort = $('#expiration-sort').val();

            $.ajax({
                url: "display.php",
                type: 'post',
                data: {
                    sort: sort,
                    typeSort: typeSort,
                    expirationSort: expirationSort,
                    displaySend: "true"
                },
                success: function(data) {
                    $('#displaytable').html(data);
                    $('#filterModal').modal('hide'); // Ensure modal is hidden
                    $('.modal-backdrop').remove(); // Remove backdrop
                }
            });
        });
    </script>
</body>
</html>
