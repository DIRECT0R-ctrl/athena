<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div style="max-width: 400px; margin: 0 auto;">
    <h2>Create Your Account</h2>
    
    <?php
    $errors = $session->get('register_errors', []);
    $old = $session->get('old_input', []);
    $session->remove('register_errors');
    $session->remove('old_input');
    ?>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <strong>Registration failed:</strong>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="/register" style="margin-top: 2rem;">
        <div class="form-group">
            <label for="fullname">Full Name</label>
            <input type="text" id="fullname" name="fullname" class="form-control" required value="<?php echo htmlspecialchars($old['fullname'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>">
        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" required>
            <small>Must be at least 6 characters long</small>
        </div>
        
        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
        </div>
        
        <button type="submit" class="btn btn-primary" style="width: 100%;">Register</button>
    </form>
    
    <p style="text-align: center; margin-top: 1rem;">
        Already have an account? <a href="/login">Login here</a>
    </p>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
