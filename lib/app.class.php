<?php

class App {

    protected static $router;

    public static $db;

    /**
     * @return mixed
     */
    public static function getRouter(){
        return self::$router;
    }
    public static function run($uri){
        
        self::$router = new Router($uri);

        self::$db = new DB(Config::get('db.host'),Config::get('db.user'),Config::get('db.password'),Config::get('db.db_name'));

        Lang::load(self::$router->getLanguage());

        $controller_class = ucfirst(self::$router->getController()).'Controller';
        $controller_method = strtolower(self::$router->getMethodPrefix().self::$router->getAction());

        $layout = self::$router->getRoute();
        if ( $layout == 'admin' && Session::get('role') != 'admin') {
            Session::destroy();
            Router::redirect('/users/login');
//            if ( $controller_method != 'admin_login') {
//                Router::redirect('/admin/users/login');
//            }
        }

        // Calling controller's method
        $controller_object = new $controller_class();
        if ( method_exists($controller_object, $controller_method) ) {
            // Controller's action may return a view path
            $view_path = $controller_object->$controller_method();
            $view_data = $controller_object->getData();    
            $view_object = new View( $view_data, $view_path);
            $content = $view_object->render();

        } else {
            throw new Exception('Method '.$controller_method.' of class '.$controller_class.' does not exist.');
        }

//        $backgrounds_obj = new BackgroundsController();
//        $backgrounds_res = $backgrounds_obj->admin_edit();
//        $backgrounds_data = $backgrounds_obj->getData();

        $layout_path = VIEWS_PATH.DS.$layout.'.html';
        $layout_view_object = new View(compact('content', 'view_data'), $layout_path);
        echo $layout_view_object->render();
    }
}