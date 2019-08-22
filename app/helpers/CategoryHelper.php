<?php
namespace CoreAPI\Helpers;

use Firebase\JWT\JWT;
use CoreAPI\Helpers\GeneralHelper;
use Phalcon\Mvc\User\Component;

class CategoryHelper extends Component{
    public function getCategory(){
        //return $this->db->fetchAll('SELECT * FROM Category');
        $allCategory = $this->db->Category->find();
        $result=[];
        
        foreach($allCategory as $Category){
            $result['data'][] = [
                'id'=> $Category['id'],
                'name' => $Category['name'],
                'date_created' => $Category['date_created'],
                'date_updated' => $Category['date_updated']
            ];
        }
        $res = json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
        return $res;
    }

    public function createCategory($data){
        //$checkCategory = $this->db->fetchOne("SELECT * FROM Category WHERE groupname='{$groupname}'");
        $checkCategory=$this->db->Category->findOne(['name'=>$data["name"]]);
        $now=date("Y-m-d H:i:s");
        if(!$checkCategory){
            //$result = $this->db->execute("INSERT INTO Category(groupname,permission,date_created,date_updated) VALUES('{$groupname}','{$permission}','{$now}','{$now}')");
            $result=$this->db->Category->insertOne([
                'id'=>$this->db->command(['eval' => "getNextValue('category_id')"])->toArray()[0]['retval'],
                'name'=>$data['name'],
                'date_created'=>$now,
                'date_updated'=>$now,
            ]);
            if($result->getInsertedCount() != 0)
                $this->db->command(['eval' => "updateNextValue('category_id')"]);
            return "{'status': '{$result->getInsertedCount()}'}";
            }
        else
            return "{'status': '0'}";
    }
    public function updateCategory($id, $data){
        //$Category= $this->db->fetchOne("SELECT * FROM Category WHERE id='{$id}'");
        $filter=["id"=>intval($id)];
        $Category=$this->db->Category->findOne($filter);
        if($Category){
            $now=date("Y-m-d H:i:s");
            //$result=$this->db->execute("UPDATE Category set permission='{$permission}' where id='{$id}'");
            $result=$this->db->Category->updateOne($filter,['$set'=>[
                'name'=>$data['name'] ?? $Category['name'],
                'date_updated'=>$now,
            ]]);
            return "{'status': '{$result->getModifiedCount()}'}";
        }
        else
            return "{'status': '0'}";
    }
    public function deleteCategory($id){
        //$result = $this->db->execute("DELETE FROM Category WHERE id='{$id}'");
        $filter=["id"=>intval($id)];
        $result=$this->db->Category->deleteOne($filter);
        return "{'status': '{$result->getDeletedCount()}'}";
    }
}
