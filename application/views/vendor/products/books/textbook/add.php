<!-- Start Breadcrumb -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
	<div>
		<h6><a href="<?php echo base_url('products/textbook'); ?>"><i class="isax isax-arrow-left me-2"></i>Add New Textbook</a></h6>
	</div>
</div>
<!-- End Breadcrumb -->
<?php echo form_open_multipart(base_url('products/textbook/add'), array('id' => 'textbook-form')); ?>
<!-- Images Card (Outside Main Card) -->
<div class="row mt-3">
	<div class="col-12">
		<div class="card mb-3">
			<div class="card-body">
				<h2 class=" border-bottom pb-3  mb-3">Images</h2>
				<div class="row gx-3">
					<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Images (Size: 440px * 530px) <span class="text-danger">*</span></label>
							<input type="file" name="images[]" id="images" class="form-control" form="textbook-form" accept="image/*" multiple required>
							<small class="text-muted fs-13">You can select multiple images. Recommended size: 440px × 530px. Drag images to reorder. Click "Set as Main" to choose the main image.</small>
							<div id="image-preview" class="mt-3 image-sortable-container"></div>
							<input type="hidden" name="image_order" id="image_order" value="">
							<input type="hidden" name="main_image_index" id="main_image_index" value="0">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<h2 class=" border-bottom pb-3  mb-3">Details</h2>
				
				<?php if (validation_errors()): ?>
					<div class="alert alert-danger alert-dismissible fade show" role="alert">
						<strong>Please fix the following errors:</strong>
						<ul class="mb-0 mt-2">
							<?php echo validation_errors('<li>', '</li>'); ?>
						</ul>
						<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
					</div>
				<?php endif; ?>
				
					<!-- Basic Information -->
					<div class="row gx-3">
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Type <span class="text-danger">*</span></label>
								<div class="input-group">
									<select name="types[]" id="types" class="select" multiple required>
										<?php if (!empty($types)): ?>
											<?php foreach ($types as $type): ?>
												<option value="<?php echo $type['id']; ?>"><?php echo htmlspecialchars($type['name']); ?></option>
											<?php endforeach; ?>
										<?php endif; ?>
									</select>
									<button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addTypeModal" style="padding: 0.4rem 1rem;">
										<i class="isax isax-add"></i> Add
									</button>
								</div>
								<?php echo form_error('types', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Publisher <span class="text-danger">*</span></label>
								<div class="input-group">
									<select name="publisher_id" id="publisher_id" class="select" required>
										<option value="">Select Publisher</option>
										<?php if (!empty($publishers)): ?>
											<?php foreach ($publishers as $publisher): ?>
												<option value="<?php echo $publisher['id']; ?>" <?php echo (isset($textbook) && isset($textbook['publisher_id']) && $textbook['publisher_id'] == $publisher['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($publisher['name']); ?></option>
											<?php endforeach; ?>
										<?php endif; ?>
									</select>
									<button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addPublisherModal" style="padding: 0.4rem 1rem;">
										<i class="isax isax-add"></i> Add
									</button>
								</div>
								<?php echo form_error('publisher_id', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Board <span class="text-danger">*</span></label>
								<select name="board_id" id="board_id" class="select" required>
									<option value="">Select Board</option>
									<?php if (!empty($boards)): ?>
										<?php foreach ($boards as $board): ?>
											<option value="<?php echo $board['id']; ?>" <?php echo (isset($textbook) && isset($textbook['board_id']) && $textbook['board_id'] == $board['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($board['board_name']); ?></option>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
								<?php echo form_error('board_id', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Select Grade/Age <span class="text-danger">*</span></label>
								<select name="grade_age_type" id="grade_age_type" class="select" required onchange="toggleGradeAgeFields()">
									<option value="">Select Option</option>
									<option value="grade" <?php echo (isset($textbook) && isset($textbook['grade_age_type']) && $textbook['grade_age_type'] == 'grade') ? 'selected' : ''; ?>>Grade</option>
									<option value="age" <?php echo (isset($textbook) && isset($textbook['grade_age_type']) && $textbook['grade_age_type'] == 'age') ? 'selected' : ''; ?>>Age</option>
								</select>
								<?php echo form_error('grade_age_type', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<!-- Grade Fields (shown when Grade is selected) -->
						<div class="col-lg-6 col-md-6" id="gradeFieldsContainer" style="display: none;">
							<div class="mb-3">
								<label class="form-label">Grade <span class="text-danger">*</span></label>
								<div class="input-group">
									<select name="grades[]" id="grades" class="select select2-multiple grade-select" multiple>
										<?php if (!empty($grades)): ?>
											<?php foreach ($grades as $grade): ?>
												<option value="<?php echo $grade['id']; ?>"><?php echo htmlspecialchars($grade['name']); ?></option>
											<?php endforeach; ?>
										<?php endif; ?>
									</select>
									<button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addGradeModal" style="padding: 0.4rem 1rem;">
										<i class="isax isax-add"></i> Add
									</button>
								</div>
								<?php echo form_error('grades', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<!-- Age Fields (shown when Age is selected) -->
						<div class="col-lg-6 col-md-6" id="ageFieldsContainer" style="display: none;">
							<div class="mb-3">
								<label class="form-label">Age <span class="text-danger">*</span></label>
								<div class="input-group">
									<select name="ages[]" id="ages" class="select select2-multiple age-select" multiple>
										<?php if (!empty($ages)): ?>
											<?php foreach ($ages as $age): ?>
												<option value="<?php echo $age['id']; ?>"><?php echo htmlspecialchars($age['name']); ?></option>
											<?php endforeach; ?>
										<?php endif; ?>
									</select>
									<button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addAgeModal" style="padding: 0.4rem 1rem;">
										<i class="isax isax-add"></i> Add
									</button>
								</div>
								<?php echo form_error('ages', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Subject <span class="text-danger">*</span></label>
								<div class="input-group">
									<select name="subjects[]" id="subjects" class="select select2-multiple subject-select" multiple required>
										<?php if (!empty($subjects)): ?>
											<?php foreach ($subjects as $subject): ?>
												<option value="<?php echo $subject['id']; ?>"><?php echo htmlspecialchars($subject['name']); ?></option>
											<?php endforeach; ?>
										<?php endif; ?>
									</select>
									<button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addSubjectModal" style="padding: 0.4rem 1rem;">
										<i class="isax isax-add"></i> Add
									</button>
								</div>
								<?php echo form_error('subjects', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Product Name/Display Name <span class="text-danger">*</span></label>
								<input type="text" name="product_name" id="product_name" class="form-control" value="<?php echo set_value('product_name', isset($textbook) ? $textbook['product_name'] : ''); ?>" required>
								<?php echo form_error('product_name', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">ISBN/Bar Code No./SKU <span class="text-danger">*</span></label>
								<input type="text" name="isbn" id="isbn" class="form-control" value="<?php echo set_value('isbn', isset($textbook) ? $textbook['isbn'] : ''); ?>" required>
								<?php echo form_error('isbn', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Min Quantity <span class="text-danger">*</span></label>
								<input type="number" name="min_quantity" id="min_quantity" class="form-control" value="<?php echo set_value('min_quantity', isset($textbook) ? $textbook['min_quantity'] : 1); ?>" min="1" required>
								<?php echo form_error('min_quantity', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Days To Exchange</label>
								<input type="number" name="days_to_exchange" id="days_to_exchange" class="form-control" value="<?php echo set_value('days_to_exchange', isset($textbook) ? $textbook['days_to_exchange'] : ''); ?>" min="0">
							</div>
						</div>
					</div>
					
					<!-- Description Fields -->
					<div class="row gx-3">
						<div class="col-12">
							<div class="mb-3">
								<label class="form-label">Pointers / Highlights</label>
								<textarea name="pointers" id="pointers" class="form-control ckeditor" rows="5"><?php echo set_value('pointers', isset($textbook) ? $textbook['pointers'] : ''); ?></textarea>
							</div>
						</div>
						<div class="col-12">
							<div class="mb-3">
								<label class="form-label">Product Description <span class="text-danger">*</span></label>
								<textarea name="product_description" id="product_description" class="form-control ckeditor" rows="5" required><?php echo set_value('product_description', isset($textbook) ? $textbook['product_description'] : ''); ?></textarea>
								<?php echo form_error('product_description', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
					</div>
				
			</div>
		</div>
	</div>
</div>

<!-- Packaging Details Card (Outside Main Card) -->
<div class="row">
	<div class="col-12">
		<div class="card mb-3">
			<div class="card-body">
				<h2 class=" border-bottom pb-3 mb-3">Packaging Size</h2>
				<div class="row gx-3">
					<div class="col-lg-3 col-md-6">
						<div class="mb-3">
							<label class="form-label">Length (in cm)</label>
							<input type="number" name="packaging_length" id="packaging_length" class="form-control" form="textbook-form" value="<?php echo set_value('packaging_length', isset($textbook) ? $textbook['packaging_length'] : ''); ?>" step="0.01" min="0">
						</div>
					</div>
					<div class="col-lg-3 col-md-6">
						<div class="mb-3">
							<label class="form-label">Width (in cm)</label>
							<input type="number" name="packaging_width" id="packaging_width" class="form-control" form="textbook-form" value="<?php echo set_value('packaging_width', isset($textbook) ? $textbook['packaging_width'] : ''); ?>" step="0.01" min="0">
						</div>
					</div>
					<div class="col-lg-3 col-md-6">
						<div class="mb-3">
							<label class="form-label">Height (in cm)</label>
							<input type="number" name="packaging_height" id="packaging_height" class="form-control" form="textbook-form" value="<?php echo set_value('packaging_height', isset($textbook) ? $textbook['packaging_height'] : ''); ?>" step="0.01" min="0">
						</div>
					</div>
					<div class="col-lg-3 col-md-6">
						<div class="mb-3">
							<label class="form-label">Weight (in gm)</label>
							<input type="number" name="packaging_weight" id="packaging_weight" class="form-control" form="textbook-form" value="<?php echo set_value('packaging_weight', isset($textbook) ? $textbook['packaging_weight'] : ''); ?>" step="0.01" min="0">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Tax Details Card (Outside Main Card) -->
<div class="row mt-3">
	<div class="col-12">
		<div class="card mb-3">
			<div class="card-body">
				<h2 class=" border-bottom pb-3 mb-3">Tax</h2>
				<div class="row gx-3">
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">GST (%) <span class="text-danger">*</span></label>
							<input type="number" name="gst_percentage" id="gst_percentage" class="form-control" form="textbook-form" value="<?php echo set_value('gst_percentage', isset($textbook) ? $textbook['gst_percentage'] : 0); ?>" step="0.01" min="0" max="100" required>
							<?php echo form_error('gst_percentage', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
						</div>
					</div>
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">HSN</label>
							<input type="text" name="hsn" id="hsn" class="form-control" form="textbook-form" value="<?php echo set_value('hsn', isset($textbook) ? $textbook['hsn'] : ''); ?>">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Price Details Card (Outside Main Card) -->
<div class="row">
	<div class="col-12">
		<div class="card mb-3">
			<div class="card-body">
				<h2 class=" border-bottom pb-3 mb-3">Price</h2>
				<div class="row gx-3">
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">Product Code (For control of school set)</label>
							<input type="text" name="product_code" id="product_code" class="form-control" form="textbook-form" value="<?php echo set_value('product_code', isset($textbook) ? $textbook['product_code'] : ''); ?>">
						</div>
					</div>
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">SKU /Product Code</label>
							<input type="text" name="sku" id="sku" class="form-control" form="textbook-form" value="<?php echo set_value('sku', isset($textbook) ? $textbook['sku'] : ''); ?>">
						</div>
					</div>
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">MRP <span class="text-danger">*</span></label>
							<input type="number" name="mrp" id="mrp" class="form-control" form="textbook-form" value="<?php echo set_value('mrp', isset($textbook) ? $textbook['mrp'] : ''); ?>" step="0.01" min="0" required>
							<small class="text-danger" id="mrp_error" style="display:none;">MRP must be higher than Selling Price</small>
							<?php echo form_error('mrp', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
						</div>
					</div>
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">Selling Price <span class="text-danger">*</span></label>
							<input type="number" name="selling_price" id="selling_price" class="form-control" form="textbook-form" value="<?php echo set_value('selling_price', isset($textbook) ? $textbook['selling_price'] : ''); ?>" step="0.01" min="0" required>
							<small class="text-danger" id="selling_price_error" style="display:none;">Selling Price must be lower than MRP</small>
							<?php echo form_error('selling_price', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Meta Details Card (Outside Main Card) -->
<div class="row">
	<div class="col-12">
		<div class="card mb-3">
			<div class="card-body">
				<h2 class=" border-bottom pb-3 mb-3">Meta Details</h2>
				<div class="row gx-3">
					<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Meta Title</label>
							<input type="text" name="meta_title" id="meta_title" class="form-control" form="textbook-form" value="<?php echo set_value('meta_title', isset($textbook) ? $textbook['meta_title'] : ''); ?>">
						</div>
					</div>
					<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Meta Keywords</label>
							<textarea name="meta_keywords" id="meta_keywords" class="form-control" form="textbook-form" rows="3"><?php echo set_value('meta_keywords', isset($textbook) ? $textbook['meta_keywords'] : ''); ?></textarea>
						</div>
					</div>
					<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Meta Description</label>
							<textarea name="meta_description" id="meta_description" class="form-control" form="textbook-form" rows="3"><?php echo set_value('meta_description', isset($textbook) ? $textbook['meta_description'] : ''); ?></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Product Type Card (Outside Main Card) -->
<div class="row">
	<div class="col-12">
		<div class="card mb-3">
			<div class="card-body">
				<h2 class=" border-bottom pb-3 mb-3">Product Type</h2>
				<div class="row gx-3">
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<div class="form-check form-switch">
								<input class="form-check-input" type="checkbox" name="is_individual" id="is_individual" value="1" form="textbook-form">
								<label class="form-check-label" for="is_individual">
									Is Individual Product
								</label>
							</div>
							<small class="text-muted">Check this if this is an individual product that can be sold separately</small>
						</div>
					</div>
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<div class="form-check form-switch">
								<input class="form-check-input" type="checkbox" name="is_set" id="is_set" value="1" form="textbook-form">
								<label class="form-check-label" for="is_set">
									Is Set Product
								</label>
							</div>
							<small class="text-muted">Check this if this is a bookset (collection of books sold together)</small>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="border-top my-3 pt-3">
	<div class="d-flex align-items-center justify-content-end gap-2">
		<a href="<?php echo base_url('products/textbook'); ?>" class="btn btn-outline">Cancel</a>
		<button type="submit" form="textbook-form" class="btn btn-primary" onclick="return validatePrice();">Create Textbook</button>
	</div>
</div>
<?php echo form_close(); ?>

<!-- Add Type Modal -->
<div class="modal fade" id="addTypeModal" tabindex="-1" aria-labelledby="addTypeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addTypeModalLabel">Add Type</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="addTypeForm">
					<div class="mb-3">
						<label class="form-label">Name <span class="text-danger">*</span></label>
						<input type="text" name="name" id="type_name" class="form-control" required>
					</div>
					<div class="mb-3">
						<label class="form-label">Description</label>
						<textarea name="description" id="type_description" class="form-control" rows="3"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" onclick="addType()">Add Type</button>
			</div>
		</div>
	</div>
</div>

<!-- Add Publisher Modal -->
<div class="modal fade" id="addPublisherModal" tabindex="-1" aria-labelledby="addPublisherModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addPublisherModalLabel">Add Publisher</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="addPublisherForm">
					<div class="mb-3">
						<label class="form-label">Name <span class="text-danger">*</span></label>
						<input type="text" name="name" id="publisher_name" class="form-control" required>
					</div>
					<div class="mb-3">
						<label class="form-label">Description</label>
						<textarea name="description" id="publisher_description" class="form-control" rows="3"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" onclick="addPublisher()">Add Publisher</button>
			</div>
		</div>
	</div>
</div>

<!-- Add Grade Modal -->
<div class="modal fade" id="addGradeModal" tabindex="-1" aria-labelledby="addGradeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addGradeModalLabel">Add Grade</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="addGradeForm">
					<div class="mb-3">
						<label class="form-label">Name <span class="text-danger">*</span></label>
						<input type="text" name="name" id="grade_name" class="form-control" required>
					</div>
					<div class="mb-3">
						<label class="form-label">Description</label>
						<textarea name="description" id="grade_description" class="form-control" rows="3"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" onclick="addGrade()">Add Grade</button>
			</div>
		</div>
	</div>
</div>

<!-- Add Age Modal -->
<div class="modal fade" id="addAgeModal" tabindex="-1" aria-labelledby="addAgeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addAgeModalLabel">Add Age</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="addAgeForm">
					<div class="mb-3">
						<label class="form-label">Name <span class="text-danger">*</span></label>
						<input type="text" name="name" id="age_name" class="form-control" required>
					</div>
					<div class="mb-3">
						<label class="form-label">Description</label>
						<textarea name="description" id="age_description" class="form-control" rows="3"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" onclick="addAge()">Add Age</button>
			</div>
		</div>
	</div>
</div>

<!-- Add Subject Modal -->
<div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addSubjectModalLabel">Add Subject</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="addSubjectForm">
					<div class="mb-3">
						<label class="form-label">Name <span class="text-danger">*</span></label>
						<input type="text" name="name" id="subject_name" class="form-control" required>
					</div>
					<div class="mb-3">
						<label class="form-label">Description</label>
						<textarea name="description" id="subject_description" class="form-control" rows="3"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" onclick="addSubject()">Add Subject</button>
			</div>
		</div>
	</div>
</div>

<style>
.input-group {
	flex-wrap: nowrap !important;
}

.card .card-body {
    padding: 1rem !important;
}
</style>

<script src="<?php echo base_url('assets/ckeditor/ckeditor.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/image-sortable.js'); ?>"></script>
<script>
// Initialize CKEditor after page loads
window.addEventListener('load', function() {
	function initCKEditor() {
		if (typeof CKEDITOR !== 'undefined') {
			// Destroy existing instances if any
			if (CKEDITOR.instances['product_description']) {
				CKEDITOR.instances['product_description'].destroy();
			}
			if (CKEDITOR.instances['pointers']) {
				CKEDITOR.instances['pointers'].destroy();
			}
			
			// Initialize CKEditor instances
			var productDesc = document.getElementById('product_description');
			var pointers = document.getElementById('pointers');
			
			if (productDesc) {
				CKEDITOR.replace('product_description');
			}
			if (pointers) {
				CKEDITOR.replace('pointers');
			}
		} else {
			// If CKEDITOR is not loaded yet, wait and try again
			setTimeout(initCKEditor, 100);
		}
	}
	
	// Wait a bit for everything to be ready
	setTimeout(initCKEditor, 300);
});

// Wait for jQuery and Select2 to be loaded
$(document).ready(function() {
	$('#types').select2({ width: '100%', multiple: true });
	
	// Image preview handled by the sortable plugin; avoid duplicate previews
	
	// Initialize grade/age fields visibility
	toggleGradeAgeFields();
});

function toggleGradeAgeFields() {
	var gradeAgeType = document.getElementById('grade_age_type').value;
	var gradeContainer = document.getElementById('gradeFieldsContainer');
	var ageContainer = document.getElementById('ageFieldsContainer');
	
	if (gradeAgeType === 'grade') {
		gradeContainer.style.display = 'block';
		ageContainer.style.display = 'none';
		// Make grades required
		document.getElementById('grades').required = true;
		// Make ages not required
		document.getElementById('ages').required = false;
		// Reinitialize Select2 for grades if needed
		setTimeout(function() {
			if ($('#grades').length && typeof $ !== 'undefined' && $.fn.select2) {
				if (!$('#grades').hasClass('select2-hidden-accessible')) {
					$('#grades').select2({
						width: '100%',
						placeholder: 'Select options...',
						allowClear: true,
						multiple: true,
						minimumResultsForSearch: 0,
						theme: 'bootstrap-5'
					});
				}
			}
		}, 100);
	} else if (gradeAgeType === 'age') {
		gradeContainer.style.display = 'none';
		ageContainer.style.display = 'block';
		// Make ages required
		document.getElementById('ages').required = true;
		// Make grades not required
		document.getElementById('grades').required = false;
		// Reinitialize Select2 for ages if needed
		setTimeout(function() {
			if ($('#ages').length && typeof $ !== 'undefined' && $.fn.select2) {
				if (!$('#ages').hasClass('select2-hidden-accessible')) {
					$('#ages').select2({
						width: '100%',
						placeholder: 'Select options...',
						allowClear: true,
						multiple: true,
						minimumResultsForSearch: 0,
						theme: 'bootstrap-5'
					});
				}
			}
		}, 100);
	} else {
		gradeContainer.style.display = 'none';
		ageContainer.style.display = 'none';
		// Make both not required
		document.getElementById('grades').required = false;
		document.getElementById('ages').required = false;
	}
}

function addType() {
	var name = document.getElementById('type_name').value;
	var description = document.getElementById('type_description').value;
	
	if (!name) {
		alert('Please enter a name');
		return;
	}
	
	var formData = new FormData();
	formData.append('name', name);
	formData.append('description', description);
	
	fetch('<?php echo base_url('products/textbook/add_type'); ?>', {
		method: 'POST',
		body: formData
	})
	.then(response => response.json())
	.then(data => {
		if (data.status === 'success') {
			// Add to Select2 dropdown
			var $select = $('#types');
			var option = new Option(data.name, data.id, true, true);
			$select.append(option).trigger('change');
			
			// Reset form but keep modal open for multiple additions
			document.getElementById('addTypeForm').reset();
			
			// Show success message
			var nameInput = document.getElementById('type_name');
			nameInput.placeholder = 'Added: ' + data.name + ' (add another or close)';
			setTimeout(function() {
				nameInput.placeholder = '';
			}, 3000);
		} else {
			alert(data.message || 'Failed to add type');
		}
	})
	.catch(error => {
		console.error('Error:', error);
		alert('An error occurred');
	});
}

function addPublisher() {
	var name = document.getElementById('publisher_name').value;
	var description = document.getElementById('publisher_description').value;
	
	if (!name) {
		alert('Please enter a name');
		return;
	}
	
	var formData = new FormData();
	formData.append('name', name);
	formData.append('description', description);
	
	fetch('<?php echo base_url('products/textbook/add_publisher'); ?>', {
		method: 'POST',
		body: formData
	})
	.then(response => response.json())
	.then(data => {
		if (data.status === 'success') {
			var select = document.getElementById('publisher_id');
			var $select = $('#publisher_id');
			
			// Check if Select2 is initialized
			if ($select.length && $select.hasClass('select2-hidden-accessible')) {
				$select.select2('destroy');
			}
			
			var option = document.createElement('option');
			option.value = data.id;
			option.textContent = data.name;
			option.selected = true;
			select.appendChild(option);
			
			// Reinitialize Select2 if needed
			if ($select.hasClass('select')) {
				$select.select2();
			}
			
			// Reset form but keep modal open for multiple additions
			document.getElementById('addPublisherForm').reset();
			
			// Show success message
			var nameInput = document.getElementById('publisher_name');
			nameInput.placeholder = 'Added: ' + data.name + ' (add another or close)';
			setTimeout(function() {
				nameInput.placeholder = '';
			}, 3000);
		} else {
			alert(data.message || 'Failed to add publisher');
		}
	})
	.catch(error => {
		console.error('Error:', error);
		alert('An error occurred');
	});
}

function addGrade() {
	var name = document.getElementById('grade_name').value;
	var description = document.getElementById('grade_description').value;
	
	if (!name) {
		alert('Please enter a name');
		return;
	}
	
	var formData = new FormData();
	formData.append('name', name);
	formData.append('description', description);
	
	fetch('<?php echo base_url('products/textbook/add_grade'); ?>', {
		method: 'POST',
		body: formData
	})
	.then(response => response.json())
	.then(data => {
		if (data.status === 'success') {
			// Add to Select2 dropdown
			var $select = $('#grades');
			var option = new Option(data.name, data.id, true, true);
			$select.append(option).trigger('change');
			
			// Reset form but keep modal open for multiple additions
			document.getElementById('addGradeForm').reset();
			
			// Show success message
			var nameInput = document.getElementById('grade_name');
			nameInput.placeholder = 'Added: ' + data.name + ' (add another or close)';
			setTimeout(function() {
				nameInput.placeholder = '';
			}, 3000);
		} else {
			alert(data.message || 'Failed to add grade');
		}
	})
	.catch(error => {
		console.error('Error:', error);
		alert('An error occurred');
	});
}

function addAge() {
	var name = document.getElementById('age_name').value;
	var description = document.getElementById('age_description').value;
	
	if (!name) {
		alert('Please enter a name');
		return;
	}
	
	var formData = new FormData();
	formData.append('name', name);
	formData.append('description', description);
	
	fetch('<?php echo base_url('products/textbook/add_age'); ?>', {
		method: 'POST',
		body: formData
	})
	.then(response => response.json())
	.then(data => {
		if (data.status === 'success') {
			// Add to Select2 dropdown
			var $select = $('#ages');
			var option = new Option(data.name, data.id, true, true);
			$select.append(option).trigger('change');
			
			// Reset form but keep modal open for multiple additions
			document.getElementById('addAgeForm').reset();
			
			// Show success message
			var nameInput = document.getElementById('age_name');
			nameInput.placeholder = 'Added: ' + data.name + ' (add another or close)';
			setTimeout(function() {
				nameInput.placeholder = '';
			}, 3000);
		} else {
			alert(data.message || 'Failed to add age');
		}
	})
	.catch(error => {
		console.error('Error:', error);
		alert('An error occurred');
	});
}

function addSubject() {
	var name = document.getElementById('subject_name').value;
	var description = document.getElementById('subject_description').value;
	
	if (!name) {
		alert('Please enter a name');
		return;
	}
	
	var formData = new FormData();
	formData.append('name', name);
	formData.append('description', description);
	
	fetch('<?php echo base_url('products/textbook/add_subject'); ?>', {
		method: 'POST',
		body: formData
	})
	.then(response => response.json())
	.then(data => {
		if (data.status === 'success') {
			// Add to Select2 dropdown
			var $select = $('#subjects');
			var option = new Option(data.name, data.id, true, true);
			$select.append(option).trigger('change');
			
			// Reset form but keep modal open for multiple additions
			document.getElementById('addSubjectForm').reset();
			
			// Show success message
			var nameInput = document.getElementById('subject_name');
			nameInput.placeholder = 'Added: ' + data.name + ' (add another or close)';
			setTimeout(function() {
				nameInput.placeholder = '';
			}, 3000);
		} else {
			alert(data.message || 'Failed to add subject');
		}
	})
	.catch(error => {
		console.error('Error:', error);
		alert('An error occurred');
	});
}

// Price validation function
function validatePrice() {
	var mrp = parseFloat(document.getElementById('mrp').value) || 0;
	var sellingPrice = parseFloat(document.getElementById('selling_price').value) || 0;
	var mrpError = document.getElementById('mrp_error');
	var sellingPriceError = document.getElementById('selling_price_error');
	var mrpInput = document.getElementById('mrp');
	var sellingPriceInput = document.getElementById('selling_price');
	
	// Hide errors initially
	if (mrpError) mrpError.style.display = 'none';
	if (sellingPriceError) sellingPriceError.style.display = 'none';
	if (mrpInput) mrpInput.classList.remove('is-invalid');
	if (sellingPriceInput) sellingPriceInput.classList.remove('is-invalid');
	
	// Validate only if both values are entered
	if (mrp > 0 && sellingPrice > 0) {
		if (mrp <= sellingPrice) {
			if (mrpError) mrpError.style.display = 'block';
			if (sellingPriceError) sellingPriceError.style.display = 'block';
			if (mrpInput) mrpInput.classList.add('is-invalid');
			if (sellingPriceInput) sellingPriceInput.classList.add('is-invalid');
			alert('MRP must be higher than Selling Price');
			return false;
		}
	}
	
	return true;
}

// Add event listeners for real-time validation
document.addEventListener('DOMContentLoaded', function() {
	var mrpInput = document.getElementById('mrp');
	var sellingPriceInput = document.getElementById('selling_price');
	
	if (mrpInput && sellingPriceInput) {
		mrpInput.addEventListener('input', validatePrice);
		mrpInput.addEventListener('blur', validatePrice);
		sellingPriceInput.addEventListener('input', validatePrice);
		sellingPriceInput.addEventListener('blur', validatePrice);
	}
});
</script>
