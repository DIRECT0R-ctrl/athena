<?php require_once __DIR__ . '/layouts/header.php'; ?>

<div class="hero">
    <h1>Welcome to <?php echo APP_NAME; ?></h1>
    <p>Your complete project management solution for agile teams</p>
    
    <?php if (!Auth::getInstance()->isLoggedIn()): ?>
        <div style="margin-top: 2rem;">
            <a href="/register" class="btn btn-primary" style="margin-right: 1rem;">Get Started</a>
            <a href="/login" class="btn">Login</a>
        </div>
    <?php else: ?>
        <div style="margin-top: 2rem;">
            <a href="/dashboard" class="btn btn-primary">Go to Dashboard</a>
        </div>
    <?php endif; ?>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; margin-top: 3rem;">
    <div class="card">
        <h3>📋 Project Management</h3>
        <p>Create, organize, and track projects with ease. Assign tasks and set deadlines.</p>
    </div>
    
    <div class="card">
        <h3>🚀 Agile Sprints</h3>
        <p>Plan sprints, track progress, and deliver value incrementally with Scrum methodology.</p>
    </div>
    
    <div class="card">
        <h3>👥 Team Collaboration</h3>
        <p>Work together seamlessly with task assignments, comments, and notifications.</p>
    </div>
    
    <div class="card">
        <h3>📊 Real-time Analytics</h3>
        <p>Get insights into team performance and project progress with detailed statistics.</p>
    </div>
</div>

<?php require_once __DIR__ . '/layouts/footer.php'; ?>
