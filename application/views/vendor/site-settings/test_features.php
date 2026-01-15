<!DOCTYPE html>
<html>
<head>
    <title>Vendor Features Test</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .feature-card { border: 1px solid #ddd; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .feature-card h3 { margin-top: 0; }
        .info { background: #f0f0f0; padding: 10px; margin-bottom: 20px; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table th, table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        table th { background: #f0f0f0; }
    </style>
</head>
<body>
    <h1>Vendor Features Test Page</h1>
    
    <div class="info">
        <strong>Current Database:</strong> <?php echo htmlspecialchars($current_database); ?><br>
        <strong>Features Found:</strong> <?php echo isset($features) ? count($features) : 0; ?>
    </div>
    
    <?php if (isset($features) && is_array($features) && count($features) > 0): ?>
        <h2>All Features (<?php echo count($features); ?>)</h2>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Feature ID</th>
                    <th>Feature Name</th>
                    <th>Feature Slug</th>
                    <th>Image</th>
                    <th>Is Enabled</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($features as $feature): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($feature['id']); ?></td>
                        <td><?php echo htmlspecialchars($feature['feature_id']); ?></td>
                        <td><?php echo htmlspecialchars($feature['feature_name']); ?></td>
                        <td><?php echo htmlspecialchars($feature['feature_slug']); ?></td>
                        <td><?php echo !empty($feature['image']) ? htmlspecialchars($feature['image']) : 'NULL'; ?></td>
                        <td><?php echo $feature['is_enabled'] ? 'Yes' : 'No'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <h2>Feature Cards</h2>
        <?php foreach ($features as $feature): ?>
            <div class="feature-card">
                <h3><?php echo htmlspecialchars($feature['feature_name']); ?></h3>
                <p><strong>Slug:</strong> <?php echo htmlspecialchars($feature['feature_slug']); ?></p>
                <p><strong>ID:</strong> <?php echo htmlspecialchars($feature['id']); ?></p>
                <p><strong>Feature ID:</strong> <?php echo htmlspecialchars($feature['feature_id']); ?></p>
                <p><strong>Image:</strong> <?php echo !empty($feature['image']) ? htmlspecialchars($feature['image']) : 'No image'; ?></p>
                <p><strong>Enabled:</strong> <?php echo $feature['is_enabled'] ? 'Yes' : 'No'; ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert" style="background: #ffcccc; padding: 15px; border-radius: 5px;">
            <strong>No features found!</strong><br>
            Features variable is: <?php echo isset($features) ? (is_array($features) ? 'Array (empty)' : gettype($features)) : 'NOT SET'; ?>
        </div>
    <?php endif; ?>
    
    <hr>
    <p><a href="<?php echo base_url('webwork/site-settings'); ?>">Back to Site Settings</a></p>
</body>
</html>

