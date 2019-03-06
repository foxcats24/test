<?php

class ChatsController extends Controller
{
    public function __construct($data = array()){
        parent::__construct($data); //конструктор, объявленный в родительском классе
        $this->model = new Chat();
    }
    
    public function index()// вывести все беседы кроме исключенных
    { 
        $this->data['chats'] = $this->model->getList();
    }
    
    public function view() //вывести сообщения из выбранноый беседы ($alias)
    {
        $params = App::getRouter()->getParams();
        if (isset($params[0])){
            $alias = strtolower($params[0]);
            $this->data['chat'] = $this->model->getByAlias($alias);
            $this->data['chats'] = $this->model->getChatList();  
        }
        if ($_POST) {  //если   post отправлен сохранить сообщение
            $id_chat = strtolower($params[0]); // id чата из адресной строки
            $id_user=Session::get('userid'); //id зарегистрированного юзера
            if ($this->model->saveMessages($_POST, $id_user, $id_chat)){ // контроллер saveMessages (сообщение post, id автора, юзера из сессии, id чата)
            Session::setFlash('Спасибо за сообщение! ');}
           
        }
    }

    public function add() //добавить новую беседу 
    {
        if ($_POST){
           if ($result = $this->model->save($_POST)){
              Session::setFlash('Новая беседа создана http://livestreet/chats/');
          } else {
              Session::setFlash('Ошибка');
          }
      }
    }
    
    public function invisibleMessages() //скрыть сообщение($id_msg) для юзера ($id_user)
    {
        $params = App::getRouter()->getParams();
        $id_user=Session::get('userid');
        if (isset($params[0]))
        {
            $id_msg = strtolower($params[0]);
            $this->data['user_messages'] = $this->model->inmessages($id_msg, $id_user);
            Router::redirect("/chats/"); 
        }
        
    }
    
    public function invisibleChats() //скрыть беседу($id_chat) для юзера ($id_user)
    {
        $params = App::getRouter()->getParams();
        $id_user=Session::get('userid');
          if (isset($params[0]))
        {
            $id_chat = strtolower($params[0]);
            $this->data['user_messages'] = $this->model->inchats($id_chat, $id_user);
             Router::redirect("/chats/index/$id_chat");    
        } 
    }
}