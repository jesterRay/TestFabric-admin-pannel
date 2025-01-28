<?php

use Illuminate\Support\Facades\Route;






use App\Http\Controllers\CocController;
use App\Http\Controllers\layouts\Blank;
use App\Http\Controllers\layouts\Fluid;

use App\Http\Controllers\MapController;
use App\Http\Middleware\CheckUserLogin;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\icons\Boxicons;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TPVSController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\cards\CardBasic;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FlyerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CareerController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\layouts\Container;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\NextStepController;
use App\Http\Controllers\StandardController;
use App\Http\Controllers\ContinentController;
use App\Http\Controllers\dashboard\Analytics;
use App\Http\Controllers\layouts\WithoutMenu;
use App\Http\Controllers\QuickLinkController;
use App\Http\Controllers\HeaderLogoController;
use App\Http\Controllers\TestMethodController;
use App\Http\Controllers\AvailableInController;
use App\Http\Controllers\layouts\WithoutNavbar;
use App\Http\Controllers\RangeFormatController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\user_interface\Alerts;
use App\Http\Controllers\user_interface\Badges;
use App\Http\Controllers\user_interface\Footer;
use App\Http\Controllers\user_interface\Modals;
use App\Http\Controllers\user_interface\Navbar;

use App\Http\Controllers\user_interface\Toasts;
use App\Http\Controllers\CountryAgentController;
use App\Http\Controllers\PriceBreakUpController;
use App\Http\Controllers\user_interface\Buttons;
use App\Http\Controllers\extended_ui\TextDivider;
use App\Http\Controllers\InterestGroupController;
use App\Http\Controllers\user_interface\Carousel;
use App\Http\Controllers\user_interface\Collapse;
use App\Http\Controllers\user_interface\Progress;
use App\Http\Controllers\user_interface\Spinners;
use App\Http\Controllers\form_elements\BasicInput;
use App\Http\Controllers\MasterCategoryController;
use App\Http\Controllers\SurveyQuestionController;
use App\Http\Controllers\SurveyResponseController;
use App\Http\Controllers\user_interface\Accordion;
use App\Http\Controllers\user_interface\Dropdowns;
use App\Http\Controllers\user_interface\Offcanvas;
use App\Http\Controllers\user_interface\TabsPills;
use App\Http\Controllers\form_elements\InputGroups;
use App\Http\Controllers\form_layouts\VerticalForm;
use App\Http\Controllers\user_interface\ListGroups;
use App\Http\Controllers\user_interface\Typography;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\pages\MiscUnderMaintenance;
use App\Http\Controllers\form_layouts\HorizontalForm;
use App\Http\Controllers\tables\Basic as TablesBasic;
use App\Http\Controllers\extended_ui\PerfectScrollbar;
use App\Http\Controllers\pages\AccountSettingsAccount;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\VerificationHistoryController;
use App\Http\Controllers\MinimumOrderQuantityController;
use App\Http\Controllers\AssociationAndPartnerController;
use App\Http\Controllers\UploadRelatedDocumentController;
use App\Http\Controllers\user_interface\TooltipsPopovers;
use App\Http\Controllers\pages\AccountSettingsConnections;
use App\Http\Controllers\pages\AccountSettingsNotifications;
use App\Http\Controllers\authentications\ForgotPasswordBasic;
use App\Http\Controllers\user_interface\PaginationBreadcrumbs;


// Route::middleware([CheckUserLogin::class])->group(function () {

// });



// user route
Route::get('/profile', [UserController::class,'profile'])->name('profile');
Route::put('/user/{id}', [UserController::class,'update'])->name('user.update');




// auth and login routes
Route::post('/auth', [UserController::class,'authUser'])->name('user.auth');
Route::get('/login', [UserController::class,'login'])->name('login');

// verification history or dashboard table route
Route::controller(VerificationHistoryController::class)->group(function () {
    Route::get('/dashboard', 'index')->name('verification-history.index');
    
});

// chat route
Route::controller(ChatController::class)->group(function () {
    Route::get('/chat', 'index')->name('chat.index');
    Route::get('/chat/{id}/edit', 'edit')->name('chat.edit');
    Route::get('/chat/{id}/view', 'view')->name('chat.view');
    Route::post('/chat', 'saveAdminChat')->name('chat.save');
    Route::get('/chat/{id}/delete', 'delete')->name('chat.destroy');
});

