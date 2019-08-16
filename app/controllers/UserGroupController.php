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
        if(GeneralHelper::checkPermission('UserGroup','update'))
        {
            $id=$this->dispatcher->getParam('id');
            $permission=$this->request->getPut('permission');
            $userGroupHelper = new UserGroupHelper();
            return json_encode($userGroupHelper->updateUserGroup($id,$permission));
        }
        else
            return("You don't have permission");
    }
    public function deleteAction(){
        if(GeneralHelper::checkPermission('UserGroup','delete'))
        {
            $id=$this->dispatcher->getParam('id');
            $userGroupHelper = new UserGroupHelper();
            return json_encode($userGroupHelper->deleteUserGroup($id));
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

