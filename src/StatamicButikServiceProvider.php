<?php

namespace Jonassiewertsen\StatamicButik;

use Jonassiewertsen\StatamicButik\Http\Models\Order;
use Jonassiewertsen\StatamicButik\Http\Models\Product;
use Jonassiewertsen\StatamicButik\Http\Models\Shipping;
use Jonassiewertsen\StatamicButik\Http\Models\Tax;
use Jonassiewertsen\StatamicButik\Policies\OrderPolicy;
use Jonassiewertsen\StatamicButik\Policies\ProductPolicy;
use Jonassiewertsen\StatamicButik\Policies\ShippingPolicy;
use Jonassiewertsen\StatamicButik\Policies\TaxPolicy;
use Livewire\Livewire;
use Mollie\Laravel\MollieServiceProvider;
use Statamic\Facades\CP\Nav;
use Statamic\Facades\Permission;
use Statamic\Providers\AddonServiceProvider;

class StatamicButikServiceProvider extends AddonServiceProvider
{
    protected $commands = [
        \Jonassiewertsen\StatamicButik\Commands\InstallButik::class,
    ];

    protected $routes = [
        'cp'    => __DIR__.'/../routes/cp.php',
        'web'   => __DIR__.'/../routes/web.php',
    ];

    protected $widgets = [
        \Jonassiewertsen\StatamicButik\Widgets\Orders::class,
    ];

    protected $fieldtypes = [
        \Jonassiewertsen\StatamicButik\Fieldtypes\Money::class,
        \Jonassiewertsen\StatamicButik\Fieldtypes\Tax::class,
    ];

    protected $listen = [
        \Jonassiewertsen\StatamicButik\Events\PaymentSubmitted::class => [
            \Jonassiewertsen\StatamicButik\Listeners\CreateOpenOrder::class,
        ],
        \Jonassiewertsen\StatamicButik\Events\PaymentSuccessful::class => [
            \Jonassiewertsen\StatamicButik\Listeners\SendPurchaseConfirmationToCustomer::class,
            \Jonassiewertsen\StatamicButik\Listeners\SendPurchaseConfirmationToSeller::class,
            \Jonassiewertsen\StatamicButik\Listeners\ReduceProductStock::class,
        ],
    ];

    protected $middlewareGroups = [
        'validateExpressCheckoutRoute' => [
            \Jonassiewertsen\StatamicButik\Http\Middleware\ValidateExpressCheckoutRoute::class,
        ],
        'validateCheckoutRoute' => [
            \Jonassiewertsen\StatamicButik\Http\Middleware\ValidateCheckoutRoute::class,
        ],
        'cartNotEmpty' => [
            \Jonassiewertsen\StatamicButik\Http\Middleware\CartNotEmpty::class,
        ],
        'butikRoutes' => [
            \Jonassiewertsen\StatamicButik\Http\Middleware\UpdateCart::class,
        ]
    ];

    protected $scripts = [
        __DIR__ . '/../public/js/statamic-butik.js',
    ];

    protected $policies = [
        Product::class  => ProductPolicy::class,
        Shipping::class => ShippingPolicy::class,
        Tax::class      => TaxPolicy::class,
        Order::class    => OrderPolicy::class,
    ];

    public function boot(): void
    {
         parent::boot();

         $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'butik');
         $this->loadViewsFrom(__DIR__.'/../resources/views', 'butik');
         $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

         $this->bootPermissions();
         $this->createNavigation();
         $this->bootLivewireComponents();

        if ($this->app->runningInConsole()) {
            // Config
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('butik.php'),
            ], 'butik-config');

            // Views
            $this->publishes([
                __DIR__.'/../resources/views/email' => resource_path('views/vendor/butik/emails'),
            ], 'butik-views');
            $this->publishes([
                __DIR__.'/../resources/views/web' => resource_path('views/vendor/butik/web'),
            ], 'butik-views');
            $this->publishes([
                __DIR__.'/../resources/views/widgets' => resource_path('views/vendor/butik/widgets'),
            ], 'butik-views');

            // Images
            $this->publishes([
                __DIR__.'/../public/images' => public_path('vendor/butik/images'),
            ], 'butik-images');

            // Resources
            $this->publishes([
                __DIR__.'/../public/css' => public_path('vendor/butik/css'),
            ], 'butik-resources');
            $this->publishes([
                __DIR__.'/../public/js' => public_path('vendor/butik/js'),
            ], 'butik-resources');

            // Lang
            $this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/butik'),
            ], 'butik-lang');
        }
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'butik');

        // Register the main class to use with the facade
        $this->app->singleton('statamic-butik', function () {
            return new StatamicButik;
        });

        // Registering the service provider
        $this->app->register(MollieServiceProvider::class);
    }

    private function createNavigation(): void {
        Nav::extend(function ($nav) {

            // Orders
            $nav->create(__('butik::order.plural'))
                ->section('Butik')
                ->can(auth()->user()->can('view orders'))
                ->route('butik.orders.index')
                ->icon('drawer-file');

            // Products
            $nav->create(__('butik::product.plural'))
                ->section('Butik')
                ->can(auth()->user()->can('view products'))
                ->route('butik.products.index')
                ->icon('tags');

            // Settings
            $nav->create(__('butik::setting.plural'))
                ->section('Butik')
                ->icon('settings-slider')
                ->children([
                   $nav->item(__('butik::tax.plural'))->route('butik.taxes.index')->can('view taxes'),
                   $nav->item(__('butik::shipping.plural'))->route('butik.shippings.index')->can('view shippings'),
               ]);
        });
    }

    protected function bootPermissions(): void {
        $this->app->booted(function () {
            Permission::group('butik', 'Statamic Butik', function () {
                Permission::register('view orders', function ($permission) {
                    $permission->children([
                        Permission::make('show orders'),
                        Permission::make('update orders'),
                    ]);
                });
                Permission::register('view products', function ($permission) {
                    $permission->children([
                        Permission::make('edit products')->children([
                            Permission::make('create products'),
                            Permission::make('delete products')
                        ])
                    ]);
                });
                Permission::register('view taxes', function ($permission) {
                    $permission->children([
                        Permission::make('edit taxes')->children([
                            Permission::make('create taxes'),
                            Permission::make('delete taxes')
                        ])
                    ]);
                });
                Permission::register('view shippings', function ($permission) {
                    $permission->children([
                        Permission::make('edit shippings')->children([
                            Permission::make('create shippings'),
                            Permission::make('delete shippings'),
                         ]),
                    ]);
                });
            });
        });
    }

    protected function bootLivewireComponents(): void {
        Livewire::component('butik::shop', \Jonassiewertsen\StatamicButik\Http\Livewire\Shop::class);
        Livewire::component('butik::cart', \Jonassiewertsen\StatamicButik\Http\Livewire\Cart::class);
        Livewire::component('butik::cart-icon', \Jonassiewertsen\StatamicButik\Http\Livewire\CartIcon::class);
        Livewire::component('butik::add-to-cart', \Jonassiewertsen\StatamicButik\Http\Livewire\AddToCart::class);
    }
}
