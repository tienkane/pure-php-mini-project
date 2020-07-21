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
            require_once  ROOT . '/App/Views/dashboard.php';
        }
    }
