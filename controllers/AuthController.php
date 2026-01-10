<?php
// controllers/AuthController.php

class AuthController {
    private $userRepo;
    private $session;
    
    public function __construct() {
        $this->userRepo = new UserRepository();
        $this->session = new Session();
    }
    
    
    public function showRegister() {
        // if user is already logged in, redirect to dashboard
        if ($this->isLoggedIn()) {
            $this->redirect('/dashboard');
        }
        
        require_once __DIR__ . '/../views/auth/register.php';
    }
    
    public function register() {
        // check if user is already logged in
        if ($this->isLoggedIn()) {
            $this->redirect('/dashboard');
        }
        
        // Validate CSRF token (i'll implement this later please please do not forget)
        
        // Get form data
        $fullname = trim($_POST['fullname'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        
        // Validate input
        $errors = [];
        
        if (empty($fullname)) {
            $errors['fullname'] = 'Full name is required';
        } elseif (strlen($fullname) < 2) {
            $errors['fullname'] = 'Full name must be at least 2 characters';
        }
        
        if (empty($email)) {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        }
        
        if (empty($password)) {
            $errors['password'] = 'Password is required';
        } elseif (strlen($password) < 6) {
            $errors['password'] = 'Password must be at least 6 characters';
        }
        
        if ($password !== $confirm_password) {
            $errors['confirm_password'] = 'Passwords do not match';
        }
        
        // Check if email already exists
        if (empty($errors['email'])) {
            $existingUser = $this->userRepo->findByEmail($email);
            if ($existingUser) {
                $errors['email'] = 'Email already registered';
            }
        }
        
        // ila kano des error show the form avec les erreur
        if (!empty($errors)) {
            // Store errors in session to display in view
            $this->session->set('register_errors', $errors);
            $this->session->set('old_input', [
                'fullname' => $fullname,
                'email' => $email
            ]);
            $this->redirect('/register');
        }
        
        // create new user
        try {
            // Determine role: first user is admin, others are members
            $userCount = $this->userRepo->count();
            $role = ($userCount == 0) ? ROLE_ADMIN : ROLE_MEMBRE;
            
            $user = new User();
            $user->setFullname($fullname)
                 ->setEmail($email)
                 ->setPassword($password)
                 ->setRoleId($role); // Dynamic role assignment
            
            // making on db
            $savedUser = $user->save();
            
            // auto-login mn mor registration
            $this->loginUser($savedUser);
            
            // set success message
            $this->session->flash('success', 'Registration successful! Welcome to Athena!');
            
            // redirect to dashboard
            $this->redirect('/dashboard');
            
        } catch (Exception $e) {
            // Handle unexpected errors
            $this->session->flash('error', 'Registration failed: ' . $e->getMessage());
            $this->redirect('/register');
        }
    }
    
    public function showLogin() {
        // ila user is already logged in, redirect to dashboard
        if ($this->isLoggedIn()) {
            $this->redirect('/dashboard');
        }
        
        require_once __DIR__ . '/../views/auth/login.php';
    }
    
    /**
     * handle login form submission
     */
    public function login() {
        // check if user is already logged in
        if ($this->isLoggedIn()) {
            $this->redirect('/dashboard');
        }
        
        // get form data
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        // Validate input
        $errors = [];
        
        if (empty($email)) {
            $errors['email'] = 'Email is required';
        }
        
        if (empty($password)) {
            $errors['password'] = 'Password is required';
        }
        
        // If there are errors, show form again
        if (!empty($errors)) {
            $this->session->set('login_errors', $errors);
            $this->redirect('/login');
        }
        
        // Find user by email
        $user = $this->userRepo->findByEmail($email);
        
        if (!$user) {
            $errors['email'] = 'Invalid email or password';
            $this->session->set('login_errors', $errors);
            $this->redirect('/login');
        }
        
        // check if user is active
        if (!$user->isActive()) {
            $errors['email'] = 'Account is disabled';
            $this->session->set('login_errors', $errors);
            $this->redirect('/login');
        }
        
        // verify password
        if (!$user->verifyPassword($password)) {
            $errors['password'] = 'Invalid email or password';
            $this->session->set('login_errors', $errors);
            $this->redirect('/login');
        }
        
        // login successful
        $this->loginUser($user);
        
        // set success message
        $this->session->flash('success', 'Welcome back, ' . $user->getFullname() . '!');
        
        // redirect based on role
        if ($user->isAdmin()) {
            $this->redirect('/admin/dashboard');
        } else {
            $this->redirect('/dashboard');
        }
    }
    
    
    public function logout() {
        $this->session->destroy();
        $this->session->flash('success', 'You have been logged out successfully.');
        $this->redirect('/');
    }
    
    /**
     * Login user (set session data)
     */
    private function loginUser(User $user) {
        $this->session->set('user_id', $user->getId());
        $this->session->set('user_email', $user->getEmail());
        $this->session->set('user_fullname', $user->getFullname());
        $this->session->set('user_role', $user->getRoleId());
        $this->session->set('user_is_active', $user->isActive());
        $this->session->set('logged_in', true);
    }
    
    /**
     * Check if user is logged in
     */
    private function isLoggedIn() {
        return $this->session->get('logged_in', false);
    }
    
    /**
     * Redirect to a URL
     */
    private function redirect($url) {
        header('Location: ' . $url);
        exit;
    }
    

    public function getCurrentUser() {
        if (!$this->isLoggedIn()) {
            return null;
        }
        
        return $this->userRepo->find($this->session->get('user_id'));
    }
}
