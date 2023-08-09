<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/migrate', function(){
    define('STDIN',fopen("php://stdin","r"));
    Artisan::call('migrate');
    dd('migrated!');
});
Route::get('/tokens/create', function (Request $request) {
    $token = $request->user()->createToken($request->token_name);

    return ['token' => $token->plainTextToken];
});
Route::prefix('/admin')->middleware('auth:sanctum')->group(function () {
    Route::prefix('/manager')->group(function () {
        //
        Route::get('/', 'ManagerController@index');
        Route::get('/{id}', 'ManagerController@show');
        Route::get('suspend/{id}', 'ManagerController@suspend');;
        Route::get('activate/{id}', 'ManagerController@activate');;

        Route::post('/', 'ManagerController@store');
        Route::put('/{id}', 'ManagerController@update');
        Route::delete('/{id}', 'ManagerController@destroy');
    });        //
    //Route::resource("")
});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/auth')->group(function () {

    //
    Route::post('/login', 'AuthController@login');
    Route::post('/register', 'UserController@register');
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', 'AuthController@me');
        Route::post('/logout', 'AuthController@logout');
        Route::put('/user/{id}', 'UserController@update');
        // Routes for sending emails
        Route::get('/send-email', 'EmailController@sendEmail');
    });

    Route::post('/reset-password', 'EmailController@resetPassword');
    Route::post("/reset-password/confirm", "EmailController@confirmResetPassword");
    // send email test
    /*Route::get("/send-message", function () {

        try {
            $random_string = Str::random(5);

            Mail::to("elgoharya837@gmail.com")->send(new \App\Mail\templates\ResetPasswordMail($random_string));

            return response()->json(['message' => 'mail sent successful']);

        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    });*/
});
Route::prefix('/product')
    ->group(function () {

        Route::get('/', 'ProductController@index');
        Route::get('/{id}', 'ProductController@show');
        Route::get('/translates/{id}', 'ProductController@show_translate');

        Route::middleware('auth:sanctum')
            ->group(function () {
                Route::post('/', 'ProductController@store');
                Route::put('/{id}', 'ProductController@update');
                Route::delete('/{id}', 'ProductController@destroy');
            });


    });

Route::prefix('/comments')->group(function () {
    //
    Route::get('/', 'CommentController@index');
    Route::middleware('auth:sanctum')
        ->group(function () {
            Route::post('/update_publish/{id}', 'CommentController@update_publish');
        });
    Route::get('/{id}', 'CommentController@show');
    Route::middleware('auth:sanctum')
        ->group(function () {
            Route::post('/', 'CommentController@store');
            Route::put('/{id}', 'CommentController@update');
            Route::delete('/{id}', 'CommentController@destroy');
        });
});
//  categories
Route::prefix('/categories')->group(function () {
    //
    Route::get('/', 'CategoryController@index');
    Route::get('/translates/{id}', 'CategoryController@show_translate');
    Route::get('/{id}', 'CategoryController@show');
    Route::get('/{id}/product', 'CategoryController@showProduct');

    Route::middleware('auth:sanctum')
        ->group(function () {
            Route::post('/', 'CategoryController@store');
            Route::put('/{id}', 'CategoryController@update');
            Route::delete('/{id}', 'CategoryController@destroy');
        });
});
Route::prefix('/coupon')->group(function () {
    //
    Route::post('/', 'CouponController@add');
    Route::get('/{id}', 'CouponController@remove');
});
Route::prefix('/pages')->group(function () {
    //
    Route::get('/', 'PagesController@index');
    Route::get('/{id}', 'PagesController@show');
});
Route::prefix('/cart')->group(function () {
    //
    Route::get('/{id}', 'CartController@show');

    Route::post('/', 'CartController@store');


    Route::put('/{id}', 'CartController@update');
    Route::post('/', 'CartController@store');
    Route::delete('/{id}', 'CartController@destroy');

});
Route::prefix('/user/address')->group(function () {
    //


    Route::middleware('auth:sanctum')
        ->group(function () {
            Route::get('/', 'UserAdressController@index');
            Route::get('/{id}', 'UserAdressController@show');
            Route::post('/', 'UserAdressController@store');
            Route::put('/{id}', 'UserAdressController@update');
            Route::delete('/{id}', 'UserAdressController@destroy');
        });
});
Route::prefix('/order')->group(function () {
    //
    Route::middleware('auth:sanctum')
        ->group(function () {

            Route::get('/', 'OrderController@index');
            Route::get('/{id}', 'OrderController@show');
            Route::post('/', 'OrderController@store');
            Route::put('/{id}', 'OrderController@update');
//            Route::delete('/{id}', 'OrderController@destroy');
        });
});


