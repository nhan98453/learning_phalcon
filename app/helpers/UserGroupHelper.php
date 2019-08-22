<?php
namespace CoreAPI\Helpers;

use Firebase\JWT\JWT;
use CoreAPI\Helpers\GeneralHelper;
use Phalcon\Mvc\User\Component;

class UserGroupHelper extends Component{
    public function getUserGroup(){
        //return $this->db->fetchAll('SELECT * FROM UserGroup');
        $allUG = $this->db->UserGroup->find();
        $result=[];
        
        foreach($allUG as $userGroup){
            $result['data'][] = [
                'id'=> $userGroup['id'],
                'name' => $userGroup['name'],
                'permission' => $userGroup['permission'],
                'date_created' => $userGroup['date_created'],
                'date_updated' => $userGroup['date_updated']
            ];
        }
        $res = json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
        return $res;
    }

    public function createUserGroup($data){
        //$checkUserGroup = $this->db->fetchOne("SELECT * FROM UserGroup WHERE groupname='{$groupname}'");
        $checkUserGroup=$this->db->UserGroup->findOne(['name'=>$data["name"]]);
        $now=date("Y-m-d H:i:s");
        if(!$checkUserGroup){
            //$result = $this->db->execute("INSERT INTO UserGroup(groupname,permission,date_created,date_updated) VALUES('{$groupname}','{$permission}','{$now}','{$now}')");
            $result=$this->db->UserGroup->insertOne([
                'id'=>$this->db->command(['eval' => "getNextValue('usergroup_id')"])->toArray()[0]['retval'],
                'name'=>$data['name'],
                'permission'=>json_decode($data['permission'],true),
                'date_created'=>$now,
                'date_updated'=>$now,
            ]);
            if($result->getInsertedCount() != 0)
                $this->db->command(['eval' => "updateNextValue('usergroup_id')"]);
            return "{'status': '{$result->getInsertedCount()}'}";
            }
        else
            return "{'status': '0'}";
    }
    public function updateUserGroup($id, $data){
        //$userGroup= $this->db->fetchOne("SELECT * FROM UserGroup WHERE id='{$id}'");
        $filter=["id"=>intval($id)];
        $userGroup=$this->db->UserGroup->findOne($filter);
        if($userGroup){
            $now=date("Y-m-d H:i:s");
            //$result=$this->db->execute("UPDATE UserGroup set permission='{$permission}' where id='{$id}'");
            $result=$this->db->UserGroup->updateOne($filter,['$set'=>[
                'name'=>$data['name'] ?? $userGroup['name'],
                'permission'=>json_decode($data['permission'],true) ?? $userGroup['permission'],
                'date_updated'=>$now,
            ]]);
            return "{'status': '{$result->getModifiedCount()}'}";
        }
        else
            return "{'status': '0'}";
    }
    public function deleteUserGroup($id){
        //$result = $this->db->execute("DELETE FROM UserGroup WHERE id='{$id}'");
        $filter=["id"=>intval($id)];
        $result=$this->db->UserGroup->deleteOne($filter);
        return "{'status': '{$result->getDeletedCount()}'}";
    }
}
