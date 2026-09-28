<?php

use App\Middleware\Middleware;
use App\Middleware\RateLimiter;
use Dotenv\Dotenv;

require_once __DIR__ . '/vendor/autoload.php';

Dotenv::createImmutable(__DIR__)->load();

date_default_timezone_set('America/Lima');

$allowed_origins = array_map('trim', explode(',', $_ENV['CORS_ORIGINS'] ?? ''));
$origin = $_SERVER['HTTP_ORIGIN'] ?? '';

if (in_array($origin, $allowed_origins, true)) {
    header("Access-Control-Allow-Origin: $origin");
}
header("Vary: Origin");

ini_set('session.cookie_domain', $_ENV['COOKIE_DOMAIN'] ?? '');

header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization , X-CSRF-TOKEN");
header("Access-Control-Allow-Credentials: true");
header("X-Content-Type-Options: nosniff");
//header("Strict-Transport-Security: max-age=31536000; includeSubDomains");

if ($_SERVER["REQUEST_METHOD"] === "OPTIONS") {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';



$request_path = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

try{
    RateLimiter::check('global_ip' , $ip , 100 , '1 minute');
}catch(\Exception $e){
    header('Content-Type: application/json');
    echo json_encode(['state' => false, 'error' => $e->getMessage()]);
    exit;

}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $request_path === '/login') {
    try {
        
        RateLimiter::check('login_ip', $ip, 10, '1 minute');
    } catch (\Exception $e) {
        header('Content-Type: application/json');
        echo json_encode(['state' => false, 'error' => $e->getMessage()]);
        exit;
    }
}

require_once __DIR__ . '/Core/bootstrap.php';

     



use App\Controllers\Controller_Category;

use App\Controllers\Controller_Products;

use App\Controllers\Controller_State;

use App\Controllers\Controller_User;

use App\Controllers\Controller_Brand;

use App\Controllers\Controller_Register;

use Pecee\SimpleRouter\SimpleRouter;

use Pecee\Http\Middleware\BaseCsrfVerifier;

SimpleRouter::csrfVerifier(new BaseCsrfVerifier());

SimpleRouter::group(['middleware' => Middleware::class] , function(){
    SimpleRouter::get('/productos' , [Controller_Products::class, 'getAllProducts']);
    SimpleRouter::get('/productos/{id}' , [Controller_Products::class , 'getProductById']);
    SimpleRouter::post('/productos' , [Controller_Products::class , 'createProduct']);
    SimpleRouter::put('/productos', [Controller_Products::class , 'updateProduct']);
    SimpleRouter::delete('/productos', [Controller_Products::class , 'deleteProduct']);
    SimpleRouter::get('/categoria',[Controller_Category::class , 'getAllCategories']);
    SimpleRouter::get('/categoria/{id}',[Controller_Category::class , 'getCategoryById']);
    SimpleRouter::get('/estado',[Controller_State::class , 'getAllStates']);
    SimpleRouter::get('/estado/{id}', [Controller_State::class ,'getStateById']);
    SimpleRouter::get('/marca' , [Controller_Brand::class, 'getallbrands']);
    SimpleRouter::get('/data_products', [Controller_Products::class , 'getallDataProducts']);
    SimpleRouter::post('/register' , [Controller_Register::class , 'create_temporal_register']);
    SimpleRouter::post('/confirm_register' , [Controller_Register::class , 'confirm_register']);
    SimpleRouter::delete('/register' , [Controller_Register::class , 'delete_temporal_register']);
    SimpleRouter::get('/data_register' , [Controller_Register::class , 'get_register_history']);
    SimpleRouter::post('/stats' , [Controller_Register::class , 'get_stats']);
    SimpleRouter::post('/logout' , [Controller_User::class , 'logout_user']);
    SimpleRouter::get('/me' , [Controller_User::class , 'obtain_user']);

});


SimpleRouter::post('/login' , [Controller_User::class , 'login_user']);



try{
    echo SimpleRouter::start();
}catch(\Exception $e){
    http_response_code(403);
    header('Content-Type: application/json');
    echo json_encode(['state' => false , 'error' => 'CRSF invalido']);
}







?>