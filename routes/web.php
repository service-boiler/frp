<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use QuadStudio\Service\Site\Exports\Excel\OrderExcel;
use QuadStudio\Service\Site\Exports\Word\ContractWordProcessor;
use QuadStudio\Service\Site\Exports\Word\EsbContractWordProcessor;
use QuadStudio\Service\Site\Exports\Word\MissionWordProcessor;
use QuadStudio\Service\Site\Models\AcademyProgramStage;
use QuadStudio\Service\Site\Models\AcademyPresentationSlide;
use QuadStudio\Service\Site\Models\Act;
use QuadStudio\Service\Site\Models\Block;
use QuadStudio\Service\Site\Models\Catalog;
use QuadStudio\Service\Site\Models\Contract;
use QuadStudio\Service\Site\Models\EsbContract;
use QuadStudio\Service\Site\Models\Element;
use QuadStudio\Service\Site\Models\Equipment;
use QuadStudio\Service\Site\Models\EventType;
use QuadStudio\Service\Site\Models\FileType;
use QuadStudio\Service\Site\Models\HeadBannerBlock;
use QuadStudio\Service\Site\Models\IndexCardsBlock;
use QuadStudio\Service\Site\Models\IndexQuadroBlock;
use QuadStudio\Service\Site\Models\Image;
use QuadStudio\Service\Site\Models\Mission;
use QuadStudio\Service\Site\Models\Mounting;
use QuadStudio\Service\Site\Models\Order;
use QuadStudio\Service\Site\Models\Repair;
use QuadStudio\Service\Site\Models\RegionItalyDistrict;
use QuadStudio\Service\Site\Models\Tender;
use QuadStudio\Service\Site\Models\RevisionPart;
use QuadStudio\Service\Site\Models\VideoBlock;
use QuadStudio\Service\Site\Pdf\ActPdf;
use QuadStudio\Service\Site\Pdf\MissionPdf;
use QuadStudio\Service\Site\Pdf\MountingPdf;
use QuadStudio\Service\Site\Pdf\RepairPdf;
use QuadStudio\Service\Site\Pdf\TenderResultPdf;
use QuadStudio\Service\Site\Pdf\RevisionPartPdf;

Route::group([
	'namespace' => 'Auth',
],
	function () {
		// Authentication Routes...
		Route::get('login', 'LoginController@showLoginForm')->name('login');
		Route::post('login', 'LoginController@login');
		Route::post('logout', 'LoginController@logout')->name('logout');

		// Registration Routes...
		Route::get('register', 'RegisterController@showRegistrationForm')->name('register');
		Route::post('register', 'RegisterController@register');
        
		Route::get('register-confirm-phone/{user}', 'RegisterController@registerConfirmPhone')->name('register_confirm_phone');
		Route::post('register-success/{user}', 'RegisterController@registerVerifyPhone')->name('register_success');
		Route::post('register-success/{user}/update-phone', 'RegisterController@registerUpdatePhone')->name('register_phone_update');
		
        Route::get('register/fl', 'RegisterController@showRegistrationFlForm')->name('register_fl');
		Route::get('register/esb', 'RegisterController@showRegistrationEsbForm')->name('register_esb_form');
		Route::post('register/esb', 'RegisterController@registerEsb')->name('register_esb');
		Route::get('register/fls', 'RegisterController@showRegistrationFlsForm')->name('register_fls');
		Route::post('register/fl', 'RegisterController@register_fl');
		Route::get('register/flm', 'RegisterController@showRegistrationFlmForm')->name('register_flm');
		Route::get('register/prereg/{guid}', 'RegisterController@showRegistrationPreregForm')->name('register_prereg');
		
		Route::get('/register/confirm/{token}', 'RegisterController@confirm')->name('confirm');
		
        Route::get('/register/confirm/{token}', 'RegisterController@confirm')->name('confirm');


		// Password Reset Routes...
        Route::get('password/confirm-phone/{user}', 'ForgotPasswordController@confirmPhone')->name('password.confirm_phone');
		Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
		Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
		Route::post('password/sms', 'ForgotPasswordController@sendResetSmsEmail')->name('password.sms');
		Route::post('password/sms/{user}', 'ForgotPasswordController@resetSmsPassword')->name('password.reset_sms');
		Route::post('password/sms-resend/{user}', 'ForgotPasswordController@resendSmsPassword')->name('password.resend_sms');
		Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
		Route::post('password/reset', 'ResetPasswordController@reset');
        
		
	});

