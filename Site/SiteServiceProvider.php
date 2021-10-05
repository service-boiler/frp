<?php

namespace ServiceBoiler\Prf\Site;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use ServiceBoiler\Prf\Site\Http\ViewComposers\CurrentRouteViewComposer;
use ServiceBoiler\Prf\Site\Http\ViewComposers\PageViewComposer;
use ServiceBoiler\Prf\Site\Http\ViewComposers\UserViewComposer;
use ServiceBoiler\Prf\Site\Listeners;
use ServiceBoiler\Prf\Site\Middleware\Admin;
use ServiceBoiler\Prf\Site\Middleware\FerroliUser;
use ServiceBoiler\Prf\Site\Support\Cart;

class SiteServiceProvider extends ServiceProvider
{

	/**
	 * Пространсво имен для контроллеров пакета
	 *
	 * @var string
	 */
	protected $namespace = 'ServiceBoiler\Prf\Site\Http\Controllers';

	protected $middleware = [
		'admin' => Admin::class,
		'ferroliUser' => FerroliUser::class,
	];

	protected $policies = [
		Models\AcademyVideo::class => Policies\AcademyVideoPolicy::class,
		Models\AcademyPresentation::class => Policies\AcademyPresentationPolicy::class,
        Models\Act::class => Policies\ActPolicy::class,
        Models\Address::class => Policies\AddressPolicy::class,
        Models\AddressType::class => Policies\AddressTypePolicy::class,
        Models\Authorization::class => Policies\AuthorizationPolicy::class,
        Models\Catalog::class => Policies\CatalogPolicy::class,
        Models\Certificate::class => Policies\CertificatePolicy::class,
        Models\Contact::class => Policies\ContactPolicy::class,
        Models\Contract::class => Policies\ContractPolicy::class,
        Models\Contragent::class => Policies\ContragentPolicy::class,
        Models\Datasheet::class => Policies\DatasheetPolicy::class,
        Models\Difficulty::class => Policies\DifficultyPolicy::class,
        Models\DigiftBonus::class => Policies\DigiftBonusPolicy::class,
        Models\DigiftUser::class => Policies\DigiftUserPolicy::class,
        Models\Distance::class => Policies\DistancePolicy::class,
        Models\Engineer::class => Policies\EngineerPolicy::class,
        Models\Equipment::class => Policies\EquipmentPolicy::class,
        Models\EsbContract::class => Policies\EsbContractPolicy::class,
        Models\EsbContractTemplate::class => Policies\EsbContractTemplatePolicy::class,
        Models\EsbProductMaintenance::class => Policies\EsbProductMaintenancePolicy::class,
        Models\Event::class => Policies\EventPolicy::class,
        Models\File::class => Policies\FilePolicy::class,
        Models\Image::class => Policies\ImagePolicy::class,
        Models\Launch::class => Policies\LaunchPolicy::class,
        Models\Mission::class => Policies\MissionPolicy::class,
        Models\Member::class => Policies\MemberPolicy::class,
        Models\Mounter::class => Policies\MounterPolicy::class,
        Models\Mounting::class => Policies\MountingPolicy::class,
        Models\Order::class => Policies\OrderPolicy::class,
        Models\OrderItem::class => Policies\OrderItemPolicy::class,
        Models\Phone::class => Policies\PhonePolicy::class,
        Models\Product::class => Policies\ProductPolicy::class,
        Models\Promocode::class => Policies\PromocodePolicy::class,
        Models\Repair::class => Policies\RepairPolicy::class,
        Models\RetailSaleReport::class => Policies\RetailSaleReportPolicy::class,
        Models\StandOrder::class => Policies\StandOrderPolicy::class,
        Models\StandOrderItem::class => Policies\StandOrderItemPolicy::class,
        Models\Storehouse::class => Policies\StorehousePolicy::class,
        Models\Tender::class => Policies\TenderPolicy::class,
        Models\Trade::class => Policies\TradePolicy::class,
        Models\User::class => Policies\UserPolicy::class,
        Models\UserRelation::class => Policies\UserRelationPolicy::class,
        Models\UserFlRoleRequest::class => Policies\UserFlRoleRequest::class,
        Models\Webinar::class => Policies\WebinarPolicy::class,

	];

