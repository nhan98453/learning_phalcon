<?php

class UsersController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {
        if($this->session->has('user'))
        {
            return json_encode(Users::find());
        }
    }
    public function logoutAction(){
        $this->session->destroy();
    }
    public function loginAction(){
        $username=$this->request->getPost('username');
        $password=$this->request->getPost('password');
        $user=Users::findFirstByUsername($username);
        if ($user) {
            if ($user->password == $password) 
                $this->session->set('user',$user);
        }
    }
}

