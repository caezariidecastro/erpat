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

// Payrolls
$route['fas/payrolls'] = 'payrolls/index';
$route['fas/payrolls/(:any)'] = 'payrolls/$1';
$route['fas/payrolls/(:any)/(:any)'] = 'payrolls/$1/$2';

// Payments

// All Projects
$route['pms/all_projects'] = 'projects/all_projects';
$route['pms/projects/view/(:any)'] = 'projects/view/$1';

// My Tasks
$route['pms/my_tasks'] = 'projects/all_tasks';

// View Gantts
$route['pms/view_gantts'] = 'projects/all_gantt';

// Timesheets
$route['pms/timesheets'] = 'projects/all_timesheets';

// Tickets
// $route['help/tickets'] = 'tickets/index';


// AMS Controller 
$route["ams"] = "entries/index";
$route["ams/entries"] = "entries/index";
$route["ams/entries/(:any)"] = "entries/$1";

$route["ams/categories"] = "Categories/index";
$route["ams/categories/(:any)"] = "Categories/$1";

$route["ams/locations"] = "Locations/index";
$route["ams/locations/(:any)"] = "Locations/$1";

$route["ams/vendors"] = "Vendors/index";
$route["ams/vendors/(:any)"] = "Vendors/$1";
$route["ams/vendors/(:any)/(:any)"] = "Vendors/$1/$2";

$route["ams/brands"] = "Brands/index";
$route["ams/brands/(:any)"] = "Brands/$1";

// CSS Controller 
$route["css"] = "tickets/index";
$route["tickets"] = "Tickets/index";
$route["tickets/(:any)"] = "Tickets/$1";
$route["tickets/(:any)/(:any)"] = "Tickets/$1/$2";
$route["tickets/(:any)/(:any)/(:any)"] = "Tickets/$1/$2/$3";

$route["ticket_types"] = "Ticket_types/index";
$route["ticket_types/(:any)"] = "Ticket_types/$1";

// FAS Controller 
$route["fas"] = "summary/index";
$route["fas/summary"] = "Expenses/cash_flow_comparison";
$route["fas/balance_sheet/(:any)"] = "Balance_sheet/$1";

$route['fas/payments'] = 'Invoice_payments/index';

$route['fas/expenses'] = 'Expenses/index';
$route["fas/expenses/(:any)"] = "Expenses/$1";
$route["fas/expenses/(:any)/(:any)"] = "Expenses/$1/$2";

$route["fas/expense_categories"] = "Expense_categories/index";
$route["fas/expense_categories/(:any)"] = "Expense_categories/$1";
$route["fas/expense_categories/(:any)/(:any)"] = "Expense_categories/$1/$2";

$route['fas/payroll'] = 'payroll/index';
$route["fas/payroll/(:any)"] = "Payroll/$1";
$route["fas/payroll/(:any)/(:any)"] = "Payroll/$1/$2";

$route['fas/transfers'] = 'account_transfers/index';
$route["fas/account_transfers/(:any)"] = "Account_transfers/$1";

$route['fas/accounts'] = 'accounts/index';
$route["fas/accounts/(:any)"] = "Accounts/$1";

// HRS Controller 
$route["hrs"] = "Team_members/index";
$route["hrs/employee"] = "Team_members/index";
$route["hrs/employee/(:any)"] = "Team_members/$1";
$route['hrs/employee/(:any)/(:any)'] = 'Team_members/$1/$2';
$route['hrs/employee/(:any)/(:any)/(:any)'] = 'Team_members/$1/$2/$3';
$route["hrs/team_members/(:any)"] = "Team_members/$1";
$route["hrs/team_members/(:any)/(:any)"] = "Team_members/$1/$2";
$route["hrs/team_members/(:any)/(:any)/(:any)"] = "Team_members/$1/$2/$3";

$route["hrs/attendance/(:any)"] = "Attendance/$1";
$route["hrs/discipline_entries/(:any)"] = "Discipline_entries/$1";
$route["hrs/discipline_categories/(:any)"] = "Discipline_categories/$1";
$route["hrs/holidays/(:any)"] = "Holidays/$1";
$route["hrs/leave_credits/(:any)"] = "Leave_credits/$1";
$route["hrs/leave_types/(:any)"] = "Leave_types/$1";
$route["hrs/leaves/(:any)"] = "Leaves/$1";
$route["hrs/leaves/(:any)/(:any)"] = "Leaves/$1/$2";

