<?php


class View
{
    protected $data; // данные от контролера в представление
    protected $path; // путь к текущему файлу представления

    public function __construct($data = array(), $path = null){ // инициализация объекта, два необязательных аргумента, пустой маасив и null
        if (!$path){
            $path = self::getDefaultViewPath();
        }
        if (!file_exists($path)){ //если файл не существует
            throw new Exception('Template file is not found in path: ' . $path);
        }
        $this->path = $path;
        $this->data = $data;
    }
    
    protected static function getDefaultViewPath(){ //роут, путь к шаблону и экшн
        $router = App::getRouter(); //объект роутера
        if (!$router){
            return false;
        }
        $controller_dir = $router->getController();
        $template_name = $router->getMethodPrefix() . $router->getAction() . '.html';
        return VIEWS_PATH . DS . $controller_dir . DS . $template_name;
    }
    
    public function render(){ // рендеринг шаблона, использует аттрибуты объекта
        $data = $this->data; // переменная $data равна аттрибуту data
        
        ob_start(); // включение буферизации вывода
        include ($this->path);
        $content = ob_get_clean();
        
        return $content;
    }

}