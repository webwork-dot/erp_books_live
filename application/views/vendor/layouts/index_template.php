<?php
/**
 * Main Layout Template for Vendor
 * 
 * This file includes header, sidebar, content, and footer
 * All vendor pages should use this layout
 */
// Reconstruct $data array from extracted variables
// CodeIgniter automatically extracts the data array keys as variables
// So we need to reconstruct it for passing to child views
$data = array();
// Get all defined variables
$vars = get_defined_vars();
// Filter out system variables and reconstruct data array
foreach ($vars as $key => $value) {
	// Skip CodeIgniter system variables
	if (!in_array($key, array('CI', 'this', 'data', 'view_data', 'vars'))) {
		$data[$key] = $value;
	}
}

// Load enabled features and subcategories for sidebar FIRST (before loading sidebar)
// This must happen before sidebar is loaded
if (!isset($data['enabled_features']) && isset($current_vendor['id'])) {
	$CI =& get_instance();
	$enabled_features = array();
	$enabled_subcategories = array();
	
	// Try to load from vendor database first (preferred method)
	if (!empty($current_vendor['database_name'])) {
		try {
			// Since database is already switched, query vendor_features table directly
			// First check if table exists
			$table_check = $CI->db->query("SHOW TABLES LIKE 'vendor_features'");
			if ($table_check && $table_check->num_rows() > 0) {
				$CI->db->select('*');
				$CI->db->from('vendor_features');
				$CI->db->where('is_enabled', 1);
				$CI->db->order_by('feature_id', 'ASC');
				$query = $CI->db->get();
				
				if ($query && $query->num_rows() > 0) {
					$all_vendor_features = $query->result_array();
					log_message('debug', 'Found ' . count($all_vendor_features) . ' enabled features in vendor database.');
					
					// Get parent_id information from master database to filter main categories
					$feature_parent_map = array();
					$feature_active_map = array();
					
					try {
						// Temporarily switch to master database
						$CI->load->database('default', FALSE, TRUE);
						$master_db = $CI->db;
						
						// Get feature IDs to check
						$feature_ids = array();
						foreach ($all_vendor_features as $vf) {
							$feature_ids[] = (int)$vf['feature_id'];
						}
						
						if (!empty($feature_ids)) {
							$master_db->select('id, parent_id, is_active');
							$master_db->from('erp_features');
							$master_db->where_in('id', $feature_ids);
							$master_query = $master_db->get();
							
							if ($master_query && $master_query->num_rows() > 0) {
								foreach ($master_query->result_array() as $mf) {
									$feature_parent_map[$mf['id']] = $mf['parent_id'];
									$feature_active_map[$mf['id']] = $mf['is_active'];
								}
								log_message('debug', 'Loaded parent_id info for ' . count($feature_parent_map) . ' features from master database.');
							} else {
								log_message('warning', 'No features found in master database for IDs: ' . implode(', ', $feature_ids));
							}
						}
					} catch (Exception $e) {
						log_message('error', 'Failed to query master database for feature parent_id: ' . $e->getMessage());
					}
					
					// Switch back to vendor database
					$CI->load->library('Tenant');
					$CI->tenant->switchDatabase($current_vendor);
					
					// Filter to only main categories (no parent_id) and active features
					// If we couldn't get parent_id from master, assume all are main categories
					log_message('debug', 'Starting feature filtering. Total features: ' . count($all_vendor_features) . ', Parent map entries: ' . count($feature_parent_map));
					
					foreach ($all_vendor_features as $vf) {
						$feature_id = (int)$vf['feature_id'];
						$parent_id = isset($feature_parent_map[$feature_id]) ? $feature_parent_map[$feature_id] : NULL;
						$is_active = isset($feature_active_map[$feature_id]) ? $feature_active_map[$feature_id] : 1;
						
						log_message('debug', 'Feature ID: ' . $feature_id . ', Name: ' . $vf['feature_name'] . ', Parent ID: ' . ($parent_id ? $parent_id : 'NULL') . ', Active: ' . $is_active);
						
						// Only include main categories (no parent_id) and active features
						// If parent_id map is empty (master DB query failed), include all features
						if (empty($feature_parent_map)) {
							// Master DB query failed or returned no results - include all features
							log_message('debug', 'Including feature (no parent map): ' . $vf['feature_name']);
							$enabled_features[] = array(
								'id' => $feature_id,
								'name' => $vf['feature_name'],
								'slug' => $vf['feature_slug'],
								'is_enabled' => 1,
								'is_active' => 1
							);
						} elseif (empty($parent_id) && $is_active == 1) {
							// Main category (no parent) and active
							log_message('debug', 'Including main category feature: ' . $vf['feature_name']);
							$enabled_features[] = array(
								'id' => $feature_id,
								'name' => $vf['feature_name'],
								'slug' => $vf['feature_slug'],
								'is_enabled' => 1,
								'is_active' => 1
							);
						} else {
							log_message('debug', 'Excluding feature (has parent or inactive): ' . $vf['feature_name'] . ' - Parent: ' . ($parent_id ? $parent_id : 'NULL') . ', Active: ' . $is_active);
						}
					}
					
					log_message('info', 'After filtering, ' . count($enabled_features) . ' main category features will be shown in sidebar out of ' . count($all_vendor_features) . ' total enabled features.');
				
					// Get enabled subcategories from vendor database
				$CI->db->select('*');
				$CI->db->from('vendor_feature_subcategories');
				$CI->db->where('is_enabled', 1);
				$subcat_query = $CI->db->get();
				
				if ($subcat_query && $subcat_query->num_rows() > 0) {
					$subcategories = $subcat_query->result_array();
					foreach ($subcategories as $subcat) {
						$feature_id = (int)$subcat['feature_id'];
						if (!isset($enabled_subcategories[$feature_id])) {
							$enabled_subcategories[$feature_id] = array();
						}
						$enabled_subcategories[$feature_id][] = array(
							'id' => $subcat['subcategory_id'],
							'name' => $subcat['subcategory_name'],
							'slug' => $subcat['subcategory_slug'],
							'is_enabled' => 1,
							'is_active' => 1
						);
					}
				} else {
					log_message('debug', 'No enabled subcategories found in vendor_feature_subcategories table.');
				}
			} else {
				log_message('debug', 'No enabled features found in vendor_features table (query returned 0 rows) for vendor: ' . $current_vendor['database_name']);
			}
		} else {
			log_message('debug', 'vendor_features table does not exist in vendor database: ' . $current_vendor['database_name']);
		}
		} catch (Exception $e) {
			log_message('error', 'Failed to load features from vendor database: ' . $e->getMessage());
			// Ensure we're back on vendor database
			if (!empty($current_vendor['database_name'])) {
				$CI->load->library('Tenant');
				$CI->tenant->switchDatabase($current_vendor);
			}
		}
	}
	
	// Fallback to master database if vendor database query failed or empty
	if (empty($enabled_features)) {
		$CI->load->model('Erp_client_model');
		$CI->load->model('Erp_feature_model');
		
		// Temporarily switch back to master database for this query
		$CI->load->database('default', FALSE, TRUE);
		
		$vendor_features = $CI->Erp_client_model->getClientFeatures($current_vendor['id']);
		
		// Switch back to vendor database
		if (!empty($current_vendor['database_name'])) {
			$CI->load->library('Tenant');
			$CI->tenant->switchDatabase($current_vendor);
		}
		
		// Get only main categories (no parent_id)
		foreach ($vendor_features as $feature) {
			if ($feature['is_enabled'] == 1 && $feature['is_active'] == 1 && empty($feature['parent_id'])) {
				$enabled_features[] = $feature;
			}
		}
		
		// Get enabled subcategories
		$CI->load->database('default', FALSE, TRUE);
		$vendor_subcategories = $CI->Erp_client_model->getClientSubcategories($current_vendor['id']);
		
		// Switch back to vendor database
		if (!empty($current_vendor['database_name'])) {
			$CI->load->library('Tenant');
			$CI->tenant->switchDatabase($current_vendor);
		}
		
		foreach ($vendor_subcategories as $subcat) {
			if (isset($subcat['is_enabled']) && $subcat['is_enabled'] == 1) {
				$feature_id = (int)$subcat['feature_id'];
				if (!isset($enabled_subcategories[$feature_id])) {
					$enabled_subcategories[$feature_id] = array();
				}
				// Get full subcategory details
				$CI->load->database('default', FALSE, TRUE);
				$subcat_details = $CI->Erp_feature_model->getFeatureById($subcat['subcategory_id']);
				
				// Switch back to vendor database
				if (!empty($current_vendor['database_name'])) {
					$CI->load->library('Tenant');
					$CI->tenant->switchDatabase($current_vendor);
				}
				
				if ($subcat_details && $subcat_details['is_active'] == 1) {
					$enabled_subcategories[$feature_id][] = $subcat_details;
				}
			}
		}
	}
	
	$data['enabled_features'] = $enabled_features;
	$data['enabled_subcategories'] = $enabled_subcategories;
	
	// Also set as variables for direct access in views
	$enabled_features = $enabled_features;
	$enabled_subcategories = $enabled_subcategories;
	
	log_message('debug', 'Final enabled_features count: ' . count($enabled_features) . ' for vendor: ' . (isset($current_vendor['name']) ? $current_vendor['name'] : 'unknown'));
}

