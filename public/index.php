<?php
error_reporting(E_ALL);

ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/logs/php_errors.log');

require __DIR__ . '/../src/Database.php';
require __DIR__ . '/../src/QueryBuilder.php';
require __DIR__ . '/../src/DB.php';
require __DIR__ . '/../src/RawExpression.php';


// Test query


//$query = (new QueryBuilder())
//    ->table('orders')
//    ->leftJoin('products', 'orders.product_id', '=', 'products.id')
//    ->rightJoin('categories', 'products.category_id', '=', 'categories.id')
//    ->select(['orders.id', 'products.name', 'categories.name as category_name'])
//    ->get();



//$query = (new QueryBuilder())
//    ->table('products')
//    ->select(['category_id', DB::raw('COUNT(*) as product_count'), DB::raw('SUM(price * stock) as total_value')])
//    ->where('status', '=', 'active')
//    ->orWhere(function($query) {
//        return $query->where('price', '>', 110)
//            ->where('stock', '>', 451);
//    })
//    ->groupBy('category_id')
//    ->having('total_value', '>', 999)
//    ->orderBy('total_value', 'DESC')
//    ->limit(3)
//    ->get();




//echo '<pre>';
//print_r($query);
//echo '</pre>';

