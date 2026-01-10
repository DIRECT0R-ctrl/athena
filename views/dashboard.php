<?php
$session = Session::getInstance();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Athena</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .header {
            background: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header h1 {
            color: #333;
            margin-bottom: 10px;
        }
        .header p {
            color: #666;
            font-size: 16px;
        }
        .welcome-section {
            background: white;
            padding: 40px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .welcome-section h2 {
            color: #667eea;
            margin-bottom: 20px;
            font-size: 28px;
        }
        .welcome-section p {
            color: #666;
            margin-bottom: 30px;
            font-size: 16px;
            line-height: 1.6;
        }
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-2px);
        }
        .btn-secondary {
            background: #764ba2;
            color: white;
        }
        .btn-secondary:hover {
            background: #633a8a;
            transform: translateY(-2px);
        }
        .btn-outline {
            background: transparent;
            color: #667eea;
            border: 2px solid #667eea;
        }
        .btn-outline:hover {
            background: #667eea;
            color: white;
        }
        .nav {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-bottom: 30px;
        }
        .nav a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        .nav a:hover {
            color: #764ba2;
        }
        .logout {
            background: #f56565;
            color: white;
        }
        .logout:hover {
            background: #e53e3e;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to Athena</h1>
            <p>Project Management System</p>
        </div>

        <div class="nav">
            <a href="/">Home</a>
            <a href="/projects">My Projects</a>
            <a href="/logout" class="logout" style="padding: 8px 20px; border-radius: 5px; text-decoration: none;">Logout</a>
        </div>

        <div class="welcome-section">
            <h2>Dashboard</h2>
            <p>Welcome back! You're now logged in and ready to manage your projects.</p>
            
            <div class="action-buttons">
                <a href="/projects" class="btn btn-primary">View My Projects</a>
                <a href="/projects/create" class="btn btn-secondary">Create New Project</a>
            </div>
        </div>
    </div>
</body>
</html>