$route['hrs/department'] = 'Team/department';
$route["hrs/team/(:any)"] = "Team/$1";
$route["hrs/team/(:any)/(:any)"] = "Team/$1/$2";

$route['hrs/attendance'] = 'attendance/index';
$route["hrs/attendance/(:any)"] = "attendance/$1";
$route["hrs/attendance/(:any)/(:any)"] = "attendance/$1/$2";

$route['hrs/disciplinary'] = 'discipline_categories/index';
$route["hrs/disciplinary/(:any)"] = "disciplinary/$1";

$route['hrs/leaves'] = 'leaves/index';
$route["hrs/leaves/(:any)"] = "leaves/$1";

$route['hrs/holidays'] = 'holidays/index';
$route["hrs/holidays/(:any)"] = "holidays/$1";

// LDS Controller 
$route["lds"] = "deliveries/index";
$route["lds/deliveries"] = "deliveries/index";
$route["lds/deliveries/(:any)"] = "Deliveries/$1";

$route["lds/transfers"] = "Transfers/index";
$route["lds/transfers/(:any)"] = "Transfers/$1";
$route["lds/transfers/(:any)/(:any)"] = "Transfers/$1/$2";
$route["lds/transfers/(:any)/(:any)/(:any)"] = "Transfers/$1/$2/$3";

$route["lds/vehicles"] = "Vehicles/index";
$route["lds/vehicles/(:any)"] = "Vehicles/$1";

$route["lds/drivers"] = "Drivers/index";
$route["lds/drivers/(:any)"] = "Drivers/$1";

// MCS Controller 
$route["mcs"] = "leads/index";
$route['mcs/leads'] = 'leads/index';
$route['mcs/leads/(:any)'] = 'leads/$1';
$route['mcs/leads/(:any)/(:any)'] = 'leads/$1/$2';

$route['mcs/lead_status'] = 'lead_status/index';
$route['mcs/lead_status/(:any)'] = 'lead_status/$1';

$route['mcs/lead_source'] = 'lead_source/index';
$route['mcs/lead_source/(:any)'] = 'lead_source/$1';

$route['mcs/epass'] = 'EventPass/index';
$route['mcs/epass/(:any)'] = 'EventPass/$1';
$route['mcs/epass/(:any)/(:any)'] = 'EventPass/$1/$2';

$route['mcs/epass_area'] = 'Epass_Area/index';
$route['mcs/epass_area/(:any)'] = 'Epass_Area/$1';
$route['mcs/epass_area/(:any)/(:any)'] = 'Epass_Area/$1/$2';

$route['mcs/epass_block'] = 'Epass_Block/index';
$route['mcs/epass_block/(:any)'] = 'Epass_Block/$1';
$route['mcs/epass_block/(:any)/(:any)'] = 'Epass_Block/$1/$2';

$route['mcs/epass_seat'] = 'Epass_Seat/index';
$route['mcs/epass_seat/(:any)'] = 'Epass_Seat/$1';
$route['mcs/epass_seat/(:any)/(:any)'] = 'Epass_Seat/$1/$2';

$route["mes/in-progress"] = "InProgress/index";
$route["mes/InProgress/(:any)"] = "InProgress/$1";

$route["purchase_orders/(:any)"] = "PurchaseOrders/$1";
$route["mes/purchase-orders"] = "PurchaseOrders/index";
$route["mes/PurchaseOrders/(:any)"] = "PurchaseOrders/$1";
$route["mes/PurchaseOrders/(:any)/(:any)"] = "PurchaseOrders/$1/$2";
$route["mes/PurchaseOrders/(:any)/(:any)/(:any)"] = "PurchaseOrders/$1/$2/$3";

$route["purchase_returns/(:any)"] = "PurchaseReturns/$1";
$route["mes/purchase-returns"] = "PurchaseReturns/index";
$route["mes/PurchaseReturns/(:any)"] = "PurchaseReturns/$1";
$route["mes/PurchaseReturns/(:any)/(:any)"] = "PurchaseReturns/$1/$2";
$route["mes/PurchaseReturns/(:any)/(:any)/(:any)"] = "PurchaseReturns/$1/$2/$3";

$route["mes/suppliers"] = "Suppliers/index";
$route["mes/Suppliers/(:any)"] = "Suppliers/$1";
$route["mes/suppliers/contacts/(:any)"] = "Suppliers/contacts/$1";

$route["mes/units"] = "Units/index";
$route["mes/units/(:any)"] = "Units/$1";


$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;