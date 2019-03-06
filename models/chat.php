<?php
class Chat extends Model
{
    public function getList()  //вывести все беседы кроме исключенных представление - index
    { 
        $id_user=Session::get('userid'); 
        return $this->db->select('SELECT chats.title, chats.id  FROM   ?_chats   LEFT JOIN ?_except_chats ON chats.id=except_chats.id_chat AND except_chats.id_user=? WHERE  except_chats.id_user IS NULL', $id_user);
    }
    
    public function getChatList() //view сообщения в беседе кроме удалённых
    {
        $params = App::getRouter()->getParams(); 
        $id_user=Session::get('userid');     
        $id_chat = strtolower($params[0]);   
        return $this->db->query('SELECT users.login, users.id, messages.id_user, messages.id,  messages.message,  messages.time  FROM users INNER JOIN messages ON messages.id_user=users.id AND messages.id_chat = ? LEFT JOIN except_messages ON messages.id=except_messages.id_msg AND except_messages.id_user=? WHERE  except_messages.id_user IS NULL', $id_chat,$id_user );
    }
      
    public function getByAlias($alias) //вывести сообщения из выбранноый беседы ($alias) представление view
    {  
        $result = $this->db->query('SELECT title FROM ?_chats WHERE id=?', 1);
        return isset($result[0]) ? $result[0] : null;
    }
  
    public function saveMessages($data, $id_user, $id_chat, $id = null)
    {       
        $id = (int)$id;
        if (!$id)
        {
        //add new record
        return $this->db->query('INSERT INTO messages (`id_user`, `id_chat`,  `message`) VALUES (?,?,?)',$id_user,$id_chat,(empty($_POST['message'])? DBSIMPLE_SKIP : $_POST['message']) ); 

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
    
    
    public function inmessages($id_msg, $id_user) //добавить для юзера ($id_user) сообщение ($id_msg) в список исключенных сообщений
    {      
        return $this->db->query('INSERT INTO except_messages ( `id_msg`,`id_user` ) VALUES (?,?)', $id_msg, $id_user);
    }
    
    
    public function inchats($id_chat, $id_user) //добавить для юзера ($id_user) чат ($id_chat) в список исключенных чатов
    {       
        //add new record
        return $this->db->query('INSERT INTO except_chats ( `id_chat`,`id_user` ) VALUES (?, ?)', $id_chat, $id_user);
    }
    
    public function save($data, $id = null) //сохранение новой беседы, представление add
    {
        $id = (int)$id;
            if (!$id){
            //add new record
                  return $this->db->query('INSERT INTO chats (`title`) 
                    VALUES (?)',(empty($_POST['title'])? DBSIMPLE_SKIP : $_POST['title']));
        } 
    }
 
}