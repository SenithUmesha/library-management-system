<?php
require '../../util/snippet.php';
require '../../includes/db_conn.php';
include '../header.php';


session_start();


?>

<style>
	body {
		padding: 0;
		margin: 0;
		width: 100%;
	}
</style>

<body>
	<div class="wrapper">
		<?php include  '../side_navbar.php'; ?>
		<div class="main p-3">
			<div class="container">
				<div style="display: flex; justify-content: space-between; align-items: center; margin-top: 18px;">
					<h4 style=" font-weight: bold;">| Fines</h4>
					<button class="btn btn-success" onclick="openAddModal()"><span class="bi-plus"></span>&nbsp;Add Fine</button>
				</div>
				<div style="margin-top:30px">
					<table id="fines_table" class="table table-striped" style="width:100%">
						<thead>
							<th>ID</th>
							<th>Student Name</th>
							<th>Book Name</th>
							<th>Borrow date</th>
							<th>Return Date</th>
							<th>Overdue Charges</th>
							<th>Actions</th>
						</thead> 
					</table>
				</div>
			</div>
		</div>
	</div>

		<!-- Modal for Add -->
	<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="addModalLabel">Add Student Fine Data</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form id="addForm" onsubmit="return validateAddForm()">
						
						 			
						<div class="mb-3">
							<label for="addStudentName" class="form-label">Student Name</label>
							<input type="text" class="form-control" id="addStudentName" name="addStudentName" required>
						</div>
						<div class="mb-3">
							<label for="addBookName" class="form-label">Book Name</label>
							<input type="text" class="form-control" id="addBookName" name="addBookName"  required>
						</div>
						<div class="mb-3">
							<label for="addBorrowdate" class="form-label">Borrow date</label>
							<input type="date" class="form-control" id="addBorrowdate" name="addBorrowdate" required>
						</div>
						<div class="mb-3">
							<label for="addReturndate" class="form-label">Return date</label>
							<input type="date" class="form-control" id="addReturndate" name="addReturndate" required>
						</div>
						<div class="mb-3">
							<label for="addoverduecharge" class="form-label">Overdue charge</label>
							<input type="number" class="form-control" id="addoverduecharge" name="addoverduecharge" required>
						</div>
						 
						<button type="submit" class="btn btn-primary" onclick="saveAddChanges()">Save</button>
					</form>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal for Edit -->
	<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="editModalLabel">Edit Fine Data</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form id="editForm" onsubmit="return validateEditForm()">

					 
						<div class="mb-3">
							<label for="editStudentName" class="form-label">Student Name</label>
							<input type="text" class="form-control" id="editStudentName" name="editStudentName" >
						</div>
						<div class="mb-3">
							<label for="editBookName" class="form-label">Book Name</label>
							<input type="text" class="form-control" id="editBookName" name="editBookName" >
						</div>
						<div class="mb-3">
							<label for="editBorrowdate" class="form-label">Borrow date</label>
							<input type="date" class="form-control" id="editBorrowdate" name="editBorrowdate" disabled>
						</div>
						<div class="mb-3">
							<label for="editReturndate" class="form-label">Return date</label>
							<input type="date" class="form-control" id="editReturndate" name="editReturndate" disabled>
						</div>
						<div class="mb-3">
							<label for="editoverduecharge" class="form-label">Overdue charge</label>
							<input type="number" class="form-control" id="editoverduecharge" name="editoverduecharge">
						</div>
						<button type="submit" class="btn btn-primary" onclick="saveEditChanges()">Save</button>
					</form>
				</div>
			</div>
		</div>
	</div>


	<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
	<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
	<script>
	   var currentStudentNo;
	   var dataTable;

$(document).ready(function() {
	fetchAllStudents();
});

