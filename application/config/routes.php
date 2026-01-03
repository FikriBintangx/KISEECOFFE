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
$route['default_controller'] = 'auth';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Route untuk laporan
$route['laporan'] = 'laporan/index';

// Route untuk transaksi
$route['transaksi/keranjang'] = 'transaksi/keranjang';
$route['transaksi/tambah_keranjang'] = 'transaksi/tambah_keranjang';
$route['transaksi/update_keranjang'] = 'transaksi/update_keranjang';
$route['transaksi/hapus_item_keranjang/(:any)'] = 'transaksi/hapus_item_keranjang/$1';
$route['transaksi/kosongkan_keranjang'] = 'transaksi/kosongkan_keranjang';
$route['transaksi/checkout'] = 'transaksi/checkout';
$route['transaksi/pembayaran/(:num)'] = 'transaksi/pembayaran/$1';
$route['transaksi/upload_bukti'] = 'transaksi/upload_bukti';
$route['transaksi/riwayat'] = 'transaksi/riwayat';
$route['transaksi/detail/(:num)'] = 'transaksi/detail/$1';
$route['transaksi/kelola'] = 'transaksi/kelola';
$route['transaksi/detail_admin/(:num)'] = 'transaksi/detail_admin/$1';
$route['transaksi/update_status'] = 'transaksi/update_status';

// Route untuk payment callback (webhook)
$route['payment/callback'] = 'transaksi/callback';

// Route untuk admin transaksi
$route['admin/transaksi'] = 'admin/transaksi/index';
$route['admin/transaksi/detail/(:num)'] = 'admin/transaksi/detail/$1';
$route['admin/transaksi/update_status'] = 'admin/transaksi/update_status';
$route['admin/transaksi/verify_payment'] = 'admin/transaksi/verify_payment';