// COC route
Route::controller(CocController::class)->group(function () {
    
    Route::get('/coc', 'index')->name('coc.index');
    Route::get('/coc/add', 'add')->name('coc.add');
    Route::post('/coc/save', 'save')->name('coc.save');
    Route::get('/coc/{id}/delete', 'delete')->name('coc.destroy');

});

// Survey Response route
Route::controller(SurveyResponseController::class)->group(function () {    
    Route::get('/survey-response/expire', 'expire')->name('survey.response.expire');
    Route::get('/survey-response/thank', 'thank')->name('survey.response.thank');
    
    Route::get('/survey-response/{id}', 'index')->name('survey.response.index');
    Route::get('/survey-response/{id}/create', 'create')->name('survey.response.create');
    Route::post('/survey-response/{id}', 'save')->name('survey.response.save');
    Route::get('/survey-response/{id}/analytics', 'analytic')->name('survey.response.analytic');
    
});

// Survey Question route
Route::controller(SurveyQuestionController::class)->group(function () {    
    Route::get('/survey-question/{id}', 'index')->name('survey.question.index');
    Route::get('/survey-question/{id}/create', 'create')->name('survey.question.create');
    Route::post('/survey-question/{id}', 'save')->name('survey.question.save');
    Route::get('/survey-question/{id}/edit', 'edit')->name('survey.question.edit');
    Route::put('/survey-question/{id}/update', 'update')->name('survey.question.update');
    
});

// Survey route
Route::controller(SurveyController::class)->group(function () {    
    Route::get('/survey', 'index')->name('survey.index');
    Route::get('/survey/create', 'create')->name('survey.create');
    Route::post('/survey', 'save')->name('survey.save');
    Route::get('/survey/{id}/preview', 'preview')->name('survey.preview');
    Route::get('/survey/{id}/edit', 'edit')->name('survey.edit');
    Route::put('/survey/{id}', 'update')->name('survey.update');
    Route::get('/survey/{id}/delete', 'delete')->name('survey.delete');
});

// About Us route
Route::controller(AboutUsController::class)->group(function () {
    Route::get('/about-us', 'index')->name('about-us.index');
    Route::get('/about-us/create', 'create')->name('about-us.create');
    Route::post('/about-us', 'save')->name('about-us.save');
    Route::get('/about-us/{id}/edit', 'edit')->name('about-us.edit');
    Route::put('/about-us/{id}', 'update')->name('about-us.update');
    Route::get('/about-us/{id}/destroy', 'destroy')->name('about-us.destroy');
});

// Associations And Partners route
Route::controller(AssociationAndPartnerController::class)->group(function () {
    Route::get('/association-and-partner', 'index')->name('association-and-partner.index');
    Route::get('/association-and-partner/create', 'create')->name('association-and-partner.create');
    Route::post('/association-and-partner', 'save')->name('association-and-partner.save');
    Route::get('/association-and-partner/{id}/edit', 'edit')->name('association-and-partner.edit');
    Route::put('/association-and-partner/{id}', 'update')->name('association-and-partner.update');
    Route::get('/association-and-partner/{id}/destroy', 'destroy')->name('association-and-partner.destroy');
});

// Career route
Route::controller(CareerController::class)->group(function () {
    Route::get('/career', 'index')->name('career.index');
    Route::get('/career/create', 'create')->name('career.create');
    Route::post('/career', 'save')->name('career.save');
    Route::get('/career/{id}/edit', 'edit')->name('career.edit');
    Route::put('/career/{id}', 'update')->name('career.update');
    Route::get('/career/{id}/destroy', 'destroy')->name('career.destroy');
});

// Header Logo route
Route::controller(HeaderLogoController::class)->group(function () {
    Route::get('/header-logo', 'index')->name('header.logo.index');
    Route::post('/header-logo', 'save')->name('header.logo.save');
});

// Banner route
Route::controller(BannerController::class)->group(function () {
    Route::get('/banner', 'index')->name('banner.index');
    Route::get('/banner/create', 'create')->name('banner.create');
    Route::post('/banner', 'save')->name('banner.save');
    Route::get('/banner/{id}/destroy', 'destroy')->name('banner.destroy');
});

