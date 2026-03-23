<style type="text/css">
.option-item,.option-radio{align-items:center;align-items:center}.option-selector,.toggle-btn{margin-right:10px;cursor:pointer}.select2{padding:0;height:auto!important}.select2-selection{min-height:auto!important}.error{font-color:12px;color:red}#email-err,#mobile-number-err{color:red;font-color:14px}.toggle-buttons{display:flex;margin-bottom:20px}.toggle-btn{padding:8px 15px;background:#ddd;border:none;border-radius:4px}.toggle-btn.active{background:#292e3c;color:#fff}.option-content,.toggle-section{display:none}.option-content.active,.toggle-section.active{display:block}.option-selector{padding:5px}.option-selector.active{font-weight:700;color:#292e3c}.option-selector-container{border-radius:8px;margin-bottom:15px;margin-left:2px;display:flex;gap:10px}.option-item{display:flex;align-items:center;margin-bottom:8px}.mb0,.option-item:last-child{margin-bottom:0}.option-item:hover{background:#f0f0f0}.option-radio{align-items:center;justify-content:center;transition:.2s}.option-item.active .option-radio{border-color:#292e3c}.option-radio-inner{background:0 0;transition:.2s}.option-item.active .option-radio-inner{background:#292e3c;display:block}.option-label{font-size:14px;color:#333;font-weight:400}.form-group .customer-label{display:inline-block;max-width:100%;margin-bottom:-10px;font-size:16px;color:#292e3c}.btn-nearby{background-color:#03151c;color:#fff;border-color:#03151c;margin-left:10px}.btn-nearby:focus,.btn-nearby:hover{background-color:#042837;color:#fff;border-color:#042837}.option-item{display:flex}.option-radio{width:18px;height:18px;border:2px solid #007bff;border-radius:50%;justify-content:center;margin-right:10px}.option-radio-inner{width:10px;height:10px;background-color:#007bff;border-radius:50%;display:none}.option-item{display:flex;align-items:center;padding:10px 15px;border-radius:4px;cursor:pointer;transition:.3s;height:40px;border:1px solid #ddd;background:#ddd}.option-radio{display:none!important}.option-item.active{border-color:#292e3c;background-color:#292e3c;color:#fff}.option-item.active .option-label{color:#fff}.pt0{padding-top:0!important}.sp1{font-size:12px;}.mobile_view{
    padding: 0px 15px;
}
</style>


