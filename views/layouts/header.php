<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - Project Management</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            background: #f5f7fa;
            color: #333;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .navbar {
            background: #2c3e50;
            color: white;
            padding: 1rem 0;
        }
        
        .navbar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            text-decoration: none;
            color: white;
        }
        
        .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 1.5rem;
        }
        
        .nav-links a:hover {
            color: #3498db;
        }
        
        .main-content {
            padding: 2rem 0;
            min-height: calc(100vh - 140px);
        }
        
        .footer {
            background: #2c3e50;
            color: white;
            text-align: center;
            padding: 1rem 0;
            margin-top: 2rem;
        }
        
        .alert {
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: 1rem;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            text-decoration: none;
        }
        
        .btn:hover {
            background: #2980b9;
        }
        
        .btn-primary {
            background: #3498db;
        }
        
        .btn-danger {
            background: #e74c3c;
        }
        
        .btn-success {
            background: #2ecc71;
        }
        
        .card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="/" class="navbar-brand"><?php echo APP_NAME; ?></a>
            <div class="nav-links">
                <?php if (Auth::getInstance()->isLoggedIn()): ?>
                    <a href="/dashboard">Dashboard</a>
                    <a href="/projects">Projects</a>
                    <a href="/logout">Logout</a>
                <?php else: ?>
                    <a href="/login">Login</a>
                    <a href="/register">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
    
    <div class="main-content">
        <div class="container">
            <?php
            $session = new Session();
            $success = $session->flash('success');
            $error = $session->flash('error');
            
            if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
