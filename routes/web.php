<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontProductsController;
use App\Http\Controllers\BlogShowController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\ProductsController;
use App\Http\Controllers\Admin\CategoriasController;
use App\Http\Controllers\Admin\SlidersController;
use App\Http\Controllers\Admin\BlogsController;
use App\Http\Controllers\Admin\VideosController;
use App\Http\Controllers\Admin\ConfiguracionController;
use App\Http\Controllers\Admin\ContratosCategoriasController;
use App\Http\Controllers\Admin\ContratosTiposController;
use App\Http\Controllers\Admin\ClientesController;
use App\Http\Controllers\Admin\ContratosController;
use App\Http\Controllers\Admin\AbonosController;
use App\Http\Controllers\Admin\PagosController;
use App\Http\Controllers\Admin\NotificacionesController;
use App\Http\Controllers\Admin\FormaspagoController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\AreaclienteController;
use App\Http\Controllers\Admin\DolarController;
use App\Http\Controllers\Admin\RolesController;
use App\Http\Controllers\Admin\ReporteController;
use App\Http\Controllers\Admin\AuditoriaController;
use App\Http\Controllers\Admin\ContratosGruposController;
use App\Http\Controllers\Admin\DestinosController;
use App\Http\Controllers\Admin\ImagenesController;
use App\Http\Controllers\Admin\PaquetesController;
use App\Http\Controllers\Admin\ContactosController;
use App\Http\Controllers\Admin\PromocionesController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\MaterialController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::post('/colors/ajax-store', [ColorController::class, 'ajaxStore'])->name('colors.ajaxStore');
Route::post('/materials/ajax-store', [MaterialController::class, 'ajaxStore'])->name('materials.ajaxStore');
#Route::get('/', function () {   return view('index'); });
Route::get('/setlenguaje/{locale}', [HomeController::class, 'setlenguaje'])->name('setlenguaje');
Route::get('/', [HomeController::class, 'index'])->name('index');
Route::get('/destinos', [HomeController::class, 'destinos'])->name('destinos');
Route::get('/paquetes', [HomeController::class, 'paquetes'])->name('paquetes');
Route::get('/blogs', [HomeController::class, 'blogs'])->name('blogs');
Route::get('/contacto', [HomeController::class, 'contacto'])->name('contacto');
Route::post('/sendemail', [HomeController::class, 'sendemail'])->name('sendemail');
Route::get('/destinos/{slug}', [HomeController::class, 'destinosDetalle'])->name('destinos.detalle');
Route::get('/paquetes/{slug}', [HomeController::class, 'paquetesDetalle'])->name('paquetes.detalle');
Route::get('/promociones/{slug}', [HomeController::class, 'promocionesDetalle'])->name('promociones.detalle');
Route::get('/categorias/{slug}', [HomeController::class, 'categoriaDetalle'])->name('categorias.detalle');
Route::get('/logout', [HomeController::class, 'logout'])
->name('home.logout');

Route::get('/admin/areacliente', [AreaclienteController::class, 'index'])->name('admin.areacliente.index');
Route::get('/admin/areacliente/addabono', [AreaclienteController::class, 'addabono'])->name('admin.areacliente.addabono');
Route::post('/admin/areacliente/storeabono', [AreaclienteController::class, 'storeabono'])->name('admin.areacliente.storeabono');




Route::get('/products', [FrontProductsController::class, 'index'])->name('products.index');
Route::get('/productos/{slug}', [FrontProductsController::class, 'detalle'])->name('products.single');
Route::get('/blog/{slug}', [BlogShowController::class, 'index'])->name('blog.show');

Route::get('/cart/add/{id}', [App\Http\Controllers\CartAddController::class, 'index'])->name('cart.add');
Route::get('/cart', [App\Http\Controllers\CartDetailController::class, 'index'])->name('cart.detail');

Auth::routes();


Route::get('/home', [App\Http\Controllers\Admin\HomeController::class, 'index'])->name('home');

Route::get('/orders', [App\Http\Controllers\Admin\OrdersController::class, 'index'])->name('admin.orders.index');

/** Productos */


