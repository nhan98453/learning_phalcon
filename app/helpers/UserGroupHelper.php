<?php
namespace CoreAPI\Helpers;

use Firebase\JWT\JWT;
use CoreAPI\Helpers\GeneralHelper;
use Phalcon\Mvc\User\Component;

class UserGroupHelper extends Component{
    public function getUserGroup(){
        return $this->db->fetchAll('SELECT * FROM UserGroup');
    }

    public function createUserGroup($data){
        $groupname=$data['groupname'];
        $checkUserGroup = $this->db->fetchOne("SELECT * FROM UserGroup WHERE groupname='{$groupname}'");
        $now=date("Y-m-d H:i:s");
        $permission=$data['permission'];
        if(!$checkUserGroup){
            $result = $this->db->execute("INSERT INTO UserGroup(groupname,permission,date_created,date_updated) VALUES('{$groupname}','{$permission}','{$now}','{$now}')");
            return "{'status': '{$result}'}";
            }
        else
            return "{'status': '0'}";
    }
    public function updateUserGroup($id, $permission){
        $userGroup= $this->db->fetchOne("SELECT * FROM UserGroup WHERE id='{$id}'");
        if($userGroup){
            $now=date("Y-m-d H:i:s");
            $result=$this->db->execute("UPDATE UserGroup set permission='{$permission}' where id='{$id}'");
            return "{'status': '{$result}'}";
        }
        else
            return "{'status': '0'}";
    }
    public function deleteUserGroup($id){
        $result = $this->db->execute("DELETE FROM UserGroup WHERE id='{$id}'");
        return "{'status': '0'}";
    }
}