// Next Step route
Route::controller(NextStepController::class)->group(function () {
    Route::get('/next-step', 'index')->name('next-step.index');
    Route::get('/next-step/create', 'create')->name('next-step.create');
    Route::post('/next-step', 'save')->name('next-step.save');
    Route::get('/next-step/{id}/edit', 'edit')->name('next-step.edit');
    Route::put('/next-step/{id}', 'update')->name('next-step.update');
    Route::get('/next-step/{id}/destroy', 'destroy')->name('next-step.destroy');
});

// Interest Group route
Route::controller(InterestGroupController::class)->group(function () {
    Route::get('/interest-group', 'index')->name('interest-group.index');
    Route::get('/interest-group/create', 'create')->name('interest-group.create');
    Route::post('/interest-group', 'save')->name('interest-group.save');
    Route::get('/interest-group/{id}/edit', 'edit')->name('interest-group.edit');
    Route::put('/interest-group/{id}', 'update')->name('interest-group.update');
    Route::get('/interest-group/{id}/destroy', 'destroy')->name('interest-group.destroy');
});

// Standards route
Route::controller(StandardController::class)->group(function () {
    Route::get('/standard', 'index')->name('standard.index');
    Route::get('/standard/create', 'create')->name('standard.create');
    Route::post('/standard', 'save')->name('standard.save');
    Route::get('/standard/{id}/edit', 'edit')->name('standard.edit');
    Route::put('/standard/{id}', 'update')->name('standard.update');
    Route::get('/standard/{id}/destroy', 'destroy')->name('standard.destroy');
});

// Test Methods route
Route::controller(TestMethodController::class)->group(function () {
    Route::get('/test-method', 'index')->name('test-method.index');
    Route::get('/test-method/create', 'create')->name('test-method.create');
    Route::post('/test-method', 'save')->name('test-method.save');
    Route::get('/test-method/{id}/edit', 'edit')->name('test-method.edit');
    Route::put('/test-method/{id}', 'update')->name('test-method.update');
    Route::get('/test-method/{id}/destroy', 'destroy')->name('test-method.destroy');
});

// Service route
Route::controller(ServiceController::class)->group(function () {
    Route::get('/service', 'index')->name('service.index');
    Route::get('/service/create', 'create')->name('service.create');
    Route::post('/service', 'save')->name('service.save');
    Route::get('/service/{id}/edit', 'edit')->name('service.edit');
    Route::put('/service/{id}', 'update')->name('service.update');
    Route::get('/service/{id}/destroy', 'destroy')->name('service.destroy');
});

// Flyer route
Route::controller(FlyerController::class)->group(function () {
    Route::get('/flyer/index', 'index')->name('flyer.index');
    Route::get('/flyer/create', 'create')->name('flyer.create');
    Route::post('/flyer', 'save')->name('flyer.save');
    Route::get('/flyer/{id}/destroy', 'destroy')->name('flyer.destroy');
});


// Continent route
Route::controller(ContinentController::class)->group(function () {
    Route::get('/continent', 'index')->name('continent.index');
    Route::get('/continent/create', 'create')->name('continent.create');
    Route::post('/continent', 'save')->name('continent.save');
    Route::get('/continent/{id}/edit', 'edit')->name('continent.edit');
    Route::put('/continent/{id}', 'update')->name('continent.update');
    Route::get('/continent/{id}/destroy', 'destroy')->name('continent.destroy');
});

// Country route
Route::controller(CountryController::class)->group(function () {
    Route::get('/country/create', 'create')->name('country.create');
    Route::post('/country', 'save')->name('country.save');
    
    Route::get('/country/{id}', 'index')->name('country.index');
    Route::get('/country/{id}/edit', 'edit')->name('country.edit');
    Route::put('/country/{id}', 'update')->name('country.update');
    Route::get('/country/{id}/destroy', 'destroy')->name('country.destroy');
});

// Country Agent route
Route::controller(CountryAgentController::class)->group(function () {
    Route::get('/country-agent', 'index')->name('country-agent.index');
    Route::get('/country-agent/create', 'create')->name('country-agent.create');
    Route::post('/country-agent', 'save')->name('country-agent.save');
    Route::get('/country-agent/{id}/edit', 'edit')->name('country-agent.edit');
    Route::put('/country-agent/{id}', 'update')->name('country-agent.update');
    Route::get('/country-agent/{id}/destroy', 'destroy')->name('country-agent.destroy');
});