<div class="mobile_view home">

  <div class="content-header">
    <div class="content-header-right col-md-12 col-12 mb-2 card">
      <div class="row breadcrumbs-top">
        <div class="col-12">
          <h4 class="card-title pull-left"><a class="back_arrow" href="<?php echo base_url('offers'); ?>"><i class="fa fa-arrow-circle-left"></i></a><?= isset($current_page) ? $current_page : 'Add New Offer' ?> </h4>
          <div class="breadcrumb-wrapper">
            <ol class="breadcrumb pull-right">
              <li class="breadcrumb-item">Home</li>
              <li class="breadcrumb-item"> <?= isset($current_page) ? $current_page : 'Add New Offer' ?></li>
            </ol>
          </div>
        </div>
      </div>
    </div>
  </div>
 
  <div class="content-body">
    <div class="row">
      <div class="col-md-7">
          
            <form action="<?php echo site_url((isset($vendor_domain) ? $vendor_domain : (isset($current_vendor['domain']) ? $current_vendor['domain'] : '')) . '/offers/add'); ?>" method="post" class="form offer-ajax-redirect" enctype="multipart/form-data">
                <!-- Discount Type Toggle -->
                <div class="card">
                    <div class="row p-2">
                        <input type="hidden" name="offer_type" id="offer_type" value="discount_code">
                        
                        <div id="discount-code-section" class="toggle-section active col-md-9">
                            <div class="form-group mb-0">
                                <label class="control-label">Coupon Code</label>
                                <input type="text" name="discount_code" id="discount_code" class="form-control" placeholder="Enter coupon code">
                            </div>
                        </div>	
            
                        <div id="automatic-discount-section" class="toggle-section col-md-9">
                            <div class="form-group">
                                <label class="control-label">Title</label>
                                <input type="text" name="title" id="title" class="form-control" placeholder="Enter title">
                            </div>
                        </div>
                        
						<div class="col-md-4" style="padding-left: 34px;margin-top:15px;">
							<input class="form-check-input" name="is_show" type="checkbox" id="is_show" value="1"/>
							<label class="control-label" for="is_show">Show On Website/App</label>
						</div>
						<div class="col-md-3" style="padding-left: 15px;margin-top:15px;">
							<input class="form-check-input" name="is_app" type="checkbox" id="is_app" value="1"/>
							<label class="control-label" for="is_app">For App Only</label>
						</div>
						<div class="col-md-3" style="padding-left: 15px;margin-top:15px;">
							<input class="form-check-input" name="is_new_only" type="checkbox" id="is_new_only" value="1"/>
							<label class="control-label" for="is_new_only">For New User Only</label>
						</div>
                    </div>
                </div>
   
                <div class="card">
					<div class="row p-2">
						<div class="col-6">
						<div class="form-group mb-0">
								<label class="control-label">No. Of Coupon</label>
								<input type="number" name="no_coupon" id="no_coupon" class="form-control" value="" placeholder="No. Of Coupon">
							</div>
						</div>
						<div class="col-6">
							<div class="form-group mb-0">
								<label class="control-label">Max Coupon Per User</label>
								<input type="number" name="max_per_user" id="max_per_user" value="" class="form-control" placeholder="Max Coupon Per User">
							</div>
						</div>
						<div class="col-12 mt-2">
							<label class="control-label">Coupon Description</label>
							<textarea name="description" id="description" class="form-control"></textarea>
						</div>
					</div>
				</div>
            
                <!-- Customer Requirements -->
                <div class="card">
                    <div class="row p-2">
                        <div class="col-12 col-sm-9 min_type">
                            <div class="form-group">
                                <label class="customer-label">Customer Buys</label>
                            </div>
                            <div class="option-selector-container">
                                <div class="option-item active" onclick="toggleOption('quantity')">
                                    <div class="option-radio">
                                        <div class="option-radio-inner"></div>
                                    </div>
                                    <span class="option-label">Minimum quantity of items</span>
                                </div>
                                <div class="option-item" onclick="toggleOption('amount')">
                                    <div class="option-radio">
                                        <div class="option-radio-inner"></div>
                                    </div>
                                    <span class="option-label">Minimum purchase amount</span>
                                </div>
                            </div>
                        </div>
                        
                        <input type="hidden" name="min_type" id="min_type" value="quantity">
                        
                        <div class="col-md-4 option-content active" id="quantity-option">
                            <div class="form-group">
                                <label class="control-label">Quantity<span class="required">*</span></label>
                                <input type="number" class="form-control" name="min_value" id="min_value" placeholder="Enter Quantity" min="1" max="99999"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 5)">
                            </div>
                        </div>
                        
                        <div class="col-md-4 option-content" id="amount-option" style="display: none;">
                            <div class="form-group">
                                <label class="control-label">Amount<span class="required">*</span></label>
                                <input type="text" class="form-control" name="min_value_amount" id="min_value_amount" placeholder="₹0.00">
                            </div>
                        </div>
                        
                        <div class="col-12 col-sm-5">
                            <div class="form-group">
                                <label class="control-label">Any items from</label>
                                <select class="form-control" name="item_type" id="item_type">
                                    <option value="all">Whole Website</option>
                                    <option value="categories">Specific Uniform Types</option>
                                    <option value="products">Specific Uniforms</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-9" id="first_selection" style="display: none;">
                            <div class="form-group">
                                <label class="control-label">Search <span id="searchLabel">Uniform Types</span></label>
                                <div class="input-group">
                                    <select name="item_type_list[]" id="item_type_list" class="select2" multiple>
                                        <?php if (isset($uniform_types) && !empty($uniform_types)): ?>
                                            <?php foreach ($uniform_types as $type): ?>
                                                <option value="<?php echo $type['id']; ?>"><?php echo htmlspecialchars($type['name']); ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
                <!-- Offer Type Selection -->
                <div class="card">
                    <div class="row p-2 offer_type">
                        <div class="col-12 col-sm-9">
                            <div class="form-group">
                                <label class="customer-label">Select Offer Type</label>
                            </div>
                            <div class="option-selector-container mb0">
                                <div class="option-item active" onclick="toggleOfferType('percentage')">
                                    <div class="option-radio">
                                        <div class="option-radio-inner"></div>
                                    </div>
                                    <span class="option-label">Percentage</span>
                                </div>
                                <div class="option-item" onclick="toggleOfferType('amount')">
                                    <div class="option-radio">
                                        <div class="option-radio-inner"></div>
                                    </div>
                                    <span class="option-label">Flat Amount</span>
                                </div>
                                <div class="option-item" onclick="toggleOfferType('free')">
                                    <div class="option-radio">
                                        <div class="option-radio-inner"></div>
                                    </div>
                                    <span class="option-label">Free</span>
                                </div> 
                            </div>
                            <!-- Hidden field for offer value type -->
                            <input type="hidden" name="offer_value_type" id="offer_value_type" value="percentage">
                        </div>
                    </div>
				
                    <!-- Percentage Section -->
                    <div class="row p-2 pt0" id="percentage-section">
                        <div class="col-md-4">
                            <div class="form-group position-relative">
                                <label class="control-label">Percentage %</label>
                                <input type="numeric" class="form-control pr-4" name="offer_value_percentage" id="offer_value_percentage" placeholder="">
                                <span class="position-absolute" style="right: 10px; top: 70%; transform: translateY(-50%); pointer-events: none;">%</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Amount Section -->
                    <div class="row p-2 pt0" id="amount-section" style="display: none;">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label">Flat Amount</label>
                                <input type="text" class="form-control" name="offer_value_amount" id="offer_value_amount" placeholder="₹0.00">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Free Section -->
                    <div id="free-section" style="display: none;">
                        <div class="row p-2 pt0">
                            <div class="col-12 col-sm-9">
                                <div class="form-group">
                                    <label class="customer-label">Customer Gets</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Quantity<span class="required">*</span></label>
                                    <input type="text" class="form-control" name="free_quantity" id="free_quantity" placeholder="Enter Quantity">
                                </div>
                            </div>
                            <div class="col-12 col-sm-5">
                                <div class="form-group">
                                    <label class="control-label">Any items from</label>
                                    <select class="form-control" name="item_type_get" id="item_type_get">
                                        <option value="all">Whole Website</option>
                                        <option value="categories">Specific Uniform Types</option>
                                        <option value="products">Specific Uniforms</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-9" id="second_selection" style="display: none;">
                                <div class="form-group">
                                    <label class="control-label">Search <span id="searchLabel_get">Uniform Types</span></label>
                                    <div class="input-group">
                                        <select name="item_type_list_get[]" id="item_type_list_get" class="select2" multiple>
                                            <?php if (isset($uniform_types) && !empty($uniform_types)): ?>
                                                <?php foreach ($uniform_types as $type): ?>
                                                    <option value="<?php echo $type['id']; ?>"><?php echo htmlspecialchars($type['name']); ?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
				
				
				<div class="card">
					<div class="row p-2">
						<div class="col-12">
							<div class="form-check mb-2">
								<input type="checkbox" id="is_cashback" name="is_cashback" class="form-check-input" value="1" onchange="toggleCashbackSection()">
								<label for="is_cashback" class="form-check-label font-weight-bold">Enable Cashback</label>
							</div>
						</div>

						<div id="cashback-options" style="display: none;" class="row w-100 m-0">
							<div class="col-md-4">
								<div class="form-group">
									<label class="control-label">Cashback Type</label>
									<select name="cashback_type" id="cashback_type" class="form-control">
										<option value="flat">Flat</option>
										<option value="percentage">Percentage</option>
									</select>
								</div>
							</div>

							<div class="col-md-4" id="cashback-flat-box">
								<div class="form-group">
									<label class="control-label">Cashback Flat Amount</label>
									<input type="number" name="cashback_flat_value" step="0.01" class="form-control" placeholder="₹0.00">
								</div>
							</div>

							<div class="col-md-4" id="cashback-percentage-box" style="display: none;">
								<div class="form-group">
									<label class="control-label">Cashback Percentage (%)</label>
									<input type="number" name="cashback_percentage_value" step="0.01" class="form-control" placeholder="% value">
								</div>

								<div class="form-check mt-2">
									<input type="checkbox" name="is_upto" id="is_upto" class="form-check-input" value="1" onchange="toggleUptoAmountBox()">
									<label for="is_upto" class="form-check-label">Upto Amount Limit</label>
								</div>

								<div class="form-group mt-2" id="upto-amount-box" style="display: none;">
									<label class="control-label">Upto Amount</label>
									<input type="number" name="upto_amount" step="0.01" class="form-control" placeholder="₹0.00">
								</div>
							</div>
						</div>
					</div>
				</div>

            
                <!-- Submit Button -->
                <div class="card">
                    <div class="row p-2">
                        <div class="col-md-12">
                            <div class="form-group mb-0">
                                <button type="submit" class="btn btn-primary btn_verify" name="btn_verify">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            
            <script>
            // Toggle between Discount Code and Automatic Discount
            function toggleSection(section) {
                // Update buttons
                document.querySelectorAll('.toggle-buttons .toggle-btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                event.target.classList.add('active');
                
                // Update sections
                document.getElementById('discount-code-section').classList.remove('active');
                document.getElementById('automatic-discount-section').classList.remove('active');
                document.getElementById(section + '-section').classList.add('active');
                
                // Update hidden field
                document.getElementById('offer_type').value = 
                    section === 'discount-code' ? 'discount_code' : 'automatic_discount';
            }
            
            // Toggle between Quantity and Amount options
            function toggleOption(type) {
                document.getElementById("min_type").value = type;
                document.getElementById("quantity-option").style.display = type === "quantity" ? "block" : "none";
                document.getElementById("amount-option").style.display = type === "amount" ? "block" : "none";
                
                // Update UI
                document.querySelectorAll(".min_type .option-item").forEach(item => item.classList.remove("active"));
                event.currentTarget.classList.add("active");
            }
            
            // Toggle between offer types (Percentage, Amount, Free)
            function toggleOfferType(type) {
                // Update UI
                document.querySelectorAll('.offer_type .option-item').forEach(item => {
                    item.classList.remove('active');
                });
                event.currentTarget.classList.add('active');
                
                // Toggle sections
                document.getElementById('percentage-section').style.display = 'none';
                document.getElementById('amount-section').style.display = 'none';
                document.getElementById('free-section').style.display = 'none';
                
                // Show selected section
                if (type === 'percentage') {
                    document.getElementById('percentage-section').style.display = 'flex';
                } else if (type === 'amount') {
                    document.getElementById('amount-section').style.display = 'flex';
                } else if (type === 'free') {
                    document.getElementById('free-section').style.display = 'block';
                }
                
                // Update hidden field
                document.getElementById('offer_value_type').value = type;
            }
			
			function toggleCashbackSection() {
				const cbBox = document.getElementById('cashback-options');
				const checkbox = document.getElementById('is_cashback');
				cbBox.style.display = checkbox.checked ? 'flex' : 'none';
				toggleCashbackType(); // ensure correct default display
			}

			function toggleCashbackType() {
				const cbType = document.getElementById('cashback_type').value;
				document.getElementById('cashback-flat-box').style.display = cbType === 'flat' ? 'block' : 'none';
				document.getElementById('cashback-percentage-box').style.display = cbType === 'percentage' ? 'block' : 'none';
			}

			function toggleUptoAmountBox() {
				const isChecked = document.getElementById('is_upto').checked;
				document.getElementById('upto-amount-box').style.display = isChecked ? 'block' : 'none';
			}
		
            // Handle item type selection changes
            document.addEventListener("DOMContentLoaded", function() {
                // For Customer Buys section
                const itemType = document.getElementById("item_type");
                const searchLabel = document.getElementById("searchLabel");
                const itemTypeList = document.getElementById("item_type_list");
                
                itemType.addEventListener("change", function() {
                    const selectedValue = this.value;
                    document.getElementById('first_selection').style.display = selectedValue === 'all' ? 'none' : 'block';
                    searchLabel.innerText = selectedValue === 'products' ? "Uniforms" : "Uniform Types";
                    if (selectedValue === "products") {
                        itemTypeList.innerHTML = `<?php if (isset($uniforms) && !empty($uniforms)): ?>
                            <?php foreach ($uniforms as $uniform): ?>
                                <option value="<?php echo $uniform['id']; ?>"><?php echo htmlspecialchars($uniform['product_name']); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>`;
                    } else {
                        itemTypeList.innerHTML = `<?php if (isset($uniform_types) && !empty($uniform_types)): ?>
                            <?php foreach ($uniform_types as $type): ?>
                                <option value="<?php echo $type['id']; ?>"><?php echo htmlspecialchars($type['name']); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>`;
                    }
                    // Reinitialize Select2
                    if ($(itemTypeList).hasClass('select2-hidden-accessible')) {
                        $(itemTypeList).select2('destroy');
                    }
                    $(itemTypeList).select2();
                });
                
                
                // For Customer Gets section (Free offers)
                const itemTypeGet = document.getElementById("item_type_get");
                const searchLabelGet = document.getElementById("searchLabel_get");
                const itemTypeListGet = document.getElementById("item_type_list_get");
                
                itemTypeGet.addEventListener("change", function() {
                    const selectedValue = this.value;
                    document.getElementById('second_selection').style.display = selectedValue === 'all' ? 'none' : 'block';
                    searchLabelGet.innerText = selectedValue === 'products' ? "Uniforms" : "Uniform Types";
                    if (selectedValue === "products") {
                        itemTypeListGet.innerHTML = `<?php if (isset($uniforms) && !empty($uniforms)): ?>
                            <?php foreach ($uniforms as $uniform): ?>
                                <option value="<?php echo $uniform['id']; ?>"><?php echo htmlspecialchars($uniform['product_name']); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>`;
                    } else {
                        itemTypeListGet.innerHTML = `<?php if (isset($uniform_types) && !empty($uniform_types)): ?>
                            <?php foreach ($uniform_types as $type): ?>
                                <option value="<?php echo $type['id']; ?>"><?php echo htmlspecialchars($type['name']); ?></option>
                            <?php endforeach; ?>
                        <?php endif; ?>`;
                    }
                    // Reinitialize Select2
                    if ($(itemTypeListGet).hasClass('select2-hidden-accessible')) {
                        $(itemTypeListGet).select2('destroy');
                    }
                    $(itemTypeListGet).select2();
                });
				
				document.getElementById('cashback_type').addEventListener('change', toggleCashbackType); 
				
            });
            
            // Form submission handling with all validations preserved
            $('.offer-ajax-redirect').submit(function(e) {
                e.preventDefault(); 
                
                let submitButton = document.querySelector(".btn_verify");
                
                let isValid = true;
                let errorMessage = "";
            
                // Discount Type Validation
                let discountType = "Discount Code";
            
                // Customer Buys Validation
                let minType = document.getElementById("min_type").value;
                if (minType === "quantity") {
                    let minValue = document.getElementById("min_value").value.trim();
                    let itemTypeList = document.querySelectorAll("#item_type_list option:checked");
                    let itemType = document.getElementById("item_type").value;
            
                    if (minValue === "" || isNaN(minValue) || parseInt(minValue) <= 0) {
                        isValid = false;
                        errorMessage += "Minimum quantity must be a valid number greater than 0.\n";
                    }
                    if (itemTypeList.length === 0 && itemType!='all') {
                        isValid = false;
                        errorMessage += "Please select at least one item type list for minimum quantity.\n";
                    }
                }
                else if (minType === "amount") {
                    let minValueAmount = document.getElementById("min_value_amount").value.trim();
                    let itemTypeList = document.querySelectorAll("#item_type_list option:checked");
                    let itemType = document.getElementById("item_type").value;
                    
                    if (minValueAmount === "" || isNaN(minValueAmount) || parseFloat(minValueAmount) <= 0) {
                        isValid = false;
                        errorMessage += "Minimum purchase amount must be a valid number greater than 0.\n";
                    }
                    if (itemTypeList.length === 0 && itemType!='all') {
                        isValid = false;
                        errorMessage += "Please select at least one item type list for minimum purchase amount.\n";
                    }
                }
            
                // Offer Type Validation
                let selectedOfferType = document.getElementById("offer_value_type").value;
                if (selectedOfferType === "percentage") {
                    let offerValue = document.getElementById("offer_value_percentage").value.trim();
                    if (offerValue === "" || isNaN(offerValue) || parseFloat(offerValue) <= 0 || parseFloat(offerValue) > 100) {
                        isValid = false;
                        errorMessage += "Offer percentage must be between 1-100.\n";
                    }
                } else if (selectedOfferType === "amount") {
                    let offerValueAmount = document.getElementById("offer_value_amount").value.trim();
                    if (offerValueAmount === "" || isNaN(offerValueAmount) || parseFloat(offerValueAmount) <= 0) {
                        isValid = false;
                        errorMessage += "Flat amount must be a valid number greater than 0.\n";
                    }
                } else if (selectedOfferType === "free") {
                    let freeQuantity = document.getElementById("free_quantity").value.trim();
                    let itemTypeGet = document.getElementById("item_type_get").value;
                    let itemTypeListGet = document.querySelectorAll("#item_type_list_get option:checked");
            
                    if (freeQuantity === "" || isNaN(freeQuantity) || parseInt(freeQuantity) <= 0) {
                        isValid = false;
                        errorMessage += "Free item quantity must be greater than 0.\n";
                    }
                    if (itemTypeListGet.length === 0 && itemTypeGet!='all') {
                        isValid = false;
                        errorMessage += "You must select an item type list for free offer.\n";
                    }
                }
                // If form is invalid, prevent submission and show error
                if (!isValid) {
                    alert(errorMessage);
                    submitButton.removeAttribute("disabled");
                    submitButton.innerHTML = 'Submit';
                    return; 
                }
            
                // Prepare form data for AJAX submission
                $('.btn_verify').attr("disabled", true);
                $('.btn_verify').html('<i class="fa fa-spinner fa-spin" aria-hidden="true" style="font-size: 14px;color: #fff;"></i> Processing');
                
                var formData = new FormData(this);
                
                // For amount type, we need to use the correct value
                if (selectedOfferType === 'amount') {
                    formData.set('offer_value', document.getElementById('offer_value_amount').value);
                } 
                // For percentage type, use the percentage value
                else if (selectedOfferType === 'percentage') {
                    formData.set('offer_value', document.getElementById('offer_value_percentage').value);
                }
                
                // For amount option in Customer Buys
                if (minType === 'amount') {
                    formData.set('min_value', document.getElementById('min_value_amount').value);
                }
			
				const isCashback = document.getElementById('is_cashback').checked;
				formData.set('is_cashback', isCashback ? '1' : '0');

				if (isCashback) {
					const cashbackType = document.getElementById('cashback_type').value;
					formData.set('cashback_type', cashbackType);

					if (cashbackType === 'flat') {
						const flatValue = document.querySelector('[name="cashback_flat_value"]').value.trim();
						if (flatValue === '' || isNaN(flatValue) || parseFloat(flatValue) <= 0) {
							isValid = false;
							errorMessage += "Cashback flat value must be a valid number greater than 0.\n";
						}
						formData.set('cashback_flat_value', flatValue);
					} else if (cashbackType === 'percentage') {
						const percentValue = document.querySelector('[name="cashback_percentage_value"]').value.trim();
						if (percentValue === '' || isNaN(percentValue) || parseFloat(percentValue) <= 0 || parseFloat(percentValue) > 100) {
							isValid = false;
							errorMessage += "Cashback percentage must be between 1-100.\n";
						}
						formData.set('cashback_percentage_value', percentValue);

						const isUpto = document.getElementById('is_upto').checked;
						formData.set('is_upto', isUpto ? '1' : '0');
						if (isUpto) {
							const uptoAmount = document.querySelector('[name="upto_amount"]').value.trim();
							if (uptoAmount === '' || isNaN(uptoAmount) || parseFloat(uptoAmount) <= 0) {
								isValid = false;
								errorMessage += "Upto amount must be greater than 0.\n";
							}
							formData.set('upto_amount', uptoAmount);
						}
					}
				} 

				if (!isValid) {
					alert(errorMessage);
					submitButton.removeAttribute("disabled");
					submitButton.innerHTML = 'Submit';
					return;
				}
                
                $.ajax({
                    type: 'POST',
                    url: this.action,
                    async: true,
                    dataType: 'json',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status == '200') {
                            $(".loader").fadeOut("slow"); 
                            Swal.fire({
                                title: "Success!",
                                text: res.message,
                                icon: "success",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                },
                                buttonsStyling: !1
                            }).then(() => { window.location.href = res.url; });
                        }
                        else { 
                            $.each(res.errors, function(key, value) {
                                $('[name="'+key+'"]').addClass('is-invalid');
                                $('[name="'+key+'"]').next().html(value);
                                if(value == "") {
                                    $('[name="'+key+'"]').removeClass('is-invalid');
                                    $('[name="'+key+'"]').addClass('is-valid');
                                }
                            });   
                            Swal.fire({
                                title: "Error!",
                                html: true,
                                html: res.message,
                                icon: "error",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                },
                                buttonsStyling: !1
                            });
                            $('.btn_verify').html('<i class="fa fa-save"></i> Save');
                            $('.btn_verify').attr("disabled", false);
                        }
                    },
                    error: function() {
                        $('.btn_verify').html('<i class="fa fa-save"></i> Save');
                        $('.btn_verify').attr("disabled", false);
                    }
                });
            });
            </script>
      
     </div>
  </div>
</div>



<script type="text/javascript">
  function isNumberKey(evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    if (charCode > 46 && (charCode < 48 || charCode > 57)) {
      return false;
    }
    return true;
  }

  function toggleSection(section) {
    // Toggle buttons active state
    document.querySelectorAll('.toggle-btn').forEach(btn => {
      btn.classList.remove('active');
    });
    event.target.classList.add('active');
    
    // Toggle sections visibility
    document.getElementById('discount-code-section').classList.remove('active');
    document.getElementById('automatic-discount-section').classList.remove('active');
    
    document.getElementById(section + '-section').classList.add('active');
  }
  

  CKEDITOR.replace('description', {
    removePlugins: 'link,about',
    removeButtons: 'Subscript,Superscript,Image',
  });
</script>

