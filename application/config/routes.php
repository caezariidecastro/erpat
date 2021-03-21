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
|	http://codeigniter.com/user_guide/general/routing.html
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
$route['default_controller'] = 'dashboard';

//custom routing for custom pages
//this route will move 'about/any-text' to 'domain.com/about/index/any-text'
$route['about/(:any)'] = 'about/index/$1';

// Estimates
$route['sms/estimates'] = 'estimates/index';
$route['sms/estimates/view/(:any)'] = 'estimates/view/$1';
$route['sms/invoices/view/(:any)'] = 'invoices/view/$1';

// Payments
$route['fas/payments'] = 'invoice_payments/index';

// Invoices
$route['sms/invoices'] = 'invoices/index';

// Sales Matrix
$route["sms/sales-matrix"] = "sales_matrix/index";

// Customers
$route['sms/customers'] = 'customers/index';

// All Projects
$route['pms/all_projects'] = 'projects/all_projects';
$route['pms/projects/view/(:any)'] = 'projects/view/$1';

// My Tasks
$route['pms/my_tasks'] = 'projects/all_tasks';

// View Gantts
$route['pms/view_gantts'] = 'projects/all_gantt';

// Timesheets
$route['pms/timesheets'] = 'projects/all_timesheets';

// Clients
$route['pms/clients'] = 'clients/index';
$route['pms/clients/view/(:any)'] = 'clients/view/$1';

// Services
$route['pms/services'] = 'item_categories/index';

// Tickets
$route['help/tickets'] = 'tickets/index';



$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
