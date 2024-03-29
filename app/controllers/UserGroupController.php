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
            return $userGroupHelper->getUserGroup();
        }
        else
            return("You don't have permission");
    }
    public function createAction(){
        if(GeneralHelper::checkPermission('User','select'))
        {
            $data= $this->request->getPost();
            $userGroupHelper = new UserGroupHelper();
            return $userGroupHelper->createUserGroup($data);
        }
        else
            return("You don't have permission");
    }
    public function editAction(){
        if(GeneralHelper::checkPermission('UserGroup','update'))
        {
            $id=$this->dispatcher->getParam('id');
            $data=$this->request->getPut();
            $userGroupHelper = new UserGroupHelper();
            return $userGroupHelper->updateUserGroup($id,$data);
        }
        else
            return("You don't have permission");
    }
    public function deleteAction(){
        if(GeneralHelper::checkPermission('UserGroup','delete'))
        {
            $id=$this->dispatcher->getParam('id');
            $userGroupHelper = new UserGroupHelper();
            return $userGroupHelper->deleteUserGroup($id);
        }
        else
            return("You don't have permission");
    }
}

