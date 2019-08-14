<?php

class ProductController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {
        if($this->request->isGet()){
            if($this->session->has('user'))
            {
                if($this->checkPermission($this->session->get('user'),'ReadProduct')){
                    $Products= $this->db->fetchAll('SELECT * FROM Product');
                    return json_encode($Products);
                }
                else
                    return("You don't have permission");
            }
            else
                return("This route is not supported");
        }
    }
    public function createAction(){
        if($this->request->isPost()){
            if($this->session->has('user'))
            {
                if($this->checkPermission($this->session->get('user'),'AddProduct'))
                {
                    $name=$this->request->getPost('name');
                    $description=$this->request->getPost('description');
                    $price=$this->request->getPost('price');
                    $now=date("Y-m-d H:i:s");

                    $result=$this->db->execute("INSERT INTO Product(name,description,price,date_created,date_updated) VALUES('{$name}','{$description}','{$price}','{$now}','{$now}')");
                    return json_encode($result);
                }
                else
                    return("You don't have permission");
            }
            else   
                return("You aren't logged in");
        }
        else 
            return("This route is not supported");
    }
    public function editAction(){
        if($this->request->isPut()){
            if($this->session->has('user'))
            {
                if($this->checkPermission($this->session->get('user'),'EditProduct'))
                {   $id=$this->request->getPut('id');
                    $product= $this->db->fetchOne("SELECT * FROM Product WHERE id='{$id}'");
                    if($product){
                        $name=$this->request->getPut('name',null,$product['name']);
                        $description=$this->request->getPut('description',null,$product['description']);
                        $price=$this->request->getPut('price',null,$product['price']);
                        $now=date("Y-m-d H:i:s");

                        $result=$this->db->execute("UPDATE Product SET name='{$name}',description='{$description}',price='{$price}',date_updated='{$now}' WHERE id='{$id}'");
                        return json_encode($result);
                    }
                    else
                        return("We don't have this products");
                }
                else
                    return("You don't have permission");
            }
            else   
                return("You aren't logged in");
        }
        else 
            return("This route is not supported");
    }
    public function deleteAction(){
        if($this->request->isDelete()){
            if($this->session->has('user'))
            {
                if($this->checkPermission($this->session->get('user'),'DeleteProduct'))
                {
                    $id=$this->request->getPut('id');
                    $result=$this->db->execute("DELETE FROM Product WHERE id='{$id}'");
                    return json_encode($result);
                }
                else
                    return("You don't have permission");
            }
            else   
                return("You aren't logged in");
        }
        else 
            return("This route is not supported");
    }
    protected function checkPermission($user,$namePermission){
        
        $permission= $this->db->fetchOne("SELECT * FROM Permission WHERE name='{$namePermission}'");
        $userGroup= $this->db->fetchOne("SELECT * FROM UsersGroup WHERE id='{$user["usergroup"]}'");
        if( ( intval($userGroup["permission"]) & intval($permission["value"]) ) != 0 ) 
            return true;
        return false;
    }
}


