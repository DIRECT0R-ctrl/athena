<?php

class CommentController {
    private $commentRepo;
    private $taskRepo;
    private $userRepo;
    private $session;
    private $auth;
    
    public function __construct() {
        $this->commentRepo = new CommentRepository();
        $this->taskRepo = new TaskRepository();
        $this->userRepo = new UserRepository();
        $this->session = new Session();
        $this->auth = Auth::getInstance();
    }
    
    /**
     * Store new comment
     */
    public function store() {
        $this->auth->requireAuth();
        
        $task_id = $_POST['task_id'] ?? null;
        $content = trim($_POST['content'] ?? '');
        
        if (!$task_id || empty($content)) {
            http_response_code(400);
            echo "Invalid comment data";
            return;
        }
        
        $task = $this->taskRepo->find($task_id);
        if (!$task) {
            http_response_code(404);
            echo "Task not found";
            return;
        }
        
        try {
            $user = $this->auth->user();
            $comment = new Comment();
            $comment->setUserId($user['id'])
                   ->setTaskId($task_id)
                   ->setContent($content);
            
            $this->commentRepo->create($comment);
            
            // Send notification
            $commenter = $this->userRepo->find($user['id']);
            NotificationService::sendCommentAdded($comment, $commenter);
            
            $this->session->flash('success', 'Comment added successfully!');
            $this->redirect('/tasks/' . $task_id);
            
        } catch (Exception $e) {
            $this->session->flash('error', 'Error adding comment: ' . $e->getMessage());
            $this->redirect('/tasks/' . $task_id);
        }
    }
    
    /**
     * Delete comment
     */
    public function delete($id) {
        $this->auth->requireAuth();
        
        $comment = $this->commentRepo->find($id);
        
        if (!$comment) {
            http_response_code(404);
            echo "Comment not found";
            return;
        }
        
        // Authorization: only comment owner or admin
        $user = $this->auth->user();
        if ($comment->getUserId() != $user['id'] && $user['role_id'] != ROLE_ADMIN) {
            http_response_code(403);
            echo "Permission denied";
            return;
        }
        
        try {
            $task_id = $comment->getTaskId();
            $this->commentRepo->delete($id);
            
            $this->session->flash('success', 'Comment deleted!');
            $this->redirect('/tasks/' . $task_id);
            
        } catch (Exception $e) {
            $this->session->flash('error', 'Error deleting comment: ' . $e->getMessage());
            $this->redirect('/tasks/' . $comment->getTaskId());
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
