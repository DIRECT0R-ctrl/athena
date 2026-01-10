<?php

class AdminController {
    private $userRepo;
    private $projectRepo;
    private $sprintRepo;
    private $taskRepo;
    private $auth;
    
    public function __construct() {
        $this->userRepo = new UserRepository();
        $this->projectRepo = new ProjectRepository();
        $this->sprintRepo = new SprintRepository();
        $this->taskRepo = new TaskRepository();
        $this->auth = Auth::getInstance();
    }
    
    public function index() {
        $this->auth->requireRole(ROLE_ADMIN);
        
        $stats = [
            'total_users' => $this->userRepo->count(),
            'active_users' => $this->userRepo->count(['is_active' => true]),
            'total_projects' => $this->projectRepo->count(),
            'active_projects' => $this->projectRepo->count(['is_active' => true]),
            'total_sprints' => $this->sprintRepo->count(),
            'total_tasks' => $this->taskRepo->count(),
            'completed_tasks' => $this->taskRepo->count(['status_id' => STATUS_DONE]),
        ];
        
        $users = $this->userRepo->findAll();
        $projects = $this->projectRepo->findAll();
        
        require_once __DIR__ . '/../views/admin/dashboard.php';
    }
    
    public function toggleUser($id) {
        $this->auth->requireRole(ROLE_ADMIN);
        
        $user = $this->userRepo->find($id);
        if ($user) {
            $user->setIsActive(!$user->getIsActive());
            $this->userRepo->update($user);
        }
        
        $this->redirect('/admin/dashboard');
    }
    
    public function toggleProject($id) {
        $this->auth->requireRole(ROLE_ADMIN);
        
        $project = $this->projectRepo->find($id);
        if ($project) {
            $project->setIsActive(!$project->getIsActive());
            $this->projectRepo->update($project);
        }
        
        $this->redirect('/admin/dashboard');
    }
}