Route::get('/admin/products', [ProductsController::class, 'index'])->name('admin.products.index')->middleware('role:4');
Route::get('/admin/products/add', [ProductsController::class, 'add'])->name('admin.products.add');
Route::post('/admin/products/store', [ProductsController::class, 'store'])->name('admin.products.store');
Route::post('/admin/products/uploadimg/{id}', [ProductsController::class, 'uploadimg'])->name('admin.products.uploadimg');
Route::get('/admin/products/{id}/edit', [ProductsController::class, 'edit'])->name('admin.products.edit');
Route::post('/admin/products/update', [ProductsController::class, 'update'])->name('admin.products.update');
Route::get('/admin/products/{id}/delete', [ProductsController::class, 'delete'])->name('admin.products.delete');
Route::get('/admin/products/{id}/delimagenes', [ProductsController::class, 'delimagenes'])->name('admin.products.delimagenes');

/** Categorias */

Route::get('/admin/categorias', [CategoriasController::class, 'index'])->name('admin.categorias.index');
Route::get('/admin/categorias/add', [CategoriasController::class, 'add'])->name('admin.categorias.add');
Route::post('/admin/categorias/store', [CategoriasController::class, 'store'])->name('admin.categorias.store');
Route::post('/admin/categorias/uploadimg/{id}', [CategoriasController::class, 'uploadimg'])->name('admin.categorias.uploadimg');
Route::get('/admin/categorias/{id}/edit', [CategoriasController::class, 'edit'])->name('admin.categorias.edit');
Route::post('/admin/categorias/update', [CategoriasController::class, 'update'])->name('admin.categorias.update');
Route::get('/admin/categorias/{id}/delete', [CategoriasController::class, 'delete'])->name('admin.categorias.delete');

/** Sliders */


Route::get('/admin/sliders', [SlidersController::class, 'index'])->name('admin.sliders.index');
Route::get('/admin/sliders/add', [SlidersController::class, 'add'])->name('admin.sliders.add');
Route::post('/admin/sliders/store', [SlidersController::class, 'store'])->name('admin.sliders.store');
Route::post('/admin/sliders/uploadimg/{id}', [SlidersController::class, 'uploadimg'])->name('admin.sliders.uploadimg');
Route::get('/admin/sliders/{id}/edit', [SlidersController::class, 'edit'])->name('admin.sliders.edit');
Route::post('/admin/sliders/update', [SlidersController::class, 'update'])->name('admin.sliders.update');
Route::get('/admin/sliders/{id}/delete', [SlidersController::class, 'delete'])->name('admin.sliders.delete');

/** Promociones  */


Route::get('/admin/promociones', [PromocionesController::class, 'index'])->name('admin.promociones.index');
Route::get('/admin/promociones/add', [PromocionesController::class, 'add'])->name('admin.promociones.add');
Route::post('/admin/promociones/store', [PromocionesController::class, 'store'])->name('admin.promociones.store');
Route::post('/admin/promociones/uploadimg/{id}', [PromocionesController::class, 'uploadimg'])->name('admin.promociones.uploadimg');
Route::get('/admin/promociones/{id}/edit', [PromocionesController::class, 'edit'])->name('admin.promociones.edit');
Route::post('/admin/promociones/update', [PromocionesController::class, 'update'])->name('admin.promociones.update');
Route::get('/admin/promociones/{id}/delete', [PromocionesController::class, 'delete'])->name('admin.promociones.delete');

/** Blogs */

Route::get('/admin/documentacion', [BlogsController::class, 'index'])->name('admin.documentacion.index');

Route::get('/admin/documentacion/{id}/detail', [BlogsController::class, 'detail'])->name('admin.documentacion.detail');



Route::get('/admin/blogs', [BlogsController::class, 'index'])->name('admin.blogs.index');



Route::get('/admin/blogs/add', [BlogsController::class, 'add'])->name('admin.blogs.add');
Route::post('/admin/blogs/store', [BlogsController::class, 'store'])->name('admin.blogs.store');
Route::post('/admin/blogs/uploadimg/{id}', [BlogsController::class, 'uploadimg'])->name('admin.blogs.uploadimg');
Route::get('/admin/blogs/{id}/edit', [BlogsController::class, 'edit'])->name('admin.blogs.edit');

Route::get('/admin/blogs/{id}/detail', [BlogsController::class, 'detail'])->name('admin.blogs.detail');

Route::post('/admin/blogs/update', [BlogsController::class, 'update'])->name('admin.blogs.update');
Route::get('/admin/blogs/{id}/delete', [BlogsController::class, 'delete'])->name('admin.blogs.delete');

/** Videos */

