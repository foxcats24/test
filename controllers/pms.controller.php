<?php

class PmsController extends Controller
{
    public function  __construct($data = array()){
        parent::__construct($data);
        $this->model = new Pm();
    }
    
   
     
     public function view(){ // одна определённая беседа
        $params = App::getRouter()->getParams(); // id профиля юзера(страницы) который открыт в данный момент
        $id_user_sender=Session::get('userid'); //id зарегистрированного юзера
        if (isset($params[0])){  //если параметр существует
            $id_user_recipient = strtolower($params[0]); //id профиля юзера(страницы)
            $this->data['user'] = $this->model->getByAlias($id_user_recipient);
            
            if ( (Session::get('userid') == ($id_user_recipient or $id_user_sender) ) ) {
            $this->data['private_messages'] = $this->model->getChatList($id_user_sender, $id_user_recipient); } 
        }
        if ($_POST) {    
                $id_chat = strtolower($params[0]);
                $this->data['private_messages'] = $this->model->addPmChats($id_user_sender, $id_user_recipient);
                $this->data['private_messages'] = $this->model->saveMessages($_POST, $id_user_sender, $id_user_recipient);
                    Session::setFlash('Thank you! Your message was send successfully! ');
                    Router::redirect("/chats/view/$id_chat/");
            }     
    }
    
    public function index(){
        
        $id_user_recipient=Session::get('userid'); //id зарегистрированного юзера

           
            $this->data['user'] = $this->model->getByAlias($id_user_recipient);
            $id_user_sender = $this->model->getRecipient($id_user_recipient);
            $this->data['users'] = $this->model->getChatList(35);
        
//        if ($_POST) {    
//                $id_chat = strtolower($params[0]);
//                $this->data['chats_pm'] = $this->model->saveChatsPm($id_user_sender, $id_user_recipient);
//                $this->data['private_messages'] = $this->model->saveMessages($_POST, $id_user_sender, $id_user_recipient);
//              
//                    Session::setFlash('Thank you! Your message was send successfully! ');
//                    Router::redirect("/chats/view/$id_chat/");
//            }     
    }
    
     public function pmlist() //личные сообщения 
    {
        if ($_POST){
           if ($result = $this->model->save($_POST)){
              Session::setFlash('Новая беседа создана http://livestreet/chats/');
          } else {
              Session::setFlash('Ошибка');
          }
      }
    }
    
  
}