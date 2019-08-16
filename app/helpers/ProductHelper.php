<?php
namespace CoreAPI\Helpers;

use CoreAPI\Helpers\GeneralHelper;
use Phalcon\Mvc\User\Component;
use MongoDB\BSON\ObjectID;
class ProductHelper extends Component{
    public function getProduct($page = null){
        // $collection = $this->db->UserGroup;
        // return $collection;
        // die();
        $result=[];
        $collection=$this->db->Product;
        if($page)
        {
            $options = [
                "limit" => PRODUCT_PER_PAGE,
                "skip" => PRODUCT_PER_PAGE*$page,
            ];
            $allProduct= $collection->find([],$options);
            foreach($allProduct as $product){
                $result['data'][] = [
                    'name' => $product['name'],
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'date_created' => $product['date_created'],
                    'date_updated' => $product['date_updated']
                ];
            }
        }
        else{
            // $numberProduct= $this->db->fetchOne('SELECT count(id) as total FROM Product');
            // $pageProduct= $this->db->fetchAll("SELECT * FROM Product LIMIT ".PRODUCT_PER_PAGE);
            $allProduct= $collection->find();
            foreach($allProduct as $product){
                $result['data'][] = [
                    'name' => $product['name'],
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'date_created' => $product['date_created'],
                    'date_updated' => $product['date_updated']
                ];
            }
            $result['total']=$collection->count();
        }
        $res= json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
        return $res;
    }

    public function createProduct($data){
        $now=date("Y-m-d H:i:s");
        $name=$data['name'];
        $description=$data['description'];
        $price=$data['price'];
        $result=$this->db->Product->insertOne([
            "name"          => $name,
            "price"         => $price,
            "description"   => $description,
            "date_created"  => $now,
            "date_updated"  => $now,
        ]);
        $result=['insert'=>$result->getInsertedCount()];
        return json_encode($result,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    public function updateProduct($id, $data){
        $filter=['_id'=>new ObjectID($id)];
        $product= $this->db->Product->findOne($filter);
        if($product){
            $now=date("Y-m-d H:i:s");

            $result=$this->db->Product->updateOne($filter,[ '$set' => [
                'name' => $this->request->getPut('name') ?? $product['name'] ,
                'price' => $this->request->getPut('price') ?? $product['price'],
                'description' => $this->request->getPut('description') ?? $product['description'],
                'date_updated' => $now,
            ]]);
            $result=['updated'=>$result->getModifiedCount()];
            return json_encode($result,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
        else
            return 0;
    }
    public function deleteProduct($id){
        $filter=['_id'=>new ObjectID($id)];
        $result = $this->db->Product->deleteOne($filter);
        $result=['deleted'=>$result->getDeletedCount()];
        return json_encode($result,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
