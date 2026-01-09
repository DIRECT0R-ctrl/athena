<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="projects-container">
    <div class="projects-header">
        <h1>📊 Projects</h1>
        <?php if ($user['role_id'] == ROLE_CHEF_PROJET || $user['role_id'] == ROLE_ADMIN): ?>
            <a href="/projects/create" class="btn btn-primary">+ New Project</a>
        <?php endif; ?>
    </div>
    
    <?php if (empty($projects)): ?>
        <div class="empty-state">
            <p>No projects yet. <?php if ($user['role_id'] == ROLE_CHEF_PROJET): ?>Create your first project!</p>
                <a href="/projects/create" class="btn btn-primary">Create Project</a>
            <?php else: ?>No projects assigned to you yet.</p>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="projects-grid">
            <?php foreach ($projects as $project): ?>
                <div class="project-card">
                    <h3><?php echo htmlspecialchars($project->getTitle()); ?></h3>
                    <p class="project-description">
                        <?php echo htmlspecialchars(substr($project->getDescription(), 0, 100)); ?>...
                    </p>
                    <div class="project-meta">
                        <span class="project-status <?php echo $project->isActive() ? 'active' : 'inactive'; ?>">
                            <?php echo $project->isActive() ? '✓ Active' : '✗ Inactive'; ?>
                        </span>
                        <small class="project-date">
                            Created: <?php echo date('M d, Y', strtotime($project->getCreatedAt())); ?>
                        </small>
                    </div>
                    <div class="project-actions">
                        <a href="/projects/<?php echo $project->getId(); ?>" class="btn btn-small btn-info">View</a>
                        <?php if ($user['role_id'] == ROLE_CHEF_PROJET || $user['role_id'] == ROLE_ADMIN): ?>
                            <a href="/projects/<?php echo $project->getId(); ?>/edit" class="btn btn-small btn-warning">Edit</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.projects-container {
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

.projects-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.projects-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}

.project-card {
    background: white;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: box-shadow 0.3s;
}

.project-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.project-card h3 {
    margin: 0 0 10px 0;
    color: #333;
}

.project-description {
    color: #666;
    margin-bottom: 15px;
    font-size: 14px;
}

.project-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    font-size: 12px;
}

.project-status {
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: bold;
}

.project-status.active {
    background-color: #d4edda;
    color: #155724;
}

.project-status.inactive {
    background-color: #f8d7da;
    color: #721c24;
}

.project-date {
    color: #999;
}

.project-actions {
    display: flex;
    gap: 10px;
}

.btn-small {
    padding: 6px 12px;
    font-size: 12px;
}

.empty-state {
    text-align: center;
    padding: 40px 20px;
}
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
