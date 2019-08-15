<?php
use Phalcon\Db;
use CoreAPI\Helpers\UserGroupHelper;
use CoreAPI\Helpers\GeneralHelper;
class UserGroupController extends \Phalcon\Mvc\Controller
{
   
    public function indexAction()
    {
        if(GeneralHelper::checkPermission('UserGroup','select')){
            $userGroupHelper = new UserGroupHelper();
            return json_encode($userGroupHelper->getUser());
        }
        else
            return("You don't have permission");
    }
    public function createAction(){
        if(GeneralHelper::checkPermission('User','select'))
        {
            $data= $this->request->getPost();
            $userGroupHelper = new UserGroupHelper();
            return json_encode($userGroupHelper->createUserGroup($data));
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
            return json_encode($userHelper->updateUser($username,$data));
        }
        else
            return("You don't have permission");
    }
    public function deleteAction(){
        if(GeneralHelper::checkPermission('User','delete'))
        {
            $username=$this->dispatcher->getParam('username');
            $userHelper = new UserHelper();
            return json_encode($userHelper->deleteUser($username));
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

