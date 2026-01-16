<!-- Start Breadcrumb -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-3 mb-3">
	<div>
		<h6><a href="<?php echo base_url('products/textbook' : 'products/textbook'); ?>"><i class="isax isax-arrow-left me-2"></i>Edit Textbook</a></h6>
	</div>
</div>
<!-- End Breadcrumb -->
<?php echo form_open_multipart(base_url('products/textbook/edit/' . $textbook['id']), array('id' => 'textbook-form')); ?>
<div class="row mt-3">
	<div class="col-12">
		<div class="card mb-3">
			<div class="card-body">
				<h2 class=" border-bottom pb-3 mb-3">Images</h2>
				<div class="row gx-3">
					<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Images (Size: 440px * 530px)</label>
							<input type="file" name="images[]" id="images" class="form-control" form="textbook-form" accept="image/*" multiple>
							<small class="text-muted fs-13">You can select multiple images. Recommended size: 440px × 530px. Leave empty to keep existing images.</small>
							<div id="image-preview" class="mt-3 image-sortable-container">
								<?php if (isset($textbook_images) && !empty($textbook_images)): ?>
									<?php 
									$main_image_id = null;
									foreach ($textbook_images as $img): 
										if (isset($img['is_main']) && $img['is_main'] == 1) {
											$main_image_id = $img['id'];
										}
									endforeach;
									?>
									<?php foreach ($textbook_images as $img): ?>
										<div class="image-preview-item existing-image" data-image-id="<?php echo $img['id']; ?>" style="position: relative; display: inline-block; margin: 3px; cursor: move; vertical-align: top;">
											<img src="<?php echo base_url(ltrim($img['image_path'], '/')); ?>" alt="Textbook Image" style="width: 120px; height: 120px; object-fit: cover; border: 2px solid #ddd; border-radius: 4px 4px 0 0; display: block;">
											<div class="image-buttons" style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.75); display: flex; gap: 2px; padding: 3px; border-radius: 0 0 4px 4px;">
												<button type="button" class="btn btn-sm set-main-existing-btn" data-image-id="<?php echo $img['id']; ?>" style="font-size: 10px; padding: 3px 6px; flex: 1; line-height: 1.2; border: none; white-space: nowrap; <?php echo ($img['id'] == $main_image_id) ? 'background: #28a745; color: #fff;' : 'background: #007bff; color: #fff;'; ?>">
													<?php echo ($img['id'] == $main_image_id) ? 'Main' : 'Set Main'; ?>
												</button>
												<button type="button" class="btn btn-sm btn-danger remove-existing-image-btn" data-image-id="<?php echo $img['id']; ?>" style="font-size: 10px; padding: 3px 6px; flex: 0 0 auto; line-height: 1.2; border: none; min-width: 28px;">
													<i class="isax isax-trash" style="font-size: 11px;"></i>
												</button>
											</div>
										</div>
									<?php endforeach; ?>
								<?php endif; ?>
							</div>
							<input type="hidden" name="image_order" id="image_order" value="">
							<input type="hidden" name="main_image_id" id="main_image_id" value="<?php echo isset($main_image_id) ? $main_image_id : ''; ?>">
							<input type="hidden" name="deleted_image_ids" id="deleted_image_ids" value="">
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
				<h2 class=" border-bottom pb-3 mb-3">Details</h2>
				
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
									<select name="types[]" id="types" class="select select2-multiple" multiple required>
										<?php 
										$textbook_types = isset($textbook_types) ? $textbook_types : array();
										$selected_type_ids = array();
										foreach ($textbook_types as $textbook_type) {
											$selected_type_ids[] = $textbook_type['type_id'];
										}
										if (!empty($types)): ?>
											<?php foreach ($types as $type): ?>
												<option value="<?php echo $type['id']; ?>" <?php echo (in_array($type['id'], $selected_type_ids)) ? 'selected' : ''; ?>><?php echo htmlspecialchars($type['name']); ?></option>
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
												<option value="<?php echo $publisher['id']; ?>" <?php echo ($textbook['publisher_id'] == $publisher['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($publisher['name']); ?></option>
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
											<option value="<?php echo $board['id']; ?>" <?php echo ($textbook['board_id'] == $board['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($board['board_name']); ?></option>
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
									<option value="grade" <?php echo (isset($textbook['grade_age_type']) && $textbook['grade_age_type'] == 'grade') ? 'selected' : ''; ?>>Grade</option>
									<option value="age" <?php echo (isset($textbook['grade_age_type']) && $textbook['grade_age_type'] == 'age') ? 'selected' : ''; ?>>Age</option>
								</select>
								<?php echo form_error('grade_age_type', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<!-- Grade Fields (shown when Grade is selected) -->
						<div class="col-lg-6 col-md-6" id="gradeFieldsContainer" style="display: <?php echo (isset($textbook['grade_age_type']) && $textbook['grade_age_type'] == 'grade') ? 'block' : 'none'; ?>;">
							<div class="mb-3">
								<label class="form-label">Grade <span class="text-danger">*</span></label>
								<div class="input-group">
									<select name="grades[]" id="grades" class="select select2-multiple grade-select" multiple>
										<?php 
										$textbook_grades = isset($textbook_grades) ? $textbook_grades : array();
										$selected_grade_ids = array();
										foreach ($textbook_grades as $textbook_grade) {
											$selected_grade_ids[] = $textbook_grade['grade_id'];
										}
										if (!empty($grades)): ?>
											<?php foreach ($grades as $grade): ?>
												<option value="<?php echo $grade['id']; ?>" <?php echo (in_array($grade['id'], $selected_grade_ids)) ? 'selected' : ''; ?>><?php echo htmlspecialchars($grade['name']); ?></option>
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
						<div class="col-lg-6 col-md-6" id="ageFieldsContainer" style="display: <?php echo (isset($textbook['grade_age_type']) && $textbook['grade_age_type'] == 'age') ? 'block' : 'none'; ?>;">
							<div class="mb-3">
								<label class="form-label">Age <span class="text-danger">*</span></label>
								<div class="input-group">
									<select name="ages[]" id="ages" class="select select2-multiple age-select" multiple>
										<?php 
										$textbook_ages = isset($textbook_ages) ? $textbook_ages : array();
										$selected_age_ids = array();
										foreach ($textbook_ages as $textbook_age) {
											$selected_age_ids[] = $textbook_age['age_id'];
										}
										if (!empty($ages)): ?>
											<?php foreach ($ages as $age): ?>
												<option value="<?php echo $age['id']; ?>" <?php echo (in_array($age['id'], $selected_age_ids)) ? 'selected' : ''; ?>><?php echo htmlspecialchars($age['name']); ?></option>
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
										<?php 
										$textbook_subjects = isset($textbook_subjects) ? $textbook_subjects : array();
										$selected_subject_ids = array();
										foreach ($textbook_subjects as $textbook_subject) {
											$selected_subject_ids[] = $textbook_subject['subject_id'];
										}
										if (!empty($subjects)): ?>
											<?php foreach ($subjects as $subject): ?>
												<option value="<?php echo $subject['id']; ?>" <?php echo (in_array($subject['id'], $selected_subject_ids)) ? 'selected' : ''; ?>><?php echo htmlspecialchars($subject['name']); ?></option>
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
								<input type="text" name="product_name" id="product_name" class="form-control" value="<?php echo set_value('product_name', $textbook['product_name']); ?>" required>
								<?php echo form_error('product_name', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">ISBN/Bar Code No./SKU <span class="text-danger">*</span></label>
								<input type="text" name="isbn" id="isbn" class="form-control" value="<?php echo set_value('isbn', $textbook['isbn']); ?>" required>
								<?php echo form_error('isbn', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Min Quantity <span class="text-danger">*</span></label>
								<input type="number" name="min_quantity" id="min_quantity" class="form-control" value="<?php echo set_value('min_quantity', $textbook['min_quantity']); ?>" min="1" required>
								<?php echo form_error('min_quantity', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Days To Exchange</label>
								<input type="number" name="days_to_exchange" id="days_to_exchange" class="form-control" value="<?php echo set_value('days_to_exchange', $textbook['days_to_exchange']); ?>" min="0">
							</div>
						</div>
					</div>
					
					<!-- Description Fields -->
					<div class="row gx-3">
						<div class="col-12">
							<div class="mb-3">
								<label class="form-label">Pointers / Highlights</label>
								<textarea name="pointers" id="pointers" class="form-control ckeditor" rows="5"><?php echo set_value('pointers', $textbook['pointers']); ?></textarea>
							</div>
						</div>
						<div class="col-12">
							<div class="mb-3">
								<label class="form-label">Product Description <span class="text-danger">*</span></label>
								<textarea name="product_description" id="product_description" class="form-control ckeditor" rows="5" required><?php echo set_value('product_description', $textbook['product_description']); ?></textarea>
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
							<input type="number" name="packaging_length" id="packaging_length" class="form-control" form="textbook-form" value="<?php echo set_value('packaging_length', $textbook['packaging_length']); ?>" step="0.01" min="0">
						</div>
					</div>
					<div class="col-lg-3 col-md-6">
						<div class="mb-3">
							<label class="form-label">Width (in cm)</label>
							<input type="number" name="packaging_width" id="packaging_width" class="form-control" form="textbook-form" value="<?php echo set_value('packaging_width', $textbook['packaging_width']); ?>" step="0.01" min="0">
						</div>
					</div>
					<div class="col-lg-3 col-md-6">
						<div class="mb-3">
							<label class="form-label">Height (in cm)</label>
							<input type="number" name="packaging_height" id="packaging_height" class="form-control" form="textbook-form" value="<?php echo set_value('packaging_height', $textbook['packaging_height']); ?>" step="0.01" min="0">
						</div>
					</div>
					<div class="col-lg-3 col-md-6">
						<div class="mb-3">
							<label class="form-label">Weight (in gm)</label>
							<input type="number" name="packaging_weight" id="packaging_weight" class="form-control" form="textbook-form" value="<?php echo set_value('packaging_weight', $textbook['packaging_weight']); ?>" step="0.01" min="0">
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
							<input type="number" name="gst_percentage" id="gst_percentage" class="form-control" form="textbook-form" value="<?php echo set_value('gst_percentage', $textbook['gst_percentage']); ?>" step="0.01" min="0" max="100" required>
							<?php echo form_error('gst_percentage', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
						</div>
					</div>
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">HSN</label>
							<input type="text" name="hsn" id="hsn" class="form-control" form="textbook-form" value="<?php echo set_value('hsn', $textbook['hsn']); ?>">
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
							<input type="text" name="product_code" id="product_code" class="form-control" form="textbook-form" value="<?php echo set_value('product_code', $textbook['product_code']); ?>">
						</div>
					</div>
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">SKU /Product Code</label>
							<input type="text" name="sku" id="sku" class="form-control" form="textbook-form" value="<?php echo set_value('sku', $textbook['sku']); ?>">
						</div>
					</div>
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">MRP <span class="text-danger">*</span></label>
							<input type="number" name="mrp" id="mrp" class="form-control" form="textbook-form" value="<?php echo set_value('mrp', $textbook['mrp']); ?>" step="0.01" min="0" required>
							<small class="text-danger" id="mrp_error" style="display:none;">MRP must be higher than Selling Price</small>
							<?php echo form_error('mrp', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
						</div>
					</div>
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">Selling Price <span class="text-danger">*</span></label>
							<input type="number" name="selling_price" id="selling_price" class="form-control" form="textbook-form" value="<?php echo set_value('selling_price', $textbook['selling_price']); ?>" step="0.01" min="0" required>
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
							<input type="text" name="meta_title" id="meta_title" class="form-control" form="textbook-form" value="<?php echo set_value('meta_title', $textbook['meta_title']); ?>">
						</div>
					</div>
					<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Meta Keywords</label>
							<textarea name="meta_keywords" id="meta_keywords" class="form-control" form="textbook-form" rows="3"><?php echo set_value('meta_keywords', $textbook['meta_keywords']); ?></textarea>
						</div>
					</div>
					<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Meta Description</label>
							<textarea name="meta_description" id="meta_description" class="form-control" form="textbook-form" rows="3"><?php echo set_value('meta_description', $textbook['meta_description']); ?></textarea>
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
								<input class="form-check-input" type="checkbox" name="is_individual" id="is_individual" value="1" form="textbook-form" <?php echo (isset($textbook['is_individual']) && $textbook['is_individual'] == 1) ? 'checked' : ''; ?>>
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
								<input class="form-check-input" type="checkbox" name="is_set" id="is_set" value="1" form="textbook-form" <?php echo (isset($textbook['is_set']) && $textbook['is_set'] == 1) ? 'checked' : ''; ?>>
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
		<a href="<?php echo base_url('products/textbook' : 'products/textbook'); ?>" class="btn btn-outline">Cancel</a>
		<button type="submit" form="textbook-form" class="btn btn-primary" onclick="return validatePrice();">Update Textbook</button>
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
    padding: 0 !important;
}

</style>

<script src="https://cdn.ckeditor.com/4.19.1/standard/ckeditor.js"></script>
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
	// Function to initialize Select2 for multiple selects
	function initMultipleSelect2() {
		if (typeof $ !== 'undefined' && $.fn.select2) {
			// Check if Select2 is already initialized and destroy if needed
			$('#types, #grades, #ages, #subjects').each(function() {
				if ($(this).hasClass('select2-hidden-accessible')) {
					$(this).select2('destroy');
				}
			});
			
			// Initialize with multiple selection support
			$('#types, #grades, #ages, #subjects').select2({
				width: '100%',
				placeholder: 'Select options...',
				allowClear: true,
				multiple: true,
				minimumResultsForSearch: 0,
				theme: 'bootstrap-5'
			});
		} else {
			// If Select2 is not ready, try again after a short delay
			setTimeout(initMultipleSelect2, 100);
		}
	}
	
	// Wait a bit for script.js to finish initializing, then reinitialize our selects
	setTimeout(initMultipleSelect2, 800);
	
	// Image preview for newly selected images
	var imageInput = document.getElementById('images');
	if (imageInput) {
		imageInput.addEventListener('change', function(e) {
			var preview = document.getElementById('image-preview');
			// Don't clear existing previews, just add new ones
			
			for (var i = 0; i < e.target.files.length; i++) {
				var file = e.target.files[i];
				if (file.type.startsWith('image/')) {
					var reader = new FileReader();
					reader.onload = (function(file) {
						return function(e) {
							var imgContainer = document.createElement('div');
							imgContainer.className = 'position-relative';
							imgContainer.style.cssText = 'width: 100px; height: 120px; margin: 5px; display: inline-block;';
							
							var img = document.createElement('img');
							img.src = e.target.result;
							img.style.cssText = 'width: 100%; height: 100%; object-fit: cover; border-radius: 4px;';
							img.alt = 'New Image Preview';
							
							var removeBtn = document.createElement('button');
							removeBtn.type = 'button';
							removeBtn.className = 'btn btn-sm btn-danger position-absolute top-0 end-0';
							removeBtn.style.cssText = 'margin: 2px; padding: 2px 6px;';
							removeBtn.innerHTML = '<i class="isax isax-close-circle"></i>';
							removeBtn.onclick = function() {
								imgContainer.remove();
								// Remove file from input
								var dt = new DataTransfer();
								var files = imageInput.files;
								for (var j = 0; j < files.length; j++) {
									if (files[j] !== file) {
										dt.items.add(files[j]);
									}
								}
								imageInput.files = dt.files;
							};
							
							imgContainer.appendChild(img);
							imgContainer.appendChild(removeBtn);
							preview.appendChild(imgContainer);
						};
					})(file);
					reader.readAsDataURL(file);
				}
			}
		});
	}
	
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
	
	fetch('<?php echo base_url('products/textbook/add_type' : 'products/textbook/add_type'); ?>', {
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
	
	fetch('<?php echo base_url('products/textbook/add_publisher' : 'products/textbook/add_publisher'); ?>', {
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
	
	fetch('<?php echo base_url('products/textbook/add_grade' : 'products/textbook/add_grade'); ?>', {
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
	
	fetch('<?php echo base_url('products/textbook/add_age' : 'products/textbook/add_age'); ?>', {
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
	
	fetch('<?php echo base_url('products/textbook/add_subject' : 'products/textbook/add_subject'); ?>', {
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

// Handle existing images - Set as Main
(function() {
	function initEditFormHandlers() {
		const deletedImageIds = [];
		const deletedInput = document.getElementById('deleted_image_ids');
		const mainImageInput = document.getElementById('main_image_id');
		
		// Set main image for existing images
		document.querySelectorAll('.set-main-existing-btn').forEach(function(btn) {
			btn.addEventListener('click', function(e) {
			e.preventDefault();
			const imageId = this.getAttribute('data-image-id');
			
			// Update main image input
			if (mainImageInput) {
				mainImageInput.value = imageId;
			}
			
			// Update all buttons
			document.querySelectorAll('.set-main-existing-btn').forEach(function(b) {
				if (b.getAttribute('data-image-id') == imageId) {
					b.textContent = 'Main';
					b.style.background = '#28a745';
					b.style.color = '#fff';
				} else {
					b.textContent = 'Set Main';
					b.style.background = '#007bff';
					b.style.color = '#fff';
				}
			});
		});
	});
	
	// Remove existing image
	document.querySelectorAll('.remove-existing-image-btn').forEach(function(btn) {
		btn.addEventListener('click', function(e) {
			e.preventDefault();
			const imageId = this.getAttribute('data-image-id');
			
			if (confirm('Are you sure you want to remove this image?')) {
				// Add to deleted list
				if (deletedImageIds.indexOf(imageId) === -1) {
					deletedImageIds.push(imageId);
				}
				
				// Update hidden input
				if (deletedInput) {
					deletedInput.value = deletedImageIds.join(',');
				}
				
				// Hide the image
				const imageItem = this.closest('.image-preview-item');
				if (imageItem) {
					imageItem.style.display = 'none';
				}
			}
		});
	});
	
	// Make existing images draggable
	const existingImages = document.querySelectorAll('.existing-image');
	existingImages.forEach(function(img) {
		img.draggable = true;
		img.addEventListener('dragstart', function(e) {
			this.style.opacity = '0.5';
			e.dataTransfer.effectAllowed = 'move';
		});
		img.addEventListener('dragend', function() {
			this.style.opacity = '1';
		});
	});
	}
	
	// Try to initialize immediately if DOM is ready
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', initEditFormHandlers);
	} else {
		// DOM already loaded, but wait a bit for images to render
		setTimeout(initEditFormHandlers, 200);
	}
})();

function deleteImage(imageId) {
	if (!confirm('Are you sure you want to delete this image?')) {
		return;
	}
	
	fetch('<?php echo base_url('products/textbook/delete_image/' : 'products/textbook/delete_image/'); ?>' + imageId, {
		method: 'POST',
		headers: {
			'X-Requested-With': 'XMLHttpRequest'
		}
	})
	.then(response => response.json())
	.then(data => {
		if (data.status === 'success') {
			// Remove the image from preview without reloading
			var imageElement = document.querySelector('[data-image-id="' + imageId + '"]');
			if (imageElement) {
				imageElement.remove();
			}
			// If no images left, show message
			var preview = document.getElementById('image-preview');
			if (preview && preview.children.length === 0) {
				preview.innerHTML = '<p class="text-muted">No images</p>';
			}
		} else {
			alert(data.message || 'Failed to delete image');
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

