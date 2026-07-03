<!-- Start Breadcrumb -->
<div class="d-flex d-block align-items-center justify-content-between flex-wrap gap-2 mb-2">
	<div>
		<h6 class="mb-0 fs-14"><a href="<?php echo base_url('products/cloths'); ?>"><i
					class="isax isax-arrow-left me-1"></i>Add New Cloth</a></h6>
	</div>
</div>
<!-- End Breadcrumb -->
<?php echo form_open_multipart(isset($cloth) ? base_url('products/cloths/edit/' . $cloth['id']) : base_url('products/cloths/add'), array('id' => 'cloth-form')); ?>
<input type="hidden" name="size_prices_json" id="size_prices_json" value="">
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
							<label class="form-label fs-13 mb-1">Images (Size: 440px * 530px) <span
									class="text-danger">*</span></label>
							<input type="file" name="images[]" id="images" class="form-control form-control-sm"
								form="cloth-form" accept="image/*" multiple <?php echo isset($cloth) ? '' : 'required'; ?>>
							<small class="text-muted fs-12">Multiple images: 440px × 530px. Drag to reorder.</small>
							<div id="image-preview" class="mt-3 image-sortable-container"></div>
							<?php if (isset($cloth_images) && !empty($cloth_images)): ?>
								<div class="mt-2 d-flex flex-wrap gap-2" id="existing-cloth-images">
									<?php foreach ($cloth_images as $img): ?>
										<div class="border rounded p-1 existing-image-item" data-image-id="<?php echo (int)$img['id']; ?>">
											<img src="<?php echo get_vendor_domain_url() . '/' . ltrim($img['image_path'], '/'); ?>" alt="" style="width:80px;height:96px;object-fit:cover;">
											<div class="form-check mt-1">
												<input class="form-check-input" type="radio" name="main_image_id" value="<?php echo (int)$img['id']; ?>" <?php echo !empty($img['is_main']) ? 'checked' : ''; ?>>
												<label class="form-check-label fs-12">Main</label>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>
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
		<div class="card mb-2">
			<div class="card-header py-2">
				<h6 class="mb-0 fs-14">Cloth Details</h6>
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
							<label class="form-label fs-13 mb-1">Cloth Type <span class="text-danger">*</span></label>
							<div class="d-flex gap-1">
								<select name="cloth_type_id" id="cloth_type_id" class="form-select form-select-sm"
									style="width: 100%;" required>
									<option value="">Select Cloth Type</option>
									<?php if (!empty($cloth_types)): ?>
										<?php foreach ($cloth_types as $type): ?>
											<option value="<?php echo $type['id']; ?>" <?php echo (isset($cloth) && $cloth['cloth_type_id'] == $type['id']) ? 'selected' : ''; ?>>
												<?php echo htmlspecialchars($type['name']); ?>
											</option>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
								<button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
									data-bs-target="#addClothTypeModal" style="padding: 4px 8px;">
									<i class="isax isax-add"></i>
								</button>
							</div>
							<?php echo form_error('cloth_type_id', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
						</div>
					</div>
					<div class="col-xl-3 col-lg-4 col-md-6">
						<div class="mb-2">
							<label class="form-label fs-13 mb-1">Gender</label>
							<select name="gender[]" id="gender" class="form-select form-select-sm select2" multiple
								data-placeholder="Select Gender" style="width: 100%;">
								<?php $selected_genders = (isset($cloth) && !empty($cloth['gender'])) ? explode(',', $cloth['gender']) : array(); ?>
								<option value="male" <?php echo in_array('male', $selected_genders) ? 'selected' : ''; ?>>Male</option>
								<option value="female" <?php echo in_array('female', $selected_genders) ? 'selected' : ''; ?>>Female</option>
								<option value="unisex" <?php echo in_array('unisex', $selected_genders) ? 'selected' : ''; ?>>Unisex</option>
							</select>
							<?php echo form_error('gender[]', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
						</div>
					</div>
					<div class="col-xl-3 col-lg-4 col-md-6">
						<div class="mb-2">
							<label class="form-label fs-13 mb-1">Color</label>
							<div class="d-flex gap-1">
								<select name="color_id" id="color_id" class="form-select form-select-sm" style="width: 100%;">
									<option value="">Select Color</option>
									<?php if (!empty($colors)): ?>
										<?php foreach ($colors as $color): ?>
											<option value="<?php echo (int) $color['id']; ?>" <?php echo (isset($cloth) && !empty($cloth['color_id']) && (int) $cloth['color_id'] === (int) $color['id']) ? 'selected' : ''; ?>>
												<?php echo htmlspecialchars($color['name']); ?>
											</option>
										<?php endforeach; ?>
									<?php endif; ?>
								</select>
								<button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
									data-bs-target="#addColorModal" style="padding: 4px 8px;">
									<i class="isax isax-add"></i>
								</button>
							</div>
						</div>
					</div>
					<div class="col-xl-3 col-lg-4 col-md-6">
						<div class="mb-2">
							<label class="form-label fs-13 mb-1">Product Name <span class="text-danger">*</span></label>
							<input type="text" name="product_name" id="product_name"
								class="form-control form-control-sm"
								value="<?php echo set_value('product_name', isset($cloth) ? $cloth['product_name'] : ''); ?>"
								required>
							<?php echo form_error('product_name', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
						</div>
					</div>
					<div class="col-xl-3 col-lg-4 col-md-6">
						<div class="mb-2">
							<label class="form-label fs-13 mb-1">ISBN/SKU</label>
							<input type="text" name="isbn" id="isbn" class="form-control form-control-sm"
								value="<?php echo set_value('isbn', isset($cloth) ? $cloth['isbn'] : ''); ?>">
						</div>
					</div>
					<div class="col-xl-3 col-lg-4 col-md-6">
						<div class="mb-2">
							<label class="form-label fs-13 mb-1">Min Quantity <span class="text-danger">*</span></label>
							<input type="number" name="min_quantity" id="min_quantity"
								class="form-control form-control-sm"
								value="<?php echo set_value('min_quantity', isset($cloth) ? $cloth['min_quantity'] : 1); ?>"
								min="1" required>
							<?php echo form_error('min_quantity', '<div class="text-danger fs-12 mt-1">', '</div>'); ?>
						</div>
					</div>
					<div class="col-xl-3 col-lg-4 col-md-6">
						<div class="mb-2">
							<label class="form-label fs-13 mb-1">Days To Exchange</label>
							<input type="number" name="days_to_exchange" id="days_to_exchange"
								class="form-control form-control-sm"
								value="<?php echo set_value('days_to_exchange', isset($cloth) ? $cloth['days_to_exchange'] : ''); ?>"
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
											<option value="<?php echo $material['id']; ?>" <?php echo (isset($cloth) && $cloth['material_id'] == $material['id']) ? 'selected' : ''; ?>>
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
								value="<?php echo set_value('product_origin', isset($cloth) ? $cloth['product_origin'] : 'India'); ?>">
						</div>
					</div>
					<div class="col-xl-3 col-lg-4 col-md-6">
						<div class="mb-2">
							<label class="form-label fs-13 mb-1">Uniform Tag</label>
							<select name="cloth_tag[]" id="cloth_tag" class="form-select form-select-sm select2" multiple
								data-placeholder="Select Uniform Tag" style="width: 100%;">
								<?php $selected_tags = (isset($cloth) && !empty($cloth['cloth_tag'])) ? explode(',', $cloth['cloth_tag']) : array(); ?>
								<option value="regular" <?php echo in_array('regular', $selected_tags) ? 'selected' : ''; ?>>Regular</option>
								<option value="PT" <?php echo in_array('PT', $selected_tags) ? 'selected' : ''; ?>>PT Uniform</option>
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
								rows="5"><?php echo set_value('product_description', isset($cloth) ? $cloth['description'] : ''); ?></textarea>
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
					<button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal"
						data-bs-target="#viewSizeChartsModal" title="View All Size Charts">
						<i class="isax isax-eye"></i> View Size Charts
					</button>
				</div>
				<div class="row gx-3">
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">Select Size Chart</label>
							<div class="d-flex gap-1">
								<select name="size_chart_id" id="size_chart_id" class="select" form="cloth-form"
									style="width: 100%;">
									<option value="">Select Size Chart</option>
									<?php if (!empty($size_charts)): ?>
										<?php foreach ($size_charts as $chart): ?>
											<option value="<?php echo $chart['id']; ?>" <?php echo (isset($cloth) && isset($cloth['size_chart_id']) && $cloth['size_chart_id'] == $chart['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($chart['name']); ?></option>
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
							<select name="size_id" id="size_id" class="select" form="cloth-form">
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
									form="cloth-form" style="width: 100%;">
									<option value="">None</option>
									<?php foreach ($master_size_charts as $msc): ?>
										<option value="<?php echo (int) $msc['id']; ?>" <?php echo set_select('master_size_chart_id', (string) $msc['id']); ?>>
											<?php echo htmlspecialchars($msc['name']); ?>
										</option>
									<?php endforeach; ?>
								</select>
								<small class="text-muted d-block mt-1">Managed under Catalog → Master Size Charts
									(storefront size chart button).</small>
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
								form="cloth-form"
								value="<?php echo set_value('packaging_length', isset($cloth) ? $cloth['length'] : ''); ?>"
								step="0.01" min="0">
						</div>
					</div>
					<div class="col-lg-3 col-md-6">
						<div class="mb-3">
							<label class="form-label">Width (in cm)</label>
							<input type="number" name="packaging_width" id="packaging_width" class="form-control"
								form="cloth-form"
								value="<?php echo set_value('packaging_width', isset($cloth) ? $cloth['width'] : ''); ?>"
								step="0.01" min="0">
						</div>
					</div>
					<div class="col-lg-3 col-md-6">
						<div class="mb-3">
							<label class="form-label">Height (in cm)</label>
							<input type="number" name="packaging_height" id="packaging_height" class="form-control"
								form="cloth-form"
								value="<?php echo set_value('packaging_height', isset($cloth) ? $cloth['height'] : ''); ?>"
								step="0.01" min="0">
						</div>
					</div>
					<div class="col-lg-3 col-md-6">
						<div class="mb-3">
							<label class="form-label">Weight (in gm)</label>
							<input type="number" name="packaging_weight" id="packaging_weight" class="form-control"
								form="cloth-form"
								value="<?php echo set_value('packaging_weight', isset($cloth) ? $cloth['weight'] : ''); ?>"
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
							<select name="gst_percentage" id="gst_percentage" class="form-control" form="cloth-form"
								required>
								<option value="">Select GST %</option>
								<?php
								$current_gst = set_value('gst_percentage', isset($cloth) ? floatval($cloth['gst']) : '');
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
							<input type="text" name="hsn" id="hsn" class="form-control" form="cloth-form"
								value="<?php echo set_value('hsn', isset($cloth) ? $cloth['hsn'] : ''); ?>">
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
								rows="5"><?php echo set_value('manufacturer_details', isset($cloth) ? $cloth['manufacturer_details'] : ''); ?></textarea>
						</div>
					</div>
					<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Packer's Details</label>
							<textarea name="packer_details" id="packer_details" class="form-control ckeditor"
								rows="5"><?php echo set_value('packer_details', isset($cloth) ? $cloth['packer_details'] : ''); ?></textarea>
						</div>
					</div>
					<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Customer Details</label>
							<textarea name="customer_details" id="customer_details" class="form-control ckeditor"
								rows="5"><?php echo set_value('customer_details', isset($cloth) ? $cloth['customer_details'] : ''); ?></textarea>
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
								form="cloth-form"
								value="<?php echo set_value('meta_title', isset($cloth) ? $cloth['meta_title'] : ''); ?>">
						</div>
					</div>
					<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Meta Keywords</label>
							<textarea name="meta_keywords" id="meta_keywords" class="form-control" form="cloth-form"
								rows="3"><?php echo set_value('meta_keywords', isset($cloth) ? $cloth['meta_keywords'] : ''); ?></textarea>
						</div>
					</div>
					<div class="col-12">
						<div class="mb-3">
							<label class="form-label">Meta Description</label>
							<textarea name="meta_description" id="meta_description" class="form-control"
								form="cloth-form"
								rows="3"><?php echo set_value('meta_description', isset($cloth) ? $cloth['meta_description'] : ''); ?></textarea>
						</div>
					</div>
					<div class="col-lg-6 col-md-6">
						<div class="mb-3">
							<label class="form-label">Status</label>
							<select name="status" id="status" class="select" form="cloth-form">
								<option value="active" <?php echo set_select('status', 'active', (isset($cloth) && $cloth['status'] == 'active') ? TRUE : FALSE); ?>>Active</option>
								<option value="inactive" <?php echo set_select('status', 'inactive', (isset($cloth) && $cloth['status'] == 'inactive') ? TRUE : FALSE); ?>>Inactive</option>
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
		<a href="<?php echo base_url('products/cloths'); ?>" class="btn btn-outline">Cancel</a>
		<button type="submit" form="cloth-form" class="btn btn-primary"
			onclick="return validateAllPrices();"><?php echo isset($cloth) ? 'Update Cloth' : 'Create Cloth'; ?></button>
	</div>
</div>
<?php echo form_close(); ?>

<!-- Add Cloth Type Modal -->
<div class="modal fade" id="addClothTypeModal" tabindex="-1" aria-labelledby="addClothTypeModalLabel"
	aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addClothTypeModalLabel">Add Cloth Type</h5>
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
<div class="modal fade" id="addColorModal" tabindex="-1" aria-labelledby="addColorModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addColorModalLabel">Add Color</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<form id="addColorForm">
					<div class="mb-3">
						<label class="form-label">Name <span class="text-danger">*</span></label>
						<input type="text" name="name" id="color_name" class="form-control" required>
					</div>
					<div class="mb-3">
						<label class="form-label">Color Code (Hex)</label>
						<input type="color" name="color_code" id="color_code" class="form-control form-control-color" value="#000000">
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary" onclick="addColor()">Add Color</button>
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

	.bg-size-even td {
		background-color: #ffffff !important;
	}

	.bg-size-odd td {
		background-color: #e5deff !important;
	}

	tr.size-group-start td {
		border-top: 2px solid #7539ff !important;
	}
</style>

<script src="<?php echo base_url('assets/ckeditor/ckeditor.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/image-sortable.js'); ?>"></script>
<?php
$initial_sizes = array();
$initial_prices = array();
if (isset($size_prices) && !empty($size_prices)) {
	$unique_sizes = array();
	foreach ($size_prices as $price) {
		$unique_sizes[(int) $price['size_id']] = array(
			'id' => (int) $price['size_id'],
			'name' => $price['size_name'],
		);
		$key = '0_' . (int) $price['size_id'];
		$initial_prices[$key] = array(
			'mrp' => $price['mrp'],
			'selling_price' => $price['selling_price'],
		);
	}
	$initial_sizes = array_values($unique_sizes);
}
?>
<script>
	// Global state for size-wise pricing
	var added_sizes = <?php echo json_encode($initial_sizes); ?>;
	var priceValues = <?php echo json_encode($initial_prices); ?>;

	// Initialize CKEditor after page loads
	window.addEventListener('load', function () {
		function initCKEditor() {
			if (typeof CKEDITOR !== 'undefined') {
				// Destroy existing instances if any
				if (CKEDITOR.instances['product_description']) {
					CKEDITOR.instances['product_description'].destroy();
				}
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
				var productDesc = document.getElementById('product_description');
				var manufacturerDetails = document.getElementById('manufacturer_details');
				var packerDetails = document.getElementById('packer_details');
				var customerDetails = document.getElementById('customer_details');

				if (productDesc) {
					CKEDITOR.replace('product_description');
				}
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

		// Size Chart change handler (using jQuery for Select2)
		$(document).ready(function () {
			// Wait for Select2 to initialize
			setTimeout(function () {
				var previousSizeChartId = <?php echo isset($cloth) && isset($cloth['size_chart_id']) && $cloth['size_chart_id'] ? (int) $cloth['size_chart_id'] : '""'; ?>;
				$('#size_chart_id').on('change', function () {
					var sizeChartId = $(this).val();
					console.log('Size chart changed to:', sizeChartId);
					if (sizeChartId == previousSizeChartId) {
						return;
					}
					previousSizeChartId = sizeChartId;
					if (sizeChartId) {
						// Clear stale rows and state when switching chart
						added_sizes = [];
						priceValues = {};
						$('#sizePricesList').empty();
						$('#size_id').html('<option value="">Select Size</option>').val('').trigger('change');
						loadSizes(sizeChartId);
					} else {
						// Clear state if no chart selected
						added_sizes = [];
						priceValues = {};
						$('#size_id').html('<option value="">Select Size</option>').trigger('change');
						$('#sizePricesList').empty();
					}
				});
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

		<?php if (isset($cloth) && !empty($cloth['size_chart_id'])): ?>
			setTimeout(function () {
				loadSizes(<?php echo (int) $cloth['size_chart_id']; ?>);
				renderPricingGroups(true);
			}, 600);
		<?php else: ?>
			renderPricingGroups(true);
		<?php endif; ?>

		// Image preview is handled by image-sortable.js script
		// No custom handler needed - image-sortable.js handles file input changes, drag-and-drop, and main image selection

		// Intercept form submission to populate size_prices_json
		$('#cloth-form').on('submit', function (e) {
			var sizePricesJson = prepareSizePricesJson();
			$('#size_prices_json').val(sizePricesJson);
		});
	});

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

		fetch('<?php echo base_url('products/cloths/add_cloth_type'); ?>', {
			method: 'POST',
			body: formData
		})
			.then(response => response.json())
			.then(data => {
				if (data.status === 'success') {
					var select = document.getElementById('cloth_type_id');
					var $select = $('#cloth_type_id');

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
							placeholder: 'Select Cloth Type',
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

		fetch('<?php echo base_url('products/cloths/add_material'); ?>', {
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
						// Destroy Select2, add option, then reinitialize
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

	function addColor() {
		var name = document.getElementById('color_name').value;
		var colorCode = document.getElementById('color_code').value;

		if (!name) {
			alert('Please enter a color name');
			return;
		}

		var formData = new FormData();
		formData.append('name', name);
		formData.append('color_code', colorCode);

		fetch('<?php echo base_url('products/cloths/add_color'); ?>', {
			method: 'POST',
			body: formData
		})
			.then(response => response.json())
			.then(data => {
				if (data.status === 'success') {
					var select = document.getElementById('color_id');
					var $select = $('#color_id');
					if ($select.length && $select.hasClass('select2-hidden-accessible')) {
						$select.select2('destroy');
					}
					var option = document.createElement('option');
					option.value = data.id;
					option.textContent = data.name;
					option.selected = true;
					select.appendChild(option);
					if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
						$select.select2({
							theme: 'bootstrap-5',
							placeholder: 'Select Color',
							allowClear: true,
							width: '100%'
						});
					}
					document.getElementById('addColorForm').reset();
					document.getElementById('color_code').value = '#000000';
					bootstrap.Modal.getInstance(document.getElementById('addColorModal')).hide();
				} else {
					alert(data.message || 'Failed to add color');
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

		fetch('<?php echo base_url('products/cloths/add_size_chart'); ?>', {
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
						// Destroy Select2, add option, then reinitialize
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
		var url = '<?php echo base_url('products/cloths/get_sizes'); ?>?size_chart_id=' + encodeURIComponent(sizeChartId);

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

				// Initialize Select2 with closeOnSelect: false for size dropdown
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

	function prepareSizePricesJson() {
		// Save current values first
		saveCurrentValues();

		// Get selected classes
		var selectedClasses = [];
		$('#class_ids option:selected').each(function () {
			selectedClasses.push($(this).val());
		});

		var sizePricesObj = {};

		added_sizes.forEach(function (size) {
			var sizeId = size.id;
			if (selectedClasses.length === 0) {
				var key = '0_' + sizeId;
				var mrp = priceValues[key]?.mrp || '';
				var sp = priceValues[key]?.selling_price || '';
				
				if (!sizePricesObj['0']) {
					sizePricesObj['0'] = {};
				}
				sizePricesObj['0'][sizeId] = {
					class_id: 0,
					size_id: parseInt(sizeId),
					mrp: mrp,
					selling_price: sp
				};
			} else {
				selectedClasses.forEach(function (classId) {
					var key = classId + '_' + sizeId;
					var mrp = priceValues[key]?.mrp || '';
					var sp = priceValues[key]?.selling_price || '';
					
					if (!sizePricesObj[classId]) {
						sizePricesObj[classId] = {};
					}
					sizePricesObj[classId][sizeId] = {
						class_id: parseInt(classId),
						size_id: parseInt(sizeId),
						mrp: mrp,
						selling_price: sp
					};
				});
			}
		});

		return JSON.stringify(sizePricesObj);
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
				<td class="align-middle text-muted fs-12">General</td>
				<td>
					<div class="input-group input-group-sm">
						<span class="input-group-text">₹</span>
						<input type="number" id="mrp_0_${sizeId}" class="form-control mrp-input" step="0.01" min="0" required placeholder="0.00" value="${mrpVal}" data-size-id="${sizeId}" data-class-id="0">
					</div>
					<small class="text-danger mrp-error fs-11" id="mrp_error_0_${sizeId}" style="display:none;">MRP must be >= Selling Price</small>
				</td>
				<td>
					<div class="input-group input-group-sm">
						<span class="input-group-text">₹</span>
						<input type="number" id="selling_price_0_${sizeId}" class="form-control selling-price-input" step="0.01" min="0" required placeholder="0.00" value="${spVal}" data-size-id="${sizeId}" data-class-id="0">
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
						<div class="input-group input-group-sm">
							<span class="input-group-text">₹</span>
							<input type="number" id="mrp_${classId}_${sizeId}" class="form-control mrp-input" step="0.01" min="0" required placeholder="0.00" value="${mrpVal}" data-size-id="${sizeId}" data-class-id="${classId}" data-first-row="${isFirst}">
						</div>
						<small class="text-danger mrp-error fs-11" id="mrp_error_${classId}_${sizeId}" style="display:none;">MRP must be >= Selling Price</small>
					</td>
					<td>
						<div class="input-group input-group-sm">
							<span class="input-group-text">₹</span>
							<input type="number" id="selling_price_${classId}_${sizeId}" class="form-control selling-price-input" step="0.01" min="0" required placeholder="0.00" value="${spVal}" data-size-id="${sizeId}" data-class-id="${classId}" data-first-row="${isFirst}">
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

	function deleteImage(imageId) {
		if (!confirm('Are you sure you want to delete this image?')) {
			return;
		}

		fetch('<?php echo base_url('products/cloths/delete_image/'); ?>' + imageId, {
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
			// Initialize Select2 on cloth_type_id dropdown
			$('#cloth_type_id').select2({
				theme: 'bootstrap-5',
				placeholder: 'Select Cloth Type',
				allowClear: true,
				width: '100%'
			});

			// Initialize Select2 on color dropdown
			$('#color_id').select2({
				theme: 'bootstrap-5',
				placeholder: 'Select Color',
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
		}
	});

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

		// Get selected class IDs (cloths use flat per-size pricing only)
		var targetClasses = ['0'];

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