<!-- Start Header -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
	<div>
		<h6>Manage Boards</h6>
	</div>
	<div>
		<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBoardModal">
			<i class="isax isax-add me-1"></i>Add Board
		</button>
	</div>
</div>
<!-- End Header -->

<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<h5 class="card-title mb-0">School Boards</h5>
			</div>
			<div class="card-body">
				<?php if (!empty($boards)): ?>
					<div class="mb-3">
						<p class="text-muted mb-0">Total Boards: <strong><?php echo isset($total_boards) ? $total_boards : count($boards); ?></strong></p>
					</div>
				<?php endif; ?>
				<div class="table-responsive">
					<table class="table table-hover">
						<thead>
							<tr>
								<th>Board Name</th>
								<th>Status</th>
								<th>Created</th>
								<th class="text-end">Actions</th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($boards)): ?>
								<?php foreach ($boards as $board): ?>
									<tr>
										<td>
											<strong><?php echo htmlspecialchars($board['board_name']); ?></strong>
										</td>
										<td>
											<?php if ($board['status'] == 'active'): ?>
												<span class="badge bg-success">Active</span>
											<?php else: ?>
												<span class="badge bg-secondary">Inactive</span>
											<?php endif; ?>
										</td>
										<td>
											<?php echo date('d M Y', strtotime($board['created_at'])); ?>
										</td>
										<td class="text-end">
											<?php if ($board['vendor_id'] !== NULL): ?>
												<button type="button" class="btn btn-sm btn-outline-primary" onclick="editBoard(<?php echo $board['id']; ?>, '<?php echo htmlspecialchars($board['board_name'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($board['description'] ? $board['description'] : '', ENT_QUOTES); ?>')">
													<i class="isax isax-edit"></i>
												</button>
												<button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteBoard(<?php echo $board['id']; ?>, '<?php echo htmlspecialchars($board['board_name'], ENT_QUOTES); ?>')">
													<i class="isax isax-trash"></i>
												</button>
											<?php else: ?>
												<span class="text-muted">System boards cannot be edited</span>
											<?php endif; ?>
										</td>
									</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr>
									<td colspan="4" class="text-center">No boards found.</td>
								</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Add Board Modal -->
<div class="modal fade" id="addBoardModal" tabindex="-1" aria-labelledby="addBoardModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addBoardModalLabel">Add New Board</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form id="addBoardForm">
				<div class="modal-body">
					<div class="mb-3">
						<label class="form-label">Board Name <span class="text-danger">*</span></label>
						<input type="text" name="board_name" id="board_name" class="form-control" required>
						<div id="board_name_error" class="text-danger fs-13 mt-1" style="display: none;"></div>
					</div>
					<div class="mb-3">
						<label class="form-label">Description</label>
						<textarea name="description" id="board_description" class="form-control" rows="3"></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary">Add Board</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Edit Board Modal -->
<div class="modal fade" id="editBoardModal" tabindex="-1" aria-labelledby="editBoardModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editBoardModalLabel">Edit Board</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form id="editBoardForm">
				<input type="hidden" id="edit_board_id" name="board_id">
				<div class="modal-body">
					<div class="mb-3">
						<label class="form-label">Board Name <span class="text-danger">*</span></label>
						<input type="text" name="board_name" id="edit_board_name" class="form-control" required>
						<div id="edit_board_name_error" class="text-danger fs-13 mt-1" style="display: none;"></div>
					</div>
					<div class="mb-3">
						<label class="form-label">Description</label>
						<textarea name="description" id="edit_board_description" class="form-control" rows="3"></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary">Update Board</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
// Add Board Form Submit
document.getElementById('addBoardForm').addEventListener('submit', function(e) {
	e.preventDefault();
	saveBoard();
});

// Edit Board Form Submit
document.getElementById('editBoardForm').addEventListener('submit', function(e) {
	e.preventDefault();
	updateBoard();
});

