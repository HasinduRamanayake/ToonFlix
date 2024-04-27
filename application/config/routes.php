<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	https://codeigniter.com/userguide3/general/routing.html
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

$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['api/auth/login']['post'] = 'Auth/index_post';
$route['api/auth/signup']['post'] = 'Auth/signup_post';

$route['api/posts/create_post'] = 'API/PostController/createPost';
$route['api/posts/get_all_posts'] = 'API/PostController/getAllPosts';
$route['api/posts/(:num)'] = 'API/PostController/getPost/$1';
$route['api/posts/update/(:id)'] = 'api/Postcontroller/updatePost/$1';

$route['api/comments/create_comment']= 'API/CommentController/createComment';
$route['api/comments/get_all_comments/(:num)'] = 'API/CommentController/getAllComments/$1';
$route['api/comments/update_comment/(:num)'] = 'API/CommentController/updateComment/$1';
$route['api/comments/delete_comment/(:num)'] = 'API/CommentController/deleteComment/$1';



//test routes
$route['api/auth/signin'] = 'API/UserController/index';
$route['api/auth/low']['get'] = 'API/UserController/test_get';



