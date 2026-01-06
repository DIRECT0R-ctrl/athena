<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div style="max-width: 400px; margin: 0 auto;">
    <h2>Login to Your Account</h2>
    
    <form method="POST" action="/login" style="margin-top: 2rem;">
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        
        <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
    </form>
    
    <p style="text-align: center; margin-top: 1rem;">
        Don't have an account? <a href="/register">Register here</a>
    </p>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