// Save new board
function saveBoard() {
	var boardName = document.getElementById('board_name').value.trim();
	var boardDescription = document.getElementById('board_description').value.trim();
	var errorDiv = document.getElementById('board_name_error');
	
	// Reset error
	errorDiv.style.display = 'none';
	errorDiv.textContent = '';
	
	if (!boardName) {
		errorDiv.textContent = 'Board name is required.';
		errorDiv.style.display = 'block';
		return;
	}
	
	// AJAX call to add board
	fetch('<?php echo base_url('schools/add_board'); ?>', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded',
		},
		body: 'board_name=' + encodeURIComponent(boardName) + '&description=' + encodeURIComponent(boardDescription) + '&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>'
	})
	.then(response => response.json())
	.then(data => {
		if (data.status === 'success') {
			// Reload page to show new board
			window.location.reload();
		} else {
			errorDiv.textContent = data.message || 'Failed to add board.';
			errorDiv.style.display = 'block';
		}
	})
	.catch(error => {
		console.error('Error:', error);
		errorDiv.textContent = 'An error occurred. Please try again.';
		errorDiv.style.display = 'block';
	});
}

// Edit board
function editBoard(boardId, boardName, description) {
	document.getElementById('edit_board_id').value = boardId;
	document.getElementById('edit_board_name').value = boardName;
	document.getElementById('edit_board_description').value = description || '';
	
	var modal = new bootstrap.Modal(document.getElementById('editBoardModal'));
	modal.show();
}

// Update board
function updateBoard() {
	var boardId = document.getElementById('edit_board_id').value;
	var boardName = document.getElementById('edit_board_name').value.trim();
	var boardDescription = document.getElementById('edit_board_description').value.trim();
	var errorDiv = document.getElementById('edit_board_name_error');
	
	// Reset error
	errorDiv.style.display = 'none';
	errorDiv.textContent = '';
	
	if (!boardName) {
		errorDiv.textContent = 'Board name is required.';
		errorDiv.style.display = 'block';
		return;
	}
	
	// AJAX call to update board
	fetch('<?php echo base_url('schools/update_board'); ?>', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded',
		},
		body: 'board_id=' + encodeURIComponent(boardId) + '&board_name=' + encodeURIComponent(boardName) + '&description=' + encodeURIComponent(boardDescription) + '&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>'
	})
	.then(response => response.json())
	.then(data => {
		if (data.status === 'success') {
			// Reload page to show updated board
			window.location.reload();
		} else {
			errorDiv.textContent = data.message || 'Failed to update board.';
			errorDiv.style.display = 'block';
		}
	})
	.catch(error => {
		console.error('Error:', error);
		errorDiv.textContent = 'An error occurred. Please try again.';
		errorDiv.style.display = 'block';
	});
}

// Delete board
function deleteBoard(boardId, boardName) {
	Swal.fire({
		title: 'Are you sure?',
		text: 'You want to delete the board "' + boardName + '"? This action cannot be undone.',
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#d33',
		cancelButtonColor: '#3085d6',
		confirmButtonText: 'Yes, delete it!',
		cancelButtonText: 'Cancel'
	}).then((result) => {
		if (result.isConfirmed) {
			// Show loading
			Swal.fire({
				title: 'Deleting...',
				text: 'Please wait while we delete the board.',
				allowOutsideClick: false,
				showConfirmButton: false,
				willOpen: () => {
					Swal.showLoading();
				}
			});

		// AJAX call to delete board
		fetch('<?php echo base_url('schools/delete_board'); ?>', {
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded',
			},
			body: 'board_id=' + encodeURIComponent(boardId) + '&<?php echo $this->security->get_csrf_token_name(); ?>=<?php echo $this->security->get_csrf_hash(); ?>'
		})
		.then(response => response.json())
		.then(data => {
			if (data.status === 'success') {
					Swal.fire({
						title: 'Deleted!',
						text: 'Board has been deleted successfully.',
						icon: 'success',
						timer: 2000,
						showConfirmButton: false
					}).then(() => {
				// Reload page to show updated list
				window.location.reload();
					});
			} else {
					Swal.fire({
						title: 'Error!',
						text: data.message || 'Failed to delete board.',
						icon: 'error',
						confirmButtonText: 'OK'
					});
			}
		})
		.catch(error => {
			console.error('Error:', error);
				Swal.fire({
					title: 'Error!',
					text: 'An error occurred. Please try again.',
					icon: 'error',
					confirmButtonText: 'OK'
				});
		});
	}
	});
}
</script>

