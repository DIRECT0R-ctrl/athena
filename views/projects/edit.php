<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="edit-project-container">
    <div class="form-wrapper">
        <h1>✏️ Edit Project</h1>
        
        <form method="POST" action="/projects/<?php echo $project->getId(); ?>/update" class="project-form">
            <div class="form-group">
                <label for="title">Project Title *</label>
                <input type="text" id="title" name="title" class="form-control" 
                       value="<?php echo htmlspecialchars($project->getTitle()); ?>" required>
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control" rows="6"><?php echo htmlspecialchars($project->getDescription()); ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="is_active">
                    <input type="checkbox" id="is_active" name="is_active" <?php echo $project->isActive() ? 'checked' : ''; ?>>
                    Active Project
                </label>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Project</button>
                <a href="/projects/<?php echo $project->getId(); ?>" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<style>
.edit-project-container {
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

.form-group input[type="checkbox"] {
    margin-right: 8px;
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

.form-actions {
    display: flex;
    gap: 10px;
    margin-top: 30px;
}
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