// Country Agent route
Route::controller(NewsController::class)->group(function () {
    Route::get('/news', 'index')->name('news.index');
    Route::get('/news/create', 'create')->name('news.create');
    Route::post('/news', 'save')->name('news.save');
    Route::get('/news/{id}/edit', 'edit')->name('news.edit');
    Route::put('/news/{id}', 'update')->name('news.update');
    Route::get('/news/{id}/destroy', 'destroy')->name('news.destroy');

    Route::get('/news/letter', 'newsLetter')->name('news.letter');

});

// Event Route
Route::controller(EventController::class)->group(function () {
    Route::get('/event', 'index')->name('event.index');
    Route::get('/event/create', 'create')->name('event.create');
    Route::post('/event', 'save')->name('event.save');
    Route::get('/event/{id}/edit', 'edit')->name('event.edit');
    Route::put('/event/{id}', 'update')->name('event.update');
    Route::get('/event/{id}/destroy', 'destroy')->name('event.destroy');
});

// Page Route
Route::controller(PageController::class)->group(function () {
    Route::get('/page', 'index')->name('page.index');
    Route::get('/page/home', 'home')->name('page.home.index');
    Route::get('/page/create', 'create')->name('page.create');
    Route::post('/page', 'save')->name('page.save');
    Route::get('/page/{id}/edit', 'edit')->name('page.edit');
    Route::put('/page/{id}', 'update')->name('page.update');
    Route::put('/page/{id}/home', 'homeUpdate')->name('page.home.update');
    Route::get('/page/{id}/destroy', 'destroy')->name('page.destroy');
});


// Quick Link Route
Route::controller(QuickLinkController::class)->group(function () {
    Route::get('/quick-link', 'index')->name('quick-link.index');
    Route::get('/quick-link/create', 'create')->name('quick-link.create');
    Route::post('/quick-link', 'save')->name('quick-link.save');
    Route::get('/quick-link/{id}/edit', 'edit')->name('quick-link.edit');
    Route::put('/quick-link/{id}', 'update')->name('quick-link.update');
    Route::get('/quick-link/{id}/destroy', 'destroy')->name('quick-link.destroy');
});

// Order Route
Route::controller(OrderController::class)->group(function () {
    
    // order heading route
    Route::get('/order/heading', 'heading')->name('order.heading.index');
    Route::put('/order/{id}', 'headingUpdate')->name('order.heading.update');
});


// Download Route
Route::controller(DownloadController::class)->group(function () {
    Route::get('/download', 'index')->name('download.index');
    Route::get('/download/create', 'create')->name('download.create');
    Route::post('/download', 'save')->name('download.save');
    Route::get('/download/{id}/destroy', 'destroy')->name('download.destroy');
});

// TPVS Route
Route::controller(TPVSController::class)->group(function () {
    Route::get('/tpvs/content', 'content')->name('tpvs.content');
    Route::get('/tpvs/info', 'info')->name('tpvs.info');
    Route::get('/tpvs/serial', 'indexSerial')->name('tpvs.serial.index');
    Route::get('/tpvs/serial/create', 'createSerial')->name('tpvs.serial.create');
    Route::post('/tpvs/serial/save', 'saveSerial')->name('tpvs.serial.save');
    Route::post('/tpvs/serial/update', 'updateSerial')->name('tpvs.serial.update');

    Route::put('/tpvs/{id}/content', 'contentUpdate')->name('tpvs.content.update');
});

// Master Category Route
Route::controller(MasterCategoryController::class)->group(function () {
    Route::get('/category', 'index')->name('category.index');
    Route::get('/category/create', 'create')->name('category.create');
    Route::post('/category', 'save')->name('category.save');
    Route::get('/category/{id}/edit', 'edit')->name('category.edit');
    Route::put('/category/{id}', 'update')->name('category.update');
    Route::get('/category/{id}/destroy', 'destroy')->name('category.destroy');
});

// Master Category Route
Route::controller(SubCategoryController::class)->group(function () {
    Route::get('/sub-category', 'index')->name('sub-category.index');
    Route::get('/sub-category/create', 'create')->name('sub-category.create');
    Route::post('/sub-category', 'save')->name('sub-category.save');
    Route::get('/sub-category/{id}/edit', 'edit')->name('sub-category.edit');
    Route::put('/sub-category/{id}', 'update')->name('sub-category.update');
    Route::get('/sub-category/{id}/destroy', 'destroy')->name('sub-category.destroy');
});

