<?php

class TaskController {
    private $taskRepo;
    private $sprintRepo;
    private $userRepo;
    private $commentRepo;
    private $session;
    private $auth;
    
    public function __construct() {
        $this->taskRepo = new TaskRepository();
        $this->sprintRepo = new SprintRepository();
        $this->userRepo = new UserRepository();
        $this->commentRepo = new CommentRepository();
        $this->session = new Session();
        $this->auth = Auth::getInstance();
    }
    
    /**
     * Show task details
     */
    public function show($id) {
        $this->auth->requireAuth();
        
        $task = $this->taskRepo->find($id);
        
        if (!$task) {
            http_response_code(404);
            echo "Task not found";
            return;
        }
        
        $sprint = $this->sprintRepo->find($task->getSprintId());
        $assignee = $task->getAssignedTo() ? $this->userRepo->find($task->getAssignedTo()) : null;
        $creator = $this->userRepo->find($task->getCreatorId());
        $comments = $this->commentRepo->findByTask($id);
        
        require_once 'views/tasks/show.php';
    }
    
    /**
     * Show create task form
     */
    public function create() {
        $this->auth->requireAuth();
        
        $sprint_id = $_GET['sprint_id'] ?? null;
        $sprint = null;
        
        if ($sprint_id) {
            $sprint = $this->sprintRepo->find($sprint_id);
        }
        
        // Get team members for assignment
        $members = [];
        if ($sprint) {
            $members = $this->userRepo->findAll();
        }
        
        require_once 'views/tasks/create.php';
    }
    
    /**
     * Store new task
     */
    public function store() {
        $this->auth->requireAuth();
        
        $sprint_id = $_POST['sprint_id'] ?? null;
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $assigned_to = $_POST['assigned_to'] ?? null;
        $priority_id = $_POST['priority_id'] ?? 2;
        
        $errors = [];
        
        if (!$sprint_id) {
            $errors['sprint'] = 'Sprint is required';
        }
        
        if (empty($title)) {
            $errors['title'] = 'Task title is required';
        } elseif (strlen($title) < 3) {
            $errors['title'] = 'Task title must be at least 3 characters';
        }
        
        if (!empty($errors)) {
            $this->session->set('task_errors', $errors);
            $this->redirect('/tasks/create?sprint_id=' . $sprint_id);
        }
        
        try {
            $user = $this->auth->user();
            $task = new Task();
            $task->setSprintId($sprint_id)
                 ->setCreatorId($user['id'])
                 ->setTitle($title)
                 ->setDescription($description)
                 ->setAssignedTo($assigned_to)
                 ->setPriorityId($priority_id)
                 ->setStatusId(1); // Default: todo
            
            $savedTask = $this->taskRepo->create($task);
            
            $this->session->flash('success', 'Task created successfully!');
            $this->redirect('/tasks/' . $savedTask->getId());
            
        } catch (Exception $e) {
            $this->session->flash('error', 'Error creating task: ' . $e->getMessage());
            $this->redirect('/tasks/create?sprint_id=' . $sprint_id);
        }
    }
    
    /**
     * Show edit form
     */
    public function edit($id) {
        $this->auth->requireAuth();
        
        $task = $this->taskRepo->find($id);
        
        if (!$task) {
            http_response_code(404);
            echo "Task not found";
            return;
        }
        
        $sprint = $this->sprintRepo->find($task->getSprintId());
        $members = $this->userRepo->findAll();
        
        // Authorization: only creator, assignee, or admin can edit
        $user = $this->auth->user();
        if ($task->getCreatorId() != $user['id'] && $task->getAssignedTo() != $user['id'] && $user['role_id'] != ROLE_ADMIN) {
            http_response_code(403);
            echo "You don't have permission to edit this task";
            return;
        }
        
        require_once 'views/tasks/edit.php';
    }
    
    /**
     * Update task
     */
    public function update($id) {
        $this->auth->requireAuth();
        
        $task = $this->taskRepo->find($id);
        
        if (!$task) {
            http_response_code(404);
            echo "Task not found";
            return;
        }
        
        // Authorization
        $user = $this->auth->user();
        if ($task->getCreatorId() != $user['id'] && $task->getAssignedTo() != $user['id'] && $user['role_id'] != ROLE_ADMIN) {
            http_response_code(403);
            echo "Permission denied";
            return;
        }
        
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $assigned_to = $_POST['assigned_to'] ?? null;
        $status_id = $_POST['status_id'] ?? $task->getStatusId();
        $priority_id = $_POST['priority_id'] ?? $task->getPriorityId();
        
        try {
            $task->setTitle($title)
                 ->setDescription($description)
                 ->setAssignedTo($assigned_to)
                 ->setStatusId($status_id)
                 ->setPriorityId($priority_id);
            
            $this->taskRepo->update($task);
            
            $this->session->flash('success', 'Task updated successfully!');
            $this->redirect('/tasks/' . $id);
            
        } catch (Exception $e) {
            $this->session->flash('error', 'Error updating task: ' . $e->getMessage());
            $this->redirect('/tasks/' . $id . '/edit');
        }
    }
    
    /**
     * Delete task
     */
    public function delete($id) {
        $this->auth->requireAuth();
        
        $task = $this->taskRepo->find($id);
        
        if (!$task) {
            http_response_code(404);
            echo "Task not found";
            return;
        }
        
        // Authorization: only creator or admin
        $user = $this->auth->user();
        if ($task->getCreatorId() != $user['id'] && $user['role_id'] != ROLE_ADMIN) {
            http_response_code(403);
            echo "Permission denied";
            return;
        }
        
        try {
            $sprint_id = $task->getSprintId();
            $this->taskRepo->delete($id);
            
            $this->session->flash('success', 'Task deleted successfully!');
            $this->redirect('/sprints/' . $sprint_id);
            
        } catch (Exception $e) {
            $this->session->flash('error', 'Error deleting task: ' . $e->getMessage());
            $this->redirect('/tasks/' . $id);
        }
    }
    
    /**
     * Redirect
     */
    private function redirect($url) {
        header('Location: ' . $url);
        exit;
    }
}
