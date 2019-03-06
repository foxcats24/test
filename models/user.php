<?php

class User extends Model
{
    public function getByLogin($login){
        $result = $this->db->query('SELECT * FROM users WHERE login=?', $login);
        if (isset($result[0])){
            return $result[0];
        }
        return false;
    }
    
    public function getList(){
        $sql = "SELECT * FROM ?_users";
        return $this->db->query($sql);
    }
    
    public function userschats($id_chat){
        $params = App::getRouter()->getParams(); 
        $id_user=Session::get('userid'); 
        if (isset($params[0])) 
        {
            $id_chat = strtolower($params[0]); 
            $result = $this->db->query('SELECT login, id  FROM   users');
            return isset($result[0]) ? $result[0] : null;     
        }
  
    }      
    
   
    public function  saveChatsPm(  $id_user_sender, $id_user_recipient, $id = null)
        {       
        $id = (int)$id;
            if (!$id){
                //add new record
              return $this->db->query('INSERT INTO private_messages (`id_user_sender`, `id_user_recipient`,  `message`) VALUES (?,?,?)',$id_user_sender,$id_user_recipient,(empty($_POST['message'])? DBSIMPLE_SKIP : $_POST['message']) ); 

            }
        }
    
    public function getById($id){
        $result = $this->db->select('SELECT * FROM users WHERE id=?', $id);
        return isset($result[0]) ? $result[0] : null;
    }
    
    public function save($data, $id = null){ // регистрация пользователя
        if(!isset($data['login']) || !isset($data['password'])) {
            return false;
        }
        $id = (int)$id;
        $login = $this->db->escape($data['login']);
        $password = $this->db->escape($data['password']);
 
        if (!$id){
            //add new record
            return $this->db->query('INSERT INTO users (`login`, `password`)  VALUES (?,?)', $login,$password );
        } else {
            //update existing record
            $sql = "UPDATE users
                    SET 
                    `login` = '{$login}',
                    `password` = '{$password}'
                    WHERE id = {$id}
              ";
        }

        return $this->db->query($sql);
    }
    
    public function delete($id){
        $id = (int)$id;
        return $this->db->query('DELETE FROM users WHERE id = ?', $id);
    }
        
    public function getByAlias($id_user_recipient){
        $result = $this->db->query('SELECT id, login FROM users WHERE id = ?', $id_user_recipient);
        return isset($result[0]) ? $result[0] : null;
    }
    
    public function getToByAlias($id_user_sender){
        $result = $this->db->query('SELECT  message, id_user_sender, id_user_recipient FROM private_messages');
        return isset($result[0]) ? $result[0] : null;
    }
    
    public function getChatList($id_user_sender, $id_user_recipient){ //view
        $params = App::getRouter()->getParams(); 
        $id_user_sender=Session::get('userid'); 
        $id_user_recipient = strtolower($params[0]);
        return $this->db->query('SELECT  message, id_user_sender, id_user_recipient FROM private_messages' );
 }
      
    public function  addPmChats($data,  $id_user_sender, $id_user_recipient, $id = null)
        {       
        $id = (int)$id;
        if (!$id){
            //add new record
          return $this->db->query('INSERT INTO chats_pm (`id_user_sender`, `id_user_recipient`) VALUES (?,?)',$id_user_sender,$id_user_recipient); 
          
     }
//         else {
//            //update existing record
//            $sql = "UPDATE messages
//                    set `id_user` = '{$id_user}',
//                    `message` = '{$message}'
//                    WHERE id = {$id}
//              ";
//        }
        
        return $this->db->query($sql);
    }    
    public function saveMessages($data,  $id_user_sender, $id_user_recipient, $id = null)
        {       
        $id = (int)$id;
        if (!$id){
            //add new record
          return $this->db->query('INSERT INTO private_messages (`id_user_sender`, `id_user_recipient`,  `message`) VALUES (?,?,?)',$id_user_sender,$id_user_recipient,(empty($_POST['message'])? DBSIMPLE_SKIP : $_POST['message']) ); 
          
        } else {
            //update existing record
            $sql = "UPDATE messages
                    set `id_user` = '{$id_user}',
                    `message` = '{$message}'
                    WHERE id = {$id}
              ";
        }
        
        return $this->db->query($sql);
    }    

}