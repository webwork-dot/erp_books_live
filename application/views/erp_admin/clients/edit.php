<h1>Edit Client</h1>

<?php echo form_open('erp-admin/clients/edit/' . $client['id']); ?>
    <div class="card" style="max-width: 600px;">
        <div class="card-body">
            <div class="form-group">
                <label for="name">Client Name *</label>
                <input type="text" name="name" id="name" class="form-control" value="<?php echo set_value('name', $client['name']); ?>" required>
                <?php echo form_error('name', '<div class="form-error">', '</div>'); ?>
            </div>
            
            <div class="form-group">
                <label for="domain">Domain *</label>
                <input type="text" name="domain" id="domain" class="form-control" value="<?php echo set_value('domain', $client['domain']); ?>" required>
                <?php echo form_error('domain', '<div class="form-error">', '</div>'); ?>
            </div>
            
            <div class="form-group">
                <label for="status">Status *</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="active" <?php echo set_select('status', 'active', $client['status'] == 'active'); ?>>Active</option>
                    <option value="suspended" <?php echo set_select('status', 'suspended', $client['status'] == 'suspended'); ?>>Suspended</option>
                    <option value="inactive" <?php echo set_select('status', 'inactive', $client['status'] == 'inactive'); ?>>Inactive</option>
                </select>
                <?php echo form_error('status', '<div class="form-error">', '</div>'); ?>
            </div>
            
            <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">Update Client</button>
                <a href="<?php echo base_url('erp-admin/clients'); ?>" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </div>
<?php echo form_close(); ?>
