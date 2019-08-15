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
        if(!$checkUserGroup){
            $permission=$data['permission'];
            $result = $this->db->execute("INSERT INTO UserGroup(groupname,permission,date_created,date_updated) VALUES('{$groupname}','{$permission}','{$now}','{$now}')");
            return "{'status': '{$result}'}";
            }
        else
            return "{'status': '0'}";
    }
    public function updateUser($username, $data){
        $user= $this->db->fetchOne("SELECT * FROM User WHERE username='{$username}'");
        if($user){
            $password=$this->request->getPut('password',null,$user['password']);
            $usergroup=$this->request->getPut('usergroup',null,$user['usergroup']);
            $email=$this->request->getPut('email',null,$user['email']);
            $now=date("Y-m-d H:i:s");
            $result=$this->db->execute("UPDATE User SET password='{$password}',usergroup='{$usergroup}',email='{$email}',date_updated='{$now}' WHERE username='{$username}'");
            return "{'status': '{$result}'}";
        }
        else
            return "{'status': '0'}";
    }
    public function deleteUser($id){
        return $this->db->execute("DELETE FROM User WHERE username='{$this->dispatcher->getParam('username')}'");
    }
    public function loginUser($data){
        $username=$data['username'];
        $password=$data['password'];
        if($this->session->has('user'))
            return("You're logged in before");
        else{
            $user = $this->db->fetchOne("SELECT username,permission FROM User,UserGroup WHERE username='{$username}' AND password='{$password}' and usergroup=id");
            if ($user)
            {
                $this->session->set('user',$user);
                $jwt = JWT::encode($user, SECRET_KEY_JWT);
                return $jwt;
            }
            return("Username or Password is not correct!");
        }

    }
}