Route::get('/admin/videos', [VideosController::class, 'index'])->name('admin.videos.index');
Route::get('/admin/videos/add', [VideosController::class, 'add'])->name('admin.videos.add');
Route::post('/admin/videos/store', [VideosController::class, 'store'])->name('admin.videos.store');
Route::post('/admin/videos/uploadimg/{id}', [VideosController::class, 'uploadimg'])->name('admin.videos.uploadimg');
Route::get('/admin/videos/{id}/edit', [VideosController::class, 'edit'])->name('admin.videos.edit');
Route::post('/admin/videos/update', [VideosController::class, 'update'])->name('admin.videos.update');
Route::get('/admin/videos/{id}/delete', [VideosController::class, 'delete'])->name('admin.videos.delete');

/** Configuracion */

Route::get('/admin/configuracion', [ConfiguracionController::class, 'index'])->name('admin.configuracion.index');
Route::get('/admin/configuracion/add', [ConfiguracionController::class, 'add'])->name('admin.configuracion.add');
Route::post('/admin/configuracion/store', [ConfiguracionController::class, 'store'])->name('admin.configuracion.store');
Route::post('/admin/configuracion/uploadimg/{id}', [ConfiguracionController::class, 'uploadimg'])->name('admin.configuracion.uploadimg');
Route::get('/admin/configuracion/{id}/edit', [ConfiguracionController::class, 'edit'])->name('admin.configuracion.edit');
Route::post('/admin/configuracion/update', [ConfiguracionController::class, 'update'])->name('admin.configuracion.update');
Route::get('/admin/configuracion/{id}/delete', [ConfiguracionController::class, 'delete'])->name('admin.configuracion.delete');

/** Contratos  */


Route::get('/admin/contratos',[ContratosController::class, 'index'])->name('admin.contratos.index');

Route::get('/admin/contratos/{id}/archivo',[ContratosController::class, 'archivo'])->name('admin.contratos.archivo');

Route::get('/admin/contratos/inactivos',[ContratosController::class, 'inactivos'])->name('admin.contratos.inactivos');
Route::get('/admin/contratos/activos',[ContratosController::class, 'activos'])->name('admin.contratos.activos');
Route::get('/admin/contratos/compras',[ContratosController::class, 'compras'])->name('admin.contratos.compras');
Route::get('/admin/contratos/adjudicados',[ContratosController::class, 'adjudicados'])->name('admin.contratos.adjudicados');
Route::get('/admin/contratos/retirados',[ContratosController::class, 'retirados'])->name('admin.contratos.retirados');

Route::get(
    '/admin/contratos/add',
    [ContratosController::class, 'add']
    )->name('admin.contratos.add');

Route::post(
    '/admin/contratos',
    [ContratosController::class, 'store']
    )->name('admin.contratos.store');

Route::post(
    '/admin/contratos',
    [ContratosController::class, 'estatus']
    )->name('admin.contratos.estatus');

Route::get(
    '/admin/contratos/{id}/edit',
    [ContratosController::class, 'edit']
)->name('admin.contratos.edit');


Route::post(
    '/admin/contratos/{id}/update',
    [ContratosController::class, 'update']
    )->name('admin.contratos.update');

Route::post(
        '/admin/contratos/actualizar',
        [ContratosController::class, 'actualizar']
        )->name('admin.contratos.actualizar');


Route::get(
    '/admin/contratos_categorias/{id}/delete',
    [ContratosController::class,'delete']
    )->name('admin.contratos_categorias.delete');


/** Contratos Categorias */

Route::get(
    '/admin/contratos_categorias',
    [ContratosCategoriasController::class, 'index']
    )->name('admin.contratos_categorias.index');

Route::get(
    '/admin/contratos_categorias/add',
    [ContratosCategoriasController::class, 'add']
    )->name('admin.contratos_categorias.add');

Route::post(
    '/admin/contratos_categorias',
    [ContratosCategoriasController::class, 'store']
    )->name('admin.contratos_categorias.store');

Route::get(
    '/admin/contratos_categorias/{id}/edit',
    [ContratosCategoriasController::class, 'edit']
)->name('admin.contratos_categorias.edit');


Route::post(
    '/admin/contratos_categorias/{id}/update',
    [ContratosCategoriasController::class, 'update']
    )->name('admin.contratos_categorias.update');


Route::get(
    '/admin/contratos_categorias/{id}/delete',
    [ContratosCategoriasController::class,'delete']
    )->name('admin.contratos_categorias.delete');