	/**
	 * Register the service provider.
	 */
	public function register()
	{
		$this->app->bind('site', function ($app) {
			return new Site($app);
		});
		$this->app->alias('site', Site::class);

		$this->app->bind('currency', function ($app) {
			return new Models\Currency($app);
		});

		$this->app->bind('cart', function ($app) {
			return new Cart($app, $app->make('session'));
		});

		$this->app->bind(Contracts\Exchange::class, function () {

			return new Exchanges\Cbr();
		});

		$this->loadConfig()->loadMigrations();
		$this->registerMiddleware();


	}

	/**
	 * @return $this
	 */
	private function loadMigrations()
	{
		$this->loadMigrationsFrom(
			$this->packagePath('database/migrations')
		);

		return $this;
	}

	/**
	 * @param $path
	 *
	 * @return string
	 */
	private function packagePath($path)
	{
		return __DIR__ . "/../{$path}";
	}

	private function loadConfig()
	{
		$this->mergeConfigFrom(
			$this->packagePath('config/site.php'), 'site'
		);

		$this->mergeConfigFrom(
			$this->packagePath('config/cart.php'), 'cart'
		);

		return $this;
	}

	private function registerMiddleware()
	{
		if (!empty($this->middleware)) {

			/** @var \Illuminate\Routing\Router $router */
			$router = $this->app['router'];
			$registerMethod = false;

			if (method_exists($router, 'middleware')) {
				$registerMethod = 'middleware';
			} elseif (method_exists($router, 'aliasMiddleware')) {
				$registerMethod = 'aliasMiddleware';
			}

			if ($registerMethod !== false) {
				foreach ($this->middleware as $key => $class) {
					$router->$registerMethod($key, $class);
				}
			}
		}

	}

	/**
	 * Bootstrap the application events.
	 */
	public function boot()
	{

		$this->publishAssets();
		$this->publishTranslations();
		$this->publishConfig();
		$this->loadCommands();
		$this->loadMorphMap();
		$this->loadViews();
		$this->extendBlade();
		$this->extendValidator();
		$this->registerEvents();
		$this->registerPolicies();
		$this->loadApiRoutes();
		$this->loadWebRoutes();


	}

	/**
	 * Publish Portal assets
	 *
	 * @return $this
	 */
	private function publishAssets()
	{

		$this->publishes([
			$this->packagePath('resources/assets') => resource_path('assets'),
		], 'public');

		return $this;
	}

	private function publishTranslations()
	{

		$this->loadTranslations();

		$this->publishes([
			$this->packagePath('resources/lang') => resource_path('lang/vendor/site'),
		], 'translations');

		return $this;
	}

	private function loadTranslations()
	{
		$this->loadTranslationsFrom($this->packagePath('resources/lang'), 'site');
	}

	/**
	 * @return $this
	 */
	private function publishConfig()
	{
		$this->publishes([
			$this->packagePath('config/site.php') => config_path('site.php'),
		], 'config');

		$this->publishes([
			$this->packagePath('config/cart.php') => config_path('cart.php'),
		], 'config');

		return $this;
	}

	/**
	 * @return $this
	 */
	private function loadCommands()
	{
		if ($this->app->runningInConsole()) {
			$this->commands([
				Console\SiteGenerateRoutesCommand::class,
				Console\SiteRunCommand::class,
				Console\SiteSetupCommand::class,
				Console\SiteResourceMakeCommand::class,
			]);
		}

		return $this;
	}

