<?php
$session = Session::getInstance();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Athena</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
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
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .stat-card h3 {
            color: #667eea;
            margin-bottom: 10px;
        }
        .stat-card .number {
            font-size: 2em;
            font-weight: bold;
            color: #333;
        }
        .section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .section h2 {
            margin-bottom: 20px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #f8f9fa;
        }
        .btn {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        .btn-success {
            background: #28a745;
            color: white;
        }
        .nav {
            margin-bottom: 20px;
        }
        .nav a {
            color: #007bff;
            text-decoration: none;
            margin-right: 15px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Admin Dashboard</h1>
            <p>Manage users, projects, and system statistics</p>
        </div>

        <div class="nav">
            <a href="/dashboard">Back to Dashboard</a>
            <a href="/logout">Logout</a>
        </div>

        <div class="stats">
            <div class="stat-card">
                <h3>Total Users</h3>
                <div class="number"><?php echo $stats['total_users']; ?></div>
                <p><?php echo $stats['active_users']; ?> active</p>
            </div>
            <div class="stat-card">
                <h3>Total Projects</h3>
                <div class="number"><?php echo $stats['total_projects']; ?></div>
                <p><?php echo $stats['active_projects']; ?> active</p>
            </div>
            <div class="stat-card">
                <h3>Total Sprints</h3>
                <div class="number"><?php echo $stats['total_sprints']; ?></div>
            </div>
            <div class="stat-card">
                <h3>Total Tasks</h3>
                <div class="number"><?php echo $stats['total_tasks']; ?></div>
                <p><?php echo $stats['completed_tasks']; ?> completed</p>
            </div>
        </div>

        <div class="section">
            <h2>Users</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user->getId(); ?></td>
                        <td><?php echo htmlspecialchars($user->getFullname()); ?></td>
                        <td><?php echo htmlspecialchars($user->getEmail()); ?></td>
                        <td><?php echo $user->getRoleId() == 1 ? 'Admin' : ($user->getRoleId() == 2 ? 'Chef' : 'Member'); ?></td>
                        <td><?php echo $user->getIsActive() ? 'Active' : 'Inactive'; ?></td>
                        <td>
                            <form method="POST" action="/admin/users/<?php echo $user->getId(); ?>/toggle" style="display: inline;">
                                <button type="submit" class="btn <?php echo $user->getIsActive() ? 'btn-danger' : 'btn-success'; ?>">
                                    <?php echo $user->getIsActive() ? 'Deactivate' : 'Activate'; ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="section">
            <h2>Projects</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Chef</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($projects as $project): ?>
                    <tr>
                        <td><?php echo $project->getId(); ?></td>
                        <td><?php echo htmlspecialchars($project->getTitle()); ?></td>
                        <td><?php echo htmlspecialchars($project->getChefName()); ?></td>
                        <td><?php echo $project->getIsActive() ? 'Active' : 'Inactive'; ?></td>
                        <td>
                            <form method="POST" action="/admin/projects/<?php echo $project->getId(); ?>/toggle" style="display: inline;">
                                <button type="submit" class="btn <?php echo $project->getIsActive() ? 'btn-danger' : 'btn-success'; ?>">
                                    <?php echo $project->getIsActive() ? 'Deactivate' : 'Activate'; ?>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>