<?php

  class Users extends Controller {
    public function __construct(){
      $this->userModel = $this->model('User');
    }


    public function register(){
      // Check for POST
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Process form
  
        // Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        // Init data
        $data =[
          'name' => trim($_POST['name']),
          'email' => trim($_POST['email']),
          'password' => trim($_POST['password']),
          'confirm_password' => trim($_POST['confirm_password']),
          'name_err' => '',
          'email_err' => '',
          'password_err' => '',
          'confirm_password_err' => ''
        ];

        // Validate Email
        if(empty($data['email'])){
          $data['email_err'] = 'Моля, въведете имейл адрес';
        } else {
          // Check email
          if($this->userModel->findUserByEmail($data['email'])){
            $data['email_err'] = 'Имейл адресът вече се използва';
          }
        }

        // Validate Name
        if(empty($data['name'])){
          $data['name_err'] = 'Моля, въведете име';
        }

        // Validate Password
        if(empty($data['password'])){
          $data['password_err'] = 'Моля, въведете парола';
        } elseif(strlen($data['password']) < 6){
          $data['password_err'] = 'Паролата трябва да е с дължина поне 6 символа';
        }

        // Validate Confirm Password
        if(empty($data['confirm_password'])){
          $data['confirm_password_err'] = 'Моля потвърдете паролата';
        } else {
          if($data['password'] != $data['confirm_password']){
            $data['confirm_password_err'] = 'Паролите не съвпадат';
          }
        }

        // Make sure errors are empty
        if(empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])){
          // Validated
          
          // Hash password
          $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        
          // Register user
          if($this->userModel->register($data)){
            flash('register_success', "Успешно се регистрирахте!");
            redirect('users/login');
          }else{
            die("Something went wrong");
          }
          
        } else {
          // Load view with errors
          $this->view('users/register', $data);
        }

      } else {
        // Init data
        $data =[
          'name' => '',
          'email' => '',
          'password' => '',
          'confirm_password' => '',
          'name_err' => '',
          'email_err' => '',
          'password_err' => '',
          'confirm_password_err' => ''
        ];

        // Load view
        $this->view('users/register', $data);
      }
    }

    public function profile() {
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $jsonFields = urldecode($_POST['jsonFields']);
            $cleanedJson = $this->clean_json_string($jsonFields);

            $fields = json_decode($cleanedJson, true);

            $this->userModel->updateUserProfile($fields);

            $userProfile = $this->userModel->getUserProfileById($_SESSION['user_id']);
            $data = [
                'userProfile' => $userProfile
            ];
            $this->view('users/profile', $data);
        }

        $userProfile = $this->userModel->getUserProfileById($_SESSION['user_id']);
        $data = [
            'userProfile' => $userProfile
        ];
        $this->view('users/profile', $data);
    }

    public function login(){
      // Check for POST
      if($_SERVER['REQUEST_METHOD'] == 'POST'){
        // Process form
        // Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        
        // Init data
        $data =[
          'email' => trim($_POST['email']),
          'password' => trim($_POST['password']),
          'email_err' => '',
          'password_err' => '',      
        ];

        // Validate Email
        if(empty($data['email'])){
          $data['email_err'] = 'Моля, въведете имейл адрес';
        }

        // Validate Password
        if(empty($data['password'])){
          $data['password_err'] = 'Моля, въведете парола';
        }

        if($this->userModel->findUserByEmail($data['email'])){
          
        }else{
          $data['email_err'] = 'Не съществува такъв потребител';
        }

        // Make sure errors are empty
        if(empty($data['email_err']) && empty($data['password_err'])){
          // Validated
          // Check and set logged in user
          $loggedInUser = $this->userModel->login($data['email'], $data['password']); // This holds either user info, or false
          
          if($loggedInUser){
            // Create session variables
            $this->createUserSession($loggedInUser);
          } else {
            $data['password_err'] = 'Грешна парола!';
            $this->view('users/login', $data);
          }
        } else {
          // Load view with errors
          $this->view('users/login', $data);
        }


      } else {
        // Init data
        $data =[    
          'email' => '',
          'password' => '',
          'email_err' => '',
          'password_err' => '',        
        ];

        // Load view
        $this->view('users/login', $data);
      }
    }

    public function createUserSession($user){
      $_SESSION['user_id'] = $user->id;
      $_SESSION['user_email'] = $user->email;
      $_SESSION['user_name'] = $user->name;
      $_SESSION['user_role'] = $user->role;

      redirect('disciplines'); //This will maybe become curriculums or some other welcoming page
    }

    public function logout(){
      unset($_SESSION['user_id']);
      unset($_SESSION['user_email']);
      unset($_SESSION['user_name']);
      unset($_SESSION['user_role']);
      session_destroy();
      redirect('users/login');
    }

    private function clean_json_string($jsonString) {
      $jsonString = preg_replace('/^\xEF\xBB\xBF/', '', $jsonString);
      $jsonString = preg_replace('/[[:cntrl:]&&:space:]]/', '', $jsonString);
      $jsonString = trim($jsonString);

      return $jsonString;
    }
  }