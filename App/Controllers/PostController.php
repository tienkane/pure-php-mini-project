<?php
    class PostController extends Controller
    {
        public function __construct()
        {
            require_once 'LoginController.php';
            $login_controller = new LoginController();
            if (!$login_controller->checkLogin()) {
                $login_controller->getLogin();
                die();
            }
        }

        public function index()
        {
            $user_model = $this->loadModel('User');
            $post_model = $this->loadModel('Post');

            $user = $user_model->getUser($_SESSION['username']);
            $posts = $post_model->getAllPosts($user->id);

            $data['screen'] = 'dashboard';
            $data['user'] = $user;
            $data['posts'] = $posts;

            $this->loadView($data);
        }
    }
