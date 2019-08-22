<?php
namespace CoreAPI\Helpers;

use Firebase\JWT\JWT;
use CoreAPI\Helpers\GeneralHelper;
use Phalcon\Mvc\User\Component;

class BrandHelper extends Component{
    public function getBrand(){
        //return $this->db->fetchAll('SELECT * FROM Brand');
        $allBrand = $this->db->Brand->find();
        $result=[];
        
        foreach($allBrand as $Brand){
            $result['data'][] = [
                'id'=> $Brand['id'],
                'name' => $Brand['name'],
                'date_created' => $Brand['date_created'],
                'date_updated' => $Brand['date_updated']
            ];
        }
        $res = json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
        return $res;
    }

    public function createBrand($data){
        $checkBrand=$this->db->Brand->findOne(['name'=>$data["name"]]);
        if(!$checkBrand){
            $now=date("Y-m-d H:i:s");
            $result=$this->db->Brand->insertOne([
                'id'=>$this->db->command(['eval' => "getNextValue('brand_id')"])->toArray()[0]['retval'],
                'name'=>$data['name'],
                'date_created'=>$now,
                'date_updated'=>$now,
            ]);
            if($result->getInsertedCount() != 0)
                $this->db->command(['eval' => "updateNextValue('brand_id')"]);
            return "{'status': '{$result->getInsertedCount()}'}";
            }
        else
            return "{'status': '0'}";
    }
    public function updateBrand($id, $data){
        //$Brand= $this->db->fetchOne("SELECT * FROM Brand WHERE id='{$id}'");
        $filter=["id"=>intval($id)];
        $Brand=$this->db->Brand->findOne($filter);
        if($Brand){
            $now=date("Y-m-d H:i:s");
            //$result=$this->db->execute("UPDATE Brand set permission='{$permission}' where id='{$id}'");
            $result=$this->db->Brand->updateOne($filter,['$set'=>[
                'name'=>$data['name'] ?? $Brand['name'],
                'date_updated'=>$now,
            ]]);
            return "{'status': '{$result->getModifiedCount()}'}";
        }
        else
            return "{'status': '0'}";
    }
    public function deleteBrand($id){
        //$result = $this->db->execute("DELETE FROM Brand WHERE id='{$id}'");
        $filter=["id"=>intval($id)];
        $result=$this->db->Brand->deleteOne($filter);
        return "{'status': '{$result->getDeletedCount()}'}";
    }
}