// Calculate total product count for vendor (if not already set)
if (!isset($data['total_products_count']) && isset($current_vendor['database_name'])) {
	$CI =& get_instance();
	$total_products = 0;
	
	try {
		// Get database connection details
		$hostname = $CI->db->hostname;
		$username = $CI->db->username;
		$password = $CI->db->password;
		$database_name = $current_vendor['database_name'];
		
		// Connect directly to vendor database
		$connection = @new mysqli($hostname, $username, $password, $database_name);
		
		if (!$connection->connect_error) {
			// Count booksets
			$result = $connection->query("SELECT COUNT(*) as count FROM `erp_booksets`");
			if ($result) {
				$row = $result->fetch_assoc();
				$total_products += (int)$row['count'];
			}
			
			// Count uniforms
			$result = $connection->query("SHOW TABLES LIKE 'erp_uniforms'");
			if ($result && $result->num_rows > 0) {
				$result = $connection->query("SELECT COUNT(*) as count FROM `erp_uniforms`");
				if ($result) {
					$row = $result->fetch_assoc();
					$total_products += (int)$row['count'];
				}
			}
			
			// Count stationery
			$result = $connection->query("SHOW TABLES LIKE 'erp_stationery'");
			if ($result && $result->num_rows > 0) {
				$result = $connection->query("SELECT COUNT(*) as count FROM `erp_stationery`");
				if ($result) {
					$row = $result->fetch_assoc();
					$total_products += (int)$row['count'];
				}
			}
			
			// Count bookset package products
			$result = $connection->query("SHOW TABLES LIKE 'erp_bookset_package_products'");
			if ($result && $result->num_rows > 0) {
				$result = $connection->query("SELECT COUNT(*) as count FROM `erp_bookset_package_products` WHERE `status` = 'active'");
				if ($result) {
					$row = $result->fetch_assoc();
					$total_products += (int)$row['count'];
				}
			}
			
			$connection->close();
		}
	} catch (Exception $e) {
		// If database connection fails, set count to 0
		$total_products = 0;
		log_message('error', 'Failed to count products for vendor: ' . $e->getMessage());
	}
	
	$data['total_products_count'] = $total_products;
}
?>
<?php $this->load->view('vendor/layouts/header_template', $data); ?>
<?php $this->load->view('vendor/layouts/sidebar_template', $data); ?>

