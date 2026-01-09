<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="project-details">
    <div class="project-header">
        <h1><?php echo htmlspecialchars($project->getTitle()); ?></h1>
        <div class="project-actions">
            <?php if ($user['role_id'] == ROLE_CHEF_PROJET || $user['role_id'] == ROLE_ADMIN): ?>
                <a href="/projects/<?php echo $project->getId(); ?>/edit" class="btn btn-warning">Edit</a>
            <?php endif; ?>
            <a href="/projects" class="btn btn-secondary">Back</a>
        </div>
    </div>
    
    <div class="project-info">
        <div class="info-section">
            <h3>📝 Description</h3>
            <p><?php echo htmlspecialchars($project->getDescription()); ?></p>
        </div>
        
        <div class="info-grid">
            <div class="info-item">
                <strong>Status:</strong>
                <span class="status <?php echo $project->isActive() ? 'active' : 'inactive'; ?>">
                    <?php echo $project->isActive() ? '✓ Active' : '✗ Inactive'; ?>
                </span>
            </div>
            <div class="info-item">
                <strong>Created:</strong>
                <span><?php echo date('M d, Y', strtotime($project->getCreatedAt())); ?></span>
            </div>
        </div>
    </div>
    
    <div class="project-content">
        <div class="section">
            <h2>👥 Team Members (<?php echo count($members); ?>)</h2>
            <?php if (empty($members)): ?>
                <p>No members yet</p>
            <?php else: ?>
                <div class="members-list">
                    <?php foreach ($members as $member): ?>
                        <div class="member-card">
                            <div class="member-info">
                                <strong><?php echo htmlspecialchars($member->getFullname()); ?></strong>
                                <small><?php echo htmlspecialchars($member->getEmail()); ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="section">
            <div class="sprints-header">
                <h2>📅 Sprints (<?php echo count($sprints); ?>)</h2>
                <?php if ($user['role_id'] == ROLE_CHEF_PROJET || $user['role_id'] == ROLE_ADMIN): ?>
                    <a href="/sprints/create?project_id=<?php echo $project->getId(); ?>" class="btn btn-small btn-primary">+ New Sprint</a>
                <?php endif; ?>
            </div>
            
            <?php if (empty($sprints)): ?>
                <p>No sprints yet. Create one to get started!</p>
            <?php else: ?>
                <div class="sprints-list">
                    <?php foreach ($sprints as $sprint): ?>
                        <div class="sprint-card">
                            <h3><?php echo htmlspecialchars($sprint->getTitle()); ?></h3>
                            <div class="sprint-dates">
                                <small>
                                    <?php echo date('M d', strtotime($sprint->getStartDate())); ?> - 
                                    <?php echo date('M d, Y', strtotime($sprint->getEndDate())); ?>
                                </small>
                            </div>
                            <a href="/sprints/<?php echo $sprint->getId(); ?>" class="btn btn-small btn-info">View Sprint</a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.project-details {
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

.project-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    border-bottom: 2px solid #007bff;
    padding-bottom: 20px;
}

.project-header h1 {
    margin: 0;
}

.project-actions {
    display: flex;
    gap: 10px;
}

.project-info {
    background: #f9f9f9;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 30px;
}

.info-section {
    margin-bottom: 20px;
}

.info-section h3 {
    margin-bottom: 10px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.info-item {
    background: white;
    padding: 15px;
    border-radius: 4px;
}

.status {
    padding: 4px 8px;
    border-radius: 4px;
    font-weight: bold;
    display: inline-block;
    margin-top: 5px;
}

.status.active {
    background-color: #d4edda;
    color: #155724;
}

.status.inactive {
    background-color: #f8d7da;
    color: #721c24;
}

.project-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
}

.section {
    background: white;
    padding: 20px;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
}

.sprints-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.members-list {
    display: grid;
    gap: 10px;
}

.member-card {
    background: #f9f9f9;
    padding: 12px;
    border-radius: 4px;
    border-left: 3px solid #007bff;
}

.member-info {
    display: flex;
    flex-direction: column;
}

.member-info small {
    color: #666;
}

.sprints-list {
    display: grid;
    gap: 12px;
}

.sprint-card {
    background: #f9f9f9;
    padding: 15px;
    border-radius: 4px;
    border-left: 4px solid #28a745;
}

.sprint-card h3 {
    margin: 0 0 8px 0;
}

.sprint-dates {
    color: #666;
    margin-bottom: 10px;
}

@media (max-width: 768px) {
    .project-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .project-actions {
        margin-top: 15px;
    }
    
    .project-content {
        grid-template-columns: 1fr;
    }
}
</style>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
