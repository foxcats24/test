<?php

Config::set('site_name', 'Чат'); // 



    //Routes. Имя роута = метод префикса
Config::set('routes', array(
    'default' => '',
   

));

Config::set('default_route', 'default');

Config::set('default_controller', 'chats');
Config::set('default_action', 'index');

Config::set('db.host', 'localhost');
Config::set('db.user', 'root');
Config::set('db.password', '');
Config::set('db.db_name', 'mvc');

Config::set('salt', 'jd7sj3sdk964he7e');