/** Formas pago */

Route::get(
    '/admin/formas_pago',
    [FormaspagoController::class, 'index']
    )->name('admin.formas_pago.index');

Route::get(
    '/admin/formas_pago/add',
    [FormaspagoController::class, 'add']
    )->name('admin.formas_pago.add');

Route::post(
    '/admin/formas_pago',
    [FormaspagoController::class, 'store']
    )->name('admin.formas_pago.store');

Route::get(
    '/admin/formas_pago/{id}/edit',
    [FormaspagoController::class, 'edit']
)->name('admin.formas_pago.edit');


Route::post(
    '/admin/formas_pago/{id}/update',
    [FormaspagoController::class, 'update']
    )->name('admin.formas_pago.update');


Route::get(
    '/admin/formas_pago/{id}/delete',
    [FormaspagoController::class,'delete']
    )->name('admin.formas_pago.delete');


    /** Roles */

Route::get(
    '/admin/roles',
    [RolesController::class, 'index']
    )->name('admin.roles.index');

Route::get(
    '/admin/roles/add',
    [RolesController::class, 'add']
    )->name('admin.roles.add');

Route::post(
    '/admin/roles',
    [RolesController::class, 'store']
    )->name('admin.roles.store');

Route::get(
    '/admin/roles/{id}/edit',
    [RolesController::class, 'edit']
)->name('admin.roles.edit');


Route::post(
    '/admin/roles/{id}/update',
    [RolesController::class, 'update']
    )->name('admin.roles.update');


Route::get(
    '/admin/roles/{id}/delete',
    [RolesController::class,'delete']
    )->name('admin.roles.delete');





    /** Users */

Route::get(
    '/admin/users',
    [UsersController::class, 'index']
    )->name('admin.users.index');

Route::get(
    '/admin/users/add',
    [UsersController::class, 'add']
    )->name('admin.users.add');

Route::post(
    '/admin/users',
    [UsersController::class, 'store']
    )->name('admin.users.store');

Route::get(
    '/admin/users/{id}/edit',
    [UsersController::class, 'edit']
)->name('admin.users.edit');


Route::post(
    '/admin/users/{id}/update',
    [UsersController::class, 'update']
    )->name('admin.users.update');


Route::get(
    '/admin/users/{id}/delete',
    [UsersController::class,'delete']
    )->name('admin.users.delete');


/** Contratos Tipos */

Route::get(
    '/admin/contratos_tipos',
    [ContratosTiposController::class, 'index']
    )->name('admin.contratos_tipos.index');

Route::get(
    '/admin/contratos_tipos/add',
    [ContratosTiposController::class, 'add']
    )->name('admin.contratos_tipos.add');

Route::post(
    '/admin/contratos_tipos',
    [ContratosTiposController::class, 'store']
    )->name('admin.contratos_tipos.store');

Route::get(
    '/admin/contratos_tipos/{id}/edit',
    [ContratosTiposController::class, 'edit']
)->name('admin.contratos_tipos.edit');


Route::post(
    '/admin/contratos_tipos/{id}/update',
    [ContratosTiposController::class, 'update']
    )->name('admin.contratos_tipos.update');


Route::get(
    '/admin/contratos_tipos/{id}/delete',
    [ContratosTiposController::class,'delete']
    )->name('admin.contratos_tipos.delete');


/**Contratos Grupos */
    
Route::get(
    '/admin/contratos_grupos',
    [ContratosGruposController::class, 'index']
    )->name('admin.contratos_grupos.index');

Route::get(
    '/admin/contratos_grupos/add',
    [ContratosGruposController::class, 'add']
    )->name('admin.contratos_grupos.add');

Route::post(
    '/admin/contratos_grupos',
    [ContratosGruposController::class, 'store']
    )->name('admin.contratos_grupos.store');

Route::post(
    '/admin/contratos_grupos',
    [ContratosGruposController::class, 'save']
    )->name('admin.contratos_grupos.save');

Route::get(
    '/admin/contratos_grupos/{id}/edit',
    [ContratosGruposController::class, 'edit']
)->name('admin.contratos_grupos.edit');


Route::post(
    '/admin/contratos_grupos/{id}/update',
    [ContratosGruposController::class, 'update']
    )->name('admin.contratos_grupos.update');


Route::get(
    '/admin/contratos_grupos/{id}/delete',
    [ContratosGruposController::class,'delete']
    )->name('admin.contratos_grupos.delete');

