<?php

use Illuminate\Support\Facades\Route;
// URL::forcescheme('https');
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\Admin\OurteamController;

Route::get('/', [App\Http\Controllers\LoginController::class, 'login']);

Route::get('/lang/{lang}', [App\Http\Controllers\LangController::class, 'lang'])->name('lang');

Route::post('/logout', [App\Http\Controllers\LoginController::class, 'logout'])->name('logout');
Route::get('/login', [App\Http\Controllers\LoginController::class, 'login'])->name('login');
Route::post('/login', [App\Http\Controllers\LoginController::class, 'loginCheck'])->name('login.check');

Route::prefix('admin')->name('admin.')->middleware('isAdmin')->group(function () {



    Route::get('/category/gallery/', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('gallery.category');
    Route::get('/category/gallery/create', [App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('gallery.category.create');
    Route::get('/category/message/', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('message.category');
    Route::get('/category/message/create', [App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('message.category.create');
    Route::get('/category/services/', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('services.category');
    Route::get('/category/services/create', [App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('services.category.create');
    Route::get('/category/update/', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('update.category');
    Route::get('/category/update/create', [App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('update.category.create');
    Route::get('/category/news_events/', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('news_events.category');
    Route::get('/category/news_events/create', [App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('news_events.category.create');
    Route::get('/category/ourteams/', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('ourteams.category');
    Route::get('/category/ourteams/create', [App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('ourteams.category.create');

    
   
    Route::get('/home', [App\Http\Controllers\LoginController::class, 'admin'])->name('home');
    Route::post('/media/storeMedia', [App\Http\Controllers\Admin\FileController::class, 'storeMedia'])->name('media.storeMedia');
    Route::post('/gallery/storeMedia', [App\Http\Controllers\Admin\GalleryController::class, 'storeMedia'])->name('gallery.storeMedia');
    Route::post('/gallery/filter',[App\Http\Controllers\Admin\GalleryController::class, 'galleryfilter'])->name('dropdown.change');

    Route::resource('/media', 'App\Http\Controllers\Admin\FileController');
    Route::resource('/gallery', 'App\Http\Controllers\Admin\GalleryController');
    Route::resource('/category', 'App\Http\Controllers\Admin\CategoryController');
    Route::resource('/slide', 'App\Http\Controllers\Admin\SlideController');
    Route::post('admin/slide/updateOrder', 'App\Http\Controllers\Admin\SlideController@updateOrder')->name('slide.updateOrder');

   

    Route::get('/update/switch', [App\Http\Controllers\Admin\UpdateController::class, 'switch'])->name('update.switch');
    Route::get('/update/trash', [App\Http\Controllers\Admin\UpdateController::class, 'trash'])->name('update.trash');
    Route::get('/update/delete/{id}', [App\Http\Controllers\Admin\UpdateController::class, 'delete'])->name('update.delete');
    Route::get('/update/recover/{id}', [App\Http\Controllers\Admin\UpdateController::class, 'recover'])->name('update.recover');

    Route::get('/social/Addmedia', [App\Http\Controllers\Admin\SocialmediaController::class, 'index'])->name('social.index');
    Route::get('/social/Editmedia', [App\Http\Controllers\Admin\SocialmediaController::class, 'edit'])->name('social.edit');


    Route::post('/socialmediastore', [App\Http\Controllers\Admin\SocialmediaController::class, 'socialStore'])->name('socialStore');

    Route::post('/socialupdate', [App\Http\Controllers\Admin\SocialmediaController::class, 'socialupdate'])->name('socialupdate');


   Route::resource('/update', 'App\Http\Controllers\Admin\UpdateController');

    Route::resource('/resource', 'App\Http\Controllers\Admin\MessageController');

    Route::get('/resource/switch', [App\Http\Controllers\Admin\MessageController::class, 'show'])->name('resource.switch');
   
    Route::get('/resource/get/trash', [App\Http\Controllers\Admin\MessageController::class, 'gettrash'])->name('resource.trash');

    Route::get('/resource/delete/{id}', [App\Http\Controllers\Admin\MessageController::class, 'delete'])->name('resource.delete');
    Route::get('/resource/recover/{id}', [App\Http\Controllers\Admin\MessageController::class, 'recover'])->name('resource.recover');

  

    Route::get('/page/switch', [App\Http\Controllers\Admin\PageController::class, 'switch'])->name('page.switch');
    Route::get('/page/trash', [App\Http\Controllers\Admin\PageController::class, 'trash'])->name('page.trash');
    Route::get('/page/delete/{id}', [App\Http\Controllers\Admin\PageController::class, 'delete'])->name('page.delete');
    Route::get('/page/recover/{id}', [App\Http\Controllers\Admin\PageController::class, 'recover'])->name('page.recover');
    Route::resource('/page', 'App\Http\Controllers\Admin\PageController');

   
    Route::resource('/contact', 'App\Http\Controllers\ContactController');
    Route::get('/contact/delete/{id}',[App\Http\Controllers\ContactController::class,'delete'])->name('contact.delete');
    Route::get('/contact/trash', [App\Http\Controllers\ContactController::class, 'trashed'])->name('contact.trash');
    Route::get('/contact/sendmail/{id}', [App\Http\Controllers\ContactController::class, 'sendmail'])->name('contact.sendmail');
    Route::get('/contact/recover/{id}', [App\Http\Controllers\ContactController::class, 'recover'])->name('contact.recover');

    
    Route::get('/user/trash', [App\Http\Controllers\Admin\UserController::class, 'trash'])->name('user.trash');
    Route::get('/user/delete/{id}', [App\Http\Controllers\Admin\UserController::class, 'delete'])->name('user.delete');
    Route::get('/user/recover/{id}', [App\Http\Controllers\Admin\UserController::class, 'recover'])->name('user.recover');
    Route::resource('/user', 'App\Http\Controllers\Admin\UserController');

  

    Route::prefix('/option')->name('option.')->group(function(){
        Route::get('/index', [App\Http\Controllers\Admin\OptionController::class, 'index'])->name('index');
        Route::post('/update', [App\Http\Controllers\Admin\OptionController::class, 'update'])->name('update');

        Route::get('/contact', [App\Http\Controllers\Admin\OptionController::class, 'contact'])->name('contact');
        Route::post('/contactUpdate', [App\Http\Controllers\Admin\OptionController::class, 'contactUpdate'])->name('contactUpdate');

        Route::get('/social', [App\Http\Controllers\Admin\OptionController::class, 'social'])->name('social');
        Route::post('/socialUpdate', [App\Http\Controllers\Admin\OptionController::class, 'socialUpdate'])->name('socialUpdate');

        Route::get('/menu/position', [App\Http\Controllers\Admin\MenuController::class, 'position'])->name('menu.position');
        Route::get('/menu/delete/{menu}', [App\Http\Controllers\Admin\MenuController::class, 'delete'])->name('menu.delete');
        Route::post('/menu/menu-name', [App\Http\Controllers\Admin\MenuController::class, 'menuName'])->name('menu.menuName');
        Route::resource('/menu', 'App\Http\Controllers\Admin\MenuController');

        Route::get('/widget', [App\Http\Controllers\Admin\OptionController::class, 'widget'])->name('widget');
        Route::post('/widgetUpdate', [App\Http\Controllers\Admin\OptionController::class, 'widgetUpdate'])->name('widgetUpdate');

        Route::resource('/redirect', 'App\Http\Controllers\Admin\RedirectController');
        Route::resource('/link', 'App\Http\Controllers\Admin\LinkController');
    });

 Route::resource('/mainmenu', 'App\Http\Controllers\Admin\MainMenuController');
 Route::get('/mainmenu/delete/{id}',[App\Http\Controllers\Admin\MainMenuController::class,'delete'])->name('mainmenu.delete');
 Route::get('/mainmenu/trash',[App\Http\Controllers\Admin\MainMenuController::class,'show'])->name('mainmenu.trashed');
 Route::get('/editmainmenu',[App\Http\Controllers\Admin\MainMenuController::class,'editmainmenu'])->name('mainmenu.editmain');

 Route::get('/status',[App\Http\Controllers\Admin\MainMenuController::class,'switch'])->name('mainmenu.statusupdate');
 Route::get('/mainmenu/recover/{id}',[App\Http\Controllers\Admin\MainMenuController::class,'recover'])->name('mainmenu.recover');
 Route::post('/mainmenu/updateorder',[App\Http\Controllers\Admin\MainMenuController::class,'updateorder'])->name('mainmenu.updateorder');

 Route::resource('/submenu', 'App\Http\Controllers\Admin\SubmenuController');
 Route::get('/submenu/delete/{id}',[App\Http\Controllers\Admin\SubmenuController::class,'delete'])->name('submenu.delete');
 Route::get('/submenu/trash',[App\Http\Controllers\Admin\SubmenuController::class,'show'])->name('submenu.trashed');
 Route::get('/editsubmenu',[App\Http\Controllers\Admin\SubmenuController::class,'editsubmenu'])->name('submenu.editmain');
 Route::get('/status/switch',[App\Http\Controllers\Admin\SubmenuController::class,'switch'])->name('submenu.switch');
 Route::get('/submenu/recover/{id}',[App\Http\Controllers\Admin\SubmenuController::class,'recover'])->name('submenu.recover');
 Route::post('/submenu/updateorder',[App\Http\Controllers\Admin\SubmenuController::class,'updateorder'])->name('submenu.updateorder');
 
 Route::resource('/childsubmenu', 'App\Http\Controllers\Admin\ChildSubmenuController');
 Route::get('/childsubmenu/delete/{id}',[App\Http\Controllers\Admin\ChildSubmenuController::class,'delete'])->name('childsubmenu.delete');
 Route::get('/childsubmenu/trash',[App\Http\Controllers\Admin\ChildSubmenuController::class,'show'])->name('childsubmenu.trashed');
 Route::get('/editchildsubmenu',[App\Http\Controllers\Admin\ChildSubmenuController::class,'editsubmenu'])->name('childsubmenu.editmain');
 Route::get('/childsubmenu/switch',[App\Http\Controllers\Admin\ChildSubmenuController::class,'switch'])->name('childsubmenu.switch');
 Route::get('/childsubmenu/recover/{id}',[App\Http\Controllers\Admin\ChildSubmenuController::class,'recover'])->name('childsubmenu.recover');
 Route::post('/childsubmenu/updateorder',[App\Http\Controllers\Admin\ChildSubmenuController::class,'updateorder'])->name('childsubmenu.updateorder');

 Route::resource('/subchildsubmenu', 'App\Http\Controllers\Admin\SubchildSubmenuController');
 Route::get('/subchildsubmenu/delete/{id}',[App\Http\Controllers\Admin\SubchildSubmenuController::class,'delete'])->name('subchildsubmenu.delete');
 Route::get('/subchildsubmenu/trash',[App\Http\Controllers\Admin\SubchildSubmenuController::class,'show'])->name('subchildsubmenu.trashed');
 Route::get('/subeditchildsubmenu',[App\Http\Controllers\Admin\SubchildSubmenuController::class,'editsubmenu'])->name('subchildsubmenu.editmain');
 Route::get('/subchildsubmenu/switch',[App\Http\Controllers\Admin\SubchildSubmenuController::class,'switch'])->name('subchildsubmenu.switch');
 Route::get('/subchildsubmenu/recover/{id}',[App\Http\Controllers\Admin\SubchildSubmenuController::class,'recover'])->name('subchildsubmenu.recover');
 Route::post('/subchildsubmenu/updateorder',[App\Http\Controllers\Admin\SubchildSubmenuController::class,'updateorder'])->name('subchildsubmenu.updateorder');

 Route::resource('testimonial', TestimonialController::class);
 Route::get('/testimonial/delete/{id}',[TestimonialController::class,'show'])->name('testimonial.delete');
 Route::get('/trash/testimonial',[TestimonialController::class,'trashed'])->name('testimonial.trash');
 Route::get('/testimonial/recover/{id}',[TestimonialController::class,'recover'])->name('testimonial.recover');
 Route::get('/changestatus',[TestimonialController::class,'switch'])->name('testimonial.dataswitch');

 Route::resource('ourteams', OurteamController::class);
 Route::get('/ourteams/delete/{id}',[OurTeamController::class,'delete'])->name('ourteams.delete');
 Route::get('/ourteams/trash',[OurTeamController::class,'show'])->name('ourteams.trashed');
 Route::get('/editourteams',[OurTeamController::class,'editourteams'])->name('ourteams.editmain');
 Route::get('/ourteams/recover/{id}',[OurTeamController::class,'recover'])->name('ourteams.recover');
 Route::post('/ourteams/updateorder',[OurTeamController::class,'updateorder'])->name('ourteams.updateorder');

 Route::resource('news_events', 'App\Http\Controllers\Admin\NewseventController');
 Route::get('/news_events/delete/{id}',[App\Http\Controllers\Admin\NewseventController::class,'delete'])->name('news_events.delete');
 Route::get('/news_events/trash',[App\Http\Controllers\Admin\NewseventController::class,'show'])->name('news_events.trash');
 Route::get('/editnews_events',[App\Http\Controllers\Admin\NewseventController::class,'editnews_events'])->name('news_events.editmain');
 Route::get('/news_events/recover/{id}',[App\Http\Controllers\Admin\NewseventController::class,'recover'])->name('news_events.recover');
 Route::post('/news_events/updateorder',[App\Http\Controllers\Admin\NewseventController::class,'updateorder'])->name('news_events.switch');
});


