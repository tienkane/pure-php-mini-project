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

            $data['screen'] = 'layout';
            $data['page'] = 'dashboard';
            $data['user'] = $user;
            $data['posts'] = $posts;

            $this->loadView($data);
        }

        public function create()
        {
            $data['screen'] = 'layout';
            $data['page'] = 'create';
            $this->loadView($data);
        }

        public function insert()
        {
            $title = $_POST['title'];
            $content = $_POST['content'];

            $content = str_replace("'", '', $content);
            preg_replace('/[^A-Za-z0-9\-]/', '', $content);

            if (strlen($title) < 10) {
                $data['screen'] = 'layout';
                $data['page'] = 'create';
                $data['error'] = 'Tiêu đề phải chứa ít nhất 10 ký tự!';
                $data['title_value'] = $title;
                $data['content_value'] = $content;
                $this->loadView($data);
            } elseif (strlen($content) < 310) {
                $data['screen'] = 'layout';
                $data['page'] = 'create';
                $data['error'] = 'Nội dung phải chứa ít nhất 50 ký tự!';
                $data['title_value'] = $title;
                $data['content_value'] = $content;
                $this->loadView($data);
            } else {
                $user_model = $this->loadModel('User');
                $post_model = $this->loadModel('Post');

                $user = $user_model->getUser($_SESSION['username']);
                if (!$post_model->createPost($user->id, $title, $content)) {
                    $data['screen'] = 'layout';
                    $data['page'] = 'create';
                    $data['error'] = 'Tạo mới thất bại!';
                    $data['title_value'] = $title;
                    $data['content_value'] = $content;
                    $this->loadView($data);
                } else {
                    header('location: /');
                }
            }
        }

        public function edit()
        {
            $post_id = $_GET['post_id'];
            $model = $this->loadModel('Post');
            $post = $model->getPostById($post_id);
            $data['screen'] = 'layout';
            $data['page'] = 'edit';
            $data['post'] = $post;
            $this->loadView($data);
        }

        public function update()
        {
            $post_id = $_POST['post_id'];
            $title = $_POST['title'];
            $content = $_POST['content'];

            $content = str_replace("'", '', $content);
            preg_replace('/[^A-Za-z0-9\-]/', '', $content);

            if (strlen($title) < 10) {
                $data['screen'] = 'layout';
                $data['page'] = 'edit';
                $data['error'] = 'Tiêu đề phải chứa ít nhất 10 ký tự!';
                $data['title_value'] = $title;
                $data['content_value'] = $content;
                $this->loadView($data);
            } elseif (strlen($content) < 310) {
                $data['screen'] = 'layout';
                $data['page'] = 'edit';
                $data['error'] = 'Nội dung phải chứa ít nhất 50 ký tự!';
                $data['title_value'] = $title;
                $data['content_value'] = $content;
                $this->loadView($data);
            } else {
                $model = $this->loadModel('Post');

                if (!$model->editPost($post_id, $title, $content)) {
                    $data['screen'] = 'layout';
                    $data['page'] = 'edit';
                    $data['error'] = 'Chỉnh sửa thất bại!';
                    $data['title_value'] = $title;
                    $data['content_value'] = $content;
                    $this->loadView($data);
                } else {
                    header('location: /');
                }
            }
        }

        public function delete()
        {
            $post_id = $_GET['post_id'];
            $model = $this->loadModel('Post');
            try {
                $model->deletePost($post_id);
                header('location: /');
            } catch (Exception $e) {
                die($e->getMessage());
            }
        }
    }