Route::get(
    '/admin/contratos_grupos/{id}/detail',
    [ContratosGruposController::class, 'detail']
    )->name('admin.contratos_grupos.detail');



    /** Dolar */

Route::get(
    '/admin/dolar',
    [DolarController::class, 'index']
    )->name('admin.dolar.index');

Route::get(
    '/admin/dolar/add',
    [DolarController::class, 'add']
    )->name('admin.dolar.add');

Route::post(
    '/admin/dolar',
    [DolarController::class, 'store']
    )->name('admin.dolar.store');

Route::get(
    '/admin/dolar/{id}/edit',
    [DolarController::class, 'edit']
)->name('admin.dolar.edit');


Route::post(
    '/admin/dolar/{id}/update',
    [DolarController::class, 'update']
    )->name('admin.dolar.update');


Route::get(
    '/admin/dolar/{id}/delete',
    [DolarController::class,'delete']
    )->name('admin.dolar.delete');


    /** Reporte */

    Route::get(
        '/admin/reporte/diario',
        [ReporteController::class, 'diario']
        )->name('admin.reporte.diario');
    
    
    
    Route::post(
        '/admin/reporte/diario',
        [ReporteController::class, 'exportDiario']
        )->name('admin.reporte.exportdiario');

Route::get(
    '/admin/reporte/clientes',
    [ReporteController::class, 'clientes']
    )->name('admin.reporte.clientes');



Route::post(
    '/admin/reporte/clientes',
    [ReporteController::class, 'exportClientes']
    )->name('admin.reporte.exportclientes');



Route::get(
    '/admin/reporte/contratos',
    [ReporteController::class, 'contratos']
    )->name('admin.reporte.contratos');



Route::post(
    '/admin/reporte/contratos',
    [ReporteController::class, 'exportContratos']
    )->name('admin.reporte.exportcontratos');

Route::get(
    '/admin/reporte/pagos',
    [ReporteController::class, 'pagos']
    )->name('admin.reporte.pagos');



Route::post(
    '/admin/reporte/pagos',
    [ReporteController::class, 'exportPagos']
    )->name('admin.reporte.exportpagos');

Route::get(
    '/admin/reporte/abonos',
    [ReporteController::class, 'abonos']
    )->name('admin.reporte.abonos');



Route::post(
    '/admin/reporte/abonos',
    [ReporteController::class, 'exportAbonos']
    )->name('admin.reporte.exportabonos');

    /** Auditoria */

    Route::get(
        '/admin/auditoria',
        [AuditoriaController::class, 'index']
        )->name('admin.auditoria.index');



    /** Abonos */
Route::get(
    '/admin/abonos',
    [AbonosController::class, 'index']
    )->name('admin.abonos.index');

    Route::get(
        '/admin/abonos/espera',
        [AbonosController::class, 'espera']
        )->name('admin.abonos.espera');

        Route::get(
            '/admin/abonos/aprobados',
            [AbonosController::class, 'aprobados']
            )->name('admin.abonos.aprobados');

            Route::get(
                '/admin/abonos/rechazados',
                [AbonosController::class, 'rechazados']
                )->name('admin.abonos.rechazados');



    Route::get(
        '/admin/archivo/{id}',
        [AbonosController::class, 'archivo']
        )->name('admin.abonos.archivo');
    

Route::get(
    '/admin/abonos/{id_cliente}/add',
    [AbonosController::class, 'add']
    )->name('admin.abonos.add');

Route::post(
    '/admin/abonos',
    [AbonosController::class, 'store']
    )->name('admin.abonos.store');

Route::get(
    '/admin/abonos/{id}/edit',
    [AbonosController::class, 'edit']
)->name('admin.abonos.edit');


Route::post(
    '/admin/abonos/{id}/update',
    [AbonosController::class, 'update']
    )->name('admin.abonos.update');

Route::post(
    '/admin/abonos/{id}/updatetasa',
    [AbonosController::class, 'updateTasa']
    )->name('admin.abonos.updatetasa');

Route::post(
    '/admin/abonos/status',
    [AbonosController::class, 'status']
    )->name('admin.abonos.status');


Route::get(
    '/admin/abonos/{id}/delete',
    [AbonosController::class,'delete']
    )->name('admin.abonos.delete');

