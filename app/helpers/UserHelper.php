<?php
namespace CoreAPI\Helpers;

use Firebase\JWT\JWT;
use CoreAPI\Helpers\GeneralHelper;
use Phalcon\Mvc\User\Component;
use MongoDB\BSON\ObjectId;
class UserHelper extends Component{
    public function getUser(){
        //return $this->db->fetchAll('SELECT * FROM User');
        $allUser = $this->db->User->find();
        $result=[];
        foreach($allUser as $user){
            $result['data'][] = [
                'id' => $user['id'],
                'username' => $user['username'],
                'password' => $user['password'],
                'usergroup' => $user['usergroup'],
                'date_created' => $user['date_created'],
                'date_updated' => $user['date_updated']
            ];
        }
        $res = json_encode($result, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );
        return $res;
    }

    public function createUser($data){
        $checkUser= $this->db->User->find(['username'=>$data['username']]);
        if($checkUser->isDead()){
            $now=date("Y-m-d H:i:s");
            $result=$this->db->User->insertOne([
                'id'=>$this->db->command(['eval' => "getNextValue('user_id')"])->toArray()[0]['retval'],
                'username'=>$data['username'],
                'password'=>$data['password'],
                'email'=>$data['email'],
                'usergroup'=>$data['usergroup'],
                'date_created'=>$now,
                'date_updated'=>$now
            ]);
            if($result->getInsertedCount()!=0)
                $this->db->command(['eval' => "updateNextValue('user_id')"]);
            return "{'inserted': '{$result->getInsertedCount()}'}";
        }
        else
            return "{'inserted': '0'}";
    }
    public function updateUser($username, $data){
        //$user= $this->db->fetchOne("SELECT * FROM User WHERE username='{$username}'");
        $user= $this->db->User->findOne(['username'=>$username]);
        if($user){
            //$result=$this->db->execute("UPDATE User SET password='{$password}',usergroup='{$usergroup}',email='{$email}',date_updated='{$now}' WHERE username='{$username}'");
                $result = $this->db->User->updateOne(['username'=>$username],
                [
                    '$set'=>
                    [
                        "password"=>$this->request->getPut('password',null,$user['password']),
                        "usergroup"=>$this->request->getPut('usergroup',null,$user['usergroup']),
                        "email"=>$this->request->getPut('email',null,$user['email']),
                        "date_updated"=>date("Y-m-d H:i:s"),
                    ]
                ]
        );
            return "{'updated': '{$result->getModifiedCount()}'}";
        }
        else
            return "{'updated': '0'}";
    }
    public function deleteUser($username){
        //$result = $this->db->execute("DELETE FROM User WHERE username='{$this->dispatcher->getParam('username')}'");
        $result = $this->db->User->deleteOne(["username"=>$username]);
        return "{'status': '{$result->getDeletedCount()}'}";
    }
    public function loginUser($data){
        $username=$data['username'];
        $password=$data['password'];
        if($this->session->has('user'))
            return("You're logged in before");
        else{
            //$user = $this->db->fetchOne("SELECT username,permission FROM User,UserGroup WHERE username='{$username}' AND password='{$password}' and usergroup=id");
            $result=$this->db->User->aggregate([
                [
                    '$match' => [
                        "username"=>$username,
                        "password"=>$password
                    ],
                ],
                [
                    '$lookup' =>[
                        "from" => "UserGroup",
                        "localField"=>"usergroup",
                        "foreignField"=>'id',
                        "as"=>"UserGroup",
                    ],
                ]

            ]);
            $user=[];
            foreach($result as $rs){
                foreach($rs['UserGroup'] as $usgroup){
                    $permission=json_decode(json_encode($usgroup['permission']), true);
                    $user = [
                        'username'=>$rs['username'],
                        'permission'=>$permission,                 
                    ];
                }
            }  
            if ($user)
            {
                $jwt = JWT::encode($user, SECRET_KEY_JWT);
                return $jwt;
            }
            return("Username or Password is not correct!");
        }

    }
}
