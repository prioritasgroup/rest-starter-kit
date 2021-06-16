<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'welcome';
$route['404_override'] = 'welcome/error_page';
$route['translate_uri_dashes'] = FALSE;

$modules_path = APPPATH.'modules/';
$modules = scandir($modules_path);

foreach($modules as $module)
{

    if($module === '.' || $module === '..') continue;
    if(is_dir($modules_path) . '/' . $module)
    {
        $routes_path = $modules_path . $module . '/config/routes.php';
        if(file_exists($routes_path))
        {

            require($routes_path);
        }
        else
        {
            // print_r($routes_path);die();
            continue;
        }
    }
}
