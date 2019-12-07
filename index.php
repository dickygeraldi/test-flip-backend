<?php
    
    // Cek path, jika ada maka lakukan split string
    // Jika tidak ada path maka masuk ke index
    if(isset($_SERVER['PATH_INFO'])){
        $path = $_SERVER['PATH_INFO'];

        $pathSplit = explode('/', ltrim($path));
    }else {
        $pathSplit = '/';
    }

    if($pathSplit === '/') {
        require_once __DIR__.'/models/indexModel.php';
        require_once __DIR__.'/controllers/indexController.php';

        $reqModel = new IndexModel();
        $reqController = new IndexController($reqModel);
        
        $model = $reqModel;
        $controller = $reqController;

        $modelObj = new $model;
        $controllerObj = new $controller($reqModel);

        $method = $reqMethod;

        if($method != '') {
            print $controllerObj->$method($req_param);
        }else{
            print $controllerObj->Index();
        }
    } else {
        $reqController = $pathSplit[1];
        $reqModel = $pathSplit[1];
        
        $reqMethod = isset($pathSplit[2]) ? $pathSplit[2] : '';

        $reqParam = array_slice($pathSplit, 3);

        $reqControllerExist = __DIR__.'/controllers/'.$reqController.'Controller.php';

        if (file_exists($reqControllerExist)) {
            require_once __DIR__.'/models/'.$reqModel.'Model.php';
            require_once __DIR__.'/controllers/'.$reqModel.'Controller.php';

            $model = ucfirst($reqModel).'Model';
            $controller = ucfirst($reqController).'Controller';

            $modelObj = new $model;
            $controllerObj = new $controller($model);

            $method = $reqMethod;

            if ($method != '') {
                print $controllerObj->$method($reqParam);
            }else{
                print $controllerObj->index();
            }
        }else{
            header('HTTP/1.1 404 Not Found');
            die ('404 Ups url tidak ditemukan');
        }
    }
?>