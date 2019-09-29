// gallery
$route['api/gallery/galleries/(:any)'] = 'api/gallery/galleries/category_id/$1'; // Example 4
$route['api/gallery/galleries/(:any)/(:any)'] = 'api/gallery/galleries/category_id/$1/page/$2'; // pagination
$route['api/gallery/user_galleries/(:any)'] = 'api/gallery/user_galleries/user_id/$1'; // Example 4
$route['api/gallery/gallery/(:any)'] = 'api/gallery/gallery/gallery_id/$1'; // Example 4