<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/



$route['default_controller'] = "home"; 
$route['cms'] = "back_office/home";
$route['option1'] = "home/option1";
$route['option2'] = "home/option2";
$route['option3'] = "home/option3";
$route['option4'] = "home/option4";
$route['option5'] = "home/option5";
$route['option5/ar'] = "home/option5/ar";
$route['ar'] = "home/ar"; // the Arabic page (design 5)
$route['sitemap.xml'] = "seo/sitemap";
$route['robots.txt'] = "seo/robots";
$route['Traboulsi-Est'] = "seo/old"; // address of the old website
//$route['(:any)'] = "pages/$1";
$route['back_office/home/dashboard'] = "back_office/home/dashboard";
//$route['back_office/nopage'] = 'nopage';
$route['back_office/manage_project'] = "back_office/manage_project";
$route['404_override'] = 'error404';
//$route['_404'] = '';
/* End of file routes.php */
/* Location: ./application/config/routes.php */