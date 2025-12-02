<?php

/**
 * Commands Flow in building this application
 * 
 * تسلسل الاوامر التى استخدمتها فى البناء
 *
 */

/**
 * php artisan cache:clear 
 * php artisan config:clear 
 * php artisan route:clear 
 * php artisan view:clear 
 * php artisan optimize:clear
 * ===============
 * php artisan config:cache
 * php artisan route:cache
 * php artisan optimize
 */



/**
 * # Commands for Laravel:
 *
 *     > php artisan key:generate // '.env' file to generate other key to the Application in that variable 'APP_KEY'
 *
 *     > php artisan config:cache // to cache the config files for better performance // look: 'bootstrap\cache\config.php'
 *     > php artisan config:clear 
 *
 *     > php artisan make:seeder UsersSeeder                // to create seeder class
 *     > php artisan db:seed --class=UserTableSeeder        // to run specific seeder class
 *     > php artisan db:seed                                // to run the main seeder 'DatabaseSeeder.php'
 *      ==> It will execute the code it finds in the "DatabaseSeeder.php" class (with "call" method) -> (for Use with many seeders if available)
 *
 *     > php artisan make:factory ProductFactory            // to create factory class
 *     > php artisan db:seed
 *
 *     > php artisan make:controller Dashboard\CategoriesController -r // -r => resource controller (with all methods inside showed in 'php artisan route:list')
 *      (7 methods: index, create, store, show, edit, update, destroy)
 *
 *     > php artisan route:list   // {very importat to know all application routes and their names}
 *
 *     > php artisan storage:link  // look 'config\filesystems.php'->['links']
 *
 *     > composer dump-autoload // to regenerate the list of all classes that need to be included in the project (autoloading)
 *
 *     > php artisan make:request CategoryRequest
 *
 *     > php artisan make:rule Filter // 'app\Rules\Filter.php' For Validation
 *
 *     > php artisan make:component alert --view  // --view ->()  : to create "View-Only Component"  -> look: 'resources\views\components\alert.blade.php'
 *             
 *     > php artisan make:component form.input --view  // 'resources\views\components\form\input.blade.php'
 *     > php artisan make:component Nav          // "Full Component" ->  look: 'app\View\Components\Nav.php' & 'resources\views\components\nav.blade.php'
 *
 *     > php artisan vendor:publish --tag=laravel-pagination // to make modifications on default pagination styles // LOOK: 'resources\views\vendor\pagination'Folder
 *
 *     > php artisan make:migration add_softDeletes_to_categories_table
 *     > php artisan migrate:status  // [Result]: Migration name .... Batch / Status
 *
 *     > php artisan make:scope ProductScope
 *
 *     > composer require symfony/intl
 * 
 * 
 *     > php artisan make:migration add_type_column_to_users_table
 *     > php artisan make:middleware checkUserType          // "app\Http\Middleware\CheckUserType.php"
 * 
 *   # Commit [7]:
 *     > php artisan make:model Cart -m
 *     > artisan make:observer CartObserver --model=Cart   // Standard name is [ModelObserver] | Directory: "app\Observers\CartObserver.php"
 *     > php artisan make:controller Front\CartController -r
 *     > php artisan make:provider CartServiceProvider  // after created this file must be registered in 'app.config' in config folder.
 *     > php artisan make:component CartMenu // 'app\View\Components\CartMenu.php' // the Goal is passing data dynamicly to the component view
 *     > npm run prod // to build the assets with [webpack] and [mix] || any changes in js codes must be done in 'resources\js\app.js' then run 'npm run dev'
 * ***
 *
 * *   # Commit [8]:
 *     > php artisan make:model Order -m
 *     > php artisan make:model OrderItem -m
 *     > php artisan make:model OrderAdress -m
 *     > php artisan make:controller Front\CheckoutController
 *     > php artisan make:migration add_quantity_column_to_products_table
 *     > php artisan make:listener EmptyCart
 *     > php artisan make:listener DeductProductQuantity
 *     > php artisan make:event OrderCreated 
 *
 */

use Illuminate\Support\Facades\Auth;

?>

<?php
// categories (id (PK), parent_id (FK), name, slug (UQ), ...)
// stores (id (PK), name, ...)
// products (id (PK), name, slug (UQ), description, price, ...)

// orders (id (PK), number, user_id, status)
// orders_items (order_id (FK), product_id, quantity)

// هذه الطريقة هى افضل لو عندى تصنيفات كثيره ويوجد تصنيفات بها تصنيفات اخرى فرعية وذلك بدلا من عمل جداول اخرى وربطهم بعضهم البعض
// ---- categories table ---- //
/*
3
    id, parent_id, name
    1 , null     , clothes
    2 , 1        , child_clothes
    3 , 2        , child_clothes_boys
    4 , 2        , child_clothes_girls
    5 , 3        , child_clothes_boys_cate
*/

?>
`
<?php
Auth::user()->name
/*
     * هذا الاوث فاساد كلاس بكتبه فى اى مكان فى البروجيكت بيتم التعرف عليه بدون ما نلحق به النايم اسبايس خاصته
     * !! اى اوث فاساد كلاس تقريبا جميعهم متعرفين فى الجلوبال نايم اسبايس  !!
     * يوجد بلارافيل حاجه اسمها الايلياسيس مثلا لما نكون فى ملفات الفيوز وعشان نستدعى مثلا كلاسات بنستخدمها كثيرا مثلا وعشان ما نضطر فى كل مره نلحق بها النايم اسبايس
     * جاءت الايلياسيس وحلت هذه المشكلة يمكن النظر فى ملف :ك
     * "config" Folder -> "app.php"
     * كما يمكن ان ننشئ كلاس خاص بنا ونعطى له اسم ونستدعيه بهذا الإسم فى الجلوبال نايم اسبايس عادى داخل هذا الكى "إلياسيس" فى ملف الآب:ك
        'aliases' => Facade::defaultAliases()->merge([
            'Auth' => \Illuminate\Support\Facades\Auth::class
            'ExampleClass' => App\Example\ExampleClass::class,
            // ...
        ])->toArray(),
    */

/*
     *      @if(Auth::check)
     *          // do something
     *      @endif
     *
     *      @if(Auth::check)
     *      @endif
     *      (===)
     *      @auth
     *      @endauth
     * the opposite of @auth ==> @guest
     *
     *
     *
     *
     */
?>
<?php

// object always returns true even if that object is null

?>
<?php ?>
<?php ?>
<?php ?>
<?php ?>
<?php ?>
<?php ?>
<?php ?>
<?php ?>
<?php ?>
<?php ?>
<?php ?>
<?php ?>
<?php ?>
<?php ?>
<?php ?>
<?php ?>
<?php ?>
<?php ?>
<?php ?>
<?php ?>
<?php ?>