	/**
	 * @return $this
	 */
	private function loadMorphMap()
	{
		Relation::morphMap([
			'acts' => Models\Act::class,
            'addresses' => Models\Address::class,
            'addresses' => Models\Address::class,
            'authorizations' => Models\Authorization::class,
            'catalogs' => Models\Catalog::class,
            'contacts' => Models\Contact::class,
            'contragents' => Models\Contragent::class,
            'distributorSales' => Models\DistributorSale::class,
            'equipments' => Models\Equipment::class,
            'esb_product_launches' => Models\EsbProductLaunch::class,
            'esb_product_maintenances' => Models\EsbProductMaintenance::class,
            'mountings' => Models\Mounting::class,
            'orders' => Models\Order::class,
            'products' => Models\Product::class,
            'repairs' => Models\Repair::class,
            'retailSaleReports' => Models\RetailSaleReport::class,
            'standOrders' => Models\StandOrder::class,
            'tickets' => Models\Ticket::class,
            'tenders' => Models\Tender::class,
            'users' => Models\User::class,
		]);

		return $this;
	}

	/**
	 * Publish Portal views
	 *
	 * @return $this
	 */
	private function loadViews()
	{
		view()->composer("*", CurrentRouteViewComposer::class);
		view()->composer("*", UserViewComposer::class);
		view()->composer("*", PageViewComposer::class);

		$viewsPath = $this->packagePath('resources/views/');

		$this->loadViewsFrom($viewsPath, 'site');

		$this->publishes([
			$viewsPath => resource_path('views/vendor/site'),
		], 'views');

		return $this;
	}

	private function extendBlade()
	{
		if (class_exists('\Blade')) {
			Blade::component('site::components.alert', 'alert');
			Blade::component('site::components.bool', 'bool');
			Blade::component('site::components.pagination', 'pagination');

			Blade::directive('admin', function () {
				return "<?php if (app('site')->isAdmin()) : ?>";
			});

			Blade::directive('elseadmin', function () {
				return "<?php else: // Site::admin ?>";
			});

			Blade::directive('endadmin', function () {
				return "<?php endif; // Site::admin ?>";
			});

			Blade::directive('pre', function ($data) {
				return "<?php pre($data); ?>";
			});
		}
	}

	private function extendValidator()
	{
		Validator::extend('isTimeStamp', function ($attribute, $value, $parameters) {
			return ((string)(int)$value === $value)
				&& ($value <= PHP_INT_MAX)
				&& ($value >= ~PHP_INT_MAX);
		});
	}

	private function registerEvents()
	{
		Event::subscribe(new Listeners\ActListener());
		Event::subscribe(new Listeners\AddressListener());
		Event::subscribe(new Listeners\AuthorizationListener());
		Event::subscribe(new Listeners\DigiftListener());
		Event::subscribe(new Listeners\FeedbackListener());
		Event::subscribe(new Listeners\MemberListener());
		Event::subscribe(new Listeners\MessageListener());
		Event::subscribe(new Listeners\MissionListener());
		Event::subscribe(new Listeners\MountingListener());
		Event::subscribe(new Listeners\MounterListener());
		Event::subscribe(new Listeners\OrderListener());
		Event::subscribe(new Listeners\RepairListener());
		Event::subscribe(new Listeners\ReviewListener());
		Event::subscribe(new Listeners\StandOrderListener());
		Event::subscribe(new Listeners\TenderListener());
		Event::subscribe(new Listeners\TenderExpiredListener());
		Event::subscribe(new Listeners\TicketListener());
		Event::subscribe(new Listeners\UserListener());
		Event::subscribe(new Listeners\UserRelationListener());
		Event::subscribe(new Listeners\UserFlRoleRequestListener());
	}

	/**
	 * Register the application's policies.
	 *
	 * @return void
	 */
	public function registerPolicies()
	{
		foreach ($this->policies as $key => $value) {
			Gate::policy($key, $value);
		}
	}

	protected function loadApiRoutes()
	{
		Route::prefix('api')
			->middleware('api')
			->namespace($this->namespace)
			->group($this->packagePath('routes/api.php'));
	}

	protected function loadWebRoutes()
	{
		Route::middleware('web')
			->namespace($this->namespace)
			->group($this->packagePath('routes/web.php'));
	}


}
