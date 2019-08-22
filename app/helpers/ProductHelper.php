<?php
namespace CoreAPI\Helpers;

use CoreAPI\Helpers\GeneralHelper;
use Phalcon\Mvc\User\Component;
use MongoDB\BSON\Regex;
class ProductHelper extends Component{
    public function getProduct($data){
        $category_id=isset($data['category_id'])?intval($data['category_id']):null;
        $brand_id=isset($data['brand_id'])?intval($data['brand_id']):null;
        $keyword=isset($data["keyword"])?new Regex($data["keyword"],'i'):null;

        $filter=[
            'category_id'=>$category_id??['$ne'=>$category_id],
            'brand_id'=>$brand_id??['$ne'=>$brand_id],
            'name'=>$keyword??['$ne'=>$keyword]
        ];
        echo(json_encode($filter));die();
        if(isset($data['page']))
        {
            $options = [
                "limit" => PRODUCT_PER_PAGE,
                "skip" => PRODUCT_PER_PAGE*$data['page'],
            ];
            $allProduct= $this->db->Product->aggregate([
                [
                    '$match' =>$filter,       
                ],
                [
                    '$limit' => PRODUCT_PER_PAGE, 
                ],
                [
                    '$skip'  => PRODUCT_PER_PAGE*($data['page']-1),   
                ],
                [
                    '$lookup' =>[
                        "from" => "Category",
                        "localField"=>"category_id",
                        "foreignField"=>'id',
                        "as"=>"Category",
                    ],
                ],
                [
                    '$lookup' =>[
                        "from" => "Brand",
                        "localField"=>"brand_id",
                        "foreignField"=>'id',
                        "as"=>"Brand",
                    ],
                ]

            ]);
            //$allProduct= $this->db->Product->find($filter,$options);
            foreach($allProduct as $product){
                foreach($product['Category'] as $cat)
                    foreach($product['Brand'] as $bra)
                    $result['data'][] = [
                        'id' => $product['id'],
                        'name' => $product['name'],
                        'description' => $product['description'],
                        'price' => $product['price'],
                        'Category' => $cat['name'],
                        'Brand'=>$bra['name'],
                        'date_created' => $product['date_created'],
                        'date_updated' => $product['date_updated']
                    ];
                    
            }
        }
        else{
            // $numberProduct= $this->db->fetchOne('SELECT count(id) as total FROM Product');
            // $pageProduct= $this->db->fetchAll("SELECT * FROM Product LIMIT ".PRODUCT_PER_PAGE);
           // $allProduct= $this->db->Product->find($filter);
            $allProduct= $this->db->Product->aggregate([
                [
                    '$match' =>$filter,       
                ],
                [
                    '$lookup' =>[
                        "from" => "Category",
                        "localField"=>"category_id",
                        "foreignField"=>'id',
                        "as"=>"Category",
                    ],
                ],
                [
                    '$lookup' =>[
                        "from" => "Brand",
                        "localField"=>"brand_id",
                        "foreignField"=>'id',
                        "as"=>"Brand",
                    ],
                ]

            ]);
            foreach($allProduct as $product){
                foreach($product['Category'] as $cat)
                    foreach($product['Brand'] as $bra)
                    $result['data'][] = [
                        'id' => $product['id'],
                        'name' => $product['name'],
                        'description' => $product['description'],
                        'price' => $product['price'],
                        'Category' => $cat['name'],
                        'Brand'=>$bra['name'],
                        'date_created' => $product['date_created'],
                        'date_updated' => $product['date_updated']
                    ];
                    
            }
            $result['total']=$this->db->Product->count($filter);
        }
        $res= json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
        return $res;
    }

    public function createProduct($data){
        $now=date("Y-m-d H:i:s");        
        $result=$this->db->Product->insertOne([
            "id"            => $this->db->command(['eval' => "getNextValue('product_id')"])->toArray()[0]['retval'],
            "name"          => $data['name'],
            "price"         => $price=$data['price'],
            "description"   => $data['description'],
            "category_id"   => (int)$data['category_id'],
            "brand_id"      => (int)$data['brand_id'],
            "date_created"  => $now,
            "date_updated"  => $now,
        ]);
        if($result->getInsertedCount()!=0)
            $res= $this->db->command(['eval' => "updateNextValue('product_id')"]);
        $result=['insert'=>$result->getInsertedCount()];
        return json_encode($result,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    public function updateProduct($id, $data){
        $filter=['id'=>intval($id)];
        $product= $this->db->Product->findOne($filter);
        if($product){
            $now=date("Y-m-d H:i:s");

            $result=$this->db->Product->updateOne($filter,[ '$set' => [
                'name'          => $this->request->getPut('name') ?? $product['name'] ,
                'price'         => $this->request->getPut('price') ?? $product['price'],
                'description'   => $this->request->getPut('description') ?? $product['description'],
                'category_id'   => $this->request->getPut('category_id') ?? $product['category_id'],
                'brand_id'      => $this->request->getPut('brand_id') ?? $product['brand_id'],
                'date_updated'  => $now,
            ]]);
            $result=['updated'=>$result->getModifiedCount()];
            return json_encode($result,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
        else
            return "{'updated':'0'}";
    }
    public function deleteProduct($id){
        $filter=['id'=>intval($id)];
        $result = $this->db->Product->deleteOne($filter);
        $result=['deleted'=>$result->getDeletedCount()];
        return json_encode($result,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
}
