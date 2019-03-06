<?php

class UsersController extends Controller
{
    public function  __construct($data = array()){
        parent::__construct($data);
        $this->model = new User();
    }
    
    public function login(){ // авторизация 
        if ($_POST && isset($_POST['login']) && isset($_POST['password'])) // если получили post поля логи и пароль переданы
            { 
            $user = $this->model->getByLogin($_POST['login']); // то присвоим переменной юзер результат вызова метода getByLogin модели юзер
            $id = $user['id'];
            $hash = md5(Config::get('salt').$_POST['password']);
            if ($user  && $hash == $user['password']){
                Session::set('login', $user['login']);
                Session::set('role', $user['role']);
                Session::set('userid', $user['id']);
                Router::redirect("/users/view/$id");  
            } else {};   
        }
    }
    
    public function logout(){ // выход 
        Session::destroy();
        Router::redirect('/');
    }

    public function index(){ // 
        $this->data['users'] = $this->model->getList();
    }
     
     public function view(){
        $params = App::getRouter()->getParams(); // id профиля юзера(страницы) который открыт в данный момент
        $id_user_sender=Session::get('userid'); //id зарегистрированного юзера
        if (isset($params[0])){  //если параметр существует
            $id_user_recipient = strtolower($params[0]); //id профиля юзера(страницы)
            $this->data['user'] = $this->model->getByAlias($id_user_recipient);
            
            if ( ($params[0] == $id_user_recipient or $id_user_sender) and (Session::get('userid') == $id_user_sender or $id_user_recipient) ) {
            $this->data['users'] = $this->model->getChatList($id_user_sender, $id_user_recipient); } 
        }
        if ($_POST) {    
                $id_chat = strtolower($params[0]);
                $this->data['private_messages'] = $this->model->addPmChats($id_user_sender, $id_user_recipient);
                $this->data['private_messages'] = $this->model->saveMessages($_POST, $id_user_sender, $id_user_recipient);
                    Session::setFlash('Thank you! Your message was send successfully! ');
                    Router::redirect("/chats/view/$id_chat/");
            }     
    }
    
    public function pm(){
        $params = App::getRouter()->getParams(); // id профиля юзера(страницы) который открыт в данный момент
        $id_user_sender=Session::get('userid'); //id зарегистрированного юзера
        if (isset($params[0])){  //если параметр существует
            $id_user_recipient = strtolower($params[0]); //id профиля юзера(страницы)
            $this->data['user'] = $this->model->getByAlias($id_user_recipient);
            $this->data['users'] = $this->model->getChatList($id_user_sender, $id_user_recipient);
        }
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
    
    public function adduser(){
        if ($_POST){
            $id = isset($_POST['id']) ? $_POST['id'] : null;
            $result = $this->model->save($_POST, $id);
            if ($result){
                Session::setFlash('user was saved.');
            } else {
                Session::setFlash('Error.');
            }
            Router::redirect('/users/');
        }

        if (isset($this->params[0])){
            $this->data['user'] = $this->model->getById($this->params[0]);
        } else {
            Session::setFlash('Wrong user id.');
            Router::redirect('/users/');
        }
    }
}