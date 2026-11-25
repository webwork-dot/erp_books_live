<script>
function loadPurchaseCities(stateId, selectedCityId) {
	var citySelect = document.getElementById('city_id');
	var stateCodeInput = document.getElementById('state_code');
	if (!citySelect) return;
	citySelect.innerHTML = '<option value="">Loading...</option>';
	if (!stateId) {
		citySelect.innerHTML = '<option value="">Select City</option>';
		if (stateCodeInput) stateCodeInput.value = '';
		return;
	}
	fetch('<?php echo base_url('purchase-in/get-cities'); ?>?state_id=' + encodeURIComponent(stateId))
		.then(function(r) { return r.json(); })
		.then(function(data) {
			var html = '<option value="">Select City</option>';
			(data.cities || []).forEach(function(city) {
				var sel = (String(selectedCityId || '') === String(city.id)) ? ' selected' : '';
				html += '<option value="' + city.id + '"' + sel + '>' + city.name + '</option>';
			});
			citySelect.innerHTML = html;
			if (stateCodeInput && data.state_code !== undefined) {
				stateCodeInput.value = data.state_code || '';
			}
		})
		.catch(function() {
			citySelect.innerHTML = '<option value="">Select City</option>';
		});
}
</script>
