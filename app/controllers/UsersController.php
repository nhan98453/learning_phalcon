<?php
use Phalcon\Db;
class UsersController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {
        if($this->request->isGet()){
            if($this->session->has('user'))
            {
                if($this->checkPermission($this->session->get('user'),'ReadUser')){
                    $Users= $this->db->fetchAll('SELECT * FROM Users');
                    return json_encode($Users);
                }
                else
                    return("You don't have permission");
            }
            else
                return("You're not logged in");
        }
        else
                return("This route is not supported");
    }
    public function createAction(){
        if($this->request->isPost()){
            if($this->session->has('user'))
            {
                if($this->checkPermission($this->session->get('user'),'AddUser'))
                {
                    $checkUser= $this->db->fetchOne("SELECT * FROM Users WHERE username='{$this->request->getPost('username')}'");
                    if(!$checkUser){
                        $username=$this->request->getPost('username');
                        $password=$this->request->getPost('password');
                        $usergroup=$this->request->getPost('usergroup');
                        $email=$this->request->getPost('email');
                        $now=date("Y-m-d H:i:s");
                        $this->db->execute("INSERT INTO Users(username,password,usergroup,email,date_created,date_updated) VALUES('{$username}','{$password}','{$usergroup}','{$email}','{$now}','{$now}')");
                        return("User Created");
                    }
                    else
                        return("User already have");
                }
                else
                    return("You don't have permission");
            }
            else   
                return("You aren't logged in");
        }
        else 
            return("This route is not supported");
    }
    public function editAction(){
        if($this->request->isPut()){
            if($this->session->has('user'))
            {
                if($this->checkPermission($this->session->get('user'),'EditUser'))
                {
                    $user= $this->db->fetchOne("SELECT * FROM Users WHERE username='{$this->request->getPut('username')}'");
                    if($user){
                        $username=$this->request->getPut('username',null,$user['username']);
                        $password=$this->request->getPut('password',null,$user['password']);
                        $usergroup=$this->request->getPut('usergroup',null,$user['usergroup']);
                        $email=$this->request->getPut('email',null,$user['email']);
                        $now=date("Y-m-d H:i:s");
                        try{
                            $user=$this->db->execute("UPDATE Users SET password='{$password}',usergroup='{$usergroup}',email='{$email}',date_updated='{$now}' WHERE username='{$username}'");
                            return json_encode($user);
                        }
                        catch(Exception $e){
                            echo $e;
                        }
                    }
                        else
                            return("Can't find this user");
                }
                else
                    return("You don't have permission");
            }
            else   
                return("You aren't logged in");
        }
        else 
            return("This route is not supported");
    }
    public function deleteAction(){
        if($this->request->isDelete()){
            if($this->session->has('user'))
            {
                if($this->checkPermission($this->session->get('user'),'DeleteUser'))
                {
                    try{
                        $result=$this->db->execute("DELETE FROM Users WHERE username='{$this->request->getPut('username')}'");
                        if($result)
                            return "Delete User successful";
                    }
                    catch(Exception $e){
                        echo $e;
                    }
                }
                else
                    return("You don't have permission");
            }
            else   
                return("You aren't logged in");
        }
        else 
            return("This route is not supported");
    }
    public function logoutAction(){
        $this->session->destroy();
        return ('You re logged out');
    }
    public function loginAction(){
        if($this->request->isPost()){
            if($this->session->has('user'))
                return("You're logged in before");
            else{
                $username=$this->request->getPost('username');
                $password=$this->request->getPost('password');
                $user = $this->db->fetchOne("SELECT * FROM Users WHERE username='{$username}' AND password='{$password}'");
                if ($user)
                {
                    $this->session->set('user',$user);
                    return("You are logged in");
                }
                return("Username or Password is not correct!");
            }
            
        }
        else
            return("This route is not supported");
    }
    protected function checkPermission($user,$namePermission){
        
        $permission= $this->db->fetchOne("SELECT * FROM Permission WHERE name='{$namePermission}'");
        $userGroup= $this->db->fetchOne("SELECT * FROM UsersGroup WHERE id='{$user["usergroup"]}'");
        if( ( intval($userGroup["permission"]) & intval($permission["value"]) ) != 0 ) 
            return true;
        return false;
    }
}