Route::get(
        '/admin/abonos/{id}/detail',
        [AbonosController::class, 'detail']
        )->name('admin.abonos.detail');


 /** Notificaciones */

 Route::get(
    '/admin/notificaciones',
    [NotificacionesController::class, 'index']
    )->name('admin.notificaciones.index');

 Route::get(
    '/admin/notificaciones/list',
    [NotificacionesController::class, 'list']
    )->name('admin.notificaciones.list');

Route::post(
    '/admin/notificaciones/update',
    [NotificacionesController::class, 'update']
    )->name('admin.notificaciones.update');
    
Route::post(
    '/admin/notificaciones/updateall',
    [NotificacionesController::class, 'updateall']
    )->name('admin.notificaciones.updateall');

    Route::get(
        '/admin/notificaciones/updateall',
        [NotificacionesController::class, 'updateall']
        )->name('admin.notificaciones.updateall');


 /** pagos */

 Route::get(
    '/admin/pagos/{id}',
    [PagosController::class, 'archivo']
    )->name('admin.pagos.archivo');

 Route::post(
    '/admin/pagos/cuota',
    [PagosController::class, 'cuota']
    )->name('admin.pagos.cuota');

Route::post(
    '/admin/pagos/procesar',
    [PagosController::class, 'procesar']
    )->name('admin.pagos.procesar');


 Route::get(
    '/admin/pagos',
    [PagosController::class, 'index']
    )->name('admin.pagos.index');

Route::get(
    '/admin/pagos/add',
    [PagosController::class, 'add']
    )->name('admin.pagos.add');

Route::post(
    '/admin/pagos',
    [PagosController::class, 'store']
    )->name('admin.pagos.store');

Route::get(
    '/admin/pagos/{id}/edit',
    [PagosController::class, 'edit']
)->name('admin.pagos.edit');

Route::post(
    '/admin/pagos/{id}/update',
    [PagosController::class, 'update']
    )->name('admin.pagos.update');

Route::post(
    '/admin/pagos/status',
    [PagosController::class, 'status']
    )->name('admin.pagos.status');


Route::get(
    '/admin/pagos/{id}/delete',
    [PagosController::class,'delete']
    )->name('admin.pagos.delete');

Route::get(
    '/admin/pagos/{id}/detail',
    [PagosController::class, 'detail']
    )->name('admin.pagos.detail');


/** Clientes */

Route::post('/admin/clientes/uploadimg/{id}', [ClientesController::class, 'uploadimg'])
->name('admin.clientes.uploadimg');

Route::post('/admin/clientes/updatePhoto', [ClientesController::class, 'updatePhoto'])
->name('admin.clientes.updatePhoto');


Route::get( '/admin/clientes',
    [ClientesController::class, 'index']
    )->name('admin.clientes.index');

Route::get(
    '/admin/clientes/morosos',
    [ClientesController::class, 'morosos']
    )->name('admin.clientes.morosos');

Route::get(
        '/admin/clientes/{id}/detalle',
        [ClientesController::class, 'getClienteDetalle']
        )->name('admin.clientes.detalle');

Route::get(
    '/admin/clientes/add',
    [ClientesController::class, 'add']
    )->name('admin.clientes.add');

Route::post(
    '/admin/clientes/store',
    [ClientesController::class, 'store']
    )->name('admin.clientes.store');

Route::get(
    '/admin/clientes/{id}/edit',
    [ClientesController::class, 'edit']
    )->name('admin.clientes.edit');

Route::post(
    '/admin/clientes/update',
    [ClientesController::class, 'update']
    )->name('admin.clientes.update');

Route::get(
    '/admin/clientes/{id}/delete',
    [ClientesController::class, 'delete']
    )->name('admin.clientes.delete');

Route::get(
    '/admin/clientes/{id}/detail',
    [ClientesController::class, 'detail']
    )->name('admin.clientes.detail');

Route::get(
    '/admin/clientes/{id}/contract',
    [ClientesController::class, 'contract']
    )->name('admin.clientes.contract');

Route::post(
    '/admin/clientes/{id}/contract',
    [ClientesController::class, 'postcontract']
    )->name('admin.clientes.postcontract');




/*** Agencia de Viajes  */


