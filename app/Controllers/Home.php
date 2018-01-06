<?php

namespace App\Controllers;

class Home extends Base_controller
{
    public $user;
    public $instructor;
    public $student;
    public $course;




    public function __construct($request,$response,$params)
    {
        parent::__construct($request,$response,$params);
        $this->user = new \App\Models\User();
    }


    public function index()
    {
        if (!$this->user->isLoggedIn()){
            return $this->redirect("/home/login/");
        }
        $courses = new \App\Models\Course_Collection();
        $events = new \App\Models\EventCollection();
        $posts_collection = new \App\Models\PostCollection($this->user->getPageId());
        $data = [];
        $data['user'] = $this->user;
        $data['courses'] = $courses->getCoursesForStudent($this->user->getId());
        $data['events'] = $events->getUserRunningEvents($this->user->getId());
        $data['posts'] = $posts_collection->getTimelinePosts($this->user->getId());
        $data['post_page_id'] = $this->user->getPageId();
        echo $this->view->load('frontend:home', $data);
        
    }

    public function getTimelinePosts()
    {
        if (!$this->user->isLoggedIn())
            return $this->redirect("/home/login/");

        if ($this->request->getQueryParam('id')) 
            $profile = new \App\Models\User($this->request->getQueryParam('id'));
        else 
            $profile = $this->user;

         $page = new \App\Models\Page();
 
       
        $data = [];
        $data['profile'] = $profile;
        $data['user'] = $this->user;
        $data['page'] = $page->getUserPage($profile->getId());
        $data['type'] = 'profile';
        $posts_collection = new \App\Models\PostCollection($page->getId());
        

        if(!$this->request->getQueryParam('start')||!$this->request->getQueryParam('limit'))
            die('no way!');
        $data['posts'] = $posts_collection->getPagePosts($this->request->getQueryParam('start'), $this->request->getQueryParam('limit'));
      

        return $this->view->load('frontend-parts/post-view',$data);
    }

    public function login()
    {
        $data = $this->request->getParsedBody();
        if(isset($data['identification'])&&isset($data['password'])) {
            if ($token = $this->user->login($data['identification'], $data['password'])) {
                
                return $this->redirect("/");
            }

            $this->Session::flash("error_login","The identification or password is incorrect!");
            return $this->redirect("/home/login/");
        } else {
            echo $this->view->load('login');
        }
    }

    public function logout()
    {
        if (!$this->user->isLoggedIn()){
            return $this->redirect("/home/login/");
        } else {
            $this->user->logout();
            return $this->redirect("/home/login/");
        }
    }
}