Route::group(['middleware' => ['online']],
	function () {

// front // Главная страница
		Route::get('/',
			'IndexController@index')
			->name('index');


// front // Интернет-магазины
		Route::match(['get', 'post'], '/eshop',
			'MapController@online_stores')
			->name('online-stores');
            
// front // Черный список
		Route::match(['get', 'post'], '/black-list',
			'MapController@black_list')
			->name('black-list');

// front // Где купить
		Route::match(['get', 'post'], '/dealers',
			'MapController@where_to_buy')
			->name('where-to-buy');

// front // Сервисные центры
		Route::match(['get', 'post'], '/services',
			'MapController@service_centers')
			->name('service-centers');
            
// front // Публичная карточка пользователя
		Route::get('/public_user_card/{user}','MapController@publicUserCard')
            ->name('public_user_card');

// front // Заявки на монтаж
		Route::match(['get', 'post'], '/mounter-requests',
			'MapController@mounter_requests')
			->name('mounter-requests');
		Route::get('/mounters/create/{address}',
			'MounterController@create')
			->name('mounters.create');
		Route::post('/mounters/{address}',
			'MounterController@store')
			->name('mounters.store');
		Route::resource('/mounters',
			'MounterController')
			->only(['index', 'show', 'edit', 'update']);

// front // Файлы
		Route::post('/files/store-single',
			'FileController@storeSingle')
			->name('files.store-single');

		Route::resource('/files',
			'FileController')
			->only(['index', 'store', 'show', 'destroy']);

// front // Каталог
		Route::resource('/catalogs',
			'CatalogController')
			->only(['index', 'show']);
		Route::get('/catalogs/{catalog}/list',
			'CatalogController@list')
			->name('catalogs.list');

// front // Оборудование
		Route::resource('/equipments',
			'EquipmentController')
			->only(['index', 'show']);

// front // Техдокументация
		Route::resource('/datasheets',
			'DatasheetController')
			->only(['index', 'show']);

// front // Витрина товаров
		Route::get('/products/list',
			'ProductController@list')
			->name('products.list');
		Route::resource('/products',
			'ProductController')
			->only(['index', 'show']);
		Route::get('/products/{product}/schemes/{scheme}',
			'ProductController@scheme')
			->name('products.scheme');

// front // Новости
		Route::resource('/announcements',
			'AnnouncementController')
			->only(['index']);
            
// front // Отзывы           
        Route::post('/review',
            'ReviewController@store');

// front // Обновление курсов валют
		Route::get('/currencies/refresh/',
			'CurrencyController@refresh')
			->name('currencies.refresh');


// front // Обновление итогов
		Route::get('/results/today/',
			'ResultsController@today')
			->name('results.today');

// front // Static pages
		Route::get('/masterplus',
			'StaticPageController@masterplus')
			->name('masterplus');

		Route::get('/managerplus',
			'StaticPageController@managerplus')
			->name('managerplus');
		Route::get('/ferroliplus',
			'StaticPageController@ferroliplus')
			->name('ferroliplus');
            
		Route::get('/masterplus-updates',
			'StaticPageController@masterplus_updates')
			->name('masterplus_updates');
            
		Route::get('/pd-fru',
			'StaticPageController@pdFru')
			->name('pd-fru');
			
		Route::get('/feedback',
			'StaticPageController@feedback')
			->name('feedback');
		Route::get('/feedback-success',
			'StaticPageController@feedbackSuccess')
			->name('feedback_success');
		Route::post('/feedback',
			'StaticPageController@message')
			->name('message');

     
		/* Мероприятия */
		Route::get('/events/webinars',
            'WebinarController@publicWebinars')
            ->name('events.webinars.index');
            
		Route::resource('/events',
			'EventController')
			->only(['show', 'index']);
            
        
        Route::get('/events/webinars/view/{webinar}',
            'WebinarController@viewPublicWebinar')
            ->name('events.webinars.view');
            
        Route::get('/events/webinars/{webinar}',
            'WebinarController@showPublicWebinar')
            ->name('events.webinars.show');
       
       Route::get('/events/webinar-public-join-form/{webinar}',
            'WebinarController@joinPublicWebinarForm')
            ->name('events.webinars.public_join_form');  
       
       Route::post('/events/webinar-public-join/{webinar}',
            'WebinarController@joinPublicWebinar')
            ->name('events.webinars.public_join');
       
       Route::get('/events/webinar-public-enter/{webinar}/{participant}',
            'WebinarController@enterPublicWebinar')
            ->name('events.webinars.public_enter');


            
        /* Типы мероприятий */
		Route::resource('/event-types',
			'EventTypeController')
			->only(['show'])
			->names([
				'show' => 'event_types.show',
			]);
		
		Route::resource('/academy',
			'AcademyEventsController')
			->names([
				'index' => 'academy',
			]);

		/* Заявки */
		Route::get('/members/confirm/{token}',
			'MemberController@confirm')
			->name('members.confirm');
		Route::resource('/members',
			'MemberController')
			->only(['index', 'store']);
		Route::get('/members/register/{event}',
			'MemberController@register')
			->name('members.register');
		Route::get('/members/create',
			'MemberController@create')
			->name('members.create');

		/* Участники */
		Route::resource('/participants',
			'ParticipantController')
			->only(['create']);



           // Корзина

            Route::post('/cart/create-order',
            'CartController@createOrder')
            ->name('cart_create_order');

            Route::get('/cart',
                'CartController@index')
                ->name('cart');
            Route::get('/cartsuess/{ordernum}',
                'CartController@suess')
                ->name('cartsuess');
            Route::post('/cart/{product}/add',
                'CartController@add')
                ->name('buy');
            Route::delete('/cart/remove',
                'CartController@remove')
                ->name('removeCartItem');
            Route::put('/cart/update',
                'CartController@update')
                ->name('updateCart');
            Route::get('/cart/clear',
                'CartController@clear')
                ->name('clearCart');

        Route::get('/order-single-show/{siteGuid}',
            'CartController@showOrder')
            ->name('orders.single_show');

        Route::get('/user-market-change/{address_id}/{region_id}',
			'CartController@userMarketChange')
			->name('user_market_change');
		
        Route::match(['get', 'post'], '/user-region-change',
			'CartController@userRegionChange')
			->name('user-region-change');
				
		Route::match(['get', 'post'], '/order-retail',
			'CartController@orderRetail')
			->name('order-retail');


			
		/* Отписаться от рассылки */
		Route::get('/unsubscribe/{email}',
			'UnsubscribeController@showUnsubscribeForm')
			->name('unsubscribe')
			->middleware('signed');
		Route::post('/unsubscribe/{email}',
			'UnsubscribeController@unsubscribe')
			->name('unsubscribe')
			->middleware('signed');
            
        Route::get('/testservice/{ingeneer_id}/{type_id}/{send}',
			'TestsController@testService')
			->name('testservice');
            
        Route::get('/force/{user}',
					'HomeController@forceTemp')->name('force_temp');

		Route::group(['middleware' => ['auth']],
			function () {
// auth                // Личный кабинет
				Route::get('/home',
					'HomeController@index')
					->name('home');
				Route::get('/edit-profile',
					'HomeController@editProfile')
					->name('edit_profile');
				Route::put('/update-profile',
					'HomeController@updateProfile')
					->name('update_profile');
				Route::get('/edit-profile-esb',
					'HomeController@editProfileEsb')
					->name('edit_profile_esb');
				Route::put('/update-profile',
					'HomeController@updateProfileEsb')
					->name('update_profile');
				Route::post('/home/logo',
					'HomeController@logo')
					->name('home.logo');
				Route::get('/users/{user}/force',
					'HomeController@force');
				Route::get('/password/create',
					'UserPasswordController@create')
					->name('password.create');
				Route::post('/password/store',
					'UserPasswordController@store')
					->name('password.store');
                
                Route::post('/home/message',
					'HomeController@message')
					->middleware('permission:messages')
					->name('home.message');


// auth     Электронная гарантия и сервисная CRM
        
                Route::get('/user-service-change/{service}','HomeController@userServiceChange')->name('user_service_change');
                Route::get('/user-service-delete/{service}','HomeController@userServiceDelete')->name('user_service_delete');

                Route::put('/esb-requests/{esbUserRequest}/status','EsbRequestController@status')->name('esb_requests.status');
                Route::resource('/esb-requests','EsbRequestController');    
               
                Route::put('/esb-visits/{esbUserVisit}/status','EsbUserVisitController@status')->name('esb_user_visits.status');
                Route::resource('/esb-visits','EsbUserVisitController');
                
                Route::get('/esb-user-products/{esbUserProduct}/manage','EsbUserProductController@manage')->name('esb-user-products.manage');

                Route::resource('/esb-user-products','EsbUserProductController');
                Route::get('/esb-user-products-archive','EsbUserProductController@indexArchive')->name('esb-user-products-archive');
                
                Route::resource('/esb-product-launches','EsbProductLaunchController');
                Route::resource('/esb-product-maintenances','EsbProductMaintenanceController');
                Route::resource('/esb-users','EsbUserController');
                Route::get('/esb-contracts/{esbContract}/download', function (EsbContract $esbContract) {
                    (new EsbContractWordProcessor($esbContract))->render();
                })->name('esb-contracts.download');

                Route::resource('/esb-contract-types','EsbContractTypeController');
                Route::resource('/esb-contract-templates','EsbContractTemplateController');
                Route::resource('/esb-contracts','EsbContractController');
                Route::resource('/esb-repairs','EsbRepairController');
                Route::resource('/esb-user-contracts','EsbUserContractController');
                Route::resource('/esb-catalog-services','EsbCatalogServiceController');

                Route::put('/esb-catalog-prices-upd-addrr/{address}',
                    'EsbCatalogServiceController@updateAddress')
                    ->name('esb-catalog-services.update_address');

                Route::put('/esb-catalog-prices-sort',
                    'EsbCatalogPriceController@sort')
                    ->name('esb-catalog-prices.sort');
                Route::resource('/esb-catalog-prices','EsbCatalogPriceController');

                
// auth                   
                Route::put('/retail-orders-esb/{retailOrder}/status','RetailOrderEsbController@status')->name('retail_orders_esb.status');
                Route::get('/retail-orders-esb','RetailOrderEsbController@index')->name('retail-orders-esb.index');
                			
              
                
				Route::get('/home/academy',
					'HomeController@academy')
					->name('home_academy');
				Route::get('/home/academy/video/{video}',
					'HomeController@video')
					->name('home_academy_video');
				Route::get('/home/academy/presentation/{presentation}',
					'HomeController@presentation')
					->name('home_academy_presentation');
				Route::get('/home/academy/presentation/{presentation}/slide/{slide_num}',
					'HomeController@slide')
					->name('home_academy_slide');
                    

//auth                  //Вебинары
                Route::resource('/webinars',
                    'WebinarController')
                    ->only(['index', 'show']);
                Route::post('/webinars/register/{webinar}',
                    'WebinarController@register')
                    ->name('webinars.register');
                Route::post('/webinars/unregister/{webinar}',
                    'WebinarController@unregister')
                    ->name('webinars.unregister');
                Route::get('/webinars/enter/{webinar}',
                    'WebinarController@enter')
                    ->name('webinars.enter');
                Route::get('/webinars/view/{webinar}',
                    'WebinarController@view')
                    ->name('webinars.view');
                    
//auth                  //Обучение
               Route::group(['prefix' => 'academy-ferroli'], function () {
                    Route::name('academy_ferroli.')->group(function (){
                        Route::get('/programs/{program}/stage/{stage}',
                            'AcademyProgramController@stage')
                            ->name('programs.stage');
                        
                        Route::get('/programs/{program}/stage/{stage}/presentation/{presentation}',
                            'AcademyProgramController@presentation')
                            ->name('programs.presentation');
                        
                        Route::get('/programs/{program}/stage/{stage}/presentation/{presentation}/slide/{slide_num}',
                            'AcademyProgramController@slide')
                            ->name('programs.slide');
                            
                        Route::get('/programs/{program}/stage/{stage}/test/{test}',
                            'AcademyProgramController@test')
                            ->name('programs.test');  
                            
                        Route::post('/programs/{program}/stage/{stage}/sendtest/{test}',
                            'AcademyProgramController@sendtest')
                            ->name('programs.sendtest');
                        
                        Route::resource('/programs',
                            'AcademyProgramController')->except(['edit']);
                    });
               });
				/*
				|--------------------------------------------------------------------------
				|                   БОНУСЫ ДИГИФТ
				|--------------------------------------------------------------------------
				*/
// auth                // Получить бонусы (вознаграждение) Дигифт
				Route::post('/digift/users/{digiftUser}/fullUrlToRedirect',
					'DigiftUserController@fullUrlToRedirect')
					->name('digift.users.fullUrlToRedirect');


// auth                //-------------------------------------------------------------------------
// auth                // Авторизации
				Route::resource('/authorizations',
					'AuthorizationController')
					->middleware('permission:authorizations')
					->only(['index', 'store', 'show']);
				Route::post('/authorizations/{authorization}/message',
					'AuthorizationController@message')
					->middleware('permission:messages')
					->name('authorizations.message');
				Route::get('/authorizations/create/{role}',
					'AuthorizationController@create')
					->name('authorizations.create')
					->middleware('permission:authorizations');

// auth                // Адреса
				Route::resource('/addresses',
					'AddressController')
					->middleware('permission:addresses')
					->except(['create']);
				Route::get('/addresses/create/{address_type}',
					'AddressController@create')
					->middleware('permission:addresses')
					->name('addresses.create');

// auth                // Телефоны адреса
				Route::resource('/addresses/{address}/phones',
					'AddressPhoneController')
					->middleware('permission:addresses')
					->except(['index'])
					->names([
						'create' => 'addresses.phones.create',
						'store' => 'addresses.phones.store',
						'edit' => 'addresses.phones.edit',
						'update' => 'addresses.phones.update',
						'destroy' => 'addresses.phones.destroy',
					]);
					
				Route::resource('/addresses/{address}/images',
								'AddressImageController')
								->only(['index', 'store', 'update', 'destroy','show'])
								->names([
									'index' => 'addresses.images.index',
									'store' => 'addresses.images.store',
									'destroy' => 'addresses.images.destroy',
									'show' => 'addresses.images.show',
								]);

							
				
				Route::put('/addresses/images/sort', function (Request $request) {
								Image::sort($request);
							})->name('addresses.images.sort');

				
				
							
							
// auth                // Инженеры
				Route::resource('/engineers',
					'EngineerController')
					->middleware('permission:engineers')
					->except(['show'])->names([
						'index' => 'engineers.index',
					]);							
							
// auth                // Связи
				Route::resource('/user-relations',
					'UserRelationController')
                   // ->middleware('permission:user-relations')
                    ->only(['index', 'update', 'destroy','create'])
					->names([
						'index' => 'user_relations.index',
						'create' => 'user_relations.create',
					]);
// auth                // Сертификаты
                
                Route::get('/cert-sc/{type}',
					'CertificateController@sc')
					->name('certificate_sc');
    
				Route::resource('/certificates',
					'CertificateController')
					->middleware('permission:engineers')
					->only(['store', 'show']);
				
                    
				Route::resource('/certificates_service',
					'CertificateServiceController')
					->middleware('permission:engineers')
					->only(['store']);   
                    
				Route::resource('/certificates_mounter',
					'CertificateMounterController')
					->middleware('permission:engineers')
					->only(['store']);

// auth                // Отчеты о продаже
				Route::resource('/retail-sale-reports',
					'RetailSaleReportController')
					->middleware('permission:retail-sale-reports')
					->only(['index', 'create', 'store', 'show']);
                    
				Route::post('/retail-sale-reports/{retail_sale_report}/message',
					'RetailSaleReportController@message')
					->middleware('permission:messages')
					->name('retail-sale-reports.message');

// auth                // Отчеты по монтажу
				Route::resource('/mountings',
					'MountingController')
					->middleware('permission:mountings')
					->only(['index', 'create', 'store', 'show']);
				Route::post('/mountings/{mounting}/message',
					'MountingController@message')
					->middleware('permission:messages')
					->name('mountings.message');
				Route::get('/mountings/{mounting}/pdf', function (Mounting $mounting) {
					return (new MountingPdf())->setModel($mounting)->render();
				})->middleware('can:pdf,mounting')
					->name('mountings.pdf');

// auth                // Заказы на витрину
				Route::resource('/stand-orders',
					'StandOrderController')
					->middleware('permission:stand-orders');
				Route::post('/stand-orders/{standOrder}/message',
					'StandOrderController@message')
					->middleware('permission:messages')
					->name('stand-orders.message');

// auth                // Заказы на витрину дистрибьютор

                Route::group(['middleware' => ['permission:stand-distr']],
					function () {
						Route::get('/stand-distr',
							'StandDistrController@index')
							->name('stand-distr.index');
						Route::get('/stand-distr/{standOrder}',
							'StandDistrController@show')
							->name('stand-distr.show');
						Route::patch('/stand-distr/{standOrder}',
							'StandDistrController@update')
							->name('stand-distr.update');
						Route::post('/stand-distr/{standOrder}/message',
							'StandDistrController@message')
							->name('stand-distr.message');
						Route::post('/stand-distr/{standOrder}/payment',
							'StandDistrController@payment')
							->name('stand-distr.payment');
					});
				

// auth                // Торговые организации
				Route::resource('/trades',
					'TradeController')
					->middleware('permission:trades')
					->except(['show']);

// auth                // Сообщения
				Route::resource('/messages',
					'MessageController')
					->middleware('permission:messages')
					->only(['index', 'show']);
                 
// auth // Ревизии оборудования (письма о изменении запчастей)
                 Route::resource('/revision-parts',
                        'RevisionPartController')
                            ->only(['index', 'show'])
                            ->names(['index'=>'revision_parts.index', 
                                     'show'=>'revision_parts.show', 
                                     ]); 
                    

// auth                // Отчеты по ремонту
				Route::resource('/repairs',
					'RepairController')
					->middleware('permission:repairs');
				Route::post('/repairs/{repair}/message',
					'RepairController@message')
					->middleware('permission:messages')
					->name('repairs.message');
				Route::get('/repairs/{repair}/pdf', function (Repair $repair) {
					return (new RepairPdf())->setModel($repair)->render();
				})->middleware('can:pdf,repair')->name('repairs.pdf');

// auth                // Контрагенты
				Route::resource('/contragents',
					'ContragentController')
					->middleware('permission:contragents');
				Route::resource('/contragents/{contragent}/addresses',
					'ContragentAddressController')
					->middleware('permission:addresses')
					->only(['edit', 'update'])
					->names([
						'edit' => 'contragents.addresses.edit',
						'update' => 'contragents.addresses.update',
					]);

// auth                // Контакты
				Route::resource('/contacts',
					'ContactController')
					->middleware('permission:contacts');

// auth                // Телефоны контакта
				Route::resource('/contacts/{contact}/phones',
					'ContactPhoneController')
					->middleware('permission:phones')
					->except(['index'])
					->names([
						'create' => 'contacts.phones.create',
						'store' => 'contacts.phones.store',
						'edit' => 'contacts.phones.edit',
						'update' => 'contacts.phones.update',
						'destroy' => 'contacts.phones.destroy',
					]);

// auth                // Входящие заказы
				Route::group(['middleware' => ['permission:distributors']],
					function () {
						Route::get('/distributors',
							'DistributorController@index')
							->name('distributors.index');
						Route::get('/distributors/{order}',
							'DistributorController@show')
							->name('distributors.show');
						Route::patch('/distributors/{order}',
							'DistributorController@update')
							->name('distributors.update');
						Route::post('/distributors/{order}/message',
							'DistributorController@message')
							->name('distributors.message');
						Route::get('/distributors/{order}/excel', function (Order $order) {
							(new OrderExcel())->setModel($order)->render();
						})->name('distributors.excel')->middleware('can:distributor,order');
						Route::post('/distributors/{order}/payment',
							'DistributorController@payment')
							->name('distributors.payment');
					});


// auth                // Заказы
				Route::post('/orders/load',
					'OrderController@load')
					->middleware('permission:orders')
					->name('orders.load');
				Route::resource('/orders',
					'OrderController')
					->except(['edit'])
					->middleware('permission:orders');
				Route::post('/orders/{order}/message',
					'OrderController@message')
					->middleware('permission:messages')
					->name('orders.message');


// auth                // Акты
				Route::resource('/acts',
					'ActController')
					->middleware('permission:acts')
					->except(['destroy']);
				Route::get('/acts/{act}/pdf', function (Act $act) {
					return (new ActPdf())->setModel($act)->render();
				})
					->middleware('can:pdf,act')
					->name('acts.pdf');

if(in_array(env('MIRROR_CONFIG'),['sfru','sfby'])) {
// auth                // Корзина
				Route::get('/cart',
					'CartController@index')
					->name('cart');
				Route::post('/cart/{product}/add',
					'CartController@add')
					->name('buy');
				Route::delete('/cart/remove',
					'CartController@remove')
					->name('removeCartItem');
				Route::put('/cart/update',
					'CartController@update')
					->name('updateCart');
				Route::get('/cart/clear',
					'CartController@clear')
					->name('clearCart');

				Route::resource('/order-items',
					'OrderItemController')
					->only(['destroy']);
}
// auth                // Контракты
				Route::group(['middleware' => ['permission:contracts']],
					function () {
						Route::resource('/contracts',
							'ContractController')
							->except(['create']);

						Route::get('/contracts/create/{contract_type}',
							'ContractController@create')
							->name('contracts.create');

						Route::get('/contracts/{contract}/download', function (Contract $contract) {
							(new ContractWordProcessor($contract))->render();
						})->name('contracts.download')
							->middleware('can:view,contract');
					});

// auth                // Оптовые склады

				Route::get('/storehouses/excel',
					'StorehouseController@excel')
					->name('storehouses.excel');

				Route::group(['middleware' => ['permission:storehouses']],
					function () {

						Route::resource('/storehouses',
							'StorehouseController');

						Route::post('/storehouses/{storehouse}/excel',
							'StorehouseExcelController@store')
							->name('storehouses.excel.store');


						Route::post('/storehouses/{storehouse}/url',
							'StorehouseUrlController@store')
							->name('storehouses.url.store');
						Route::post('/storehouses/{storehouse}/load',
							'StorehouseUrlController@load')
							->name('storehouses.url.load');

		// auth                // Лог ошибок оптового склада
						Route::resource('/storehouses/{storehouse}/logs',
							'StorehouseLogController')
							->only(['index'])
							->names([
								'index' => 'storehouses.logs.index',
							]);
					});

//auth                  //Продажи дистрибьторов			

				Route::group(['middleware' => ['permission:storehouses']],
					function () {

						Route::resource('/distributor-sales',
							'DistributorSaleController');

						Route::post('/distributor-sales/{user}/excel',
							'DistributorSaleExcelController@store')
							->name('distributor_sales.excel.store');


						Route::post('/distributor-sales/{user}/url-update',
							'DistributorSaleController@urlUpdate')
							->name('distributor_sales.url_update');

						Route::post('/distributor-sales/{user}/url',
							'DistributorSaleUrlController@store')
							->name('distributor_sales.url.store');
						Route::post('/distributor-sales/{user}/load',
							'DistributorSaleUrlController@load')
							->name('distributor_sales.url.load');
                

                });

/*          
***       Админка  Академии
*/
                
                
                Route::group(['middleware' => ['ferroliUser'], 'prefix' => 'academy-admin', 'namespace' => 'Admin'], function () {
                    Route::get('/', 'AcademyController@index')->name('academy-admin');
                    Route::name('academy-admin.')->group(function (){

                        Route::get('/themes', 'AcademyThemeController@index')->name('themes.index');
                        Route::get('/themes/create', 'AcademyThemeController@create')->name('themes.create');
                        Route::get('/themes/{academyTheme}/edit', 'AcademyThemeController@edit')->name('themes.edit');
                        Route::post('/themes/store', 'AcademyThemeController@store')->name('themes.store');
                        Route::put('/themes/{academyTheme}/update', 'AcademyThemeController@update')->name('themes.update');
                      
                        Route::resource('/tests', 'AcademyTestController');
                        Route::resource('/questions', 'AcademyQuestionController');
                        Route::get('/answers/create/{question}', 'AcademyAnswerController@create')->name('answers.create');
                        Route::resource('/answers', 'AcademyAnswerController');
                        Route::resource('/videos', 'AcademyVideoController');
                        Route::get('/presentations/{presentation}/preview/{slide_num}', 'AcademyPresentationController@preview')->name('presentations.preview');
                        Route::resource('/presentations', 'AcademyPresentationController');
                        
                        Route::get('/presentation-slides/create/', 'AcademyPresentationSlideController@create')->name('presentation_slides.create');
                        Route::get('/presentation-slides/{slide}', 'AcademyPresentationSlideController@show')->name('presentation_slides.show');
                        Route::get('/presentation-slides/{slide}/card', 'AcademyPresentationSlideController@card')->name('presentation_slides.card');
                        Route::put('/presentation-slides/sort-slides', function (Request $request) {
								AcademyPresentationSlide::sort($request);
							})->name('presentation_slides.sort');
                        Route::resource('/stages', 'AcademyStageController');
                        Route::put('/programs/sort-stages', function (Request $request) {
								AcademyProgramStage::sort($request);
							})->name('programs.sort-stages');
                        
                        Route::resource('/programs', 'AcademyProgramController');
                        
                        //Route::resource('/themes', 'AcademyThemeController');
                    });
                    
                  });
 
/*          
***       Админка  менеджеров Ферроли
*/
 
                Route::group(['middleware' => ['ferroliUser'], 'prefix' => 'ferroli-user', 'namespace' => 'Admin'], function () {
                    Route::get('/','FerroliUserController@index')->name('ferroli-user.home');
                    Route::name('ferroli-user.')->group(function (){
                        Route::resource('/promocodes','PromocodeController')->except(['show']);
                        Route::resource('/webinar-themes','WebinarThemeController');
                        
                        Route::resource('/webinars','WebinarController');
                        
//FerroliUser        // Мероприятия
                        Route::resource('/events',
                            'EventController');
                        Route::get('/events/{event}/mailing',
                            'EventController@mailing')
                            ->name('events.mailing');
                        Route::get('/events/{event}/attachment',
                            'EventController@attachment')
                            ->name('events.attachment');
                        Route::get('/events/create/{member?}',
                            'EventController@create')
                            ->name('events.create');
                        Route::post('/events/store/{member?}',
                            'EventController@store')
                            ->name('events.store');
                        
                        Route::get('/events/{event}/participants',
                            'EventController@editParticipants')
                            ->name('events.edit_participats'); 
                        Route::get('/events/{event}/participants/{participant}/edit',
                            'EventController@editParticipant')
                            ->name('events.edit_participant'); 
                        Route::post('/events/{event}/participants',
                            'EventController@storeParticipants')
                            ->name('events.store_participants');
                        Route::post('/events/{event}/participants-xls',
                            'EventController@storeParticipantsXls')
                            ->name('events.participants_xls');
                        Route::post('/events/{event}/participants/{participant}/update',
                            'EventController@updateParticipant')
                            ->name('events.update_participant');
                        
                        
                        
//FerroliUser        // Участники мероприятия
                        Route::resource('/members',
                            'MemberController');               
                      

//FerroliUser        // Физики
                        Route::get('/participants/create/{member}',
                            'ParticipantController@create')
                            ->name('participants.create');
                        Route::post('/participants/store/{member}',
                            'ParticipantController@store')
                            ->name('participants.store');
                        Route::resource('/participants',
                            'ParticipantController')
                            ->only(['destroy']);                            

                                
//FerroliUser        // Предрегистрация                             

                        Route::get('/userprereg/mounters',
                            'UserPreregController@mounters')
                            ->name('user_prereg.mounters');
                        Route::get('/userprereg/mounters/create',
                            'UserPreregController@createMounter')
                            ->name('user_prereg.create_mounters');
                        Route::get('/userprereg/mounters/edit/{prereg}',
                            'UserPreregController@edit')
                            ->name('user_prereg.edit');
                        Route::put('/userprereg/mounters/update/{prereg}',
                            'UserPreregController@update')
                            ->name('user_prereg.update');
                        Route::post('/userprereg/store',
                            'UserPreregController@store')
                            ->name('user_prereg.store');
                        
                        Route::post('/userprereg/invite-mounters',
                            'UserPreregController@inviteMounters')
                            ->name('user_prereg.invite_mounters');
                        Route::post('/userprereg/userpreregs-mounters-xls',
                            'UserPreregController@storeUserpreregsMountersXls')
                            ->name('user_prereg.userpreregs_mounters_xls');
                        
                        Route::resource('/userprereg',
                            'UserPreregController')
                            ->names(['destroy'=>'user_prereg.destroy']);  
   
                            
//FerroliUser        // Изображения
                        Route::put('/images/sort', function (Request $request) {
                                    Image::sort($request);
                                })->name('images.sort');

                                Route::resource('/images',
                                    'ImageController')
                                    ->only(['index', 'store', 'show', 'destroy']);

                                Route::resource('/files',
                                    'FileController')
                                    ->only(['index', 'store', 'show', 'destroy']);
                    });
                });
               
                
/*
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
|--------------------------------------------------------------------------
|                               АДМИНКА
|--------------------------------------------------------------------------
*/


				Route::middleware('ferroliUser')
					->namespace('Admin')
					->prefix('admin')
					->group(function () {
                        Route::name('admin.')->group(function (){
//admin FerroliUser				// Пользователи
							Route::resource('/users',
								'UserController');
							Route::get('/users/{user}/schedule',
								'UserController@schedule')
								->name('users.schedule');
							Route::get('/users/{user}/force',
								'UserController@force')
								->name('users.force');
							Route::post('/users/{user}/invite',
								'UserController@invite')
								->name('users.invite');
							Route::get('/users/{user}/messages',
								'UserController@messages')
								->name('users.messages');
                                


							Route::put('/users/{user}/role',
								'UserController@role')
								->name('users.role');

//admin FerroliUser         // Командировки

                            Route::get('/missions/{mission}/pdf', function (Mission $mission) {
                                return (new MissionPdf())->setModel($mission)->render();
                            })->name('missions.pdf');
                            
							Route::resource('/missions',
								'MissionController');
 
                            Route::post('/missions/{mission}/message',
                            'MissionController@message')
                            ->name('missions.message');
                            Route::put('/missions/{mission}/status',
                            'MissionController@status')
                            ->name('missions.status');
                            
//admin FerroliUser         // Рассылка пользователям почтовая
							Route::resource('/mailings',
								'MailingController')
								->only(['create', 'store']);
                                
//admin FerroliUser         // Рассылка пользователям в кабинеты
							Route::resource('/messagings',
								'MessagingsController')
								->only(['create', 'store']);

//admin FerroliUser         //Шаблоны для рассылок                        

                            Route::resource('/templates',
                                'TemplateController');                         


//admin FerroliUser		    // Сброс пароля пользователя
							Route::resource('/users/{user}/password',
								'UserPasswordController')
								->only(['create', 'store'])
								->names([
									'create' => 'users.password.create',
									'store' => 'users.password.store',
								]);
                                
//admin FerroliUser		    // Авторизации
                            Route::resource('/authorizations',
								'AuthorizationController')
								->except(['delete']);
							
                            Route::post('/authorizations/{authorization}/message',
								'AuthorizationController@message')
								->name('authorizations.message');


//admin	FerroliUser			// Договора
							Route::resource('/contracts',
								'ContractController')
								->only(['index', 'show']);

							Route::get('/contracts/{contract}/download', function (Contract $contract) {
								(new ContractWordProcessor($contract))->render();
							})->name('contracts.download');

//admin	FerroliUser	        // Отчеты по ремонту
							Route::resource('/repairs',
								'RepairController')
								->only(['index', 'show', 'update']);
							Route::post('/repairs/{repair}/message',
								'RepairController@message')
								->name('repairs.message');
                                
//admin	FerroliUser			// Акты выполненных работ
							Route::resource('/acts',
								'ActController');
							Route::get('/acts/{act}/schedule',
								'ActController@schedule')
								->name('acts.schedule');
                                
//admin	 FerroliUser        // Отчеты о продаже
							Route::resource('/retail-sale-reports',
								'RetailSaleReportController')
								->only(['index', 'show', 'update'])
                                ;
                                
//admin	 FerroliUser        // Отчеты по монтажу
							Route::resource('/mountings',
								'MountingController')
								->only(['index', 'show', 'update']);

//admin	 FerroliUser        // Заказы
							Route::resource('/orders',
								'OrderController')
								->only(['index', 'show', 'update']);
							Route::post('/orders/{order}/message',
								'OrderController@message')
								->name('orders.message');
							Route::resource('/order-items',
								'OrderItemController')
							->only(['destroy', 'update']);
							Route::get('/orders/{order}/schedule',
								'OrderController@schedule')
								->name('orders.schedule');
							Route::post('/orders/{order}/payment',
								'OrderController@payment')
								->name('orders.payment');

//admin	 FerroliUser        // Тендеры
							Route::get('/tenders/{tender}/order', 'TenderController@order')->name('tenders.order-create');
							Route::patch('/tenders/{tender}/contragent', 'TenderController@contragentUpdate')->name('tenders.contragent_update');
                            Route::resource('/tenders',
								'TenderController');
                            Route::post('/tenders/{tender}/message',
                            'TenderController@message')
                            ->name('tender.message');
                            Route::put('/tenders/{tender}/status',
                            'TenderController@status')
                            ->name('tenders.status');
                            
                            
                            Route::get('/tenders/{tender}/pdf', function (Tender $tender) {
                                return (new TenderResultPdf())->setModel($tender)->render();
                            })->name('tenders.pdf');
                            
                                                    
//admin	 FerroliUser        // Ревизии оборудования (письма о изменении запчастей)
                        Route::resource('/revision-parts',
                            'RevisionPartController')
                                ->names(['index'=>'revision_parts.index', 
                                         'store'=>'revision_parts.store', 
                                         'show'=>'revision_parts.show', 
                                         'create'=>'revision_parts.create',  
                                         'edit'=>'revision_parts.edit',  
                                         'update'=>'revision_parts.update',  
                                         'destroy'=>'revision_parts.destroy',
                                         ]); 
                                         
                        Route::get('/revision-parts/{revisionPart}/pdf', function (RevisionPart $revisionPart) {
                                return (new RevisionPartPdf())->setModel($revisionPart)->render();
                            })->name('revision_parts.pdf');
                        
                        Route::post('/revision-parts/{revisionPart}/notice',
                            'RevisionPartController@notice')->name('revision_parts.notice');      
                            
                            
//admin	 FerroliUser        // Тикеты
                        Route::resource('/tickets',
                            'TicketController');  
                            Route::post('/tickets/{ticket}/message',
                            'TicketController@message')
                            ->name('ticket.message');
                            Route::put('/tickets/{ticket}/status',
                            'TicketController@status')
                            ->name('tickets.status');

//admin				// Темы тикетов
							
							Route::resource('/ticket-themes',
								'TicketThemeController')
                                ;
//admin				// Заказы на витрину
                            Route::get('/stand-orders/{standOrder}/order', 'StandOrderController@order')->name('stand-orders.order-create');
                            Route::get('/stand-orders/user', 'StandOrderController@user')->name('stand-orders.user');
                            Route::get('/stand-orders/create/{user}', 'StandOrderController@create')->name('stand-orders.create');
							
                            Route::resource('/stand-orders',
								'StandOrderController')
								->only(['index', 'show', 'update','store']);
							Route::post('/stand-orders/{order}/message',
								'StandOrderController@message')
								->name('stand-orders.message');
							Route::resource('/stand-order-items',
								'StandOrderItemController')
							->only(['destroy', 'update']);
							Route::post('/stand-orders/{standOrder}/payment',
								'StandOrderController@payment')
								->name('stand-orders.payment');
							Route::get('/stand-orders/{standOrder}/print',
								'StandOrderController@print')
								->name('stand-orders.print');
                                

//admin	 FerroliUser        // Адреса
							Route::resource('/addresses',
								'AddressController')
								->except(['create', 'store']);
                                
//admin	 FerroliUser        // Адреса контрагентов
							Route::resource('/contragents/{contragent}/addresses',
								'ContragentAddressController')
								->only(['edit', 'update'])
								->names([
									'edit' => 'contragents.addresses.edit',
									'update' => 'contragents.addresses.update',
								]);


//admin	 FerroliUser        // Телефоны адреса
							Route::resource('/addresses/{address}/phones',
								'AddressPhoneController')
								->only(['create', 'store', 'edit', 'update', 'destroy'])
								->names([
									'create' => 'addresses.phones.create',
									'store' => 'addresses.phones.store',
									'edit' => 'addresses.phones.edit',
									'update' => 'addresses.phones.update',
									'destroy' => 'addresses.phones.destroy',
								]);
								
//admin	 FerroliUser        // Изображения адреса								
							Route::resource('/addresses/{address}/images',
								'AddressImageController')
								->only(['index', 'store', 'update', 'destroy','show'])
								->names([
									'index' => 'addresses.images.index',
									'store' => 'addresses.images.store',
									'destroy' => 'addresses.images.destroy',
									'show' => 'addresses.images.show',
								]);

								
//admin	 FerroliUser       // Зоны дистрибуции адреса
							Route::resource('/addresses/{address}/regions',
								'AddressRegionController')
								->only(['index', 'store'])
								->names([
									'index' => 'addresses.regions.index',
									'store' => 'addresses.regions.store',
								]);
//admin	FerroliUser			// Темы вебинаров
							
							Route::resource('/webinar-themes',
								'WebinarThemeController')
                                ;
	
//admin	FerroliUser			// Вебинары
							Route::get('/webinars/{webinar}/create-zoom-webinar','WebinarController@createZoomWebinar')->name('webinars.create_zoom_webinar');
							Route::get('/webinars/{webinar}/get-zoom-stat','WebinarController@getWebinarStatistic')->name('webinars.zoom_webinar_stat');
							Route::get('/webinars/{webinar}/add-user-registrant-zoom-webinar/{user}','WebinarController@addWebinarUserRegistrant');
                            
							Route::resource('/webinars','WebinarController');
                            
                            
//admin FerroliUser               //Заявки на монтаж                                
                            Route::resource('/mounters',
								'MounterController')
								->except(['delete']);

//admin FerroliUser  //Заказы на маркете                                
                            Route::resource('/retail-orders',
								'RetailOrderController')
								->except(['delete']);

//admin FerroliUser  // Отчеты дистрибьюторов о продажах
						Route::resource('/distributor-sales',
							'DistributorSaleController');


//admin FerroliUser  // Инженеры
							Route::resource('/engineers',
								'EngineerController');
//admin FerroliUser  // Клиенты
							Route::get('/esb-clients/{esbClient}/esb-products',
								'EsbClientController@esbProducts')->name('esb-clients.esb-products.index');

							Route::get('/esb-clients/{esbClient}/esb-products/create',
								'EsbClientController@createProduct')->name('esb-clients.esb-products.create');

							Route::get('/esb-clients/{esbClient}/esb-contracts/create',
								'EsbClientController@createContract')->name('esb-clients.esb-contracts.create');

							Route::resource('/esb-clients',
								'EsbClientController');

//admin FerroliUser  // Торговые организации
							Route::resource('/trades',
								'TradeController')
								->only(['index', 'edit', 'update']);

//admin FerroliUser  // Контрагенты
							Route::resource('/contragents',
								'ContragentController')
								->except(['create', 'store', 'destroy']);


							Route::post('/users/{user}/message',
								'UserController@message')
								->name('users.message');

//admin FerroliUser  // Контрагенты пользователя
							Route::get('/users/{user}/contragents', 'UserContragentController@index')->name('users.contragents.index');
							Route::get('/users/{user}/contragents/create', 'UserContragentController@create')->name('users.contragents.create');
							Route::post('/users/{user}/contragents/store', 'UserContragentController@store')->name('users.contragents.store');

//admin FerroliUser  // Цены пользователя
							Route::resource('/users/{user}/prices',
								'UserPriceController')
								->only(['index', 'store'])
								->names([
									'index' => 'users.prices.index',
									'store' => 'users.prices.store',
								]);
//admin FerroliUser  // Сообщения
                        Route::resource('/messages',
                            'MessageController')
                            ->only(['index', 'show']);
                            
//admin FerroliUser  // Склады дистрибьютора
							Route::resource('/storehouses',
								'StorehouseController')
								->only(['index', 'show', 'create', 'store']);
                            
//admin FerroliUser  // Клиенты (не пользователи, подрядчики, инвесторы и т.п.)
		                    Route::resource('/customers',
								'CustomerController');
                            
                            Route::get('/customer_contact/{contact}', 'CustomerContactController@show')->name('customer_contact.show');
                            
                            
                            Route::resource('/customers/{customer}/contacts',
								'CustomerContactController')
                                    ->names([
                                                'create' => 'customer_contact.create',
                                                'edit' => 'customer_contact.edit',
                                                'update' => 'customer_contact.update',
                                                'store' => 'customer_contact.store',
                                                'destroy' => 'customer_contact.destroy'
                                                ]);
		             
 
								
//admin FerroliUser  //Контакты
							Route::resource('/contacts',
								'ContactController');

//admin FerroliUser  // Телефоны
							Route::resource('/phones',
								'PhoneController')
								->except(['show']);
							                               
                   

//admin FerroliUser  // Узлы схемы
							Route::resource('/blocks',
								'BlockController');

//admin FerroliUser  // Документация
							Route::resource('/datasheets',
								'DatasheetController');

//admin FerroliUser  // Оборудование, к которому подходит документация
							Route::resource('/datasheets/{datasheet}/products',
								'DatasheetProductController')
								->only(['index', 'store'])
								->names([
									'index' => 'datasheets.products.index',
									'store' => 'datasheets.products.store',
								]);
							Route::delete('/datasheets/{datasheet}/products/destroy',
								'DatasheetProductController@destroy')
								->name('datasheets.products.destroy');

//admin FerroliUser  // Аналоги
							Route::resource('/products/{product}/analogs',
								'ProductAnalogController')
								->only(['index', 'store'])
								->names([
									'index' => 'products.analogs.index',
									'store' => 'products.analogs.store',
								]);
							Route::delete('/products/{product}/analogs/destroy',
								'ProductAnalogController@destroy')
								->name('products.analogs.destroy');

//admin FerroliUser  // Детали
							Route::resource('/products/{product}/details',
								'ProductDetailController')
								->only(['index', 'store'])
								->names([
									'index' => 'products.details.index',
									'store' => 'products.details.store',
								]);
							Route::delete('/products/{product}/details/destroy',
								'ProductDetailController@destroy')
								->name('products.details.destroy');

//admin FerroliUser  // Подходит к
							Route::resource('/products/{product}/relations',
								'ProductRelationController')
								->only(['index', 'store'])
								->names([
									'index' => 'products.relations.index',
									'store' => 'products.relations.store',
								]);
							Route::delete('/products/{product}/relations/destroy',
								'ProductRelationController@destroy')
								->name('products.relations.destroy');

							Route::put('/product-images/{product}/sort',
								'ProductImageController@sort')
								->name('products.images.sort');

//admin FerroliUser  // Каталог
							Route::put('/catalogs/sort', function (Request $request) {
								Catalog::sort($request);
							})->name('catalogs.sort');
							Route::resource('/catalogs',
								'CatalogController');
							Route::get('/catalogs/create/{catalog?}',
								'CatalogController@create')
								->name('catalogs.create.parent');
							Route::get('/tree',
								'CatalogController@tree')
								->name('catalogs.tree');

//admin FerroliUser  // Товары
							Route::resource('/products',
								'ProductController');

//admin FerroliUser  // Спецификация товара
							Route::resource('/productspecs',
								'ProductSpecController');
                                
//admin FerroliUser  // Спецификация товара
							Route::resource('/products/{product}/specs',
								'ProductSpecRelationController')
								->only(['index', 'store'])
								->names([
									'index' => 'products.specs.index',
									'store' => 'products.specs.store',
								]);
                                
//admin FerroliUser  // Изображения товара
							Route::resource('/products/{product}/images',
								'ProductImageController')
								->only(['index', 'store'])
								->names([
									'index' => 'products.images.index',
									'store' => 'products.images.store',
								]);

//admin FerroliUser  // Оборудование

							Route::put('/equipments/sort', function (Request $request) {
								Equipment::sort($request);
							})->name('equipments.sort');
							
							Route::put('/equipments/sort-menu', function (Request $request) {
								Equipment::sortm($request);
							})->name('equipments.sort-menu');
                            
							Route::resource('/equipments',
								'EquipmentController');
							Route::get('/equipments/create/{catalog?}',
								'EquipmentController@create')
								->name('equipments.create.parent');
								
							Route::get('/equipments-market-sort',
								'EquipmentController@equipmentsMarketSort')
								->name('equipments.market-sort');

//admin FerroliUser  // Изображения оборудования
							Route::resource('/equipments/{equipment}/images',
								'EquipmentImageController')
								->only(['index', 'store'])
								->names([
									'index' => 'equipments.images.index',
									'store' => 'equipments.images.store',
								]);

//admin FerroliUser  // Изображения
							Route::put('/images/sort', function (Request $request) {
								Image::sort($request);
							})->name('images.sort');

							Route::resource('/images',
								'ImageController')
								->only(['index', 'store', 'show', 'destroy']);

							Route::resource('/files',
								'FileController')
								->only(['index', 'store', 'show', 'destroy']);

//admin FerroliUser  // Серийные номера
							Route::resource('/serials',
								'SerialController')
								->only(['index', 'create', 'store']);

//admin FerroliUser  // Сертификаты
							Route::resource('/certificates',
								'CertificateController')
								->only(['index', 'destroy']);
							Route::get('/certificates/create/{certificate_type}',
								'CertificateController@create')
								->name('certificates.create');
							Route::post('/certificates/{certificate_type}',
								'CertificateController@store')
								->name('certificates.store');
                    
                                
                                
//admin FerroliUser  // Отчеты
							Route::resource('/reports/asc','ReportAscController');
                                
                            Route::get('/reports/asc-year/{year}','ReportController@ascYear');
                            

                            Route::get('/dashboards','DashboardController@index')->name('dashboards.index');	
                            
                            Route::get('/dashboards/asc-csc','DashboardController@ascCsc')->name('dashboards.asc_scs');	
                            Route::post('/dashboards/asc-csc','DashboardController@ascCsc')->name('dashboards.asc_csc_year');
                            
                            Route::get('/dashboards/tenders','DashboardController@tenders')->name('dashboards.tenders');	
                            Route::post('/dashboards/tenders','DashboardController@tenders')->name('dashboards.tenders_year');
                            
                            
							Route::put('/faq/sort', function (Request $request) {
								VideoBlock::sort($request);
							})->name('faq.sort');
                            route::resource('/faq','FaqController');
                        
                        });
                    });

 
                    
                    
                    
                    

                            
                            

				Route::middleware('admin')
					->namespace('Admin')
					->prefix('admin')
					->group(function () {

// admin               // Панель управления
						Route::get('/',
							'IndexController@index')
						->name('admin');

						Route::name('admin.')->group(function (){

// admin               // Авторизации
							Route::resource('/authorization-brands',
								'AuthorizationBrandController')
								->except(['delete']);
							Route::resource('/authorization-roles',
								'AuthorizationRoleController')
								->except(['delete', 'show', 'create']);
							Route::get('/authorization-roles/create/{role}',
								'AuthorizationRoleController@create')
								->name('authorization-roles.create');
							Route::resource('/authorization-types',
								'AuthorizationTypeController')
								->except(['delete']);


// admin                //Отзывы
                            Route::resource('/reviews',
								'ReviewController')
								->except(['delete']);
                                
//admin				// Роуты
							Route::get('routes',
								'RouteController@index')
								->name('routes.index');


							/*
							|--------------------------------------------------------------------------
							|                   БОНУСЫ ДИГИФТ
							|--------------------------------------------------------------------------
							*/
//admin				// Добавить бонус вручную к отчету по монтажу
							Route::post('/mountings/{mounting}/digift-bonuses',
								'MountingDigiftBonusController@store')
							->name('mountings.digift-bonuses.store');
                            
//admin				// Добавить бонус вручную к отчету о продаже
							Route::post('/retail-sale-reports/{retail_sale_report}/digift-bonuses',
								'RetailSaleDigiftBonusController@store')
							->name('retail-sale-reports.digift-bonuses.store');

//admin				// Отчет по бонусам
							Route::get('/digift/bonuses',
								'DigiftBonusController@index')
								->name('digift.bonuses.index');

//admin				// Отправить бонус вручную в Дигифт
							Route::patch('/digift/bonuses/{digiftBonus}/changeBalance',
								'DigiftBonusController@changeBalance')
								->name('digift.bonuses.changeBalance');

//admin				// Отменить бонус отчета
							Route::delete('/digift/bonuses/{digiftBonus}/rollbackBalanceChange',
								'DigiftBonusController@rollbackBalanceChange')
								->name('digift.bonuses.rollbackBalanceChange');

//admin				// Отменить все бонусы пользователя
							Route::delete('/digift/users/{digiftUser}/rollbackBalanceChange',
								'DigiftUserController@rollbackBalanceChange')
								->name('digift.users.rollbackBalanceChange');

//admin				// Обновить access токен пользователя вручную
							Route::patch('/digift/users/{digiftUser}/refreshToken',
								'DigiftUserController@refreshToken')
								->name('digift.users.refreshToken');
/*                                
2) Отправить бонусы в Дигифт (digift_bonuses)
GET http://service.ferroli.test/api/digift/changeBalance
>>> POST https://ferroli.digift.pro/ampApi/changeBalance

3) Сверить баланс (digift_users)
GET http://service.ferroli.test/api/digift/profile
>>> GET https://ferroli.digift.pro/auth/profile?accessToken=accessToken&platformToken=platformToken                                
 */                               
                                
//admin				//-------------------------------------------------------------------------


//admin				// Бонусы за монтаж
							Route::resource('/mounting-bonuses',
								'MountingBonusController')
								->except(['show']);

//admin				// Бонусы за продажу
							Route::resource('/retail-sale-bonuses',
								'ProductRetailSaleBonusController')
								->except(['show']);


                                

//admin				// Банки
							Route::resource('/banks',
								'BankController');

//admin				// Организации
							Route::resource('/organizations',
								'OrganizationController');

//admin				// Классы сложности
							Route::put('/difficulties/sort',
								'DifficultyController@sort')
								->name('difficulties.sort');
							Route::resource('/difficulties',
								'DifficultyController');

//admin				// Системные переменные                                
							Route::resource('/variables',
								'VariableController')
								->only(['index', 'edit', 'update']);

//admin				// Тарифы на транспорт
							Route::put('/distances/sort',
								'DistanceController@sort')
								->name('distances.sort');
							Route::resource('/distances',
								'DistanceController')->except(['show']);


//admin				// Группы стоимости ТО
							Route::put('/esb-maintenance-product-groups/sort',
								'EsbMaintenanceProductGroupController@sort')
								->name('esb-maintenance-product-groups.sort');
							Route::resource('/esb-maintenance-product-groups',
								'EsbMaintenanceProductGroupController');

//admin				// Тарифы на транспорт ТО
							Route::put('/esb-maintenance-distances/sort',
								'EsbMaintenanceDistanceController@sort')
								->name('esb-maintenance-distances.sort');
							Route::resource('/esb-maintenance-distances',
								'EsbMaintenanceDistanceController')->except(['show']);
//admin				// Валюта
							Route::resource('/currencies',
								'CurrencyController');
							Route::resource('/currency_archives',
								'CurrencyArchiveController')->only(['index']);

//admin				// Типы товаров
							Route::resource('/product_types',
								'ProductTypeController');

//admin				// Типы цен
							Route::resource('/price_types',
								'PriceTypeController')
								->except(['create', 'store', 'destroy']);

//admin				// Типы файлов
							Route::resource('/file_types',
								'FileTypeController');

//admin				// Группы файлов
							Route::resource('/file_groups',
								'FileGroupController');

//admin				// Склады
							Route::resource('/warehouses',
								'WarehouseController');

//admin				// Страницы
							Route::resource('/pages',
								'PageController');
//admin				// черный список
							Route::resource('/black-list',
								'BlackListController');
								
//admin				// Видео Youtube
							
							Route::put('/video_blocks/sort', function (Request $request) {
								VideoBlock::sort($request);
							})->name('video_blocks.sort');
							
							Route::resource('/video_blocks',
								'VideoBlockController');
								
//admin				// Карточки разделов на главной
							
							Route::put('/index_cards_blocks/sort', function (Request $request) {
								IndexCardsBlock::sort($request);
							})->name('index_cards_blocks.sort');
							
							Route::resource('/index_cards_blocks',
								'IndexCardsBlockController');
								
//admin				// Баннеры квадратные на главной
							
							Route::put('/index_quadro_blocks/sort', function (Request $request) {
								IndexQuadroBlock::sort($request);
							})->name('index_quadro_blocks.sort');
							
							Route::resource('/index_quadro_blocks',
								'IndexQuadroBlockController');
								
//admin				// Баннеры верхние широкие
							
							Route::put('/head_banner_blocks/sort', function (Request $request) {
								HeadBannerBlock::sort($request);
							})->name('head_banner_blocks.sort');
							
							Route::resource('/head_banner_blocks',
								'HeadBannerBlockController');								

//admin				// Промокоды
							
							Route::resource('/promocodes',
								'PromocodeController')
                                ->except(['show']);


							

//admin				// Регионы
							Route::resource('/regions',
								'RegionController');

//admin				// Регионы для Италии
							Route::resource('/region-italy-districts',
								'RegionItalyDistrictController');



//admin				// Типы мероприятий
							Route::put('/event_types/sort', function (Request $request) {
								EventType::sort($request);
							})->name('event_types.sort');
							Route::resource('/event_types',
								'EventTypeController');

//admin				// Запчасти

                                
                                
							Route::resource('/parts',
								'PartController')
								->only(['edit', 'update', 'destroy']);



							Route::resource('/elements',
								'ElementController');
							Route::resource('/pointers',
								'PointerController');
							Route::resource('/shapes',
								'ShapeController');



//admin				// Новости
							Route::post('/announcements/image',
								'AnnouncementController@image')
								->name('announcements.image');
							Route::resource('/announcements',
								'AnnouncementController');

							Route::post('/schemes/image',
								'SchemeController@image')
								->name('schemes.image');
							Route::get('/schemes/{scheme}/pointers',
								'SchemeController@pointers')
								->name('schemes.pointers');
							Route::get('/schemes/{scheme}/shapes',
								'SchemeController@shapes')
								->name('schemes.shapes');
							Route::get('/schemes/{scheme}/elements',
								'SchemeController@elements')
								->name('schemes.elements');
							Route::post('/schemes/{scheme}/elements',
								'SchemeController@elements')
								->name('schemes.elements.update');
							Route::delete('/schemes/{scheme}/elements',
								'SchemeController@elements')
								->name('schemes.elements.delete');
							Route::resource('/schemes',
								'SchemeController');

							Route::resource('/prices',
								'PriceController');

							Route::put('/blocks/sort', function (Request $request) {
								Block::sort($request);
							})->name('blocks.sort');


							Route::put('/elements/sort', function (Request $request) {
								Element::sort($request);
							})->name('elements.sort');

							Route::put('/file_types/sort', function (Request $request) {
								FileType::sort($request);
							})->name('file_types.sort');


//admin				// Типы договоров
							Route::resource('/contract-types',
								'ContractTypeController');

						});

					});
			});
	});

