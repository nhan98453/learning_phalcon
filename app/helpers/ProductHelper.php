<?php
namespace CoreAPI\Helpers;

use CoreAPI\Helpers\GeneralHelper;
use Phalcon\Mvc\User\Component;

class ProductHelper extends Component{
    public function getProduct($page = null){
        if($page)
        {
            $offset = PRODUCT_PER_PAGE * ($_GET['page']-1) ;
            $sql = "SELECT * FROM Product LIMIT {$offset} , ". PRODUCT_PER_PAGE;
            $result= $this->db->fetchAll($sql);
        }
        else{
            $numberProduct= $this->db->fetchOne('SELECT count(id) as total FROM Product');
            $pageProduct= $this->db->fetchAll("SELECT * FROM Product LIMIT ".PRODUCT_PER_PAGE);
            $result=array(
                "info"=>$numberProduct,
                "data"=>$pageProduct,
            );
        }
        return $result;
    }

    public function createProduct($data){
        $now=date("Y-m-d H:i:s");
        $name=$data['name'];
        $description=$data['description'];
        $price=$data['price'];
        return $this->db->execute("INSERT INTO Product(name,description,price,date_created,date_updated) VALUES('{$name}','{$description}','{$price}','{$now}','{$now}')");
    }
    public function updateProduct($id, $data){
        $product= $this->db->fetchOne("SELECT * FROM Product WHERE id='{$id}'");
        if($product){
            $name=$data['name'] ?? $product['name'];
            $description=$data['description'] ?? $product['description'];
            $price=$data['price'] ??  $product['price'];
            $now=date("Y-m-d H:i:s");

            return $this->db->execute("UPDATE Product SET name='{$name}',description='{$description}',price='{$price}',date_updated='{$now}' WHERE id='{$id}'");
        }
        else
            return 0;
    }
    public function deleteProduct($id){
        return $this->db->execute("DELETE FROM Product WHERE id='{$id}'");
    }
}
