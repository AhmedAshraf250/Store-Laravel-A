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
 *     > php artisan config:cache // to cache the config files for better performance // look: 'bootstrap\cache\config.php'
 *     > php artisan config:clear
 *     > php artisan route:list   // {very importat to know all application routes and their names}
 *     > composer dump-autoload // to regenerate the list of all classes that need to be included in the project (autoloading)
 *     > php artisan migrate:status  // [Result]: Migration name .... Batch / Status
 * ***
 *
 *   # Commit [1]: first commit For Project V0.6 --> still Loding...
 *     > php artisan storage:link  // look 'config\filesystems.php'->['links']
 *     > php artisan make:request CategoryRequest
 *     > php artisan make:rule Filter // 'app\Rules\Filter.php' For Validation
 * ***
 *
 *   # Commit [2]: Appling & Adding Components(Class-based Components + Anonymous Components)
 *     > php artisan make:component alert --view  // --view ->()  : to create "View-Only Component"  -> look: 'resources\views\components\alert.blade.php'
 *     > php artisan make:component form.input --view  // 'resources\views\components\form\input.blade.php'
 *     > php artisan make:component Nav          // "Full Component" ->  look: 'app\View\Components\Nav.php' & 'resources\views\components\nav.blade.php'
 * ***
 *
 *   # Commit [3]: adding Pagination to categories tab, and make some modifications of defualt laravel Pagination
 *     > php artisan vendor:publish --tag=laravel-pagination // to make modifications on default pagination styles // LOOK: 'resources\views\vendor\pagination'Folder
 * ***
 *
 *
 *   # Commit [4]: adding & apply local and Global Scopes with impelement softDelets and adding some columns to exist tables, make seed with factories
 *     > php artisan make:migration add_softDeletes_to_categories_table
 *     > php artisan make:seeder UsersSeeder                // to create seeder class
 *     > php artisan db:seed --class=UserTableSeeder        // to run specific seeder class
 *     > php artisan make:factory ProductFactory            // to create factory class Dir: 'database/factories/ProductFactory.php'
 *     > php artisan make:factory CategoryFactory
 *     > php artisan make:factory StoreFactory
 *     > php artisan db:seed                                // to run the main seeder 'DatabaseSeeder.php'
 *          ==> It will execute the code it finds in the "DatabaseSeeder.php" class (with "call" method) -> (for Use with many seeders if available)
 *     > php artisan make:migration add_store_id to_users_table
 *     > php artisan make:controller Dashboard\CategoriesController -r // -r => resource controller (with all methods inside showed in 'php artisan route:list')
 *          (7 methods: index, create, store, show, edit, update, destroy)
 *     > php artisan make:scope ProductScope
 *     > composer require --dev mbezhanov/laravel-faker-provider-collection
 * ***
 *
 *   # Commit [5]: RELATIONS (['one_to_one', 'one_to_many', 'many_to_many'])
 *     > php artisan make:model Profile -m
 *     > php artisan make:model tag -m
 *     > php artisan make:migration create_product_tag_table
 *     > php artisan make:controller Dashboard\ProfileController
 *     > composer require symfony/intl
 * ***
 *
 *
 *   # Commit [6]: feat: add front interface layout with their components and middlewares
 *     > php artisan make:component FrontLayout
 *          -> [make 2 Files] ->'app\View\Components\FrontLayout.php'  [render]--->  'resources\views\components\front-layout.blade.php'
 *     > php artisan make:controller Front\HomeController
 *     > php artisan make:controller Front\ProductsController
 *     > php artisan make:migration add_type_column_to_users_table
 *     > php artisan make:migration add_last_active_at_column_to_users_table
 *     > php artisan make:middleware checkUserType          // "app\Http\Middleware\CheckUserType.php"
 *     > php artisan make:middleware UpdateUserLastActiveAt
 *
 *
 *   # Commit [7]: chore: remove (app/js) built asset and ignore it
 *     > rm -rf resources/js/app.js
 * ***
 *
 *
 *   # Commit [8]: Cart: initial shopping cart implementation using Repository Pattern, contract binding, constructor injection, custom facade & observer (WIP)
 *     > php artisan make:model Cart -m
 *     > artisan make:observer CartObserver --model=Cart   // Standard name is [ModelObserver] | Directory: "app\Observers\CartObserver.php"
 *     > php artisan make:controller Front\CartController -r
 *     > php artisan make:provider CartServiceProvider  // after created this file must be registered in 'app.config' in config folder.
 *     > php artisan make:component CartMenu // 'app\View\Components\CartMenu.php' // the Goal is passing data dynamicly to the component view
 *     > npm run prod // to build the assets with [webpack] and [mix] || any changes in js codes must be done in 'resources\js\app.js' then run 'npm run dev'
 * ***
 *
 *   # Commit [9]: Checkout: initiate checkout process using Order models with events & listeners
 *     > php artisan make:model Order -m
 *     > php artisan make:model OrderItem -m
 *     > php artisan make:model OrderAdress -m
 *     > php artisan make:controller Front\CheckoutController
 *     > php artisan make:migration add_quantity_column_to_products_table
 *     > php artisan make:listener EmptyCart
 *     > php artisan make:listener DeductProductQuantity
 *     > php artisan make:event OrderCreated
 *  ***
 *
 *   # Commit [10]: feat(notifications): implement initial notifications using database, mail, broadcast & queue
 *     > php artisan make:notification OrderCreatedNotification
 *     > php artisan make:listener SendOrderCreatedNotification
 *     > npm run mailpit // to start mailpit server || I initialize it in 'package.json' file >>> "mailpit": ".\\tools\\mailpit.exe",
 *     > php artisan vendor:publish --tag=laravel-notifications  // '\Illuminate\Notifications\resources\views 'to 'resources\views\vendor\notifications'
 *     > php artisan vendor:publish --tag=laravel-mail
 *     > php artisan notification:table // to create migration file for notification table  [Migration: 'create_notifications_table']
 *     > php artisan make:component Dashboard\NotificationsMenu
 *     > php artisan make:middleware MarkNotificationAsRead
 *     > php artisan queue:table                                                         // [Migration: 'create_jobs_table']
 *     > php artisan queue:work --queue=default,high,...
 *     > npm install --save laravel-echo pusher-js
 * ***
 *
 *   # Commit [11]: feat(auth): implement multi-guard authentication with Fortify + 2FA support
 *     > composer require laravel/fortify
 *     > php artisan vendor:publish --provider="Laravel\Fortify\FortifyServiceProvider"
 *     > composer require barryvdh/laravel-debugbar --dev  // To remove it 'composer remove barryvdh/laravel-debugbar'  // [DebugBar]
 *     > php artisan make:factory AdminFactory
 * ***
 *
 *   # Commit [12]: feat(api): implement Sanctum authentication and start building API resources/endpoints
 *     > php artisan make:controller Api\ProductsController --api
 *     > php artisan make:resource ProductResource
 *     > php artisan make:controller Api\AccessTokensController
 *     > php artisan make:middleware CheckApiToken
 * ***
 * 
 *   # Commit [13]: 
 *     > php artisan make:controller Front\CurrencyConverterController
 *     > composer require laravel/telescope --dev    >    php artisan telescope:install    >    php artisan migrate
 *     > php artisan make:middleware SetAppLocale
 *     > composer require mcamara/laravel-localization
 *     > php artisan vendor:publish --provider="Mcamara\LaravelLocalization\LaravelLocalizationServiceProvider"
 * ***
 * 
 *  # Commit [14]: feat(authorization): implement role-based permissions with MorphToMany polymorphic relations, Gates & Policies + distribute guards between Breeze (admin) and Fortify (web)
 *     > php artisan make:model Role -m
 *     > php artisan make:model RoleAbility -m
 *     > php artisan make:migration create_role_user_table
 *     > php artisan make:controller Dashboard\RolesController -r
 *     > php artisan make:controller Dashboard\AdminsController -r
 *     > php artisan make:controller Dashboard\UsersController -r
 *     > php artisan make:policy RolePolicy --model=Role      // Default name is [ModelPolicy] | Directory: "app\Policies\RolePolicy.php"
 *     > php artisan make:policy ProductPolicy --model=Product
 *     > php artisan make:policy ModelPolicy
 * ***
 * 
 *  # Commit [15]: implement queue|jobs, commands, custom exceptions.
 *     > php artisan make:exception InvalidOrderException
 *     > php artisan make:job DeleteExpireOrders
 *              > php artisan schedule:run  || > php artisan schedule:work ...
 *     > php artisan make:command DeleteExpireOrders // look in 'app\Console\Commands\DeleteExpireOrders.php'
 *              > php artisan orders:delete-expire --days=30 |/|\| [No expired pending orders found.] OR [✅ Deleted 70 expired pending orders.]
 *      > php artisan make:job ImportProducts
 *      > php artisan queue:work --queue=import,default --tries=3 --timeout=300
 *      
 *          [on server may be needed]:
 *      > composer install --optimize-autoload --no-dev | php artisan optimize (caching) | symlink(__DIR__.'/../AhmedIKA/storage/app/public', __DIR__.'/storage')
 *  ***
 * 
 *  # Commit [16]: feat(auth): initial social login implementation using Laravel Socialite
 *      > composer require laravel/socialite
 *      > php artisan make:controller Auth\SocialLoginController
 *      > php artisan make:migration add_social_providers_columns_to_users_table  // [[Deprecated]]
 *      > php artisan make:migration create_social_accounts_table
 *      > php artisan make:model SocialAccount
 *  ***
 * 
 *  # Commit [17]: feat(payments): add Stripe payment gateway with webhooks support (initial implementation)
 *      > composer require stripe/stripe-php
 *      > php artisan make:controller Fornt\PaymentsController
 *      > php artisan make:model Payment -m
 *      > php artisan make:job HandleStripeEvent
 *  ***
 * 
 *  # Commit [18]: feat(delivery): initial realtime map updates for delivery using Pusher broadcasting
 *      > php artisan make:model Delivery -m
 *      > php artisan make:migration add_pending_status_to_deliveries_table
 *      > php artisan make:controller Api\DeliveriesController
 *      > php artisan make:event DeliveryLocationUpdated
 * 
 *  # Commit [19]: feat(auth): refactor auth system - separate front controllers/views, add OTP + custom email verification, prepare 2FA, session isolation
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