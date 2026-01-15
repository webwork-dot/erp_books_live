<h1>Manage Features for <?php echo htmlspecialchars($vendor['name']); ?></h1>

<?php echo form_open('erp-admin/vendors/features/' . $vendor['id']); ?>
    <div class="card">
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Feature</th>
                        <th>Description</th>
                        <th>Enabled</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_features as $feature): ?>
                        <?php
                        $enabled = FALSE;
                        if (!empty($vendor_features)) {
                            foreach ($vendor_features as $vf) {
                                if (isset($vf['id']) && $vf['id'] == $feature['id'] && isset($vf['is_enabled']) && $vf['is_enabled']) {
                                    $enabled = TRUE;
                                    break;
                                }
                            }
                        }
                        ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($feature['name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($feature['description']); ?></td>
                            <td>
                                <input type="checkbox" name="features[<?php echo $feature['id']; ?>]" value="1" <?php echo $enabled ? 'checked' : ''; ?>>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
                <button type="submit" name="assign_features" class="btn btn-primary">Save Features</button>
                <a href="<?php echo base_url('erp-admin/vendors'); ?>" class="btn btn-outline">Back to Vendors</a>
            </div>
        </div>
    </div>
<?php echo form_close(); ?>

