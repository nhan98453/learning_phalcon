<?php
use Phalcon\Db;
use CoreAPI\Helpers\CategoryHelper;
use CoreAPI\Helpers\GeneralHelper;
class CategoryController extends \Phalcon\Mvc\Controller
{
   
    public function indexAction()
    {
        if(GeneralHelper::checkPermission('Category','select')){
            $CategoryHelper = new CategoryHelper();
            return $CategoryHelper->getCategory();
        }
        else
            return("You don't have permission");
    }
    public function createAction(){
        if(GeneralHelper::checkPermission('User','select'))
        {
            $data= $this->request->getPost();
            $CategoryHelper = new CategoryHelper();
            return $CategoryHelper->createCategory($data);
        }
        else
            return("You don't have permission");
    }
    public function editAction(){
        if(GeneralHelper::checkPermission('Category','update'))
        {
            $id=$this->dispatcher->getParam('id');
            $data=$this->request->getPut();
            $CategoryHelper = new CategoryHelper();
            return $CategoryHelper->updateCategory($id,$data);
        }
        else
            return("You don't have permission");
    }
    public function deleteAction(){
        if(GeneralHelper::checkPermission('Category','delete'))
        {
            $id=$this->dispatcher->getParam('id');
            $CategoryHelper = new CategoryHelper();
            return $CategoryHelper->deleteCategory($id);
        }
        else
            return("You don't have permission");
    }
}