Route::prefix('/store')->group(function () {

    Route::get('/', 'StoreController@index');
    Route::middleware('auth:sanctum')
        ->group(function () {
            Route::post('/', 'StoreController@store');
            Route::put('/{id}', 'StoreController@update');
        });
});

/**
 * Offers API
 */
Route::prefix('/offers')->group(function () {

    Route::get('/', 'OfferController@index');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', 'OfferController@store');
        Route::put('/{id}', 'OfferController@update');
        Route::delete('/{id}', 'OfferController@destroy');
    });
});


Route::prefix('/lang')->group(function () {


    Route::get('/', 'LangController@index');
    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/update', 'LangController@update');
    });

});

Route::prefix('/vendors')->group(function () {


    Route::get('/', 'VendorsController@index');
    Route::get('/{id}', 'VendorsController@show');


});
Route::middleware('auth:sanctum')->prefix('/wishlist')->group(function () {

    Route::get('/', 'WishListController@index');
    Route::post('/', 'WishListController@store');
    Route::get('/{id}', 'WishListController@show');
    Route::put('/{id}', 'WishListController@update');
    Route::delete('/{id}', 'WishListController@destroy');

});


Route::prefix('/banner')->group(function () {
    //
    Route::get('/', 'BannerController@index');
    Route::get('/{id}', 'BannerController@show');
    Route::middleware('auth:sanctum')
        ->group(function () {
            Route::post('/', 'BannerController@store');
            Route::put('/{id}', 'BannerController@update');
            Route::delete('/{id}', 'BannerController@destroy');
        });
});

Route::prefix('/attachment')->group(function () {
    //
    Route::get('/', 'AttachmentController@index');
    Route::get('/{id}', 'AttachmentController@show');
    Route::middleware('auth:sanctum')
        ->group(function () {
            Route::post('/', 'AttachmentController@store');
            Route::put('/{id}', 'AttachmentController@update');
            Route::delete('/{id}', 'AttachmentController@destroy');
        });
});

Route::namespace("App\Http\Controllers")->prefix('/promotion')->group(function () {

    Route::get('/', "PromotionController@index");
    Route::get('/{id}', 'PromotionController@show');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', 'PromotionController@store');
        Route::put('/{id}', 'PromotionController@update');
        Route::delete('/{id}', 'PromotionController@destroy');
    });
});


Route::prefix('/customer')->group(function () {

    Route::middleware('auth:sanctum') //should make admin middleware
    ->group(function () {

        Route::get('/', 'CustomerController@index');
        Route::get('/{id}', 'CustomerController@show');
        Route::post('/', 'CustomerController@store');
        Route::put('/{id}', 'CustomerController@update');
//            Route::delete('/{id}', 'CustomerController@destroy');
    });
});


Route::prefix('/csv')->group(function () {

   Route::middleware('auth:sanctum')->group(function () {
       Route::post('/upload', function (Request $request) {

           $request->validate([
               'csv_file' => 'required|file|mimes:csv|max:2048',
           ]);


           $upload_file = $request->file('csv_file');
           $csv = new \App\Classes\CSV();

           if ($upload_file->isValid()) {
               $content = $csv->read($upload_file);

               $data = $csv->write($content);

               return response()->json($data);
           }

           return response()->json(['error' => 'Invalid file'], 400);

       });
   });


});

