<?php

use CoreAPI\Helpers\ProductHelper;
use CoreAPI\Helpers\GeneralHelper;

class ProductController extends \Phalcon\Mvc\Controller
{
    public function indexAction()
    {
       if(GeneralHelper::checkPermission('Product', 'select')){
            $productHelper = new ProductHelper();
            $page = $_GET['page'] ?? null;
            $result = $productHelper->getProduct($page);
            return $result;
        }
        else
            return("You don't have permission");
      
    }

    public function createAction(){
        if(GeneralHelper::checkPermission('Product','create'))
        {
            $data=$this->request->getPost();
            $productHelper = new ProductHelper();
            return $productHelper->createProduct($data);
        }
        else
            return("You don't have permission");
    }
    public function editAction(){
        if(GeneralHelper::checkPermission('Product','update'))
        {   
            $id=$this->dispatcher->getParam('id');
            $data=$this->request->getPut();
            $productHelper = new ProductHelper();
            return $productHelper->updateProduct($id,$data);
        }
        else
            return("You don't have permission");
    }
    public function deleteAction(){
        if(GeneralHelper::checkPermission('Product','delete'))
        {
            $id=$this->dispatcher->getParam('id');
            $productHelper = new ProductHelper();
            return $productHelper->deleteProduct($id);
        }
        else
            return("You don't have permission");
    }
}


