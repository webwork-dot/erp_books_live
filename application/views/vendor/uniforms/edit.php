<!-- Start Breadcrumb -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-2 mb-2">
	<div>
		<h6 class="mb-0 fs-14"><a href="<?php echo base_url('products/uniforms'); ?>"><i
					class="isax isax-arrow-left me-1"></i>Edit Uniform</a></h6>
	</div>
</div>
<script src="<?= base_url(); ?>assets/ckeditor/ckeditor.js"></script>
<!-- End Breadcrumb -->
<?php echo form_open_multipart(base_url('products/uniforms/edit/' . $uniform['id']), array('id' => 'uniform-form')); ?>
<!-- Images Card (Outside Main Card) -->
<div class="row mt-2">
	<div class="col-12">
		<div class="card mb-2">
			<div class="card-header py-2">
				<h6 class="mb-0 fs-14">Images</h6>
			</div>
			<div class="card-body p-2">
				<div class="row g-2">
					<div class="col-12">
						<div class="mb-2">
							<label class="form-label fs-13 mb-1">Images (Size: 440px * 530px)</label>
							<input type="file" name="images[]" id="images" class="form-control form-control-sm"
								form="uniform-form" accept="image/*" multiple>
							<small class="text-muted fs-12">You can select multiple images. Recommended size: 440px ×
								530px. Drag images to reorder. Click "Set as Main" to choose the main image. Leave empty
								to keep existing images.</small>
							<div id="image-preview" class="mt-3 image-sortable-container">
								<?php if (isset($uniform_images) && !empty($uniform_images)): ?>
									<?php
									$main_image_id = null;
									foreach ($uniform_images as $img):
										if (isset($img['is_main']) && $img['is_main'] == 1) {
											$main_image_id = $img['id'];
										}
									endforeach;
									?>

									<?php foreach ($uniform_images as $img): ?>
										<?php
										$stored_path = trim($img['image_path']);
										if (strpos($stored_path, 'http://') === 0 || strpos($stored_path, 'https://') === 0) {
											$image_url = $stored_path;
										} else {
											$image_url = get_vendor_domain_url() . '/' . $stored_path;
										}
										?>
										<div class="image-preview-item existing-image" data-image-id="<?php echo $img['id']; ?>"
											style="position: relative; display: inline-block; margin: 3px; cursor: move; vertical-align: top;">
											<img src="<?php echo $image_url; ?>" alt="Uniform Image"
												style="width: 120px; height: 120px; object-fit: cover; border: 2px solid #ddd; border-radius: 4px 4px 0 0; display: block;">
											<div class="image-buttons"
												style="position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.75); display: flex; gap: 2px; padding: 3px; border-radius: 0 0 4px 4px;">
												<button type="button" class="btn btn-sm set-main-existing-btn"
													data-image-id="<?php echo $img['id']; ?>"
													style="font-size: 10px; padding: 3px 6px; flex: 1; line-height: 1.2; border: none; white-space: nowrap; <?php echo ($img['id'] == $main_image_id) ? 'background: #28a745; color: #fff;' : 'background: #007bff; color: #fff;'; ?>">
													<?php echo ($img['id'] == $main_image_id) ? 'Main' : 'Set Main'; ?>
												</button>
												<button type="button" class="btn btn-sm btn-danger remove-existing-image-btn"
													data-image-id="<?php echo $img['id']; ?>"
													style="font-size: 10px; padding: 3px 6px; flex: 0 0 auto; line-height: 1.2; border: none; min-width: 28px;">
													<i class="isax isax-trash" style="font-size: 11px;"></i>
												</button>
											</div>
										</div>
									<?php endforeach; ?>

								<?php endif; ?>
							</div>
							<input type="hidden" name="image_order" id="image_order" value="">
							<input type="hidden" name="main_image_index" id="main_image_index" value="-1">
							<input type="hidden" name="main_image_id" id="main_image_id"
								value="<?php echo isset($main_image_id) ? $main_image_id : ''; ?>">
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
		<div class="card mb-2">
			<div class="card-header py-2">
				<h6 class="mb-0 fs-14">Uniform Details</h6>
			</div>
			<div class="card-body p-2">
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
				<div class="row g-2">
					<div class="col-xl-3 col-lg-4 col-md-6">
						<div class="mb-2">
							<label class="form-label fs-13 mb-1">Uniform Type <span class="text-danger">*</span></label>
							<div class="d-flex gap-1">
								<select name="uniform_type_id" id="uniform_type_id" class="form-select form-select-sm"
									style="width: 100%;" required>
									<option value="">Select Uniform Type</option>
									<?php if (!empty($uniform_types)): ?>
										<?php foreach ($uniform_types as $type): ?>
											<option value="<?php echo $type['id']; ?>" <?php echo ($uniform['uniform_type_id'] == $type['id']) ? 'selected' : ''; ?>>
												<?php echo htmlspecialchars($type['name']); ?>
											</option>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
								<button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
									data-bs-target="#addUniformTypeModal" style="padding: 4px 8px;">
									<i class="isax isax-add"></i>
								</button>
							</div>
							<?php echo form_error('uniform_type_id', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
						</div>
					</div>
					<div class="col-xl-3 col-lg-4 col-md-6">
						<div class="mb-2">
							<label class="form-label fs-13 mb-1">School <span class="text-danger">*</span></label>
							<select name="school_id" id="school_id" class="form-select form-select-sm" required>
								<option value="">Select School</option>
								<?php if (!empty($schools)): ?>
									<?php foreach ($schools as $school): ?>
										<option value="<?php echo $school['id']; ?>" <?php echo ($uniform['school_id'] == $school['id']) ? 'selected' : ''; ?>>
											<?php echo htmlspecialchars($school['school_name']); ?>
										</option>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>
							<?php echo form_error('school_id', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
						</div>
					</div>
					<div class="col-xl-3 col-lg-4 col-md-6" id="branch_container"
						style="display: <?php echo (!empty($branches)) ? 'block' : 'none'; ?>;">
						<div class="mb-2">
							<label class="form-label fs-13 mb-1">Branch</label>
							<select name="branch_id" id="branch_id" class="form-select form-select-sm">
								<option value="">Select Branch</option>
								<?php if (!empty($branches)): ?>
									<?php foreach ($branches as $branch): ?>
										<option value="<?php echo $branch['id']; ?>" <?php echo (isset($uniform['branch_id']) && $uniform['branch_id'] == $branch['id']) ? 'selected' : ''; ?>>
											<?php echo htmlspecialchars($branch['branch_name']); ?>
										</option>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>
						</div>
					</div>
					<div class="col-xl-3 col-lg-4 col-md-6">
						<div class="mb-2">
							<label class="form-label fs-13 mb-1">Board <span class="text-danger">*</span></label>
							<select name="board_id" id="board_id" class="form-select form-select-sm" required>
								<option value="">Select Board</option>
								<?php if (!empty($school_boards)): ?>
									<?php foreach ($school_boards as $board): ?>
										<option value="<?php echo $board['id']; ?>" <?php echo ($uniform['board_id'] == $board['id']) ? 'selected' : ''; ?>>
											<?php echo htmlspecialchars($board['board_name']); ?>
										</option>
									<?php endforeach; ?>
								<?php endif; ?>
							</select>
							<?php echo form_error('board_id', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
						</div>
					</div>
					<div class="col-xl-3 col-lg-4 col-md-6">
						<div class="mb-2">
							<label class="form-label fs-13 mb-1">Classes</label>
							<div class="d-flex gap-1">
								<select name="class_ids[]" id="class_ids" class="form-control form-control-sm select2"
									multiple data-placeholder="Select Classes" style="width: 100%;">
									<?php if (!empty($classes)): ?>
										<?php foreach ($classes as $class): ?>
											<option value="<?php echo $class['id']; ?>" <?php echo (in_array($class['id'], $selected_classes)) ? 'selected' : ''; ?>>
												<?php echo htmlspecialchars($class['class_name']); ?>
											</option>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
								<button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
									data-bs-target="#addClassModal" style="padding: 4px 8px;">
									<i class="isax isax-add"></i>
								</button>
							</div>
							<?php echo form_error('class_ids[]', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
						</div>
					</div>
					<div class="col-xl-3 col-lg-4 col-md-6">
						<div class="mb-2">
							<label class="form-label fs-13 mb-1">Gender</label>
							<select name="gender[]" id="gender" class="form-select form-select-sm select2" multiple
								data-placeholder="Select Gender" style="width: 100%;">
								<?php $selected_genders = !empty($uniform['gender']) ? explode(',', $uniform['gender']) : array(); ?>
								<option value="male" <?php echo in_array('male', $selected_genders) ? 'selected' : ''; ?>>Male</option>
								<option value="female" <?php echo in_array('female', $selected_genders) ? 'selected' : ''; ?>>Female</option>
								<option value="unisex" <?php echo in_array('unisex', $selected_genders) ? 'selected' : ''; ?>>Unisex</option>
							</select>
							<?php echo form_error('gender[]', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
						</div>
					</div>
					<div class="col-xl-3 col-lg-4 col-md-6">
						<div class="mb-2">
							<label class="form-label fs-13 mb-1">Houses</label>
							<select name="color" id="color" class="form-control form-control-sm select2-color"
								data-placeholder="Select a house">
								<option value=""></option>
								<option value="Red" <?php echo set_select('color', 'Red', $uniform['color'] == 'Red'); ?>>
									Red</option>
								<option value="Blue" <?php echo set_select('color', 'Blue', $uniform['color'] == 'Blue'); ?>>Blue</option>
								<option value="Green" <?php echo set_select('color', 'Green', $uniform['color'] == 'Green'); ?>>Green</option>
								<option value="Yellow" <?php echo set_select('color', 'Yellow', $uniform['color'] == 'Yellow'); ?>>Yellow</option>
							</select>
						</div>
					</div>
					<div class="col-xl-3 col-lg-4 col-md-6">
						<div class="mb-2">
							<label class="form-label fs-13 mb-1">Product Name <span class="text-danger">*</span></label>
							<input type="text" name="product_name" id="product_name"
								class="form-control form-control-sm"
								value="<?php echo set_value('product_name', $uniform['product_name']); ?>" required>
							<?php echo form_error('product_name', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
						</div>
					</div>
					<div class="col-xl-3 col-lg-4 col-md-6">
						<div class="mb-2">
							<label class="form-label fs-13 mb-1">ISBN/SKU</label>
							<input type="text" name="isbn" id="isbn" class="form-control form-control-sm"
								value="<?php echo set_value('isbn', $uniform['isbn']); ?>">
						</div>
					</div>
					<div class="col-xl-3 col-lg-4 col-md-6">
						<div class="mb-2">
							<label class="form-label fs-13 mb-1">Min Quantity <span class="text-danger">*</span></label>
							<input type="number" name="min_quantity" id="min_quantity"
								class="form-control form-control-sm"
								value="<?php echo set_value('min_quantity', $uniform['min_quantity']); ?>" min="1"
								required>
							<?php echo form_error('min_quantity', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
						</div>
					</div>
					<div class="col-xl-3 col-lg-4 col-md-6">
						<div class="mb-2">
							<label class="form-label fs-13 mb-1">Days To Exchange</label>
							<input type="number" name="days_to_exchange" id="days_to_exchange"
								class="form-control form-control-sm"
								value="<?php echo set_value('days_to_exchange', $uniform['days_to_exchange']); ?>"
								min="0">
						</div>
					</div>
					<div class="col-xl-3 col-lg-4 col-md-6">
						<div class="mb-2">
							<label class="form-label fs-13 mb-1">Material</label>
							<div class="d-flex gap-1">
								<select name="material_id" id="material_id" class="form-select form-select-sm"
									style="width: 100%;">
									<option value="">Select Material</option>
									<?php if (!empty($materials)): ?>
										<?php foreach ($materials as $material): ?>
											<option value="<?php echo $material['id']; ?>" <?php echo ($uniform['material_id'] == $material['id']) ? 'selected' : ''; ?>>
												<?php echo htmlspecialchars($material['name']); ?>
											</option>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
								<button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
									data-bs-target="#addMaterialModal" style="padding: 4px 8px;">
									<i class="isax isax-add"></i>
								</button>
							</div>
							<?php echo form_error('material_id', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
						</div>
					</div>
					<div class="col-xl-3 col-lg-4 col-md-6">
						<div class="mb-2">
							<label class="form-label fs-13 mb-1">Product Origin</label>
							<input type="text" name="product_origin" id="product_origin"
								class="form-control form-control-sm"
								value="<?php echo set_value('product_origin', $uniform['product_origin'] ? $uniform['product_origin'] : 'India'); ?>">
						</div>
					</div>
					<div class="col-xl-3 col-lg-4 col-md-6">
						<div class="mb-2">
							<label class="form-label fs-13 mb-1">Uniform Tag</label>
							<select name="uniform_tag" id="uniform_tag" class="form-select form-select-sm">
								<option value="regular" <?php echo (isset($uniform) && $uniform['uniform_tag'] == 'regular') ? 'selected' : ''; ?>>Regular</option>
								<option value="PT" <?php echo (isset($uniform) && $uniform['uniform_tag'] == 'PT') ? 'selected' : ''; ?>>PT Uniform</option>
							</select>
						</div>
					</div>
				</div>

				<!-- Description Fields -->
				<div class="row gx-3">
					<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Product Description</label>
							<textarea name="product_description" id="product_description" class="form-control ckeditor"
								rows="5"><?php echo set_value('product_description', $uniform['product_description']); ?></textarea>
							<?php echo form_error('product_description', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
						</div>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>

<!-- Price and Size Card (Outside Main Card) -->
<div class="row">
	<div class="col-12">
		<div class="card mb-3">
			<div class="card-body">
				<div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-3">
					<h2 class="mb-0">Size</h2>
					<div class="d-flex gap-2">
						<a href="<?php echo base_url('size-charts'); ?>" class="btn btn-outline-primary btn-sm"
							title="Manage Size Charts">
							<i class="isax isax-edit"></i> Manage Size Charts
						</a>
						<button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
							data-bs-target="#viewSizeChartsModal" title="View All Size Charts">
							<i class="isax isax-eye"></i> View Size Charts
						</button>
					</div>
				</div>
				<div class="row gx-3">
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">Select Size Chart</label>
							<div class="d-flex gap-1">
								<select name="size_chart_id" id="size_chart_id" class="select" form="uniform-form"
									style="width: 100%;">
									<option value="">Select Size Chart</option>
									<?php if (!empty($size_charts)): ?>
										<?php foreach ($size_charts as $chart): ?>
											<option value="<?php echo $chart['id']; ?>" <?php echo (isset($uniform) && isset($uniform['size_chart_id']) && $uniform['size_chart_id'] == $chart['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($chart['name']); ?></option>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
								<button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
									data-bs-target="#addSizeChartModal" style="padding: 0.4rem 1rem;">
									<i class="isax isax-add"></i> Add
								</button>
							</div>
						</div>
					</div>
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">Size</label>
							<select name="size_id" id="size_id" class="select" form="uniform-form">
								<option value="">Select Size</option>
							</select>
							<small class="text-muted d-block mt-1">Select a size to add pricing</small>
						</div>
					</div>
					<?php if (isset($master_size_charts)): ?>
						<div class="col-lg-6 col-md-6">
							<div class="mb-3">
								<label class="form-label">Size chart images (gallery)</label>
								<select name="master_size_chart_id" id="master_size_chart_id" class="select"
									form="uniform-form" style="width: 100%;">
									<option value="">None</option>
									<?php foreach ($master_size_charts as $msc): ?>
										<option value="<?php echo (int) $msc['id']; ?>" <?php echo (isset($uniform['master_size_chart_id']) && (int) $uniform['master_size_chart_id'] === (int) $msc['id']) ? 'selected' : ''; ?>>
											<?php echo htmlspecialchars($msc['name']); ?></option>
									<?php endforeach; ?>
								</select>
								<small class="text-muted d-block mt-1">Managed under Catalog → Master Size Charts.</small>
							</div>
						</div>
					<?php endif; ?>
				</div>

				<!-- Size Prices Container -->
				<div id="sizePricesContainer" class="mt-4" style="display: none;">
					<div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
						<h6 class="mb-0">Size-wise Pricing</h6>
						<button type="button" class="btn btn-outline-primary btn-sm px-3" data-bs-toggle="modal"
							data-bs-target="#bulkPriceModal" style="font-size: 12px; padding: 4px 8px;">
							<i class="isax isax-edit me-1"></i> Bulk Edit Prices
						</button>
					</div>
					<div class="table-responsive">
						<table class="table table-bordered align-middle fs-13">
							<thead class="table-light">
								<tr>
									<th style="width: 8%;" class="text-center">Sr. No.</th>
									<th style="width: 15%;">Size</th>
									<th style="width: 22%;">Class</th>
									<th style="width: 22%;">MRP</th>
									<th style="width: 22%;">Selling Price</th>
									<th style="width: 11%;" class="text-center">Action</th>
								</tr>
							</thead>
							<tbody id="sizePricesList">
								<!-- Dynamic rows will be added here -->
							</tbody>
						</table>
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
							<input type="number" name="packaging_length" id="packaging_length" class="form-control"
								form="uniform-form"
								value="<?php echo set_value('packaging_length', $uniform['packaging_length']); ?>"
								step="0.01" min="0">
						</div>
					</div>
					<div class="col-lg-3 col-md-6">
						<div class="mb-3">
							<label class="form-label">Width (in cm)</label>
							<input type="number" name="packaging_width" id="packaging_width" class="form-control"
								form="uniform-form"
								value="<?php echo set_value('packaging_width', $uniform['packaging_width']); ?>"
								step="0.01" min="0">
						</div>
					</div>
					<div class="col-lg-3 col-md-6">
						<div class="mb-3">
							<label class="form-label">Height (in cm)</label>
							<input type="number" name="packaging_height" id="packaging_height" class="form-control"
								form="uniform-form"
								value="<?php echo set_value('packaging_height', $uniform['packaging_height']); ?>"
								step="0.01" min="0">
						</div>
					</div>
					<div class="col-lg-3 col-md-6">
						<div class="mb-3">
							<label class="form-label">Weight (in gm)</label>
							<input type="number" name="packaging_weight" id="packaging_weight" class="form-control"
								form="uniform-form"
								value="<?php echo set_value('packaging_weight', $uniform['packaging_weight']); ?>"
								step="0.01" min="0">
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
							<select name="gst_percentage" id="gst_percentage" class="form-control" form="uniform-form"
								required>
								<option value="">Select GST %</option>
								<?php
								$current_gst = set_value('gst_percentage', floatval($uniform['gst_percentage']));
								$gst_options = [0, 5, 12, 18, 28];
								foreach ($gst_options as $gst_val):
									$selected = ($current_gst != '' && floatval($current_gst) == $gst_val) ? 'selected' : '';
									if (empty($selected) && !empty(set_value('gst_percentage'))) {
										$selected = (set_value('gst_percentage') == $gst_val) ? 'selected' : '';
									}
									?>
									<option value="<?php echo $gst_val; ?>" <?php echo $selected; ?>>
										<?php echo $gst_val; ?>%
									</option>
								<?php endforeach; ?>
								<?php
								// If custom GST value exists (not in standard list), add it as an option
								if (!empty($current_gst) && !in_array(floatval($current_gst), $gst_options)):
									?>
									<option value="<?php echo htmlspecialchars($current_gst); ?>" selected>
										<?php echo htmlspecialchars($current_gst); ?>%
									</option>
								<?php endif; ?>
							</select>
							<?php echo form_error('gst_percentage', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
						</div>
					</div>
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">HSN</label>
							<input type="text" name="hsn" id="hsn" class="form-control" form="uniform-form"
								value="<?php echo set_value('hsn', $uniform['hsn']); ?>">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
$commissionType = set_value(
	'school_commission_type',
	$uniform['school_commission_type'] ?? ''
);

$commissionValue = set_value(
	'school_commission_value',
	$uniform['school_commission_value'] ?? ''
);
?>

<!-- School Commissions Card -->
<div class="row mt-3">
	<div class="col-12">
		<div class="card mb-3">
			<div class="card-body">
				<h2 class="border-bottom pb-3 mb-3">School Commissions</h2>

				<div class="row gx-3">

					<!-- Commission Type -->
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">Commission Type</label>
							<select name="school_commission_type" id="school_commission_type" class="select">
								<option value="">Select Commission Type</option>
								<option value="fixed" <?= ($commissionType === 'fixed') ? 'selected' : ''; ?>>Fixed
								</option>
								<option value="percentage" <?= ($commissionType === 'percentage') ? 'selected' : ''; ?>>
									Percentage</option>
							</select>
						</div>
					</div>

					<!-- Fixed -->
					<div class="col-lg-6 col-md-6" id="fixed_commission_container" style="display:none;">
						<div class="mb-3">
							<label class="form-label">Fixed Commission Amount (₹)</label>
							<input type="number" id="fixed_commission_value" name="school_commission_value"
								class="form-control" step="0.01" min="0"
								value="<?= ($commissionType === 'fixed') ? $commissionValue : ''; ?>" disabled>
						</div>
					</div>

					<!-- Percentage -->
					<div class="col-lg-6 col-md-6" id="percentage_commission_container" style="display:none;">
						<div class="mb-3">
							<label class="form-label">Commission Percentage (%)</label>
							<input type="number" id="percentage_commission_value" name="school_commission_value"
								class="form-control" step="0.01" min="0" max="100"
								value="<?= ($commissionType === 'percentage') ? $commissionValue : ''; ?>" disabled>
						</div>
					</div>

				</div>

			</div>
		</div>
	</div>
</div>

<!-- Manufacturer, Packer & Customer Details Card -->
<div class="row">
	<div class="col-12">
		<div class="card mb-3">
			<div class="card-body">
				<h2 class=" border-bottom pb-3 mb-3">Additional Details</h2>
				<div class="row gx-3">
					<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Manufacturer's Details</label>
							<textarea name="manufacturer_details" id="manufacturer_details"
								class="form-control ckeditor"
								rows="5"><?php echo set_value('manufacturer_details', $uniform['manufacturer_details']); ?></textarea>
						</div>
					</div>
					<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Packer's Details</label>
							<textarea name="packer_details" id="packer_details" class="form-control ckeditor"
								rows="5"><?php echo set_value('packer_details', $uniform['packer_details']); ?></textarea>
						</div>
					</div>
					<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Customer Details</label>
							<textarea name="customer_details" id="customer_details" class="form-control ckeditor"
								rows="5"><?php echo set_value('customer_details', $uniform['customer_details']); ?></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Meta Details and Status Card (Outside Main Card) -->
<div class="row">
	<div class="col-12">
		<div class="card mb-3">
			<div class="card-body">
				<h2 class=" border-bottom pb-3 mb-3">Meta Details</h2>
				<div class="row gx-3">
					<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Meta Title</label>
							<input type="text" name="meta_title" id="meta_title" class="form-control"
								form="uniform-form"
								value="<?php echo set_value('meta_title', $uniform['meta_title']); ?>">
						</div>
					</div>
					<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Meta Keywords</label>
							<textarea name="meta_keywords" id="meta_keywords" class="form-control" form="uniform-form"
								rows="3"><?php echo set_value('meta_keywords', $uniform['meta_keywords']); ?></textarea>
						</div>
					</div>
					<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Meta Description</label>
							<textarea name="meta_description" id="meta_description" class="form-control"
								form="uniform-form"
								rows="3"><?php echo set_value('meta_description', $uniform['meta_description']); ?></textarea>
						</div>
					</div>
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">Status</label>
							<select name="status" id="status" class="select" form="uniform-form">
								<option value="active" <?php echo ($uniform['status'] == 'active') ? 'selected' : ''; ?>>
									Active</option>
								<option value="inactive" <?php echo ($uniform['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="uniform-edit-action-spacer"></div>
<div class="uniform-edit-actionbar border-top">
	<div class="d-flex align-items-center justify-content-end gap-2">
		<a href="<?php echo base_url('products/uniforms'); ?>" class="btn btn-outline">Cancel</a>
		<button type="submit" form="uniform-form" class="btn btn-primary" onclick="return validateAllPrices();">Update
			Uniform</button>
	</div>
</div>
<?php echo form_close(); ?>

<!-- Add Uniform Type Modal -->
<div class="modal fade" id="addUniformTypeModal" tabindex="-1" aria-labelledby="addUniformTypeModalLabel"
	aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addUniformTypeModalLabel">Add Uniform Type</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="addUniformTypeForm">
					<div class="mb-3">
						<label class="form-label">Name <span class="text-danger">*</span></label>
						<input type="text" name="name" id="uniform_type_name" class="form-control" required>
					</div>
					<div class="mb-3">
						<label class="form-label">Description</label>
						<textarea name="description" id="uniform_type_description" class="form-control"
							rows="3"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" onclick="addUniformType()">Add Type</button>
			</div>
		</div>
	</div>
</div>

<!-- Add Material Modal -->
<!-- Add Class Modal -->
<div class="modal fade" id="addClassModal" tabindex="-1" aria-labelledby="addClassModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addClassModalLabel">Add / Edit Classes</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<!-- Section 1: Add New Class -->
				<form id="addClassForm" class="mb-4">
					<label class="form-label fw-semibold fs-13 mb-1">Add New Class</label>
					<div class="d-flex gap-2">
						<input type="text" name="name" id="new_class_name" class="form-control form-control-sm"
							placeholder="e.g. Class 1, 2nd Grade" required style="height: 38px;">
						<button type="button" class="btn btn-primary btn-sm" onclick="addClass()">Add Class</button>
					</div>
				</form>

				<hr class="my-3">

				<!-- Section 2: Edit Existing Classes -->
				<div class="existing-classes-section">
					<label class="form-label fw-semibold fs-13 mb-2">Edit Existing Classes</label>
					<div id="edit-classes-list" class="p-1" style="max-height: 250px; overflow-y: auto;">
						<!-- Dynamically populated via JS -->
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="addMaterialModal" tabindex="-1" aria-labelledby="addMaterialModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addMaterialModalLabel">Add Material</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="addMaterialForm">
					<div class="mb-3">
						<label class="form-label">Name <span class="text-danger">*</span></label>
						<input type="text" name="name" id="material_name" class="form-control" required>
					</div>
					<div class="mb-3">
						<label class="form-label">Description</label>
						<textarea name="description" id="material_description" class="form-control" rows="3"></textarea>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" onclick="addMaterial()">Add Material</button>
			</div>
		</div>
	</div>
</div>

<!-- View Size Charts Modal -->
<div class="modal fade" id="viewSizeChartsModal" tabindex="-1" aria-labelledby="viewSizeChartsModalLabel"
	aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="viewSizeChartsModalLabel">Available Size Charts</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div id="sizeChartsList">
					<?php if (!empty($size_charts)): ?>
						<?php foreach ($size_charts as $chart): ?>
							<div class="card mb-3">
								<div class="card-header">
									<h6 class="mb-0"><?php echo htmlspecialchars($chart['name']); ?></h6>
									<?php if (!empty($chart['description'])): ?>
										<small class="text-muted"><?php echo htmlspecialchars($chart['description']); ?></small>
									<?php endif; ?>
								</div>
								<div class="card-body">
									<?php
									$this->load->model('Uniform_model');
									$sizes = $this->Uniform_model->getSizesBySizeChart($chart['id']);
									if (!empty($sizes)):
										?>
										<div class="d-flex flex-wrap gap-2">
											<?php foreach ($sizes as $size): ?>
												<span class="badge bg-primary"><?php echo htmlspecialchars($size['name']); ?></span>
											<?php endforeach; ?>
										</div>
									<?php else: ?>
										<p class="text-muted mb-0">No sizes available</p>
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
					<?php else: ?>
						<div class="alert alert-info">
							<p class="mb-0">No size charts available. Click "Add" to create your first size chart.</p>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
			</div>
		</div>
	</div>
</div>

<!-- Add Size Chart Modal -->
<div class="modal fade" id="addSizeChartModal" tabindex="-1" aria-labelledby="addSizeChartModalLabel"
	aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addSizeChartModalLabel">Add Size Chart</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="addSizeChartForm">
					<div class="mb-3">
						<label class="form-label">Size Chart Name <span class="text-danger">*</span></label>
						<input type="text" name="name" id="size_chart_name" class="form-control" required>
					</div>
					<div class="mb-3">
						<label class="form-label">Description</label>
						<textarea name="description" id="size_chart_description" class="form-control"
							rows="3"></textarea>
					</div>
					<div class="mb-3">
						<label class="form-label">Sizes <span class="text-danger">*</span></label>
						<small class="text-muted d-block mb-2">Enter sizes separated by commas (e.g., S, M, L, XL, XXL)
							or one per line</small>
						<textarea name="sizes" id="size_chart_sizes" class="form-control" rows="5"
							placeholder="S, M, L, XL, XXL" required></textarea>
						<small class="text-muted">You can enter sizes as: S, M, L, XL or one per line</small>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" onclick="addSizeChart()">Add Size Chart</button>
			</div>
		</div>
	</div>
</div>

<!-- Bulk Price Edit Modal -->
<div class="modal fade" id="bulkPriceModal" tabindex="-1" aria-labelledby="bulkPriceModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="bulkPriceModalLabel">Bulk Edit & Add Prices</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="bulkPriceForm">
					<!-- Step 1: Select Sizes -->
					<div class="mb-3">
						<label class="form-label fw-semibold">1. Select Sizes to Apply To</label>
						<div class="d-flex gap-2 mb-2">
							<button type="button" class="btn btn-xs btn-outline-primary" id="btn-select-all-sizes"
								onclick="toggleAllBulkSizes(true)" style="padding: 2px 6px; font-size: 11px;">Select
								All</button>
							<button type="button" class="btn btn-xs btn-outline-secondary" id="btn-deselect-all-sizes"
								onclick="toggleAllBulkSizes(false)" style="padding: 2px 6px; font-size: 11px;">Deselect
								All</button>
						</div>
						<div id="bulk_size_checkboxes"
							class="border rounded p-2 d-flex flex-wrap gap-3 align-items-center"
							style="max-height: 150px; overflow-y: auto; background-color: #f8f9fa;">
							<!-- Dynamic checkboxes -->
						</div>
					</div>

					<!-- Step 2: Select Classes -->
					<div class="mb-3" id="bulk_class_section">
						<label class="form-label fw-semibold">2. Select Classes to Apply To</label>
						<div class="d-flex gap-2 mb-2">
							<button type="button" class="btn btn-xs btn-outline-primary" id="btn-select-all-classes"
								onclick="toggleAllBulkClasses(true)" style="padding: 2px 6px; font-size: 11px;">Select
								All</button>
							<button type="button" class="btn btn-xs btn-outline-secondary" id="btn-deselect-all-classes"
								onclick="toggleAllBulkClasses(false)"
								style="padding: 2px 6px; font-size: 11px;">Deselect All</button>
						</div>
						<div id="bulk_class_checkboxes"
							class="border rounded p-2 d-flex flex-wrap gap-3 align-items-center"
							style="max-height: 150px; overflow-y: auto; background-color: #f8f9fa;">
							<!-- Dynamic checkboxes -->
						</div>
					</div>

					<!-- Step 3: Enter Prices -->
					<div class="mb-3 border-top pt-3">
						<label class="form-label fw-semibold">3. Enter Prices to Apply</label>

						<div class="mb-3">
							<div class="form-check mb-1">
								<input class="form-check-input" type="checkbox" id="bulk_set_mrp"
									onchange="toggleBulkInput('bulk_mrp_value', this.checked)">
								<label class="form-check-label fw-semibold" for="bulk_set_mrp">
									Update MRP
								</label>
							</div>
							<div class="input-group">
								<span class="input-group-text">₹</span>
								<input type="number" id="bulk_mrp_value" class="form-control"
									placeholder="Enter MRP amount" min="0" step="0.01" disabled>
							</div>
						</div>

						<div class="mb-3">
							<div class="form-check mb-1">
								<input class="form-check-input" type="checkbox" id="bulk_set_sp"
									onchange="toggleBulkInput('bulk_sp_value', this.checked)">
								<label class="form-check-label fw-semibold" for="bulk_set_sp">
									Update Selling Price
								</label>
							</div>
							<div class="input-group">
								<span class="input-group-text">₹</span>
								<input type="number" id="bulk_sp_value" class="form-control"
									placeholder="Enter Selling Price amount" min="0" step="0.01" disabled>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" onclick="applyBulkPrices()">Apply to Table</button>
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

	.uniform-edit-actionbar {
		position: fixed;
		left: 200px;
		right: 0;
		bottom: 0;
		z-index: 1030;
		background: #ffffff;
		padding: 10px 18px;
		box-shadow: 0 -2px 8px rgba(0, 0, 0, 0.08);
	}

	.uniform-edit-action-spacer {
		height: 72px;
	}

	.bg-size-even td {
		background-color: #ffffff !important;
	}

	.bg-size-odd td {
		background-color: #e5deff !important;
	}

	tr.size-group-start td {
		border-top: 2px solid #7539ff !important;
	}

	@media (max-width: 991px) {
		.uniform-edit-actionbar {
			left: 0;
			padding: 10px 12px;
		}
	}
</style>


<script>
	document.addEventListener('DOMContentLoaded', function () {

		const typeSelect = document.getElementById('school_commission_type');
		const fixedBox = document.getElementById('fixed_commission_container');
		const percentBox = document.getElementById('percentage_commission_container');
		const fixedInput = document.getElementById('fixed_commission_value');
		const percentInput = document.getElementById('percentage_commission_value');

		if (!typeSelect) return;

		function toggleCommissionFields() {

			const type = typeSelect.value;

			// Reset
			fixedBox.style.display = 'none';
			percentBox.style.display = 'none';
			fixedInput.disabled = true;
			percentInput.disabled = true;

			if (type === 'fixed') {
				fixedBox.style.display = 'block';
				fixedInput.disabled = false;
				percentInput.value = '';
			}

			if (type === 'percentage') {
				percentBox.style.display = 'block';
				percentInput.disabled = false;
				fixedInput.value = '';
			}
		}

		// Initial load (Edit page)
		setTimeout(toggleCommissionFields, 150);

		// Native select
		typeSelect.addEventListener('change', toggleCommissionFields);

		// Select2 support
		if (window.jQuery) {
			$('#school_commission_type').on('change select2:select', toggleCommissionFields);
		}
	});
</script>


<script src="<?php echo base_url('assets/js/image-sortable.js'); ?>"></script>
<script src="<?php echo base_url('assets/ckeditor/ckeditor.js'); ?>"></script>
<?php
// Initialize javascript states with current database rows
$initial_sizes = array();
$initial_prices = array();
if (isset($size_prices) && !empty($size_prices)) {
	// First gather unique sizes
	$unique_sizes = array();
	foreach ($size_prices as $price) {
		$unique_sizes[(int) $price['size_id']] = array(
			'id' => (int) $price['size_id'],
			'name' => $price['size_name']
		);
		$key = (int) $price['class_id'] . '_' . (int) $price['size_id'];
		$initial_prices[$key] = array(
			'mrp' => $price['mrp'],
			'selling_price' => $price['selling_price']
		);
	}
	$initial_sizes = array_values($unique_sizes);
}
?>
<script>
	// Global state for size-wise pricing initialized with current database rows
	var added_sizes = <?php echo json_encode($initial_sizes); ?>;
	var priceValues = <?php echo json_encode($initial_prices); ?>;

	CKEDITOR.replace('product_description', {
		removePlugins: 'link,about',
		removeButtons: 'Subscript,Superscript,Image',
		allowedContent: true
	});
	// Initialize CKEditor after page loads
	window.addEventListener('load', function () {
		function initCKEditor() {
			if (typeof CKEDITOR !== 'undefined') {
				// Destroy existing instances if any

				if (CKEDITOR.instances['manufacturer_details']) {
					CKEDITOR.instances['manufacturer_details'].destroy();
				}
				if (CKEDITOR.instances['packer_details']) {
					CKEDITOR.instances['packer_details'].destroy();
				}
				if (CKEDITOR.instances['customer_details']) {
					CKEDITOR.instances['customer_details'].destroy();
				}

				// Initialize CKEditor instances
				var manufacturerDetails = document.getElementById('manufacturer_details');
				var packerDetails = document.getElementById('packer_details');
				var customerDetails = document.getElementById('customer_details');


				if (manufacturerDetails) {
					CKEDITOR.replace('manufacturer_details');
				}
				if (packerDetails) {
					CKEDITOR.replace('packer_details');
				}
				if (customerDetails) {
					CKEDITOR.replace('customer_details');
				}
			} else {
				// If CKEDITOR is not loaded yet, wait and try again
				setTimeout(initCKEditor, 100);
			}
		}

		// Wait a bit for everything to be ready
		setTimeout(initCKEditor, 300);
	});

	document.addEventListener('DOMContentLoaded', function () {

		// School change handler - handle both regular select and Select2
		var schoolSelect = document.getElementById('school_id');
		if (schoolSelect) {
			// Check if Select2 is initialized
			var $schoolSelect = $('#school_id');
			if ($schoolSelect.length && $schoolSelect.hasClass('select2-hidden-accessible')) {
				// Use jQuery change event for Select2
				$schoolSelect.on('change', function () {
					var schoolId = $(this).val();
					console.log('School changed to:', schoolId);
					if (schoolId) {
						loadBranches(schoolId);
						loadBoards(schoolId);
					} else {
						document.getElementById('branch_container').style.display = 'none';
						var $branchSelect = $('#branch_id');
						if ($branchSelect.hasClass('select2-hidden-accessible')) {
							$branchSelect.empty().append('<option value="">Select Branch</option>').trigger('change');
						} else {
							document.getElementById('branch_id').innerHTML = '<option value="">Select Branch</option>';
						}
						var $boardSelect = $('#board_id');
						if ($boardSelect.hasClass('select2-hidden-accessible')) {
							$boardSelect.empty().append('<option value="">Select Board</option>').trigger('change');
						} else {
							document.getElementById('board_id').innerHTML = '<option value="">Select Board</option>';
						}
					}
				});
			} else {
				// Use regular change event
				schoolSelect.addEventListener('change', function () {
					var schoolId = this.value;
					console.log('School changed to:', schoolId);
					if (schoolId) {
						loadBranches(schoolId);
						loadBoards(schoolId);
					} else {
						document.getElementById('branch_container').style.display = 'none';
						document.getElementById('branch_id').innerHTML = '<option value="">Select Branch</option>';
						document.getElementById('board_id').innerHTML = '<option value="">Select Board</option>';
					}
				});
			}
		}

		// Listen to classes change to re-render pricing groups
		$('#class_ids').on('change select2:select select2:unselect', function () {
			renderPricingGroups();
		});

		// Initial rendering of pricing groups
		renderPricingGroups();

		// Size Chart change handler (using jQuery for Select2)
		$(document).ready(function () {
			// Wait for Select2 to initialize
			setTimeout(function () {
				var previousSizeChartId = <?php echo isset($uniform) && isset($uniform['size_chart_id']) && $uniform['size_chart_id'] ? (int)$uniform['size_chart_id'] : '""'; ?>;
				$('#size_chart_id').on('change', function () {
					var sizeChartId = $(this).val();
					console.log('Size chart changed to:', sizeChartId);
					if (sizeChartId == previousSizeChartId) {
						return;
					}
					previousSizeChartId = sizeChartId;
					if (sizeChartId) {
						// Clear pricing rows and state when chart changes
						added_sizes = [];
						priceValues = {};
						$('#sizePricesList').empty();
						// Reset size dropdown before loading new sizes
						$('#size_id').html('<option value="">Select Size</option>').val('').trigger('change');
						loadSizes(sizeChartId);
					} else {
						// Clear sizes if no chart selected
						added_sizes = [];
						priceValues = {};
						$('#size_id').html('<option value="">Select Size</option>').trigger('change');
						$('#sizePricesList').empty();
					}
				});

				// Load sizes on page load if editing and size chart is already selected
				<?php if (isset($uniform) && isset($uniform['size_chart_id']) && $uniform['size_chart_id']): ?>
					var initialSizeChartId = <?php echo $uniform['size_chart_id']; ?>;
					if (initialSizeChartId) {
						loadSizes(initialSizeChartId);
					}
				<?php endif; ?>
			}, 500);
		});

		// Size change handler - add row for pricing (using jQuery for Select2)
		$(document).ready(function () {
			// Wait for Select2 to initialize
			setTimeout(function () {
				// Use select2:select for more reliable handling with Select2
				$('#size_id').on('select2:select', function (e) {
					var data = e.params.data;
					var sizeId = data.id;
					var sizeName = data.text;

					console.log('Size select2:select event:', sizeId, sizeName);

					if (sizeId && sizeName !== 'Select Size') {
						// Add to state
						if (!added_sizes.some(s => s.id == sizeId)) {
							added_sizes.push({ id: sizeId, name: sizeName });
						}

						// Disable the selected option
						var $option = $(this).find('option[value="' + sizeId + '"]');
						$option.prop('disabled', true);

						renderPricingGroups();

						// Reset the select value
						$(this).val('').trigger('change');

						// Force Select2 results to re-render while open
						$(this).select2('close').select2('open');
					}
				});
			}, 500);
		});

		// Use shared image-sortable uploader in edit form too so new uploads get Main/Delete controls.
		if (typeof window.initImageSortable === 'function') {
			window.initImageSortable('image-preview', 'image_order', 'main_image_index');
		}
	});

	function loadBranches(schoolId) {
		fetch('<?php echo base_url('products/uniforms/get_branches'); ?>?school_id=' + schoolId)
			.then(response => response.json())
			.then(data => {
				var branchSelect = document.getElementById('branch_id');
				var branchContainer = document.getElementById('branch_container');
				var $branchSelect = $('#branch_id');

				// Check if Select2 is initialized
				var isSelect2 = $branchSelect.length && $branchSelect.hasClass('select2-hidden-accessible');

				if (data.status === 'success' && data.branches.length > 0) {
					// Destroy Select2 if initialized
					if (isSelect2) {
						$branchSelect.select2('destroy');
					}

					// Clear and populate options
					branchSelect.innerHTML = '<option value="">Select Branch</option>';
					data.branches.forEach(function (branch) {
						var option = document.createElement('option');
						option.value = branch.id;
						option.textContent = branch.branch_name;
						branchSelect.appendChild(option);
					});

					// Reinitialize Select2 if it was initialized before
					if (isSelect2 && $branchSelect.hasClass('select')) {
						$branchSelect.select2();
					}

					branchContainer.style.display = 'block';
				} else {
					// Destroy Select2 if initialized
					if (isSelect2) {
						$branchSelect.select2('destroy');
					}

					branchSelect.innerHTML = '<option value="">No branches available</option>';
					branchContainer.style.display = 'none';

					// Reinitialize Select2 if it was initialized before
					if (isSelect2 && $branchSelect.hasClass('select')) {
						$branchSelect.select2();
					}
				}
			})
			.catch(error => {
				console.error('Error loading branches:', error);
			});
	}

	function loadBoards(schoolId) {
		if (!schoolId) {
			document.getElementById('board_id').innerHTML = '<option value="">Select Board</option>';
			return;
		}

		fetch('<?php echo base_url('products/uniforms/get_boards'); ?>?school_id=' + schoolId)
			.then(response => {
				if (!response.ok) {
					throw new Error('Network response was not ok');
				}
				return response.json();
			})
			.then(data => {
				console.log('Boards response:', data);
				var boardSelect = document.getElementById('board_id');

				if (data.status === 'success' && data.boards && data.boards.length > 0) {
					boardSelect.innerHTML = '<option value="">Select Board</option>';
					data.boards.forEach(function (board) {
						var option = document.createElement('option');
						option.value = board.id;
						option.textContent = board.board_name;
						<?php if (isset($uniform['board_id']) && $uniform['board_id']): ?>
							if (board.id == <?php echo $uniform['board_id']; ?>) {
								option.selected = true;
							}
						<?php endif; ?>
						boardSelect.appendChild(option);
					});
				} else {
					boardSelect.innerHTML = '<option value="">No boards available</option>';
					console.warn('No boards found for school:', schoolId, data);
				}
			})
			.catch(error => {
				console.error('Error loading boards:', error);
				document.getElementById('board_id').innerHTML = '<option value="">Error loading boards</option>';
			});
	}

	function addUniformType() {
		var name = document.getElementById('uniform_type_name').value;
		var description = document.getElementById('uniform_type_description').value;

		if (!name) {
			alert('Please enter a name');
			return;
		}

		var formData = new FormData();
		formData.append('name', name);
		formData.append('description', description);

		fetch('<?php echo base_url('products/uniforms/add_uniform_type'); ?>', {
			method: 'POST',
			body: formData
		})
			.then(response => response.json())
			.then(data => {
				if (data.status === 'success') {
					var select = document.getElementById('uniform_type_id');
					var $select = $('#uniform_type_id');

					// Check if Select2 is initialized and destroy it
					if ($select.length && $select.hasClass('select2-hidden-accessible')) {
						$select.select2('destroy');
					}

					var option = document.createElement('option');
					option.value = data.id;
					option.textContent = data.name;
					option.selected = true;
					select.appendChild(option);

					// Reinitialize Select2 with search
					if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
						$select.select2({
							theme: 'bootstrap-5',
							placeholder: 'Select Uniform Type',
							allowClear: true,
							width: '100%'
						});
					}

					// Reset form but keep modal open for multiple additions
					document.getElementById('addUniformTypeForm').reset();

					// Show success message
					var nameInput = document.getElementById('uniform_type_name');
					nameInput.placeholder = 'Added: ' + data.name + ' (add another or close)';
					setTimeout(function () {
						nameInput.placeholder = '';
					}, 3000);
				} else {
					alert(data.message || 'Failed to add uniform type');
				}
			})
			.catch(error => {
				console.error('Error:', error);
				alert('An error occurred');
			});
	}

	function addMaterial() {
		var name = document.getElementById('material_name').value;
		var description = document.getElementById('material_description').value;

		if (!name) {
			alert('Please enter a name');
			return;
		}

		var formData = new FormData();
		formData.append('name', name);
		formData.append('description', description);

		fetch('<?php echo base_url('products/uniforms/add_material'); ?>', {
			method: 'POST',
			body: formData
		})
			.then(response => response.json())
			.then(data => {
				if (data.status === 'success') {
					var select = document.getElementById('material_id');
					var $select = $('#material_id');

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
					document.getElementById('addMaterialForm').reset();

					// Show success message
					var nameInput = document.getElementById('material_name');
					nameInput.placeholder = 'Added: ' + data.name + ' (add another or close)';
					setTimeout(function () {
						nameInput.placeholder = '';
					}, 3000);
				} else {
					alert(data.message || 'Failed to add material');
				}
			})
			.catch(error => {
				console.error('Error:', error);
				alert('An error occurred');
			});
	}

	function addSizeChart() {
		var name = document.getElementById('size_chart_name').value;
		var description = document.getElementById('size_chart_description').value;
		var sizesText = document.getElementById('size_chart_sizes').value;

		if (!name) {
			alert('Please enter a size chart name');
			return;
		}

		if (!sizesText || sizesText.trim() === '') {
			alert('Please enter at least one size');
			return;
		}

		// Parse sizes - handle both comma-separated and line-separated
		var sizes = sizesText.split(/[,\n]/).map(function (size) {
			return size.trim();
		}).filter(function (size) {
			return size.length > 0;
		});

		if (sizes.length === 0) {
			alert('Please enter at least one valid size');
			return;
		}

		var formData = new FormData();
		formData.append('name', name);
		formData.append('description', description);
		sizes.forEach(function (size, index) {
			formData.append('sizes[]', size);
		});

		fetch('<?php echo base_url('products/uniforms/add_size_chart'); ?>', {
			method: 'POST',
			body: formData
		})
			.then(response => response.json())
			.then(data => {
				if (data.status === 'success') {
					var select = document.getElementById('size_chart_id');
					var $select = $('#size_chart_id');

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

					// Load sizes for this chart
					loadSizes(data.id);

					// Reset form but keep modal open for multiple additions
					document.getElementById('addSizeChartForm').reset();

					// Show success message
					var nameInput = document.getElementById('size_chart_name');
					nameInput.placeholder = 'Added: ' + data.name + ' (add another or close)';
					setTimeout(function () {
						nameInput.placeholder = '';
					}, 3000);
				} else {
					alert(data.message || 'Failed to add size chart');
				}
			})
			.catch(error => {
				console.error('Error:', error);
				alert('An error occurred');
			});
	}

	function loadSizes(sizeChartId) {
		if (!sizeChartId) {
			document.getElementById('size_id').innerHTML = '<option value="">Select Size</option>';
			return;
		}

		// Use GET request to avoid CSRF issues for read-only operation
		var url = '<?php echo base_url('products/uniforms/get_sizes'); ?>?size_chart_id=' + encodeURIComponent(sizeChartId);

		fetch(url, {
			method: 'GET',
			headers: {
				'X-Requested-With': 'XMLHttpRequest'
			}
		})
			.then(response => {
				if (!response.ok) {
					throw new Error('Network response was not ok');
				}
				return response.json();
			})
			.then(data => {
				var $sizeSelect = $('#size_id');
				if ($sizeSelect.length === 0) {
					console.error('Size select element not found');
					return;
				}

				// Clear existing options
				$sizeSelect.html('<option value="">Select Size</option>');

				if (data.status === 'success' && data.sizes && data.sizes.length > 0) {
					data.sizes.forEach(function (size) {
						var $option = $('<option></option>').attr('value', size.id).text(size.name);
						// Check if this size is already in the pricing list
						if (added_sizes.some(s => s.id == size.id)) {
							$option.prop('disabled', true);
						}
						$sizeSelect.append($option);
					});
				} else {
					console.warn('No sizes found for size chart:', sizeChartId);
				}

				$sizeSelect.select2({
					theme: 'bootstrap-5',
					placeholder: 'Select Size',
					allowClear: true,
					width: '100%',
					closeOnSelect: false,
					templateResult: function (data) {
						if (!data.id) { return data.text; }
						var $result = $('<span>' + data.text + '</span>');
						// Check if this size is already in the pricing list
						if (added_sizes.some(s => s.id == data.id)) {
							$result.css('color', '#adb5bd').css('font-style', 'italic');
						}
						return $result;
					}
				});

				// Trigger Select2 update
				$sizeSelect.trigger('change');
			})
			.catch(error => {
				console.error('Error loading sizes:', error);
				alert('Failed to load sizes. Please try again.');
			});
	}

	function saveCurrentValues() {
		var mrpInputs = document.querySelectorAll('.mrp-input');
		mrpInputs.forEach(function (input) {
			var sizeId = input.getAttribute('data-size-id');
			var classId = input.getAttribute('data-class-id') || '0';
			var key = classId + '_' + sizeId;
			if (!priceValues[key]) priceValues[key] = {};
			priceValues[key].mrp = input.value;
		});

		var spInputs = document.querySelectorAll('.selling-price-input');
		spInputs.forEach(function (input) {
			var sizeId = input.getAttribute('data-size-id');
			var classId = input.getAttribute('data-class-id') || '0';
			var key = classId + '_' + sizeId;
			if (!priceValues[key]) priceValues[key] = {};
			priceValues[key].selling_price = input.value;
		});
	}

	function renderPricingGroups(skipSaveCurrent = false) {
		// 1. Save typed values first to avoid data loss
		if (!skipSaveCurrent) {
			saveCurrentValues();
		}

		var container = document.getElementById('sizePricesContainer');
		var tbody = document.getElementById('sizePricesList');
		if (!container || !tbody) return;

		if (added_sizes.length === 0) {
			container.style.display = 'none';
			tbody.innerHTML = '';
			return;
		}

		container.style.display = 'block';
		tbody.innerHTML = '';

		// 2. Get selected classes
		var selectedClasses = [];
		$('#class_ids option:selected').each(function () {
			selectedClasses.push({
				id: $(this).val(),
				name: $(this).text().trim()
			});
		});

		// 3. Render rows for each added size
		var rowNum = 1;
		added_sizes.forEach(function (size, sizeIdx) {
			var sizeId = size.id;
			var sizeName = size.name;
			var bgClass = sizeIdx % 2 === 0 ? 'bg-size-even' : 'bg-size-odd';
			var startClass = sizeIdx > 0 ? 'size-group-start' : '';
			var currentSizeNo = rowNum++;

			if (selectedClasses.length === 0) {
				// Render a single general pricing row (class_id = 0)
				var key = '0_' + sizeId;
				var mrpVal = priceValues[key]?.mrp || '';
				var spVal = priceValues[key]?.selling_price || '';

				var tr = document.createElement('tr');
				tr.className = `${bgClass} ${startClass}`;
				tr.innerHTML = `
					<td class="align-middle text-center fw-medium">${currentSizeNo}</td>
					<td class="align-middle fw-semibold">${sizeName}</td>
					<td class="align-middle text-muted fs-12">General (All Classes)</td>
					<td>
						<div class="input-group input-group-sm">
							<span class="input-group-text">₹</span>
							<input type="hidden" name="size_prices[0][${sizeId}][class_id]" value="0" form="uniform-form">
							<input type="hidden" name="size_prices[0][${sizeId}][size_id]" value="${sizeId}" form="uniform-form">
							<input type="number" name="size_prices[0][${sizeId}][mrp]" id="mrp_0_${sizeId}" class="form-control mrp-input" step="0.01" min="0" required form="uniform-form" placeholder="0.00" value="${mrpVal}" data-size-id="${sizeId}" data-class-id="0">
						</div>
						<small class="text-danger mrp-error fs-11" id="mrp_error_0_${sizeId}" style="display:none;">MRP must be >= Selling Price</small>
					</td>
					<td>
						<div class="input-group input-group-sm">
							<span class="input-group-text">₹</span>
							<input type="number" name="size_prices[0][${sizeId}][selling_price]" id="selling_price_0_${sizeId}" class="form-control selling-price-input" step="0.01" min="0" required form="uniform-form" placeholder="0.00" value="${spVal}" data-size-id="${sizeId}" data-class-id="0">
						</div>
						<small class="text-danger selling-price-error fs-11" id="selling_price_error_0_${sizeId}" style="display:none;">Selling Price must be <= MRP</small>
					</td>
					<td class="text-center align-middle">
						<button type="button" class="btn btn-outline-danger btn-sm p-1" onclick="removeSizePricing(${sizeId})" title="Remove Size">
							<i class="isax isax-trash" style="font-size: 16px; display: inline-block; vertical-align: middle;"></i>
						</button>
					</td>
				`;
				tbody.appendChild(tr);
			} else {
				selectedClasses.forEach(function (cls, index) {
					var classId = cls.id;
					var className = cls.name;
					var key = classId + '_' + sizeId;
					var mrpVal = priceValues[key]?.mrp || '';
					var spVal = priceValues[key]?.selling_price || '';

					var tr = document.createElement('tr');
					tr.className = `${bgClass} ${index === 0 ? startClass : ''}`;

					var srNoTd = '';
					var sizeTd = '';
					var actionTd = '';
					if (index === 0) {
						srNoTd = `<td class="align-middle text-center fw-medium" rowspan="${selectedClasses.length}">${currentSizeNo}</td>`;
						sizeTd = `<td class="align-middle fw-semibold text-center" rowspan="${selectedClasses.length}">${sizeName}</td>`;
						actionTd = `
							<td class="text-center align-middle" rowspan="${selectedClasses.length}">
								<button type="button" class="btn btn-outline-danger btn-sm p-1" onclick="removeSizePricing(${sizeId})" title="Remove Size">
									<i class="isax isax-trash" style="font-size: 16px; display: inline-block; vertical-align: middle;"></i>
								</button>
							</td>
						`;
					}

					var isFirst = index === 0 ? 'true' : 'false';

					tr.innerHTML = `
						${srNoTd}
						${sizeTd}
						<td class="align-middle text-dark fw-semibold fs-12">${className}</td>
						<td>
							<input type="hidden" name="size_prices[${classId}][${sizeId}][class_id]" value="${classId}" form="uniform-form">
							<input type="hidden" name="size_prices[${classId}][${sizeId}][size_id]" value="${sizeId}" form="uniform-form">
							<div class="input-group input-group-sm">
								<span class="input-group-text">₹</span>
								<input type="number" name="size_prices[${classId}][${sizeId}][mrp]" id="mrp_${classId}_${sizeId}" class="form-control mrp-input" step="0.01" min="0" required form="uniform-form" placeholder="0.00" value="${mrpVal}" data-size-id="${sizeId}" data-class-id="${classId}" data-first-row="${isFirst}">
							</div>
							<small class="text-danger mrp-error fs-11" id="mrp_error_${classId}_${sizeId}" style="display:none;">MRP must be >= Selling Price</small>
						</td>
						<td>
							<div class="input-group input-group-sm">
								<span class="input-group-text">₹</span>
								<input type="number" name="size_prices[${classId}][${sizeId}][selling_price]" id="selling_price_${classId}_${sizeId}" class="form-control selling-price-input" step="0.01" min="0" required form="uniform-form" placeholder="0.00" value="${spVal}" data-size-id="${sizeId}" data-class-id="${classId}" data-first-row="${isFirst}">
							</div>
							<small class="text-danger selling-price-error fs-11" id="selling_price_error_${classId}_${sizeId}" style="display:none;">Selling Price must be <= MRP</small>
						</td>
						${actionTd}
					`;
					tbody.appendChild(tr);
				});
			}
		});

		// 4. Attach validation event listeners
		var mrpInputs = tbody.querySelectorAll('.mrp-input');
		var spInputs = tbody.querySelectorAll('.selling-price-input');

		mrpInputs.forEach(function (input) {
			input.addEventListener('input', validatePriceRow);
			input.addEventListener('blur', validatePriceRow);

			// Auto-fill/propagate first row price to sibling classes for the same size
			if (input.getAttribute('data-first-row') === 'true') {
				input.addEventListener('input', function (e) {
					var val = e.target.value;
					var sizeId = e.target.getAttribute('data-size-id');
					var siblingInputs = tbody.querySelectorAll(`.mrp-input[data-size-id="${sizeId}"]:not([data-first-row="true"])`);
					siblingInputs.forEach(function (sib) {
						sib.value = val;
						sib.dispatchEvent(new Event('input'));
					});
				});
			}
		});
		spInputs.forEach(function (input) {
			input.addEventListener('input', validatePriceRow);
			input.addEventListener('blur', validatePriceRow);

			// Auto-fill/propagate first row price to sibling classes for the same size
			if (input.getAttribute('data-first-row') === 'true') {
				input.addEventListener('input', function (e) {
					var val = e.target.value;
					var sizeId = e.target.getAttribute('data-size-id');
					var siblingInputs = tbody.querySelectorAll(`.selling-price-input[data-size-id="${sizeId}"]:not([data-first-row="true"])`);
					siblingInputs.forEach(function (sib) {
						sib.value = val;
						sib.dispatchEvent(new Event('input'));
					});
				});
			}
		});
	}

	function removeSizePricing(sizeId) {
		// 1. Remove from added_sizes array
		added_sizes = added_sizes.filter(function (size) {
			return size.id != sizeId;
		});

		// 2. Re-enable in dropdown
		$('#size_id option[value="' + sizeId + '"]').prop('disabled', false);
		$('#size_id').trigger('change');

		// 3. Remove keys from priceValues cache
		var keys = Object.keys(priceValues);
		keys.forEach(function (key) {
			if (key.endsWith('_' + sizeId)) {
				delete priceValues[key];
			}
		});

		// 4. Re-render
		renderPricingGroups();
	}

	function validatePriceRow(e) {
		var sizeId = e.target.getAttribute('data-size-id');
		var classId = e.target.getAttribute('data-class-id') || '0';
		if (!sizeId) return;

		var mrpInput = document.getElementById('mrp_' + classId + '_' + sizeId);
		var sellingPriceInput = document.getElementById('selling_price_' + classId + '_' + sizeId);
		var mrpError = document.getElementById('mrp_error_' + classId + '_' + sizeId);
		var sellingPriceError = document.getElementById('selling_price_error_' + classId + '_' + sizeId);

		if (!mrpInput || !sellingPriceInput) return;

		var mrp = parseFloat(mrpInput.value) || 0;
		var sellingPrice = parseFloat(sellingPriceInput.value) || 0;

		// Hide errors initially
		if (mrpError) mrpError.style.display = 'none';
		if (sellingPriceError) sellingPriceError.style.display = 'none';
		mrpInput.classList.remove('is-invalid');
		sellingPriceInput.classList.remove('is-invalid');

		// Validate only if both values are entered
		if (mrp > 0 && sellingPrice > 0) {
			if (mrp < sellingPrice) {
				if (mrpError) mrpError.style.display = 'block';
				if (sellingPriceError) sellingPriceError.style.display = 'block';
				mrpInput.classList.add('is-invalid');
				sellingPriceInput.classList.add('is-invalid');
				return false;
			}
		}

		return true;
	}

	function validateAllPrices() {
		var isValid = true;
		var mrpInputs = document.querySelectorAll('.mrp-input');

		mrpInputs.forEach(function (mrpInput) {
			var sizeId = mrpInput.getAttribute('data-size-id');
			var classId = mrpInput.getAttribute('data-class-id') || '0';
			if (sizeId) {
				var event = { target: mrpInput };
				if (!validatePriceRow(event)) {
					isValid = false;
				}
			}
		});

		return isValid;
	}

	function refreshSizeChartsView() {
		// Reload the page to refresh the size charts list in the view modal
		// This is a simple approach - you could also use AJAX to update just the modal content
		var viewModal = document.getElementById('viewSizeChartsModal');
		if (viewModal) {
			try {
				var modalInstance = bootstrap.Modal.getInstance(viewModal);
				if (modalInstance && modalInstance._isShown) {
					// If the view modal is open, reload the page to refresh the list
					location.reload();
				}
			} catch (e) {
				// Modal instance doesn't exist or error accessing it, ignore
			}
		}
	}

	// Handle existing images - Set as Main
	(function () {
		function initEditFormHandlers() {
			const deletedImageIds = [];
			const deletedInput = document.getElementById('deleted_image_ids');
			const mainImageInput = document.getElementById('main_image_id');

			function updateImageOrder() {
				const orderInput = document.getElementById('image_order');
				if (!orderInput) return;

				const imageItems = document.querySelectorAll('#image-preview .image-preview-item');
				const imageIds = Array.from(imageItems).map(function (item) {
					return item.getAttribute('data-image-id') || 'new-' + Array.from(item.parentNode.children).indexOf(item);
				}).filter(function (id) {
					return id && id !== '';
				});

				orderInput.value = imageIds.join(',');
			}

			// Set main image for existing images
			document.querySelectorAll('.set-main-existing-btn').forEach(function (btn) {
				btn.addEventListener('click', function (e) {
					e.preventDefault();
					const imageId = this.getAttribute('data-image-id');
					const mainImageIndexInput = document.getElementById('main_image_index');

					// Update main image input
					if (mainImageInput) {
						mainImageInput.value = imageId;
					}
					if (mainImageIndexInput) {
						mainImageIndexInput.value = '-1';
					}

					document.querySelectorAll('.set-main-btn').forEach(function (newBtn) {
						newBtn.textContent = 'Set Main';
						newBtn.style.background = '#007bff';
						newBtn.style.color = '#fff';
					});

					// Update all buttons
					document.querySelectorAll('.set-main-existing-btn').forEach(function (b) {
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
			document.querySelectorAll('.remove-existing-image-btn').forEach(function (btn) {
				btn.addEventListener('click', function (e) {
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

						// Update image order
						updateImageOrder();
					}
				});
			});

			// Make existing images draggable with full drag-and-drop support
			const preview = document.getElementById('image-preview');
			if (preview) {
				let draggedElement = null;

				// Add drag-and-drop handlers to the container
				preview.addEventListener('dragover', function (e) {
					e.preventDefault();
					e.dataTransfer.dropEffect = 'move';

					const afterElement = getDragAfterElement(preview, e.clientY);
					const existingImages = preview.querySelectorAll('.existing-image:not([style*="display: none"])');

					if (afterElement == null) {
						existingImages.forEach(function (img) {
							img.style.border = '';
						});
					} else {
						existingImages.forEach(function (img) {
							img.style.border = '';
							if (img === afterElement) {
								img.style.border = '2px dashed #007bff';
							}
						});
					}
				});

				preview.addEventListener('drop', function (e) {
					e.preventDefault();

					if (draggedElement && draggedElement !== e.target.closest('.existing-image')) {
						const afterElement = getDragAfterElement(preview, e.clientY);
						const targetElement = e.target.closest('.existing-image');

						if (afterElement == null) {
							preview.appendChild(draggedElement);
						} else {
							preview.insertBefore(draggedElement, afterElement);
						}

						// Update image order
						updateImageOrder();
					}

					// Reset borders
					preview.querySelectorAll('.existing-image').forEach(function (img) {
						img.style.border = '';
					});
				});

				// Helper function to find element after dragged position
				function getDragAfterElement(container, y) {
					const existingImages = Array.from(container.querySelectorAll('.existing-image:not([style*="display: none"])'));

					return existingImages.reduce(function (closest, child) {
						const box = child.getBoundingClientRect();
						const offset = y - box.top - box.height / 2;

						if (offset < 0 && offset > closest.offset) {
							return { offset: offset, element: child };
						} else {
							return closest;
						}
					}, { offset: Number.NEGATIVE_INFINITY }).element;
				}

				// Make existing images draggable
				const existingImages = document.querySelectorAll('.existing-image');
				existingImages.forEach(function (img) {
					img.draggable = true;
					img.addEventListener('dragstart', function (e) {
						draggedElement = this;
						this.style.opacity = '0.5';
						e.dataTransfer.effectAllowed = 'move';
					});
					img.addEventListener('dragend', function () {
						this.style.opacity = '1';
						this.style.border = '';
					});
				});

				// Initialize image order on page load
				updateImageOrder();
			}
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

		fetch('<?php echo base_url('products/uniforms/delete_image/'); ?>' + imageId, {
			method: 'POST',
			headers: {
				'X-Requested-With': 'XMLHttpRequest'
			}
		})
			.then(response => response.json())
			.then(data => {
				if (data.status === 'success') {
					location.reload();
				} else {
					alert(data.message || 'Failed to delete image');
				}
			})
			.catch(error => {
				console.error('Error:', error);
				alert('An error occurred');
			});
	}

	// Initialize Select2 for uniform type dropdown with search
	document.addEventListener('DOMContentLoaded', function () {
		if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
			// Initialize Select2 on uniform_type_id dropdown
			$('#uniform_type_id').select2({
				theme: 'bootstrap-5',
				placeholder: 'Select Uniform Type',
				allowClear: true,
				width: '100%'
			});

			// Initialize Select2 on color dropdown
			$('.select2-color').select2({
				theme: 'bootstrap-5',
				placeholder: 'Select a color',
				allowClear: true,
				width: '100%'
			});
			// Initialize Select2 on gender dropdown
			$('#gender').select2({
				theme: 'bootstrap-5',
				placeholder: 'Select Gender',
				allowClear: true,
				width: '100%'
			});

			function formatClassOption(state) {
				if (!state.id) {
					return state.text;
				}

				var $container = $('<div class="d-flex align-items-center justify-content-between w-100 py-1"></div>');
				var $label = $('<span></span>').text(state.text);
				var $editBtn = $('<span class="btn-edit-class-dropdown text-primary ms-2" style="cursor: pointer; opacity: 0.7; padding: 2px 4px;" title="Edit Class Name"><i class="isax isax-edit-2 fs-14"></i></span>');

				$editBtn.on('mousedown mouseup click', function (e) {
					e.preventDefault();
					e.stopPropagation();

					if (e.type !== 'click') {
						return;
					}

					var currentName = state.text;
					Swal.fire({
						title: 'Rename Class',
						input: 'text',
						inputValue: currentName,
						showCancelButton: true,
						confirmButtonText: 'Save',
						confirmButtonColor: 'var(--primary, #7539ff)',
						cancelButtonColor: '#6c757d',
						inputValidator: (value) => {
							if (!value || !value.trim()) {
								return 'Class name cannot be empty!';
							}
						}
					}).then((result) => {
						if (result.isConfirmed) {
							var newName = result.value.trim();
							$.ajax({
								url: '<?php echo base_url('products/uniforms/edit_class'); ?>',
								type: 'POST',
								data: { id: state.id, name: newName },
								dataType: 'json',
								success: function (response) {
									if (response.status === 'success') {
										// Update Select2's internal cached state text for this option
										state.text = response.name;

										// Update the option in the underlying select tag
										var option = $('#class_ids option[value="' + state.id + '"]');
										option.text(response.name);

										// Trigger change event to notify Select2 and update active selected pills/tags & pricing table
										var selectEl = $('#class_ids');
										selectEl.trigger('change');

										// Force the dropdown menu to close and open to redraw dropdown option items
										selectEl.select2('close').select2('open');

										// Reload the editing modal list if it is open/drawn
										if (typeof loadClassesForEditing === 'function') {
											loadClassesForEditing();
										}
									} else {
										Swal.fire('Error', response.message || 'Failed to update class', 'error');
									}
								},
								error: function () {
									Swal.fire('Error', 'Failed to update class. Please try again.', 'error');
								}
							});
						}
					});
				});

				$editBtn.on('mouseenter', function () {
					$(this).css('opacity', '1');
				}).on('mouseleave', function () {
					$(this).css('opacity', '0.7');
				});

				$container.append($label).append($editBtn);
				return $container;
			}

			// Store formatClassOption globally so it can be reused elsewhere if needed
			window.formatClassOption = formatClassOption;

			// Initialize Select2 on class_ids dropdown with edit button template
			$('#class_ids').select2('destroy').select2({
				theme: 'bootstrap-5',
				placeholder: 'Select Classes',
				allowClear: true,
				width: '100%',
				templateResult: formatClassOption
			});
		}
	});
	function addClass() {
		var name = $('#new_class_name').val();

		if (!name) {
			alert('Please enter class name');
			return;
		}

		$.ajax({
			url: '<?php echo base_url('products/uniforms/add_class'); ?>',
			type: 'POST',
			data: { name: name },
			success: function (response) {
				if (response.status === 'success') {
					// Add to select2
					var newOption = new Option(response.name, response.id, true, true);
					$('#class_ids').append(newOption).trigger('change');

					// Reset and close modal
					$('#new_class_name').val('');
					$('#addClassModal').modal('hide');
				} else {
					alert(response.message);
				}
			},
			error: function () {
				alert('Failed to add class. Please try again.');
			}
		});
	}

	function toggleBulkInput(inputId, isChecked) {
		document.getElementById(inputId).disabled = !isChecked;
		if (!isChecked) {
			document.getElementById(inputId).value = '';
		}
	}

	function toggleAllBulkSizes(checked) {
		document.querySelectorAll('.bulk-size-cb').forEach(function (cb) {
			cb.checked = checked;
		});
	}

	// Select/deselect all classes
	function toggleAllBulkClasses(checked) {
		document.querySelectorAll('.bulk-class-cb').forEach(function (cb) {
			cb.checked = checked;
		});
	}

	// Bootstrap modal setup for bulk pricing
	document.addEventListener('DOMContentLoaded', function () {
		var bulkPriceModal = document.getElementById('bulkPriceModal');
		if (bulkPriceModal) {
			bulkPriceModal.addEventListener('show.bs.modal', function () {
				// Save typed values first to avoid data loss
				saveCurrentValues();

				// Populate sizes checkboxes
				var sizeContainer = document.getElementById('bulk_size_checkboxes');
				sizeContainer.innerHTML = '';
				if (added_sizes.length === 0) {
					sizeContainer.innerHTML = '<span class="text-muted fs-12 p-2">No sizes added yet. Please select sizes first.</span>';
				} else {
					added_sizes.forEach(function (size) {
						sizeContainer.innerHTML += `
						<div class="form-check me-2">
							<input class="form-check-input bulk-size-cb" type="checkbox" value="${size.id}" id="bulk_size_${size.id}" checked>
							<label class="form-check-label fs-13 mb-0" for="bulk_size_${size.id}">
								${size.name}
							</label>
						</div>
					`;
					});
				}

				// Populate classes checkboxes
				var classSection = document.getElementById('bulk_class_section');
				var classContainer = document.getElementById('bulk_class_checkboxes');
				classContainer.innerHTML = '';

				var selectedClasses = [];
				$('#class_ids option:selected').each(function () {
					selectedClasses.push({
						id: $(this).val(),
						name: $(this).text().trim()
					});
				});

				if (selectedClasses.length === 0) {
					// No classes selected -> general pricing (class_id = 0)
					classSection.style.display = 'none';
					classContainer.innerHTML = `
					<div class="form-check">
						<input class="form-check-input bulk-class-cb" type="checkbox" value="0" id="bulk_class_0" checked disabled>
						<label class="form-check-label fs-13 mb-0" for="bulk_class_0">
							General (All Classes)
						</label>
					</div>
				`;
				} else {
					classSection.style.display = 'block';
					selectedClasses.forEach(function (cls) {
						classContainer.innerHTML += `
						<div class="form-check me-2">
							<input class="form-check-input bulk-class-cb" type="checkbox" value="${cls.id}" id="bulk_class_${cls.id}" checked>
							<label class="form-check-label fs-13 mb-0" for="bulk_class_${cls.id}">
								${cls.name}
							</label>
						</div>
					`;
					});
				}

				// Reset inputs
				document.getElementById('bulk_set_mrp').checked = false;
				document.getElementById('bulk_mrp_value').value = '';
				document.getElementById('bulk_mrp_value').disabled = true;

				document.getElementById('bulk_set_sp').checked = false;
				document.getElementById('bulk_sp_value').value = '';
				document.getElementById('bulk_sp_value').disabled = true;
			});
		}
	});

	function applyBulkPrices() {
		var setMrp = document.getElementById('bulk_set_mrp').checked;
		var mrpVal = document.getElementById('bulk_mrp_value').value;
		var setSp = document.getElementById('bulk_set_sp').checked;
		var spVal = document.getElementById('bulk_sp_value').value;

		if (!setMrp && !setSp) {
			alert('Please check and enter at least one pricing option (MRP or Selling Price) to update.');
			return;
		}

		if (setMrp && (mrpVal === '' || parseFloat(mrpVal) < 0)) {
			alert('Please enter a valid MRP amount.');
			return;
		}

		if (setSp && (spVal === '' || parseFloat(spVal) < 0)) {
			alert('Please enter a valid Selling Price amount.');
			return;
		}

		if (setMrp && setSp && parseFloat(mrpVal) < parseFloat(spVal)) {
			alert('MRP must be greater than or equal to Selling Price.');
			return;
		}

		// Get selected size IDs
		var targetSizes = [];
		document.querySelectorAll('.bulk-size-cb:checked').forEach(function (cb) {
			targetSizes.push(cb.value);
		});

		if (targetSizes.length === 0) {
			alert('Please select at least one size.');
			return;
		}

		// Get selected class IDs
		var targetClasses = [];
		var selectedClassesCount = $('#class_ids option:selected').length;
		if (selectedClassesCount === 0) {
			targetClasses = ['0'];
		} else {
			document.querySelectorAll('.bulk-class-cb:checked').forEach(function (cb) {
				targetClasses.push(cb.value);
			});
			if (targetClasses.length === 0) {
				alert('Please select at least one class.');
				return;
			}
		}

		// Apply values to priceValues cache
		targetSizes.forEach(function (sizeId) {
			targetClasses.forEach(function (classId) {
				var key = classId + '_' + sizeId;
				if (!priceValues[key]) priceValues[key] = {};

				if (setMrp) {
					priceValues[key].mrp = parseFloat(mrpVal).toFixed(2);
				}
				if (setSp) {
					priceValues[key].selling_price = parseFloat(spVal).toFixed(2);
				}
			});
		});

		// Re-render the pricing groups table
		renderPricingGroups(true);

		// Close Modal
		var modalEl = document.getElementById('bulkPriceModal');
		var modalInstance = bootstrap.Modal.getInstance(modalEl);
		if (modalInstance) {
			modalInstance.hide();
		} else {
			$('#bulkPriceModal').modal('hide');
		}
	}

	$('#addClassModal').on('show.bs.modal', function () {
		loadClassesForEditing();
	});

	function loadClassesForEditing() {
		var listContainer = $('#edit-classes-list');
		listContainer.empty();

		$('#class_ids option').each(function () {
			var id = $(this).val();
			var name = $(this).text().trim();
			if (id) {
				var row = $('<div class="d-flex align-items-center justify-content-between mb-2 p-2 border rounded"></div>');
				var nameSpan = $('<span class="class-name-text fw-medium fs-13"></span>').text(name);
				var editInput = $('<input type="text" class="form-control form-control-sm class-edit-input d-none" style="height: 30px;" />').val(name);

				var btnGroup = $('<div class="d-flex gap-1"></div>');
				var editBtn = $('<button type="button" class="btn btn-outline-primary btn-sm p-1 px-2 btn-edit-class" title="Rename" style="padding: 2px 6px !important;"><i class="isax isax-edit-2 fs-14"></i></button>');
				var saveBtn = $('<button type="button" class="btn btn-success btn-sm p-1 px-2 btn-save-class d-none" title="Save" style="padding: 2px 6px !important;"><i class="isax isax-tick-circle fs-14"></i></button>');
				var cancelBtn = $('<button type="button" class="btn btn-outline-secondary btn-sm p-1 px-2 btn-cancel-class d-none" title="Cancel" style="padding: 2px 6px !important;"><i class="isax isax-close-circle fs-14"></i></button>');

				editBtn.on('click', function () {
					nameSpan.addClass('d-none');
					editInput.removeClass('d-none').focus();
					editBtn.addClass('d-none');
					saveBtn.removeClass('d-none');
					cancelBtn.removeClass('d-none');
				});

				cancelBtn.on('click', function () {
					nameSpan.removeClass('d-none');
					editInput.addClass('d-none').val(name);
					editBtn.removeClass('d-none');
					saveBtn.addClass('d-none');
					cancelBtn.addClass('d-none');
				});

				saveBtn.on('click', function () {
					var newName = editInput.val().trim();
					if (!newName) {
						alert('Class name cannot be empty');
						return;
					}

					$.ajax({
						url: '<?php echo base_url('products/uniforms/edit_class'); ?>',
						type: 'POST',
						data: { id: id, name: newName },
						dataType: 'json',
						success: function (response) {
							if (response.status === 'success') {
								name = response.name;
								nameSpan.text(name).removeClass('d-none');
								editInput.addClass('d-none').val(name);
								editBtn.removeClass('d-none');
								saveBtn.addClass('d-none');
								cancelBtn.addClass('d-none');

								// Update option in select2
								var option = $('#class_ids option[value="' + id + '"]');
								option.text(name);

								// Trigger change to update active selected choice pills/tags & pricing table
								$('#class_ids').trigger('change');
							} else {
								alert(response.message || 'Failed to update class');
							}
						},
						error: function () {
							alert('Failed to update class. Please try again.');
						}
					});
				});

				var leftSide = $('<div class="d-flex align-items-center flex-grow-1 me-2"></div>').append(nameSpan).append(editInput);
				btnGroup.append(editBtn).append(saveBtn).append(cancelBtn);
				row.append(leftSide).append(btnGroup);
				listContainer.append(row);
			}
		});
	}
</script>

<style>
	/* Ensure disabled options in Select2 are visually distinct and stay visible */
	.select2-results__option[aria-disabled=true] {
		display: block !important;
		color: #adb5bd !important;
		background-color: #f8f9fa !important;
		cursor: not-allowed !important;
		pointer-events: none;
	}
</style>