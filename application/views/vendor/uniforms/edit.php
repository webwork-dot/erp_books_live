<!-- Start Breadcrumb -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-2 mb-2">
	<div>
		<h6 class="mb-0 fs-14"><a href="<?php echo base_url('products/uniforms'); ?>"><i class="isax isax-arrow-left me-1"></i>Edit Uniform</a></h6>
	</div>
</div>
<script src="<?=base_url();?>assets/ckeditor/ckeditor.js"></script>
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
							<input type="file" name="images[]" id="images" class="form-control form-control-sm" form="uniform-form" accept="image/*" multiple>
							<small class="text-muted fs-12">You can select multiple images. Recommended size: 440px × 530px. Drag images to reorder. Click "Set as Main" to choose the main image. Leave empty to keep existing images.</small>
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
										<div class="image-preview-item existing-image" data-image-id="<?php echo $img['id']; ?>" style="position: relative; display: inline-block; margin: 3px; cursor: move; vertical-align: top;">
											<img src="<?php echo $image_url; ?>" alt="Uniform Image" style="width: 120px; height: 120px; object-fit: cover; border: 2px solid #ddd; border-radius: 4px 4px 0 0; display: block;">
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
								<div class="input-group">
									<select name="uniform_type_id" id="uniform_type_id" class="form-select form-select-sm" required>
										<option value="">Select Uniform Type</option>
										<?php if (!empty($uniform_types)): ?>
											<?php foreach ($uniform_types as $type): ?>
												<option value="<?php echo $type['id']; ?>" <?php echo ($uniform['uniform_type_id'] == $type['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($type['name']); ?></option>
											<?php endforeach; ?>
										<?php endif; ?>
									</select>
									<button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUniformTypeModal" style="padding: 4px 8px;">
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
											<option value="<?php echo $school['id']; ?>" <?php echo ($uniform['school_id'] == $school['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($school['school_name']); ?></option>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
								<?php echo form_error('school_id', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-xl-3 col-lg-4 col-md-6" id="branch_container" style="display: <?php echo (!empty($branches)) ? 'block' : 'none'; ?>;">
							<div class="mb-2">
								<label class="form-label fs-13 mb-1">Branch</label>
								<select name="branch_id" id="branch_id" class="form-select form-select-sm">
									<option value="">Select Branch</option>
									<?php if (!empty($branches)): ?>
										<?php foreach ($branches as $branch): ?>
											<option value="<?php echo $branch['id']; ?>" <?php echo (isset($uniform['branch_id']) && $uniform['branch_id'] == $branch['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($branch['branch_name']); ?></option>
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
											<option value="<?php echo $board['id']; ?>" <?php echo ($uniform['board_id'] == $board['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($board['board_name']); ?></option>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
								<?php echo form_error('board_id', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-xl-3 col-lg-4 col-md-6">
							<div class="mb-2">
								<label class="form-label fs-13 mb-1">Gender <span class="text-danger">*</span></label>
								<select name="gender" id="gender" class="form-select form-select-sm" required>
									<option value="">Select Gender</option>
									<option value="male" <?php echo ($uniform['gender'] == 'male') ? 'selected' : ''; ?>>Male</option>
									<option value="female" <?php echo ($uniform['gender'] == 'female') ? 'selected' : ''; ?>>Female</option>
									<option value="unisex" <?php echo ($uniform['gender'] == 'unisex') ? 'selected' : ''; ?>>Unisex</option>
								</select>
								<?php echo form_error('gender', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-xl-3 col-lg-4 col-md-6">
							<div class="mb-2">
								<label class="form-label fs-13 mb-1">Color</label>
								<select name="color" id="color" class="form-control form-control-sm select2-color" data-placeholder="Select a color">
									<option value=""></option>
									<option value="White" <?php echo set_select('color', 'White', $uniform['color'] == 'White'); ?>>White</option>
									<option value="Black" <?php echo set_select('color', 'Black', $uniform['color'] == 'Black'); ?>>Black</option>
									<option value="Navy Blue" <?php echo set_select('color', 'Navy Blue', $uniform['color'] == 'Navy Blue'); ?>>Navy Blue</option>
									<option value="Royal Blue" <?php echo set_select('color', 'Royal Blue', $uniform['color'] == 'Royal Blue'); ?>>Royal Blue</option>
									<option value="Sky Blue" <?php echo set_select('color', 'Sky Blue', $uniform['color'] == 'Sky Blue'); ?>>Sky Blue</option>
									<option value="Light Blue" <?php echo set_select('color', 'Light Blue', $uniform['color'] == 'Light Blue'); ?>>Light Blue</option>
									<option value="Dark Blue" <?php echo set_select('color', 'Dark Blue', $uniform['color'] == 'Dark Blue'); ?>>Dark Blue</option>
									<option value="Red" <?php echo set_select('color', 'Red', $uniform['color'] == 'Red'); ?>>Red</option>
									<option value="Maroon" <?php echo set_select('color', 'Maroon', $uniform['color'] == 'Maroon'); ?>>Maroon</option>
									<option value="Burgundy" <?php echo set_select('color', 'Burgundy', $uniform['color'] == 'Burgundy'); ?>>Burgundy</option>
									<option value="Green" <?php echo set_select('color', 'Green', $uniform['color'] == 'Green'); ?>>Green</option>
									<option value="Forest Green" <?php echo set_select('color', 'Forest Green', $uniform['color'] == 'Forest Green'); ?>>Forest Green</option>
									<option value="Olive Green" <?php echo set_select('color', 'Olive Green', $uniform['color'] == 'Olive Green'); ?>>Olive Green</option>
									<option value="Yellow" <?php echo set_select('color', 'Yellow', $uniform['color'] == 'Yellow'); ?>>Yellow</option>
									<option value="Gold" <?php echo set_select('color', 'Gold', $uniform['color'] == 'Gold'); ?>>Gold</option>
									<option value="Orange" <?php echo set_select('color', 'Orange', $uniform['color'] == 'Orange'); ?>>Orange</option>
									<option value="Purple" <?php echo set_select('color', 'Purple', $uniform['color'] == 'Purple'); ?>>Purple</option>
									<option value="Lavender" <?php echo set_select('color', 'Lavender', $uniform['color'] == 'Lavender'); ?>>Lavender</option>
									<option value="Pink" <?php echo set_select('color', 'Pink', $uniform['color'] == 'Pink'); ?>>Pink</option>
									<option value="Hot Pink" <?php echo set_select('color', 'Hot Pink', $uniform['color'] == 'Hot Pink'); ?>>Hot Pink</option>
									<option value="Brown" <?php echo set_select('color', 'Brown', $uniform['color'] == 'Brown'); ?>>Brown</option>
									<option value="Tan" <?php echo set_select('color', 'Tan', $uniform['color'] == 'Tan'); ?>>Tan</option>
									<option value="Beige" <?php echo set_select('color', 'Beige', $uniform['color'] == 'Beige'); ?>>Beige</option>
									<option value="Cream" <?php echo set_select('color', 'Cream', $uniform['color'] == 'Cream'); ?>>Cream</option>
									<option value="Ivory" <?php echo set_select('color', 'Ivory', $uniform['color'] == 'Ivory'); ?>>Ivory</option>
									<option value="Gray" <?php echo set_select('color', 'Gray', $uniform['color'] == 'Gray'); ?>>Gray</option>
									<option value="Charcoal" <?php echo set_select('color', 'Charcoal', $uniform['color'] == 'Charcoal'); ?>>Charcoal</option>
									<option value="Silver" <?php echo set_select('color', 'Silver', $uniform['color'] == 'Silver'); ?>>Silver</option>
									<option value="Platinum" <?php echo set_select('color', 'Platinum', $uniform['color'] == 'Platinum'); ?>>Platinum</option>
									<option value="Multi-Color" <?php echo set_select('color', 'Multi-Color', $uniform['color'] == 'Multi-Color'); ?>>Multi-Color</option>
									<option value="Patterned" <?php echo set_select('color', 'Patterned', $uniform['color'] == 'Patterned'); ?>>Patterned</option>
									<option value="Striped" <?php echo set_select('color', 'Striped', $uniform['color'] == 'Striped'); ?>>Striped</option>
									<option value="Checkered" <?php echo set_select('color', 'Checkered', $uniform['color'] == 'Checkered'); ?>>Checkered</option>
								</select>
							</div>
						</div>
						<div class="col-xl-3 col-lg-4 col-md-6">
							<div class="mb-2">
								<label class="form-label fs-13 mb-1">Product Name <span class="text-danger">*</span></label>
								<input type="text" name="product_name" id="product_name" class="form-control form-control-sm" value="<?php echo set_value('product_name', $uniform['product_name']); ?>" required>
								<?php echo form_error('product_name', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-xl-3 col-lg-4 col-md-6">
							<div class="mb-2">
								<label class="form-label fs-13 mb-1">ISBN/SKU</label>
								<input type="text" name="isbn" id="isbn" class="form-control form-control-sm" value="<?php echo set_value('isbn', $uniform['isbn']); ?>">
							</div>
						</div>
						<div class="col-xl-3 col-lg-4 col-md-6">
							<div class="mb-2">
								<label class="form-label fs-13 mb-1">Min Quantity <span class="text-danger">*</span></label>
								<input type="number" name="min_quantity" id="min_quantity" class="form-control form-control-sm" value="<?php echo set_value('min_quantity', $uniform['min_quantity']); ?>" min="1" required>
								<?php echo form_error('min_quantity', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-xl-3 col-lg-4 col-md-6">
							<div class="mb-2">
								<label class="form-label fs-13 mb-1">Days To Exchange</label>
								<input type="number" name="days_to_exchange" id="days_to_exchange" class="form-control form-control-sm" value="<?php echo set_value('days_to_exchange', $uniform['days_to_exchange']); ?>" min="0">
							</div>
						</div>
						<div class="col-xl-3 col-lg-4 col-md-6">
							<div class="mb-2">
								<label class="form-label fs-13 mb-1">Material <span class="text-danger">*</span></label>
								<div class="input-group">
									<select name="material_id" id="material_id" class="form-select form-select-sm" required>
										<option value="">Select Material</option>
										<?php if (!empty($materials)): ?>
											<?php foreach ($materials as $material): ?>
												<option value="<?php echo $material['id']; ?>" <?php echo ($uniform['material_id'] == $material['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($material['name']); ?></option>
											<?php endforeach; ?>
										<?php endif; ?>
									</select>
									<button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addMaterialModal" style="padding: 4px 8px;">
										<i class="isax isax-add"></i>
									</button>
								</div>
								<?php echo form_error('material_id', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
							</div>
						</div>
						<div class="col-xl-3 col-lg-4 col-md-6">
							<div class="mb-2">
								<label class="form-label fs-13 mb-1">Product Origin</label>
								<input type="text" name="product_origin" id="product_origin" class="form-control form-control-sm" value="<?php echo set_value('product_origin', $uniform['product_origin'] ? $uniform['product_origin'] : 'India'); ?>">
							</div>
						</div>
					</div>
					
					<!-- Description Fields -->
					<div class="row gx-3">
						<div class="col-12">
							<div class="mb-3">
								<label class="form-label">Product Description <span class="text-danger">*</span></label>
								<textarea name="product_description" id="product_description" class="form-control ckeditor" rows="5" required><?php echo set_value('product_description', $uniform['product_description']); ?></textarea>
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
					<button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#viewSizeChartsModal" title="View All Size Charts">
						<i class="isax isax-eye"></i> View Size Charts
					</button>
				</div>
				<div class="row gx-3">
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">Select Size Chart</label>
							<div class="input-group">
								<select name="size_chart_id" id="size_chart_id" class="select" form="uniform-form">
									<option value="">Select Size Chart</option>
									<?php if (!empty($size_charts)): ?>
										<?php foreach ($size_charts as $chart): ?>
											<option value="<?php echo $chart['id']; ?>" <?php echo (isset($uniform) && isset($uniform['size_chart_id']) && $uniform['size_chart_id'] == $chart['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($chart['name']); ?></option>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
								<button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addSizeChartModal" style="padding: 0.4rem 1rem;">
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
				</div>
				
				<!-- Size Prices Container -->
				<div id="sizePricesContainer" class="mt-4">
					<h6 class="mb-3">Size-wise Pricing</h6>
					<div id="sizePricesList">
						<?php 
						if (isset($size_prices) && !empty($size_prices)):
							foreach ($size_prices as $price):
						?>
							<div class="row gx-3 mb-3 align-items-end" data-size-id="<?php echo $price['size_id']; ?>">
								<div class="col-lg-3 col-md-4">
									<label class="form-label">Size</label>
									<input type="text" class="form-control" value="<?php echo htmlspecialchars($price['size_name']); ?>" readonly>
									<input type="hidden" name="size_prices[<?php echo $price['size_id']; ?>][size_id]" value="<?php echo $price['size_id']; ?>" form="uniform-form">
								</div>
								<div class="col-lg-4 col-md-4">
									<label class="form-label">MRP <span class="text-danger">*</span></label>
									<input type="number" name="size_prices[<?php echo $price['size_id']; ?>][mrp]" id="mrp_<?php echo $price['size_id']; ?>" class="form-control mrp-input" step="0.01" min="0" required form="uniform-form" value="<?php echo $price['mrp']; ?>" data-size-id="<?php echo $price['size_id']; ?>">
									<small class="text-danger mrp-error" id="mrp_error_<?php echo $price['size_id']; ?>" style="display:none;">MRP must be higher than Selling Price</small>
								</div>
								<div class="col-lg-4 col-md-4">
									<label class="form-label">Selling Price <span class="text-danger">*</span></label>
									<div class="input-group">
										<input type="number" name="size_prices[<?php echo $price['size_id']; ?>][selling_price]" id="selling_price_<?php echo $price['size_id']; ?>" class="form-control selling-price-input" step="0.01" min="0" required form="uniform-form" value="<?php echo $price['selling_price']; ?>" data-size-id="<?php echo $price['size_id']; ?>">
										<button type="button" class="btn btn-outline-danger" onclick="removeSizePriceRow(this)" title="Remove" style="padding: 0.4rem 1rem;">
											<i class="isax isax-close-circle"></i>
										</button>
									</div>
									<small class="text-danger selling-price-error" id="selling_price_error_<?php echo $price['size_id']; ?>" style="display:none;">Selling Price must be lower than MRP</small>
								</div>
							</div>
						<?php 
							endforeach;
						endif;
						?>
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
							<input type="number" name="packaging_length" id="packaging_length" class="form-control" form="uniform-form" value="<?php echo set_value('packaging_length', $uniform['packaging_length']); ?>" step="0.01" min="0">
						</div>
					</div>
					<div class="col-lg-3 col-md-6">
						<div class="mb-3">
							<label class="form-label">Width (in cm)</label>
							<input type="number" name="packaging_width" id="packaging_width" class="form-control" form="uniform-form" value="<?php echo set_value('packaging_width', $uniform['packaging_width']); ?>" step="0.01" min="0">
						</div>
					</div>
					<div class="col-lg-3 col-md-6">
						<div class="mb-3">
							<label class="form-label">Height (in cm)</label>
							<input type="number" name="packaging_height" id="packaging_height" class="form-control" form="uniform-form" value="<?php echo set_value('packaging_height', $uniform['packaging_height']); ?>" step="0.01" min="0">
						</div>
					</div>
					<div class="col-lg-3 col-md-6">
						<div class="mb-3">
							<label class="form-label">Weight (in gm)</label>
							<input type="number" name="packaging_weight" id="packaging_weight" class="form-control" form="uniform-form" value="<?php echo set_value('packaging_weight', $uniform['packaging_weight']); ?>" step="0.01" min="0">
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
							<select name="gst_percentage" id="gst_percentage" class="form-control" form="uniform-form" required>
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
								<option value="<?php echo $gst_val; ?>" <?php echo $selected; ?>><?php echo $gst_val; ?>%</option>
								<?php endforeach; ?>
								<?php 
								// If custom GST value exists (not in standard list), add it as an option
								if (!empty($current_gst) && !in_array(floatval($current_gst), $gst_options)): 
								?>
								<option value="<?php echo htmlspecialchars($current_gst); ?>" selected><?php echo htmlspecialchars($current_gst); ?>%</option>
								<?php endif; ?>
							</select>
							<?php echo form_error('gst_percentage', '<div class="text-danger fs-13 mt-1">', '</div>'); ?>
						</div>
					</div>
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">HSN</label>
							<input type="text" name="hsn" id="hsn" class="form-control" form="uniform-form" value="<?php echo set_value('hsn', $uniform['hsn']); ?>">
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
							<select name="school_commission_type"
									id="school_commission_type"
									class="select">
								<option value="">Select Commission Type</option>
								<option value="fixed" <?= ($commissionType === 'fixed') ? 'selected' : ''; ?>>Fixed</option>
								<option value="percentage" <?= ($commissionType === 'percentage') ? 'selected' : ''; ?>>Percentage</option>
							</select>
						</div>
					</div>

					<!-- Fixed -->
					<div class="col-lg-6 col-md-6" id="fixed_commission_container" style="display:none;">
						<div class="mb-3">
							<label class="form-label">Fixed Commission Amount (₹)</label>
							<input type="number"
								   id="fixed_commission_value"
								   name="school_commission_value"
								   class="form-control"
								   step="0.01"
								   min="0"
								   value="<?= ($commissionType === 'fixed') ? $commissionValue : ''; ?>"
								   disabled>
						</div>
					</div>

					<!-- Percentage -->
					<div class="col-lg-6 col-md-6" id="percentage_commission_container" style="display:none;">
						<div class="mb-3">
							<label class="form-label">Commission Percentage (%)</label>
							<input type="number"
								   id="percentage_commission_value"
								   name="school_commission_value"
								   class="form-control"
								   step="0.01"
								   min="0"
								   max="100"
								   value="<?= ($commissionType === 'percentage') ? $commissionValue : ''; ?>"
								   disabled>
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
							<textarea name="manufacturer_details" id="manufacturer_details" class="form-control ckeditor" rows="5"><?php echo set_value('manufacturer_details', $uniform['manufacturer_details']); ?></textarea>
						</div>
					</div>
					<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Packer's Details</label>
							<textarea name="packer_details" id="packer_details" class="form-control ckeditor" rows="5"><?php echo set_value('packer_details', $uniform['packer_details']); ?></textarea>
						</div>
					</div>
					<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Customer Details</label>
							<textarea name="customer_details" id="customer_details" class="form-control ckeditor" rows="5"><?php echo set_value('customer_details', $uniform['customer_details']); ?></textarea>
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
							<input type="text" name="meta_title" id="meta_title" class="form-control" form="uniform-form" value="<?php echo set_value('meta_title', $uniform['meta_title']); ?>">
							</div>
						</div>
					<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Meta Keywords</label>
							<textarea name="meta_keywords" id="meta_keywords" class="form-control" form="uniform-form" rows="3"><?php echo set_value('meta_keywords', $uniform['meta_keywords']); ?></textarea>
					</div>
					</div>
					<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Meta Description</label>
							<textarea name="meta_description" id="meta_description" class="form-control" form="uniform-form" rows="3"><?php echo set_value('meta_description', $uniform['meta_description']); ?></textarea>
							</div>
					</div>
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">Status</label>
							<select name="status" id="status" class="select" form="uniform-form">
								<option value="active" <?php echo ($uniform['status'] == 'active') ? 'selected' : ''; ?>>Active</option>
								<option value="inactive" <?php echo ($uniform['status'] == 'inactive') ? 'selected' : ''; ?>>Inactive</option>
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="border-top my-3 pt-3">
	<div class="d-flex align-items-center justify-content-end gap-2">
		<a href="<?php echo base_url('products/uniforms'); ?>" class="btn btn-outline">Cancel</a>
		<button type="submit" form="uniform-form" class="btn btn-primary" onclick="return handleFormSubmit();">Update Uniform</button>
	</div>
</div>
<?php echo form_close(); ?>

<!-- Add Uniform Type Modal -->
<div class="modal fade" id="addUniformTypeModal" tabindex="-1" aria-labelledby="addUniformTypeModalLabel" aria-hidden="true">
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
						<textarea name="description" id="uniform_type_description" class="form-control" rows="3"></textarea>
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
<div class="modal fade" id="viewSizeChartsModal" tabindex="-1" aria-labelledby="viewSizeChartsModalLabel" aria-hidden="true">
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
<div class="modal fade" id="addSizeChartModal" tabindex="-1" aria-labelledby="addSizeChartModalLabel" aria-hidden="true">
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
						<textarea name="description" id="size_chart_description" class="form-control" rows="3"></textarea>
					</div>
					<div class="mb-3">
						<label class="form-label">Sizes <span class="text-danger">*</span></label>
						<small class="text-muted d-block mb-2">Enter sizes separated by commas (e.g., S, M, L, XL, XXL) or one per line</small>
						<textarea name="sizes" id="size_chart_sizes" class="form-control" rows="5" placeholder="S, M, L, XL, XXL" required></textarea>
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

<style>
.input-group {
	flex-wrap: nowrap !important;
}

.card .card-body {
    padding: 1rem !important;
}
</style>


<script>
document.addEventListener('DOMContentLoaded', function () {

	const typeSelect   = document.getElementById('school_commission_type');
	const fixedBox     = document.getElementById('fixed_commission_container');
	const percentBox   = document.getElementById('percentage_commission_container');
	const fixedInput   = document.getElementById('fixed_commission_value');
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
<script>

CKEDITOR.replace('product_description', {
    removePlugins: 'link,about',
    removeButtons: 'Subscript,Superscript,Image',
    allowedContent: true
  });
// Initialize CKEditor after page loads
window.addEventListener('load', function() {
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

document.addEventListener('DOMContentLoaded', function() {
	
	// School change handler - handle both regular select and Select2
	var schoolSelect = document.getElementById('school_id');
	if (schoolSelect) {
		// Check if Select2 is initialized
		var $schoolSelect = $('#school_id');
		if ($schoolSelect.length && $schoolSelect.hasClass('select2-hidden-accessible')) {
			// Use jQuery change event for Select2
			$schoolSelect.on('change', function() {
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
			schoolSelect.addEventListener('change', function() {
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
	
	// Size Chart change handler (using jQuery for Select2)
	$(document).ready(function() {
		// Wait for Select2 to initialize
		setTimeout(function() {
			$('#size_chart_id').on('change', function() {
				var sizeChartId = $(this).val();
				console.log('Size chart changed to:', sizeChartId);
				if (sizeChartId) {
					loadSizes(sizeChartId);
				} else {
					// Clear sizes if no chart selected
					$('#size_id').html('<option value="">Select Size</option>').trigger('change');
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
	$(document).ready(function() {
		// Wait for Select2 to initialize
		setTimeout(function() {
			$('#size_id').on('change', function() {
				var sizeId = $(this).val();
				var sizeName = $(this).find('option:selected').text();
				if (sizeId && sizeName !== 'Select Size') {
					addSizePriceRow(sizeId, sizeName);
					// Reset the select to allow selecting another size
					$(this).val('').trigger('change');
				}
			});
		}, 500);
	});
	
	// Image preview for edit forms - image-sortable.js skips initialization when existing images are present
	// So we need custom handler for new file uploads in edit forms
	var imageInput = document.getElementById('images');
	if (imageInput) {
		// Check if this is an edit form (has existing images)
		var preview = document.getElementById('image-preview');
		var hasExistingImages = preview && preview.querySelectorAll('.existing-image').length > 0;
		
		if (hasExistingImages) {
			// Edit form - add custom handler for new uploads only
			imageInput.addEventListener('change', function(e) {
				if (!e.target.files || e.target.files.length === 0) return;
				
				var preview = document.getElementById('image-preview');
				if (!preview) return;
				
				// Remove any previously added new preview images (not existing ones)
				var newPreviews = preview.querySelectorAll('.image-preview-item:not(.existing-image)');
				newPreviews.forEach(function(item) {
					item.remove();
				});
				
				// Process new files
				for (var i = 0; i < e.target.files.length; i++) {
					var file = e.target.files[i];
					if (file.type.startsWith('image/')) {
						(function(file) {
							var reader = new FileReader();
							reader.onload = function(e) {
								var wrapper = document.createElement('div');
								wrapper.className = 'image-preview-item';
								wrapper.style.cssText = 'position: relative; display: inline-block; margin: 3px; cursor: move; vertical-align: top;';
								
								var img = document.createElement('img');
								img.src = e.target.result;
								img.style.cssText = 'width: 120px; height: 120px; object-fit: cover; border: 2px solid #ddd; border-radius: 4px; display: block;';
								
								wrapper.appendChild(img);
								preview.appendChild(wrapper);
							};
							reader.readAsDataURL(file);
						})(file);
					}
				}
			});
		}
		// If no existing images, image-sortable.js will handle it
	}
	
	// Add validation event listeners to existing price rows
	var existingMrpInputs = document.querySelectorAll('.mrp-input');
	existingMrpInputs.forEach(function(mrpInput) {
		var sizeId = mrpInput.getAttribute('data-size-id');
		if (sizeId) {
			var sellingPriceInput = document.getElementById('selling_price_' + sizeId);
			if (sellingPriceInput) {
				mrpInput.addEventListener('input', validatePriceRow);
				mrpInput.addEventListener('blur', validatePriceRow);
				sellingPriceInput.addEventListener('input', validatePriceRow);
				sellingPriceInput.addEventListener('blur', validatePriceRow);
			}
		}
	});
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
				data.branches.forEach(function(branch) {
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
				data.boards.forEach(function(board) {
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
			setTimeout(function() {
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
			setTimeout(function() {
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
	var sizes = sizesText.split(/[,\n]/).map(function(size) {
		return size.trim();
	}).filter(function(size) {
		return size.length > 0;
	});
	
	if (sizes.length === 0) {
		alert('Please enter at least one valid size');
		return;
	}
	
	var formData = new FormData();
	formData.append('name', name);
	formData.append('description', description);
	sizes.forEach(function(size, index) {
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
			setTimeout(function() {
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
			data.sizes.forEach(function(size) {
				$sizeSelect.append($('<option></option>').attr('value', size.id).text(size.name));
			});
		} else {
			console.warn('No sizes found for size chart:', sizeChartId);
		}
		
		// Trigger Select2 update
		$sizeSelect.trigger('change');
	})
	.catch(error => {
		console.error('Error loading sizes:', error);
		alert('Failed to load sizes. Please try again.');
	});
}

function addSizePriceRow(sizeId, sizeName) {
	// Check if this size is already added
	var existingRow = document.querySelector('[data-size-id="' + sizeId + '"]');
	if (existingRow) {
		alert('This size has already been added. Please remove it first if you want to change the pricing.');
		return;
	}
	
	var container = document.getElementById('sizePricesList');
	var row = document.createElement('div');
	row.className = 'row gx-3 mb-3 align-items-end';
	row.setAttribute('data-size-id', sizeId);
	row.innerHTML = `
		<div class="col-lg-1 col-md-4">
			<label class="form-label">Size</label>
			<input type="text" class="form-control" value="${sizeName}" readonly>
			<input type="hidden" name="size_prices[${sizeId}][size_id]" value="${sizeId}" form="uniform-form">
		</div>
		<div class="col-lg-5 col-md-4">
			<label class="form-label">MRP <span class="text-danger">*</span></label>
			<input type="number" name="size_prices[${sizeId}][mrp]" id="mrp_${sizeId}" class="form-control mrp-input" step="0.01" min="0" required form="uniform-form" placeholder="0.00" data-size-id="${sizeId}">
			<small class="text-danger mrp-error" id="mrp_error_${sizeId}" style="display:none;">MRP must be higher than Selling Price</small>
		</div>
		<div class="col-lg-6 col-md-4">
			<label class="form-label">Selling Price <span class="text-danger">*</span></label>
			<div class="input-group">
				<input type="number" name="size_prices[${sizeId}][selling_price]" id="selling_price_${sizeId}" class="form-control selling-price-input" step="0.01" min="0" required form="uniform-form" placeholder="0.00" data-size-id="${sizeId}">
				<button type="button" class="btn btn-outline-danger" onclick="removeSizePriceRow(this)" title="Remove" style="padding: 0.4rem 1rem;">
					<i class="isax isax-close-circle"></i>
				</button>
			</div>
			<small class="text-danger selling-price-error" id="selling_price_error_${sizeId}" style="display:none;">Selling Price must be lower than MRP</small>
		</div>
	`;
	container.appendChild(row);
	
	// Add validation event listeners
	var mrpInput = document.getElementById('mrp_' + sizeId);
	var sellingPriceInput = document.getElementById('selling_price_' + sizeId);
	
	if (mrpInput && sellingPriceInput) {
		mrpInput.addEventListener('input', validatePriceRow);
		mrpInput.addEventListener('blur', validatePriceRow);
		sellingPriceInput.addEventListener('input', validatePriceRow);
		sellingPriceInput.addEventListener('blur', validatePriceRow);
	}
}

function validatePriceRow(e) {
	var sizeId = e.target.getAttribute('data-size-id');
	if (!sizeId) return;
	
	var mrpInput = document.getElementById('mrp_' + sizeId);
	var sellingPriceInput = document.getElementById('selling_price_' + sizeId);
	var mrpError = document.getElementById('mrp_error_' + sizeId);
	var sellingPriceError = document.getElementById('selling_price_error_' + sizeId);
	
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
		if (mrp <= sellingPrice) {
			if (mrpError) mrpError.style.display = 'block';
			if (sellingPriceError) sellingPriceError.style.display = 'block';
			mrpInput.classList.add('is-invalid');
			sellingPriceInput.classList.add('is-invalid');
			return false;
		}
	}
	
	return true;
}

// Function to update image_order hidden input (global scope for form submit)
function updateImageOrder() {
	const orderInput = document.getElementById('image_order');
	if (!orderInput) return;
	
	const preview = document.getElementById('image-preview');
	if (!preview) return;
	
	// Get all existing images (not deleted ones)
	const existingImages = Array.from(preview.querySelectorAll('.existing-image:not([style*="display: none"])'));
	const imageIds = existingImages.map(function(img) {
		return img.getAttribute('data-image-id');
	}).filter(function(id) {
		return id && id !== '';
	});
	
	orderInput.value = imageIds.join(',');
}

function handleFormSubmit() {
	// Update image order before submitting
	updateImageOrder();
	
	// Validate prices first
	if (!validateAllPrices()) {
		return false;
	}
	
	// Form will submit normally - files should be included
	return true;
}

function validateAllPrices() {
	var isValid = true;
	var mrpInputs = document.querySelectorAll('.mrp-input');
	
	mrpInputs.forEach(function(mrpInput) {
		var sizeId = mrpInput.getAttribute('data-size-id');
		if (sizeId) {
			var event = { target: mrpInput };
			if (!validatePriceRow(event)) {
				isValid = false;
			}
		}
	});
	
	return isValid;
}

function removeSizePriceRow(button) {
	var row = button.closest('.row');
	if (row) {
		row.remove();
	}
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
		preview.addEventListener('dragover', function(e) {
			e.preventDefault();
			e.dataTransfer.dropEffect = 'move';
			
			const afterElement = getDragAfterElement(preview, e.clientY);
			const existingImages = preview.querySelectorAll('.existing-image:not([style*="display: none"])');
			
			if (afterElement == null) {
				existingImages.forEach(function(img) {
					img.style.border = '';
				});
			} else {
				existingImages.forEach(function(img) {
					img.style.border = '';
					if (img === afterElement) {
						img.style.border = '2px dashed #007bff';
					}
				});
			}
		});
		
		preview.addEventListener('drop', function(e) {
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
			preview.querySelectorAll('.existing-image').forEach(function(img) {
				img.style.border = '';
			});
		});
		
		// Helper function to find element after dragged position
		function getDragAfterElement(container, y) {
			const existingImages = Array.from(container.querySelectorAll('.existing-image:not([style*="display: none"])'));
			
			return existingImages.reduce(function(closest, child) {
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
		existingImages.forEach(function(img) {
			img.draggable = true;
			img.addEventListener('dragstart', function(e) {
				draggedElement = this;
				this.style.opacity = '0.5';
				e.dataTransfer.effectAllowed = 'move';
			});
			img.addEventListener('dragend', function() {
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
document.addEventListener('DOMContentLoaded', function() {
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
	}
});
</script>