Route::group([
	'namespace' => 'Api',
	'prefix' => 'api',
],
	function () {




//--API // Товары для отчета по ремонту
		Route::name('api')->get('/parts',
			'PartController@index')
			->name('.parts.index');
		Route::name('api')->get('/parts/create/{product}',
			'PartController@create')
			->name('.parts.create');

//--API // Товары для тендера
		Route::name('api')->get('/tender-items/create/{product}',
			'PartController@createTender')
			->name('.tender_items.create');

		// Товары для заявки на витрину
		Route::name('api')->get('/stand-items',
			'StandItemController@index')
			->name('.stand-items.index');
		Route::name('api')->get('/stand-items/create/{product}',
			'StandItemController@create')
			->name('.stand-items.create');
		Route::name('api')->get('/stand-items/createAdmin/{product}',
			'StandItemController@createAdmin')
			->name('.stand-items.createAdmin');	
	        
		// Спецификации для товаров
		Route::name('api')->get('/product-specs',
			'ProductSpecController@index')
			->name('.product_specs.index');
		Route::name('api')->get('/product-specs/create/{spec}',
			'ProductSpecController@create')
			->name('.product_specs.create');            
        
		// Вопросы для тестов
		Route::name('api')->get('/program-stages',
			'AcademyStageController@index')
			->name('.program-stages.index');
		Route::name('api')->get('/program-stages/create/{stage}',
			'AcademyStageController@create')
			->name('.program-stages.create');        
            
		// Вопросы для тестов
		Route::name('api')->get('/test-questions',
			'AcademyQuestionController@index')
			->name('.test-questions.index');
		Route::name('api')->get('/test-questions/create/{question}',
			'AcademyQuestionController@create')
			->name('.test-questions.create');
		        
		// Презентации для разделов
		Route::name('api')->get('/stage-presentations',
			'AcademyPresentationController@index')
			->name('.stage-presentations.index');
		Route::name('api')->get('/stage-presentations/create/{presentation}',
			'AcademyPresentationController@create')
			->name('.stage-presentations.create');
			        
		// Видео для разделов
		Route::name('api')->get('/stage-videos',
			'AcademyVideoController@index')
			->name('.stage-videos.index');
		Route::name('api')->get('/stage-videos/create/{video}',
			'AcademyVideoController@create')
			->name('.stage-videos.create');
				
        
        // Пользователи поиск
		Route::name('api')->get('/users',
			'UserController@index')
			->name('.users.index');
		// Route::name('api')->get('/users/create/{product}',
			// 'UsersController@create')
			// ->name('.users.create');
		Route::name('api')->get('/users/createAdmin/{product}',
			'UserController@createAdmin')
			->name('.users.createAdmin');
                     
//--API // Сотрудники
		Route::name('api')->get('/users/create-mission/{user}',
			'UserController@createMisssion')
			->name('.users.create_mission');   

		// Сервисные центры на карте
		Route::name('api')->get('/services/{region?}',
			'MapController@service_centers')
			->name('.service-centers');
			
			
		Route::name('api')->get('/address/{address}',
			'MapController@address')
			->name('.service-address');

		// Дилеры на карте
		Route::name('api')->get('/dealers/{region?}',
			'MapController@where_to_buy')
			->name('.where-to-buy');

		// Монтажники на карте
		Route::name('api')->get('/mounters/{region?}',
			'MapController@mounter_requests')
			->name('.mounter-requests');

		// Пользователи
		Route::name('api')->resource('/users',
			'UserController')
			->only(['show']);		
        
        Route::name('api')->get('/user-search',
            'UserController@search')
            ->name('.user_search');
            
          Route::name('api')->get('/phone-exists',
            'UserController@phoneExists')
            ->name('.phone_exists');
            
          Route::name('api')->get('/email-exists',
            'UserController@emailExists')
            ->name('.email_exists');
            
	
	// Клиенты
		Route::name('api')->resource('/customer',
			'CustomerController')
			->only(['show']);		
        
        Route::name('api')->get('/customer-search',
            'CustomerController@search')
            ->name('.customer_search');
        
        Route::name('api')->get('/customer-create/{field_name}',
            'CustomerController@create')
            ->name('.customer_create');
            
        Route::name('api')->post('/customer-store',
            'CustomerController@store')
            ->name('.customer_store');


        Route::name('api')->get('/views/create-address-esb',
            'ViewController@createAddressEsb')
            ->name('.create_address_esb');
            

		// Заказы
		Route::name('api')->resource('/orders',
			'OrderController')
			->only(['show']);

		// Акты выполненных работ
		Route::name('api')->resource('/acts',
			'ActController')
			->only(['show']);

        // Товары для отчета по монтажу
        Route::name('api')->get('/products/mounting','ProductController@mounting');

//--API // Товары для заявки на монтаж
        Route::get('/products/mounter','ProductController@mounter')->name('api.products.mounter');


//--API // Товары имя артикул
        Route::name('api')->get('/products/create-short/{product}',
            'PartController@createShort')
            ->name('.products.create_short');

//--API // Товары для ревизий оборудования
        Route::name('api')->get('/products/create-revision-part/{product}',
            'PartController@createRevisionPart')
            ->name('.products.create_revision_part');

        Route::name('api')->get('/products/create-from-equipment/{equipment}',
            'PartController@createRevisionPartFromEquipment');

        Route::name('api')->get('/products/search',
            'ProductController@search')
            ->name('.products.search');

		// Быстрый заказ
		Route::get('/products/fast','ProductController@fast')->name('api.products.fast');
		Route::get('/products/eq-search','ProductController@eqSearch')->name('api.products.equipment');
		Route::name('api')->get('/products/analog', 'ProductController@analog');
		Route::name('api')->get('/products/product', 'ProductController@product');
        Route::name('api')->get('/products/{product}', 'ProductController@show');
        
            
        Route::name('api')->get('/equipments','EquipmentController@search')->name('.equipment_search');
        Route::name('api')->get('/equipments/specifications/{equipment}','EquipmentController@specifications')->name('.equipment_specifications');



        Route::name('api')->get('/dadata/address',
            'DadataController@address')
            ->name('.dadata_address');    
        
        Route::name('api')->get('/dadata/inn',
            'DadataController@inn')
            ->name('.dadata_inn'); 
        
        Route::name('api')->get('/dadata/bank',
            'DadataController@bank')
            ->name('.dadata_bank');
            
        Route::name('api')->get('/dadata/ip',
            'DadataController@ip')
            ->name('.dadata_ip');

		Route::name('api')->get('/esb-product-serach-serial/{serial}', 'EsbController@serialSearch');
		Route::name('api')->get('/esb-product-serach-phone/{phone}', 'EsbController@phoneSearch');
		Route::name('api')->get('/esb-product-serach-user-id/', 'EsbController@userIdSearch');
        
		Route::name('api')->get('/esb-claim-create', 'EsbController@esbClaimCreate');
		Route::name('api')->post('/esb-user-invite', 'EsbController@esbUserInvite');
		Route::name('api')->post('/esb-visit-planned-create', 'EsbController@esbVisitPlannedCreate');
		Route::name('api')->post('/esb-visit-planned-delete', 'EsbController@esbVisitPlannedDelete');
		Route::name('api')->post('/esb-contract-template-add-field', 'EsbController@esbContractTemplateAddField');

      
		Route::name('api')->get('/storehouses/cron',
			'StorehouseController@cron')
			->name('.storehouses.cron');
		
        Route::name('api')->get('/distributor-sales/cron',
			'DistributorSaleController@cron')
			->name('.storehouses.cron');
            
		Route::name('api')->get('/cron/day',
			'CronController@day');
            
        
		Route::name('api')->get('/zoom/create-zoom-webinar/{webinar}','ZoomController@createZoomWebinar');    
		Route::name('api')->get('/zoom/get-zoom-webinar/{webinar_id}','ZoomController@getWebinar');   
		Route::name('api')->get('/zoom/add-zoom-registrant/{webinar_id}','ZoomController@addWebinarRegistrant');   
		Route::name('api')->get('/zoom/add-zoom-user-registrant/{webinar}/{user}','ZoomController@addWebinarUserRegistrant');   
		Route::name('api')->get('/zoom/get-zoom-registrant/{webinar}/{registrant_id}','ZoomController@getWebinarRegistrant');   
		Route::name('api')->get('/zoom/get-zoom-webinar-questions/{webinar}','ZoomController@getWebinarRegistrantQuestions');   
		Route::name('api')->get('/zoom/get-zoom-webinars-stat','ZoomController@getWebinarsStatistic');   
		
        
        Route::name('api')->get('/mqtt/gettemp/{deviceId}', 'MqttController@getTemperature');
        Route::name('api')->get('/mqtt/getstatusarc/{deviceId}', 'MqttController@getArcStatus');
        Route::name('api')->get('/mqtt/settemperature/{deviceId}', 'MqttController@setTemperature');



        
	});

    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
