<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="create-project-container">
    <div class="form-wrapper">
        <h1>➕ Create New Project</h1>
        
        <?php
        $errors = $session->get('create_errors', []);
        $session->remove('create_errors');
        ?>
        
        <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <strong>Validation errors:</strong>
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="/projects/store" class="project-form">
            <div class="form-group">
                <label for="title">Project Title *</label>
                <input type="text" id="title" name="title" class="form-control" 
                       value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>" 
                       placeholder="Enter project title" required>
                <small>Min 3 characters</small>
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control" 
                          rows="6" placeholder="Describe your project..."><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Create Project</button>
                <a href="/projects" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<style>
.create-project-container {
    padding: 40px 20px;
    max-width: 600px;
    margin: 0 auto;
}

.form-wrapper {
    background: white;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.project-form {
    margin-top: 25px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    color: #333;
}

.form-control {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    font-family: Arial, sans-serif;
}

.form-control:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 5px rgba(0,123,255,0.25);
}

.form-group small {
    display: block;
    margin-top: 5px;
    color: #999;
    font-size: 12px;
}

.form-actions {
    display: flex;
    gap: 10px;
    margin-top: 30px;
}

.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 4px;
}

.alert-error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.alert ul {
    margin: 10px 0 0 20px;
}
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