<div class="page-wrapper">
	<div class="content">
		<?php 
		// Get flashdata and immediately clear it to prevent showing on refresh
		$flash_success = $this->session->flashdata('success');
		$flash_error = $this->session->flashdata('error');
		
		// Immediately clear flashdata using CodeIgniter's unmark_flash method
		// This properly removes flashdata from __ci_vars so it won't persist
		if ($flash_success) {
			$this->session->unmark_flash('success');
			$this->session->unset_userdata('success');
		}
		if ($flash_error) {
			$this->session->unmark_flash('error');
			$this->session->unset_userdata('error');
		}
		
		// Only show one alert at a time - prioritize error over success
		if ($flash_error): ?>
			<div class="alert alert-danger alert-dismissible fade show" role="alert" id="flash-alert" data-alert-id="<?php echo md5($flash_error . time()); ?>">
				<?php echo htmlspecialchars($flash_error); ?>
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		<?php elseif ($flash_success): ?>
			<div class="alert alert-success alert-dismissible fade show" role="alert" id="flash-alert" data-alert-id="<?php echo md5($flash_success . time()); ?>">
				<?php echo htmlspecialchars($flash_success); ?>
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		<?php endif; ?>
		<?php echo isset($content) ? $content : ''; ?>
	</div>
</div>

<script>
// Auto-dismiss alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
	var alert = document.getElementById('flash-alert');
	if (alert) {
		var alertId = alert.getAttribute('data-alert-id');
		var storageKey = 'alert_shown_' + alertId;
		
		// Check if this specific alert was already shown (using unique ID)
		if (localStorage.getItem(storageKey)) {
			// Already shown, hide it immediately
			alert.remove();
			return;
		}
		
		// Mark this alert as shown
		localStorage.setItem(storageKey, '1');
		
		// Auto-dismiss after 5 seconds
		setTimeout(function() {
			if (alert && alert.parentNode) {
				var bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
				bsAlert.close();
			}
		}, 5000);
		
		// Clean up localStorage after alert is closed
		alert.addEventListener('closed.bs.alert', function() {
			setTimeout(function() {
				localStorage.removeItem(storageKey);
			}, 1000);
		});
		
		// Also clean up on manual close
		var closeBtn = alert.querySelector('.btn-close');
		if (closeBtn) {
			closeBtn.addEventListener('click', function() {
				setTimeout(function() {
					localStorage.removeItem(storageKey);
				}, 1000);
			});
		}
		
		// Clean up old localStorage entries (older than 5 minutes)
		var now = Date.now();
		for (var i = 0; i < localStorage.length; i++) {
			var key = localStorage.key(i);
			if (key && key.startsWith('alert_shown_')) {
				var timestamp = localStorage.getItem(key);
				if (timestamp && (now - parseInt(timestamp)) > 300000) { // 5 minutes
					localStorage.removeItem(key);
				}
			}
		}
	}
});
</script>

<?php $this->load->view('vendor/layouts/footer_template', $data); ?>

