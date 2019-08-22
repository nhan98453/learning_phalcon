<?php
use Phalcon\Db;
use CoreAPI\Helpers\UserHelper;
use CoreAPI\Helpers\GeneralHelper;
class UserController extends \Phalcon\Mvc\Controller
{
   
    public function indexAction()
    {
        if(GeneralHelper::checkPermission('User','select')){
            $userHelper = new UserHelper();
            return $userHelper->getUser();
        }
        else
            return("You don't have permission");
    }
    public function createAction(){
        if(GeneralHelper::checkPermission('User','select'))
        {
            $data= $this->request->getPost();
            $userHelper = new UserHelper();
            return $userHelper->createUser($data);
        }
        else
            return("You don't have permission");
    }
    public function editAction(){
        if(GeneralHelper::checkPermission('User','update'))
        {
            $username=$this->dispatcher->getParam('username');
            $data=$this->request->getPut();
            $userHelper = new UserHelper();
            return $userHelper->updateUser($username,$data);
        }
        else
            return("You don't have permission");
    }
    public function deleteAction(){
        if(GeneralHelper::checkPermission('User','delete'))
        {
            $username=$this->dispatcher->getParam('username');
            $userHelper = new UserHelper();
            return $userHelper->deleteUser($username);
        }
        else
            return("You don't have permission");
    }
    public function logoutAction(){
        $this->session->destroy();
        return ('You re logged out');
    }
    public function loginAction(){
        $data= $this->request->getPost();
        $userHelper= new UserHelper();
        return $userHelper->loginUser($data);
    }
}