/** Destinos  */


    Route::get(
        '/admin/destinos',
        [DestinosController::class, 'index']
        )->name('admin.destinos.index');
    
    Route::get(
        '/admin/destinos/add',
        [DestinosController::class, 'add']
        )->name('admin.destinos.add');
    
    Route::post(
        '/admin/destinos',
        [DestinosController::class, 'store']
        )->name('admin.destinos.store');
    
    Route::get(
        '/admin/destinos/{id}/edit',
        [DestinosController::class, 'edit']
    )->name('admin.destinos.edit');


    Route::get(
        '/admin/destinos/{id}/duplicar',
        [DestinosController::class, 'duplicar']
    )->name('admin.destinos.duplicar');
    
    
    Route::post(
        '/admin/destinos/{id}/update',
        [DestinosController::class, 'update']
        )->name('admin.destinos.update');
    
    
    Route::get(
        '/admin/destinos/{id}/delete',
        [DestinosController::class,'delete']
        )->name('admin.destinos.delete');


/** Promociones   */


Route::get(
    '/admin/promociones',
    [PromocionesController::class, 'index']
    )->name('admin.promociones.index');

Route::get(
    '/admin/promociones/add',
    [PromocionesController::class, 'add']
    )->name('admin.promociones.add');

Route::post(
    '/admin/promociones',
    [PromocionesController::class, 'store']
    )->name('admin.promociones.store');

Route::post(
    '/admin/promociones/itinerario',
    [PromocionesController::class, 'itinerario']
    )->name('admin.promociones.itinerario');

    Route::post(
        '/admin/promociones/deleteitinerario',
        [PromocionesController::class, 'deleteitinerario']
        )->name('admin.promociones.deleteitinerario');

Route::get(
    '/admin/promociones/{id}/edit',
    [PromocionesController::class, 'edit']
)->name('admin.promociones.edit');


Route::get(
    '/admin/promociones/{id}/duplicar',
    [PromocionesController::class, 'duplicar']
)->name('admin.promociones.duplicar');


Route::post(
    '/admin/promociones/{id}/update',
    [PromocionesController::class, 'update']
    )->name('admin.promociones.update');


Route::get(
    '/admin/promociones/{id}/delete',
    [PromocionesController::class,'delete']
    )->name('admin.promociones.delete');
        
/** Paquetes   */


    Route::get(
        '/admin/paquetes',
        [PaquetesController::class, 'index']
        )->name('admin.paquetes.index');
    
    Route::get(
        '/admin/paquetes/add',
        [PaquetesController::class, 'add']
        )->name('admin.paquetes.add');
    
    Route::post(
        '/admin/paquetes',
        [PaquetesController::class, 'store']
        )->name('admin.paquetes.store');
    
    Route::post(
        '/admin/paquetes/itinerario',
        [PaquetesController::class, 'itinerario']
        )->name('admin.paquetes.itinerario');

        Route::post(
            '/admin/paquetes/deleteitinerario',
            [PaquetesController::class, 'deleteitinerario']
            )->name('admin.paquetes.deleteitinerario');
    
    Route::get(
        '/admin/paquetes/{id}/edit',
        [PaquetesController::class, 'edit']
    )->name('admin.paquetes.edit');


    Route::get(
        '/admin/paquetes/{id}/duplicar',
        [PaquetesController::class, 'duplicar']
    )->name('admin.paquetes.duplicar');
    
    
    Route::post(
        '/admin/paquetes/{id}/update',
        [PaquetesController::class, 'update']
        )->name('admin.paquetes.update');
    
    
    Route::get(
        '/admin/paquetes/{id}/delete',
        [PaquetesController::class,'delete']
        )->name('admin.paquetes.delete');





/** Imagenes  */


Route::get(
    '/admin/imagenes',
    [ImagenesController::class, 'index']
    )->name('admin.imagenes.index');

Route::get(
    '/admin/imagenes/add',
    [ImagenesController::class, 'add']
    )->name('admin.imagenes.add');

Route::post(
    '/admin/imagenes/{id}/{type}',
    [ImagenesController::class, 'store']
    )->name('admin.imagenes.store');

Route::get(
    '/admin/imagenes/{id}/edit',
    [ImagenesController::class, 'edit']
)->name('admin.imagenes.edit');


Route::post(
    '/admin/imagenes/{id}/update',
    [ImagenesController::class, 'update']
    )->name('admin.imagenes.update');


Route::get(
    '/admin/imagenes/{id}/delete',
    [ImagenesController::class,'delete']
    )->name('admin.imagenes.delete');

    /** Contacto */

    Route::get(
        '/admin/contactos',
        [ContactosController::class, 'index']
        )->name('admin.contactos.index');