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

// Users route
$route['hrm/user/view/(:any)'] = 'team_members/view/$1';
$route['hrm/user/view/(:any)/general'] = 'team_members/view/$1/general';
$route['hrm/user/view/(:any)/account'] = 'team_members/view/$1/account';
$route['hrm/user/view/(:any)/my_preferences'] = 'team_members/view/$1/my_preferences';
$route['hrm/user'] = 'team_members/index';

// Leaves
$route['hrm/leaves'] = 'leaves/index';

// Attendance
$route['hrm/attendance'] = 'attendance/index';

// Department
$route['hrm/department'] = 'team/department';

// Holidays
$route['hrm/holidays'] = 'holidays/index';

// Contributions
$route['fas/contributions'] = 'contribution_categories/index';

// Disciplinary
$route['hrm/disciplinary'] = 'discipline_categories/index';

// Incentives
$route['fas/incentives'] = 'incentive_categories/index';

// Leads
$route['mcm/leads'] = 'leads/index';
$route['mcm/leads/view/(:any)'] = 'leads/view/$1';

// Estimates
$route['sms/estimates'] = 'estimates/index';
$route['sms/estimates/view/(:any)'] = 'estimates/view/$1';
$route['sms/invoices/view/(:any)'] = 'invoices/view/$1';

// Invoices
$route['sms/invoices'] = 'invoices/index';

// Payments
$route['fas/payments'] = 'invoice_payments/index';

// Expenses
$route['fas/expenses'] = 'expenses/index';

// Income vs Expenses
$route['fas/summary'] = 'expenses/income_vs_expenses';

// Accounts
$route['fas/accounts'] = 'accounts/index';

// Transfers
$route['fas/transfers'] = 'account_transfers/index';

// Balance Sheet
$route['fas/balancesheet'] = 'balance_sheet/index';

// Payroll
$route['fas/payroll'] = 'payroll/index';

// Warehouse
$route['lms/warehouse'] = 'warehouse/index';

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

// Items
$route['pid/items'] = 'inventory_item_categories/index';

// Tickets
$route['help/tickets'] = 'tickets/index';

// Transfer
$route['lms/transfer'] = 'inventory_transfers/index';

// Inventory
$route['pid/inventory'] = 'inventory/index';

// Delivery
$route['lms/delivery'] = 'deliveries/index';

// Vehicles
$route['lms/vehicles'] = 'vehicles/index';

// Consumers
$route['lms/consumers'] = 'consumers/index';

// Sales Matrix
$route["sms/sales-matrix"] = "sales_matrix/index";

// Materials
$route["pid/materials"] = "material_categories/index";

// Bill of Materials
$route["pid/bill-of-materials"] = "bill_of_materials/index";

// Productions
$route["pid/productions"] = "productions/index";

// Customers
$route['sms/customers'] = 'customers/index';

// Drivers
$route['lms/drivers'] = 'drivers/index';

// Suppliers
$route['pid/supplier'] = 'vendors/index';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
