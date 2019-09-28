<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
// $route['default_controller'] = 'welcome';
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['admin/menus/group'] = 'admin';
$route['uadmin/users/add'] = 'uadmin/users/add';
$route['uadmin/users/delete'] = 'uadmin/users/delete';

$route['uadmin/users/(:any)'] = 'uadmin/users/index/$1';

// category
$route['api/user/(:num)'] = 'api/user/users/user_id/$1'; // Example 4
// category
$route['api/category/categories/(:num)'] = 'api/category/categories/id/$1'; // Example 4
$route['api/category/categories/(:num)/start/(:any)/(:any)'] = 'api/category/categories/id/$1/start/$2/limit/$3'; // Example 4
// categories_by_group
$route['api/category/group_id/(:any)'] = 'api/category/categories_by_group/group_id/$1'; // Example 4
// porduct
$route['api/product/products/(:any)'] = 'api/product/products/category_id/$1'; // Example 4
$route['api/product/user_products/(:any)'] = 'api/product/user_products/user_id/$1'; // Example 4
$route['api/product/product/(:any)'] = 'api/product/product/product_id/$1'; // Example 4
// porduct
$route['api/store/stores/(:any)'] = 'api/store/stores/group_id/$1'; // Example 4
$route['api/store/user_store/(:any)'] = 'api/store/user_store/user_id/$1'; // Example 4
$route['api/store/store/(:any)'] = 'api/store/store/store_id/$1'; // Example 4
