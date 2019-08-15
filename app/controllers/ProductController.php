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
            return json_encode($result);
        }
        else
            return("You don't have permission");
      
    }

    public function createAction(){
        if(GeneralHelper::checkPermission('Product','create'))
        {
            $data=$this->request->getPost();
            $productHelper = new ProductHelper();
            $result=$productHelper->createProduct($data);
            return "{'status':'$result'}";
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
            $result= $productHelper->updateProduct($id,$data);
            return "{'status':'$result'}";
        }
        else
            return("You don't have permission");
    }
    public function deleteAction(){
        if(GeneralHelper::checkPermission('Product','delete'))
        {
            $id=$this->dispatcher->getParam('id');
            $productHelper = new ProductHelper();
            $result=$productHelper->deleteProduct($id);
            return "{'status':'$result'}";
        }
        else
            return("You don't have permission");
    }
}


