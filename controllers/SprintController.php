<?php

class SprintController {
    private $sprintRepo;
    private $projectRepo;
    private $taskRepo;
    private $session;
    private $auth;
    
    public function __construct() {
        $this->sprintRepo = new SprintRepository();
        $this->projectRepo = new ProjectRepository();
        $this->taskRepo = new TaskRepository();
        $this->session = new Session();
        $this->auth = Auth::getInstance();
    }
    
    /**
     * Show sprint details
     */
    public function show($id) {
        $this->auth->requireAuth();
        
        $sprint = $this->sprintRepo->find($id);
        
        if (!$sprint) {
            http_response_code(404);
            echo "Sprint not found";
            return;
        }
        
        $project = $this->projectRepo->find($sprint->getProjectId());
        $tasks = $this->taskRepo->findBySprint($id);
        
        require_once __DIR__ . '/../views/sprints/show.php';
    }
    
    /**
     * Show create sprint form
     */
    public function create() {
        $this->auth->requireRole(ROLE_CHEF_PROJET);
        
        $project_id = $_GET['project_id'] ?? null;
        $project = null;
        
        if ($project_id) {
            $project = $this->projectRepo->find($project_id);
        }
        
        require_once __DIR__ . '/../views/sprints/create.php';
    }
    
    /**
     * Store new sprint
     */
    public function store() {
        $this->auth->requireRole(ROLE_CHEF_PROJET);
        
        $project_id = $_POST['project_id'] ?? null;
        $title = trim($_POST['title'] ?? '');
        $start_date = $_POST['start_date'] ?? '';
        $end_date = $_POST['end_date'] ?? '';
        
        $errors = [];
        
        if (!$project_id) {
            $errors['project'] = 'Project is required';
        }
        
        if (empty($title)) {
            $errors['title'] = 'Sprint title is required';
        } elseif (strlen($title) < 3) {
            $errors['title'] = 'Sprint title must be at least 3 characters';
        }
        
        if (empty($start_date)) {
            $errors['start_date'] = 'Start date is required';
        }
        
        if (empty($end_date)) {
            $errors['end_date'] = 'End date is required';
        }
        
        if (!empty($errors)) {
            $this->session->set('sprint_errors', $errors);
            $this->redirect('/sprints/create?project_id=' . $project_id);
        }
        
        try {
            $sprint = new Sprint();
            $sprint->setProjectId($project_id)
                   ->setTitle($title)
                   ->setStartDate($start_date)
                   ->setEndDate($end_date);
            
            if (!$sprint->isDateRangeValid()) {
                throw new Exception("End date must be after start date");
            }
            
            $savedSprint = $this->sprintRepo->create($sprint);
            
            $this->session->flash('success', 'Sprint created successfully!');
            $this->redirect('/sprints/' . $savedSprint->getId());
            
        } catch (Exception $e) {
            $this->session->flash('error', 'Error creating sprint: ' . $e->getMessage());
            $this->redirect('/sprints/create?project_id=' . $project_id);
        }
    }
    
    /**
     * Edit sprint
     */
    public function edit($id) {
        $this->auth->requireRole(ROLE_CHEF_PROJET);
        
        $sprint = $this->sprintRepo->find($id);
        
        if (!$sprint) {
            http_response_code(404);
            echo "Sprint not found";
            return;
        }
        
        $project = $this->projectRepo->find($sprint->getProjectId());
        
        require_once __DIR__ . '/../views/sprints/edit.php';
    }
    
    /**
     * Update sprint
     */
    public function update($id) {
        $this->auth->requireRole(ROLE_CHEF_PROJET);
        
        $sprint = $this->sprintRepo->find($id);
        
        if (!$sprint) {
            http_response_code(404);
            echo "Sprint not found";
            return;
        }
        
        $title = trim($_POST['title'] ?? '');
        $start_date = $_POST['start_date'] ?? '';
        $end_date = $_POST['end_date'] ?? '';
        
        try {
            $sprint->setTitle($title)
                   ->setStartDate($start_date)
                   ->setEndDate($end_date);
            
            if (!$sprint->isDateRangeValid()) {
                throw new Exception("End date must be after start date");
            }
            
            $this->sprintRepo->update($sprint);
            
            $this->session->flash('success', 'Sprint updated successfully!');
            $this->redirect('/sprints/' . $id);
            
        } catch (Exception $e) {
            $this->session->flash('error', 'Error updating sprint: ' . $e->getMessage());
            $this->redirect('/sprints/' . $id . '/edit');
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
