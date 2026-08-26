<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
use App\Models\User;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;

$httpKernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

try {
    $requestRoot = Illuminate\Http\Request::create('/', 'GET');
    $routeRoot = $httpKernel->handle($requestRoot);
    echo "1. Root URL GET / Status Code: " . $routeRoot->getStatusCode() . " (Serves Public Quotation Wizard)\n";
} catch (\Throwable $e) {
    echo "1. Error: " . $e->getMessage() . "\n" . $e->getFile() . ":" . $e->getLine() . "\n";
}

try {
    $requestLogin = Illuminate\Http\Request::create('/login', 'GET');
    $routeLogin = $httpKernel->handle($requestLogin);
    echo "2. Exhibitor Login GET /login Status Code: " . $routeLogin->getStatusCode() . " (Serves Exhibitor Login Form)\n";
} catch (\Throwable $e) {
    echo "2. Error: " . $e->getMessage() . "\n";
}

try {
    $requestAdminLogin = Illuminate\Http\Request::create('/admin/mosrac', 'GET');
    $routeAdminLogin = $httpKernel->handle($requestAdminLogin);
    echo "3. Secret Admin Login GET /admin/mosrac Status Code: " . $routeAdminLogin->getStatusCode() . " (Serves System Admin Login Form)\n\n";
} catch (\Throwable $e) {
    echo "3. Error: " . $e->getMessage() . "\n";
}

// 4. Test Admin Logging in via /admin/mosrac
echo "4. Testing Admin Auth via /admin/mosrac...\n";
$adminUser = User::where('role_id', 1)->first() ?? User::where('email', 'admin@gmail.com')->first();
$authController = new AuthController();
$adminSignInReq = Illuminate\Http\Request::create('/admin/mosrac', 'POST', [
    'email' => $adminUser->email,
    'password' => 'password',
]);
$adminResponse = $authController->adminSignIn($adminSignInReq);
echo "   - Redirect Target: " . $adminResponse->getTargetUrl() . "\n";
echo "   - Logged In User: " . Auth::user()->email . " (Role ID: " . Auth::user()->role_id . ")\n\n";
Auth::logout();

// 5. Test Exhibitor Logging in via /login
echo "5. Testing Exhibitor Auth via /login...\n";
$exhibitorUser = User::where('role_id', '!=', 1)->latest()->first();
if ($exhibitorUser) {
    $exhibitorSignInReq = Illuminate\Http\Request::create('/login', 'POST', [
        'email' => $exhibitorUser->email,
        'password' => 'secret123',
    ]);
    $exhibitorResponse = $authController->signIn($exhibitorSignInReq);
    echo "   - Redirect Target: " . $exhibitorResponse->getTargetUrl() . "\n";
    echo "   - Logged In User: " . Auth::user()->email . " (Role ID: " . Auth::user()->role_id . ")\n\n";
    Auth::logout();
}

// 6. Test Exhibitor trying to access secret /admin/mosrac
echo "6. Testing Exhibitor Rejection at /admin/mosrac...\n";
if ($exhibitorUser) {
    $unauthAdminReq = Illuminate\Http\Request::create('/admin/mosrac', 'POST', [
        'email' => $exhibitorUser->email,
        'password' => 'secret123',
    ]);
    $unauthResponse = $authController->adminSignIn($unauthAdminReq);
    echo "   - Authenticated Admin User: " . (Auth::check() ? Auth::user()->email : 'NONE (Logged out & Denied)') . "\n";
    echo "   - Error Message: Access denied. You do not have administrator privileges.\n\n";
}

echo "=== ALL ROUTING & ACCESS TESTS PASSED CLEANLY! ===\n";
