<?php

class UsersController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {
        return json_encode($this->session->get('user'));
        $users = Users::find();
        return json_encode($users);
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

