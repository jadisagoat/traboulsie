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
$route['option1'] = "home/old_design"; // removed designs 1 to 4: 301 to the home page
$route['option2'] = "home/old_design";
$route['option3'] = "home/old_design";
$route['option4'] = "home/old_design";
$route['option5'] = "home/option5";
$route['option5/ar'] = "home/option5/ar";
$route['ar'] = "home/ar"; // the Arabic page
$route['photos'] = "home/gallery/photos"; // every photo of the site
$route['videos'] = "home/gallery/videos"; // every video
$route['ar/photos'] = "home/gallery/photos/ar";
$route['ar/videos'] = "home/gallery/videos/ar";
// one page per sector, category and product, in English and in Arabic (controllers/home.php, texts in config/sectors.php)
$route['([a-z-]+)-equipment'] = "home/sector/$1";
$route['ar/([a-z-]+)-equipment'] = "home/sector/$1/ar";
$route['products/([a-z0-9-]+)'] = "home/category/$1";
$route['ar/products/([a-z0-9-]+)'] = "home/category/$1/ar";
$route['product/([a-z0-9-]+)'] = "home/product/$1";
$route['ar/product/([a-z0-9-]+)'] = "home/product/$1/ar";
$route['export'] = "home/export";
$route['ar/export'] = "home/export/ar";
// one page per entry of the menu: a part of the home page shown on its own
$route['(products|services|about|clients|projects|faq|contact)'] = "home/section/$1";
$route['ar/(products|services|about|clients|projects|faq|contact)'] = "home/section/$1/ar";
// the guides (texts in config/guides.php)
$route['guides'] = "home/guides";
$route['ar/guides'] = "home/guides/ar";
$route['guides/([a-z0-9-]+)'] = "home/guide/$1";
$route['ar/guides/([a-z0-9-]+)'] = "home/guide/$1/ar";
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