// Product Route
Route::controller(ProductController::class)->group(function () {
    Route::get('/product', 'index')->name('product.index');
    Route::get('/product/create', 'create')->name('product.create');
    Route::post('/product', 'save')->name('product.save');
    Route::get('/product/{id}/edit', 'edit')->name('product.edit');
    Route::put('/product/{id}', 'update')->name('product.update');
    Route::get('/product/{id}/destroy', 'destroy')->name('product.destroy');

    // delete product by subcategory
    Route::get('/product/subcategory/delete', 'subcategoryDelete')->name('product.subcategory.delete'); //to show view file
    Route::post('/product/subcategory/destroy', 'subcategoryDestroy')->name('product.subcategory.destroy'); //to delete 

});

// Available In Route
Route::controller(AvailableInController::class)->group(function () {
    Route::get('/available-in', 'index')->name('available-in.index');
    Route::get('/available-in/create', 'create')->name('available-in.create');
    Route::post('/available-in', 'save')->name('available-in.save');
    Route::get('/available-in/{id}/edit', 'edit')->name('available-in.edit');
    Route::put('/available-in/{id}', 'update')->name('available-in.update');
    Route::get('/available-in/{id}/destroy', 'destroy')->name('available-in.destroy');
});


// Minimum Order Quantity Route
Route::controller(MinimumOrderQuantityController::class)->group(function () {
    Route::get('/min-order-quantity', 'index')->name('min-order-quantity.index');
    Route::get('/min-order-quantity/create', 'create')->name('min-order-quantity.create');
    Route::post('/min-order-quantity', 'save')->name('min-order-quantity.save');
    Route::get('/min-order-quantity/{id}/edit', 'edit')->name('min-order-quantity.edit');
    Route::put('/min-order-quantity/{id}', 'update')->name('min-order-quantity.update');
    Route::get('/min-order-quantity/{id}/destroy', 'destroy')->name('min-order-quantity.destroy');
});

// Units Route
Route::controller(UnitController::class)->group(function () {
    Route::get('/unit', 'index')->name('unit.index');
    Route::get('/unit/create', 'create')->name('unit.create');
    Route::post('/unit', 'save')->name('unit.save');
    Route::get('/unit/{id}/edit', 'edit')->name('unit.edit');
    Route::put('/unit/{id}', 'update')->name('unit.update');
    Route::get('/unit/{id}/destroy', 'destroy')->name('unit.destroy');
});

// Range Format Route
Route::controller(RangeFormatController::class)->group(function () {
    Route::get('/range-format', 'index')->name('range-format.index');
    Route::get('/range-format/create', 'create')->name('range-format.create');
    Route::post('/range-format', 'save')->name('range-format.save');
    Route::get('/range-format/{id}/edit', 'edit')->name('range-format.edit');
    Route::put('/range-format/{id}', 'update')->name('range-format.update');
    Route::get('/range-format/{id}/destroy', 'destroy')->name('range-format.destroy');
});

// Price Break Up Route
Route::controller(PriceBreakUpController::class)->group(function () {
    Route::get('/price-break-up', 'index')->name('price-break-up.index');
    Route::get('/price-break-up/create', 'create')->name('price-break-up.create');
    Route::post('/price-break-up', 'save')->name('price-break-up.save');
    Route::get('/price-break-up/{id}/edit', 'edit')->name('price-break-up.edit');
    Route::put('/price-break-up/{id}', 'update')->name('price-break-up.update');
    Route::get('/price-break-up/{id}/destroy', 'destroy')->name('price-break-up.destroy');
});

// Upload Related Document Route
Route::controller(UploadRelatedDocumentController::class)->group(function () {
    Route::get('/upload-related-document', 'index')->name('upload-related-document.index');
    Route::get('/upload-related-document/create/{id?}', 'create')->name('upload-related-document.create');
    Route::post('/upload-related-document', 'save')->name('upload-related-document.save');
    Route::get('/upload-related-document/{id}/destroy', 'destroy')->name('upload-related-document.destroy');
});












































// Their middleware





















































































// Main Page Route
Route::get('/', [Analytics::class, 'index'])->name('dashboard-analytics');

