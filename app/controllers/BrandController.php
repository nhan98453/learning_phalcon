<?php
use Phalcon\Db;
use CoreAPI\Helpers\BrandHelper;
use CoreAPI\Helpers\GeneralHelper;
class BrandController extends \Phalcon\Mvc\Controller
{
   
    public function indexAction()
    {
        if(GeneralHelper::checkPermission('Brand','select')){
            $BrandHelper = new BrandHelper();
            return $BrandHelper->getBrand();
        }
        else
            return("You don't have permission");
    }
    public function createAction(){
        if(GeneralHelper::checkPermission('User','select'))
        {
            $data= $this->request->getPost();
            $BrandHelper = new BrandHelper();
            return $BrandHelper->createBrand($data);
        }
        else
            return("You don't have permission");
    }
    public function editAction(){
        if(GeneralHelper::checkPermission('Brand','update'))
        {
            $id=$this->dispatcher->getParam('id');
            $data=$this->request->getPut();
            $BrandHelper = new BrandHelper();
            return $BrandHelper->updateBrand($id,$data);
        }
        else
            return("You don't have permission");
    }
    public function deleteAction(){
        if(GeneralHelper::checkPermission('Brand','delete'))
        {
            $id=$this->dispatcher->getParam('id');
            $BrandHelper = new BrandHelper();
            return $BrandHelper->deleteBrand($id);
        }
        else
            return("You don't have permission");
    }
}

