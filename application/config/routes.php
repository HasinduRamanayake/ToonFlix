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

$route['api/auth/signin'] = 'API/UserController/signin';
$route['api/auth/signup'] = 'API/UserController/signup';
$route['api/auth/signout'] = 'API/UserController/signout';
$route['api/auth/session_validity'] = 'API/UserController/validateSession';


$route['api/posts/create_post'] = 'API/PostController/createPost';
$route['api/posts/get_all_posts'] = 'API/PostController/getAllPosts';
$route['api/posts/get_user_posts'] = 'API/PostController/getUserPosts';
$route['api/posts/get_post/(:num)'] = 'API/PostController/getPost/$1';
$route['api/posts/update_post/(:num)'] = 'API/PostController/updatePost/$1';
$route['api/posts/delete_post/(:num)'] = 'API/PostController/deletePost/$1';

$route['api/comments/create_comment']= 'API/CommentController/createComment';
$route['api/comments/get_all_comments/(:num)'] = 'API/CommentController/getAllComments/$1';
$route['api/comments/update_comment/(:num)'] = 'API/CommentController/updateComment/$1';
$route['api/comments/delete_comment/(:num)'] = 'API/CommentController/deleteComment/$1';

$route['api/posts/search_by_name'] = 'API/PostController/searchByName';
$route['api/posts/search_by_tag'] = 'API/PostController/searchByTag';

$route['api/likes/add_like/(:num)'] = 'API/LikeController/addLike/$1';
$route['api/likes/remove_like/(:num)'] = 'API/LikeController/removeLike/$1';
$route['api/likes/count/(:num)'] = 'API/LikeController/getLikeCount/$1';