// layout
Route::get('/layouts/without-menu', [WithoutMenu::class, 'index'])->name('layouts-without-menu');
Route::get('/layouts/without-navbar', [WithoutNavbar::class, 'index'])->name('layouts-without-navbar');
Route::get('/layouts/fluid', [Fluid::class, 'index'])->name('layouts-fluid');
Route::get('/layouts/container', [Container::class, 'index'])->name('layouts-container');
Route::get('/layouts/blank', [Blank::class, 'index'])->name('layouts-blank');

// pages
Route::get('/pages/account-settings-account', [AccountSettingsAccount::class, 'index'])->name('pages-account-settings-account');
Route::get('/pages/account-settings-notifications', [AccountSettingsNotifications::class, 'index'])->name('pages-account-settings-notifications');
Route::get('/pages/account-settings-connections', [AccountSettingsConnections::class, 'index'])->name('pages-account-settings-connections');
Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');
Route::get('/pages/misc-under-maintenance', [MiscUnderMaintenance::class, 'index'])->name('pages-misc-under-maintenance');

// authentication
Route::get('/auth/login-basic', [LoginBasic::class, 'index'])->name('auth-login-basic');
Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');
Route::get('/auth/forgot-password-basic', [ForgotPasswordBasic::class, 'index'])->name('auth-reset-password-basic');

// cards
Route::get('/cards/basic', [CardBasic::class, 'index'])->name('cards-basic');

// User Interface
Route::get('/ui/accordion', [Accordion::class, 'index'])->name('ui-accordion');
Route::get('/ui/alerts', [Alerts::class, 'index'])->name('ui-alerts');
Route::get('/ui/badges', [Badges::class, 'index'])->name('ui-badges');
Route::get('/ui/buttons', [Buttons::class, 'index'])->name('ui-buttons');
Route::get('/ui/carousel', [Carousel::class, 'index'])->name('ui-carousel');
Route::get('/ui/collapse', [Collapse::class, 'index'])->name('ui-collapse');
Route::get('/ui/dropdowns', [Dropdowns::class, 'index'])->name('ui-dropdowns');
Route::get('/ui/footer', [Footer::class, 'index'])->name('ui-footer');
Route::get('/ui/list-groups', [ListGroups::class, 'index'])->name('ui-list-groups');
Route::get('/ui/modals', [Modals::class, 'index'])->name('ui-modals');
Route::get('/ui/navbar', [Navbar::class, 'index'])->name('ui-navbar');
Route::get('/ui/offcanvas', [Offcanvas::class, 'index'])->name('ui-offcanvas');
Route::get('/ui/pagination-breadcrumbs', [PaginationBreadcrumbs::class, 'index'])->name('ui-pagination-breadcrumbs');
Route::get('/ui/progress', [Progress::class, 'index'])->name('ui-progress');
Route::get('/ui/spinners', [Spinners::class, 'index'])->name('ui-spinners');
Route::get('/ui/tabs-pills', [TabsPills::class, 'index'])->name('ui-tabs-pills');
Route::get('/ui/toasts', [Toasts::class, 'index'])->name('ui-toasts');
Route::get('/ui/tooltips-popovers', [TooltipsPopovers::class, 'index'])->name('ui-tooltips-popovers');
Route::get('/ui/typography', [Typography::class, 'index'])->name('ui-typography');

// extended ui
Route::get('/extended/ui-perfect-scrollbar', [PerfectScrollbar::class, 'index'])->name('extended-ui-perfect-scrollbar');
Route::get('/extended/ui-text-divider', [TextDivider::class, 'index'])->name('extended-ui-text-divider');

// icons
Route::get('/icons/boxicons', [Boxicons::class, 'index'])->name('icons-boxicons');

// form elements
Route::get('/forms/basic-inputs', [BasicInput::class, 'index'])->name('forms-basic-inputs');
Route::get('/forms/input-groups', [InputGroups::class, 'index'])->name('forms-input-groups');

// form layouts
Route::get('/form/layouts-vertical', [VerticalForm::class, 'index'])->name('form-layouts-vertical');
Route::get('/form/layouts-horizontal', [HorizontalForm::class, 'index'])->name('form-layouts-horizontal');

// tables
Route::get('/tables/basic', [TablesBasic::class, 'index'])->name('tables-basic');
