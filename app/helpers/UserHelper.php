<?php
namespace CoreAPI\Helpers;

use Firebase\JWT\JWT;
use CoreAPI\Helpers\GeneralHelper;
use Phalcon\Mvc\User\Component;

class UserHelper extends Component{
    public function getUser(){
        return $this->db->fetchAll('SELECT * FROM User');
    }

    public function createUser($data){
        $username=$data['username'];

        $checkUser= $this->db->fetchOne("SELECT * FROM User WHERE username='{$username}'");
        if(!$checkUser){
            $password=$data['password'];
            $email=$data['email'];
            $usergroup=$data['usergroup'];
            $now=date("Y-m-d H:i:s");
            $result = $this->db->execute("INSERT INTO User(username,password,email,usergroup,date_created,date_updated) VALUES('{$username}','{$password}','{$email}','{$usergroup}','{$now}','{$now}')");
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
        $result = $this->db->execute("DELETE FROM User WHERE username='{$this->dispatcher->getParam('username')}'");
        return "{'status': '0'}";
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