function fetchAllStudents() {
	dataTable = $('#fines_table').DataTable({
		ajax: {
			url: '../../api/api_fines.php',
			type: 'POST',
			data: {
				action: 'fetch_all'
			}
		},
		columns: [{
				data: 'id',
				title: 'id'
			},
			{
				data: 'student_name',
				title: 'Student Name',
				searchable: true
			},
			{
				data: 'book_Name',
				title: 'Book Name'
			},
			{
				data: 'borrow_date',
				title: 'Borrow date'
			},
			{
				data: 'return_date',
				title: 'Return date'
			},
			{
				data: 'overdue_charge',
				title: 'Overdue charge'
			},
			{
				data: null,
				title: 'Actions',
				render: function(data, type, row) {
					return `
						<button class="btn btn-primary" onclick="openEditModal(${row.id})">
							<span class="bi-pencil">&nbsp;Edit
						</button>
						<button name="submit" class="btn btn-danger" onclick="confirmDelete(${row.id})">
							<span class="bi-trash">&nbsp;Delete
						</button>`;
				}
			}
		],
		lengthMenu: [8, 25, 50, 100],
		paging: true,
		pageLength: 8,
		pagingType: 'full_numbers'
	});
}


		function reloadDataTable() {
			dataTable.ajax.reload();
		}

		function validateEditForm() {
			var updatedStudentName = document.getElementById('editStudentName').value;
			var updatedBookName = document.getElementById('editBookName').value;
			var updateOverdueCharge = document.getElementById('editoverduecharge').value;

			if (!updatedStudentName || !updatedBookName || !updateOverdueCharge) {
				return false;
			}

			return true;
		}

		function validateAddForm() {
			var newStudentName = document.getElementById('addStudentName').value;
			var newBookName = document.getElementById('addBookName').value;
			var newBorrowdate = document.getElementById('addBorrowdate').value;
			var newReturndate = document.getElementById('addReturndate').value;
			var newoverduecharge = document.getElementById('addoverduecharge').value;

			if (!newStudentName || !newBookName || !newBorrowdate || !newReturndate || !newoverduecharge) {
				return false;
			}

		
			return true;
		}

		function openAddModal() {
			var modal = new bootstrap.Modal(document.getElementById('addModal'));
			modal.show();
		}

		function saveAddChanges() {
			var newStudentName = document.getElementById('addStudentName').value;
			var newBookName = document.getElementById('addBookName').value;
			var newBorrowdate = document.getElementById('addBorrowdate').value;
			var newReturndate = document.getElementById('addReturndate').value;
			var newoverduecharge = document.getElementById('addoverduecharge').value;

			if (!validateAddForm()) {
				return;
			}

			$.ajax({
				url: '../../api/api_fines.php',
				type: 'POST',
				data: {
					action: 'add',
					newStudentName: newStudentName,
					newBookName: newBookName,
					newBorrowdate: newBorrowdate,
					newReturndate: newReturndate,
					newoverduecharge: newoverduecharge
				},
				dataType: 'json',
				success: function(data) {
					if (data.error) {
						alert(data.error);
						return;
					} else {
						console.log('New student added successfully:', data);

						reloadDataTable();

						var modal = new bootstrap.Modal(document.getElementById('addModal'));
						modal.hide();
					}
				},
				error: function(xhr, status, error) {
					console.error('AJAX Error:', status, error);
					console.log('Response:', xhr.responseText);
					alert('Error adding new fine');
				}
			});
		}

		function openEditModal(id) {
    		currentStudentNo = id;

    		$.ajax({
    			    url: '../../api/api_fines.php',
       				 type: 'POST',
       				 data: {
        					    action: 'fetch',
         						   id: id
   	     },
        dataType: 'json',
        success: function (data) {
            if (data.error) {
                alert(data.error);
                return;
            }

            document.getElementById('editStudentName').value = data.student_name;
            document.getElementById('editBookName').value = data.book_Name;
            document.getElementById('editBorrowdate').value = data.borrow_date;
            document.getElementById('editReturndate').value = data.return_date;
            document.getElementById('editoverduecharge').value = data.overdue_charge;

            var modal = new bootstrap.Modal(document.getElementById('editModal'));
            modal.show();
        },
        error: function (xhr, status, error) {
            console.error('AJAX Error:', status, error);
            console.log('Response:', xhr.responseText);
            alert('Error fetching fine data');
        }
    });
}
 
function saveEditChanges() {
    var id = currentStudentNo;
    var updatedStudentName = document.getElementById('editStudentName').value;
    var updatedBookName = document.getElementById('editBookName').value;
    var updatedBorrowdate = document.getElementById('editBorrowdate').value;
    var updatedReturndate = document.getElementById('editReturndate').value;
    var updatedOverdueCharge = document.getElementById('editoverduecharge').value;

    if (!validateEditForm()) {
        return;
    }

    $.ajax({
        url: '../../api/api_fines.php',
        type: 'POST',
        data: {
            action: 'update',
            id: id,
            updatedStudentName: updatedStudentName,
            updatedBookName: updatedBookName,
            updatedBorrowdate: updatedBorrowdate,
            updatedReturndate: updatedReturndate,
            updatedOverdueCharge: updatedOverdueCharge  // Fixed variable name
        },
        dataType: 'json',
        success: function (data) {
            if (data.error) {
                alert(data.error);
                return;
            }
            console.log('Changes saved successfully:', data);

            reloadDataTable();

            var modal = new bootstrap.Modal(document.getElementById('editModal'));
            modal.hide();
        },
        error: function (xhr, status, error) {
            console.error('AJAX Error:', status, error);
            console.log('Response:', xhr.responseText);
            alert('Error saving changes');
        }
    });
}

 


// In the confirmDelete function
function confirmDelete(studentNo) {
    if (confirm('Are you sure you want to delete this fine data?')) {
        $.ajax({
            url: '../../api/api_fines.php',
            type: 'POST',
            data: {
                action: 'delete',
                id: studentNo // Corrected parameter name
            },
            dataType: 'json',
            success: function(data) {
                if (data.error) {
                    alert(data.error);
                } else if (data.success) {
                    reloadDataTable();
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                console.log('Response:', xhr.responseText);
                alert('Error confirming delete');
            }
        });
    }
}

	</script>

</body>