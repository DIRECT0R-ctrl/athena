<?php

<?php

class ProjectController {
    private $projectRepo;
    private $sprintRepo;
    private $taskRepo;
    private $userRepo;
    private $session;
    private $auth;
    
    public function __construct() {
        $this->projectRepo = new ProjectRepository();
        $this->sprintRepo = new SprintRepository();
        $this->taskRepo = new TaskRepository();
        $this->userRepo = new UserRepository();
        $this->session = new Session();
        $this->auth = Auth::getInstance();
    }
    
    /**
     * List all projects
     */
    public function index() {
        $this->auth->requireAuth();
        
        $user = $this->auth->user();
        $projects = $this->projectRepo->findAll();
        
        require_once 'views/projects/index.php';
    }
    
    /**
     * Show project details
     */
    public function show($id) {
        $this->auth->requireAuth();
        
        $project = $this->projectRepo->find($id);
        
        if (!$project) {
            http_response_code(404);
            echo "Project not found";
            return;
        }
        
        $sprints = $this->sprintRepo->findByProject($id);
        $members = $this->projectRepo->getMembers($id);
        
        require_once 'views/projects/show.php';
    }
    
    /**
     * Show create project form
     */
    public function create() {
        $this->auth->requireRole(ROLE_CHEF_PROJET);
        
        require_once 'views/projects/create.php';
    }
    
    /**
     * Store new project
     */
    public function store() {
        $this->auth->requireRole(ROLE_CHEF_PROJET);
        
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        
        $errors = [];
        
        if (empty($title)) {
            $errors['title'] = 'Project title is required';
        } elseif (strlen($title) < 3) {
            $errors['title'] = 'Project title must be at least 3 characters';
        }
        
        if (!empty($errors)) {
            $this->session->set('create_errors', $errors);
            $this->redirect('/projects/create');
        }
        
        try {
            $user = $this->auth->user();
            $project = new Project();
            $project->setChefProjetId($user['id'])
                   ->setTitle($title)
                   ->setDescription($description);
            
            $savedProject = $this->projectRepo->create($project);
            
            // Add creator as member
            $this->projectRepo->addMember($savedProject->getId(), $user['id']);
            
            $this->session->flash('success', 'Project created successfully!');
            $this->redirect('/projects/' . $savedProject->getId());
            
        } catch (Exception $e) {
            $this->session->flash('error', 'Error creating project: ' . $e->getMessage());
            $this->redirect('/projects/create');
        }
    }
    
    /**
     * Show edit form
     */
    public function edit($id) {
        $this->auth->requireAuth();
        
        $project = $this->projectRepo->find($id);
        
        if (!$project) {
            http_response_code(404);
            echo "Project not found";
            return;
        }
        
        // Only chef de projet or admin can edit
        $user = $this->auth->user();
        if ($project->getChefProjetId() != $user['id'] && $user['role_id'] != ROLE_ADMIN) {
            http_response_code(403);
            echo "You don't have permission to edit this project";
            return;
        }
        
        require_once 'views/projects/edit.php';
    }
    
    /**
     * Update project
     */
    public function update($id) {
        $this->auth->requireAuth();
        
        $project = $this->projectRepo->find($id);
        
        if (!$project) {
            http_response_code(404);
            echo "Project not found";
            return;
        }
        
        // Authorization check
        $user = $this->auth->user();
        if ($project->getChefProjetId() != $user['id'] && $user['role_id'] != ROLE_ADMIN) {
            http_response_code(403);
            echo "Permission denied";
            return;
        }
        
        $title = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $is_active = isset($_POST['is_active']) ? 1 : 0;
        
        try {
            $project->setTitle($title)
                   ->setDescription($description)
                   ->setIsActive($is_active);
            
            $this->projectRepo->update($project);
            
            $this->session->flash('success', 'Project updated successfully!');
            $this->redirect('/projects/' . $id);
            
        } catch (Exception $e) {
            $this->session->flash('error', 'Error updating project: ' . $e->getMessage());
            $this->redirect('/projects/' . $id . '/edit');
        }
    }
    
    /**
     * Add member to project
     */
    public function addMember($project_id) {
        $this->auth->requireAuth();
        
        $project = $this->projectRepo->find($project_id);
        $user_id = $_POST['user_id'] ?? null;
        
        if (!$project || !$user_id) {
            $this->session->flash('error', 'Invalid request');
            $this->redirect('/projects/' . $project_id);
            return;
        }
        
        // Authorization
        $user = $this->auth->user();
        if ($project->getChefProjetId() != $user['id'] && $user['role_id'] != ROLE_ADMIN) {
            $this->session->flash('error', 'Permission denied');
            $this->redirect('/projects/' . $project_id);
            return;
        }
        
        try {
            $this->projectRepo->addMember($project_id, $user_id);
            $this->session->flash('success', 'Member added to project!');
        } catch (Exception $e) {
            $this->session->flash('error', 'Error adding member: ' . $e->getMessage());
        }
        
        $this->redirect('/projects/' . $project_id);
    }
    
    /**
     * Redirect
     */
    private function redirect($url) {
        header('Location: ' . $url);
        exit;
    }
}