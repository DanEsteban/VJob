<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For detailed instructions you can look the title section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'title' => 'VJOB',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For detailed instructions you can look the favicon section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_ico_only' => true,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Google Fonts
    |--------------------------------------------------------------------------
    |
    | Here you can allow or not the use of external google fonts. Disabling the
    | google fonts may be useful if your admin panel internet access is
    | restricted somehow.
    |
    | For detailed instructions you can look the google fonts section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'google_fonts' => [
        'allowed' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For detailed instructions you can look the logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'logo' => 'VISUAL<b>JOB</b>',
    'logo_img' => '',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => 'vendor/adminlte/dist/img/Logo-web-1.png',
    'logo_img_xl_class' => 'brand-image-xl',
    'logo_img_alt' => 'Admin Logo',

    /*
    |--------------------------------------------------------------------------
    | Authentication Logo
    |--------------------------------------------------------------------------
    |
    | Here you can setup an alternative logo to use on your login and register
    | screens. When disabled, the admin panel logo will be used instead.
    |
    | For detailed instructions you can look the auth logo section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'auth_logo' => [
        'enabled' => false,
        'img' => [
            'path' => 'vendor/adminlte/dist/img/Logo-web-1.png',
            'alt' => 'Auth Logo',
            'class' => '',
            'width' => 50,
            'height' => 50,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Preloader Animation
    |--------------------------------------------------------------------------
    |
    | Here you can change the preloader animation configuration.
    |
    | For detailed instructions you can look the preloader section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'preloader' => [
        'enabled' => false,
        'img' => [
            'path' => 'vendor/adminlte/dist/img/Logo-web-1.png',
            'alt' => 'AdminLTE Preloader Image',
            'effect' => 'animation__shake',
            'width' => 280,
            'height' => 100,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For detailed instructions you can look the user menu section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => true,
    'usermenu_header_class' => 'bg-danger',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For detailed instructions you can look the layout section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => true,
    'layout_fixed_navbar' => true,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the authentication views.
    |
    | For detailed instructions you can look the auth classes section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For detailed instructions you can look the admin panel classes here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'classes_body' => '',
    'classes_brand' => 'bg-white',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-danger elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For detailed instructions you can look the sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For detailed instructions you can look the right sidebar section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Layout-and-Styling-Configuration
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'light',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For detailed instructions you can look the urls section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Basic-Configuration
    |
    */

    'use_route_url' => false,
    'dashboard_url' => '/dashboard',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Mix
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Mix option for the admin panel.
    |
    | For detailed instructions you can look the laravel mix section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'enabled_laravel_mix' => false,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'menu' => [
        // Navbar items:
        [
            'type'         => 'navbar-search',
            'text'         => 'search',
            'topnav_right' => true,
        ],
        [
            'type'         => 'fullscreen-widget',
            'topnav_right' => true,
        ],
        [
            'type'         => 'navbar-notification',
            'id'           => 'my-notification',
            'icon'         => 'fas fa-bell',
            'url'          => '#',
            'topnav_right' => true,
            'dropdown_mode'   => true,
            'dropdown_flabel' => 'All notifications',
            'update_cfg'   => [
                'url' => '#',
                'period' => 30,
            ],
        ],
        [
            'text'        => 'Proformas',
            'url'         => '/orders/create',
            'classes'     => 'text-white',
            'icon'        => 'fa-solid fa-file-contract',
            'can'         => 'estimate.create'
        ],
        [
            'text'        => 'Facturas',
            'url'         => '/invoices/create',
            'classes'     => 'text-white',
            'icon'        => 'fa-solid fa-file-invoice-dollar',
            'can'         => 'invoice.create'
        ],
        [
            'text'        => 'Tickets',
            'url'         => '/ticket',
            'classes'     => 'text-white',
            'icon'        => 'fa-solid fa-spa'
        ],
        [
            'text'        => 'Movimientos',
            'url'         => '/movements',
            'classes'     => 'text-white',
            'icon'        => 'fa fa-retweet'
        ],
        [
            'text'        => 'Compras',
            'url'         => '/bills/create',
            'classes'     => 'text-white',
            'icon'        => 'fa-solid fa-truck-ramp-box',
            'can'         => 'bill.index'
        ],
        [
            'text'        => 'Cobros',
            'url'         => '/payments',
            'classes'     => 'text-white',
            'icon'        => 'fa-solid fa-hand-holding-dollar',
            'can'         => 'payment.index'
        ],
        [
            'text'        => 'Códigos de Barras',
            'url'         => '/labels',
            'id'          => 'label-id',
            'classes'     => 'text-white',
            'icon'        => 'fa-solid fa-barcode',
            'can'         => 'print.index'
        ],
        ['header' => 'CENTROS DE OPERACIONES',
            'can'   => [
                'estimate.index',
                'invoice.index',
                'customer.index',
                'vendor.index',
                'inventory.index',
                'report.index'
            ]
        ],
        [
            'text'        => 'Centro de Proformas',
            'url'         => '/orders',
            'classes'     => 'text-white',
            'icon'        => 'fa-solid fa-list-check',
            'can'         => 'estimate.index'
        ],
        [
            'text'        => 'Centro de Facturas',
            'url'         => '/invoices',
            'id'          => 'invoice-id',
            'classes'     => 'text-white',
            'icon'        => 'fa-solid fa-clipboard-list',
            'can'         => 'invoice.index'
        ],
        [
            'text'        => 'Centro de Compras',
            'url'         => '/bills',
            'id'          => 'bill-id',
            'classes'     => 'text-white',
            'icon'        => 'fa-solid fa-boxes-packing',
            'can'         => 'bill.index'
        ],
        [
            'text'        => 'Centro de Pedidos',
            'url'         => '/vendors/access/api',
            'classes'     => 'text-white',
            'icon'        => 'fa-solid fa-cart-flatbed',       
        ],
        [
            'text'        => 'Clientes',
            'url'         => '/customers',
            'id'          => 'customer-id',
            'classes'     => 'text-white',
            'icon'        => 'fa fa-address-book',
            'can'         => 'customer.index'
        ],
        [
            'text'        => 'Proveedores',
            'url'         => '/vendors',
            'id'          => 'vendor-id',
            'classes'     => 'text-white',
            'icon'        => 'fa solid fa-handshake',
            'can'         => 'vendor.index'
        ],
        [
            'text'        => 'Inventarios',
            'url'         => '/inventories',
            'id'          => 'inventory-id',
            'classes'     => 'text-white',
            'icon'        => 'fa fa-boxes',
            'can'         => 'inventory.index'
        ],
        [
            'text'    => 'Reportes',
            'icon'    => 'fa-solid fa-file-lines',
            //'can'     => 'report.index',
            //'can'     => 'report.index',
            'submenu' => [
                [
                    'text'    => 'Ventas',
                    'url'     => '/reports/sales',
                    'url'     => '/reports/sales',
                    'icon'    => 'fa-solid fa-file-lines',
                    'shift'   => 'ml-3',
                ],
                [
                    'text'    => 'Saldos de Clientes',
                    'url'     => '/reports/customer',
                    'text'    => 'Customer Balance',
                    'url'     => '/reports/customer',
                    'icon'    => 'fa-solid fa-file-lines',
                    'shift'   => 'ml-3',
                ],
                [
                    'text'    => 'Saldos de Productos',
                    'url'     => '/reports/product',
                    'text'    => 'Product Balance',
                    'url'     => '/reports/product',
                    'icon'    => 'fa-solid fa-file-lines',
                    'shift'   => 'ml-3',
                ],
                [
                    'text'    => 'Saldos de Caja',
                    'url'     => '/cashier',
                    'icon'    => 'fa-solid fa-file-lines',
                    'shift'   => 'ml-3',
                ],
                [
                    'text'    => 'Kardex',
                    'url'     => '/kardex',
                    'icon'    => 'fa-solid fa-file-lines',
                    'shift'   => 'ml-3',
                ]
            ],
        ],
        ['header' => 'OPCIONES DE ADMINISTRADOR',
            'can' => [
                'users.index',
                'roles.index',
                'options.index',
                'warehouse.index',
            ]
        ],
        [
            'text' => 'Usuarios',
            'url'  => '/users',
            'id'   => 'users-id',
            'classes'  => 'text-white',
            'icon' => 'fas fa-fw fa-user',
            'can'  => 'users.index'
        ],
        [
            'text' => 'Roles',
            'url'  => '/roles',
            'id'   => 'roles-id',
            'classes'  => 'text-white',
            'icon' => 'fas fa-users-cog fa-fw',
            'can'  => 'roles.index'
        ],
        [
            'text' => 'Opciones',
            'url'  => '/options',
            'id'   => 'options-id',
            'classes'  => 'text-white',
            'icon' => 'fa-solid fa-microchip',
            'can'  => 'options.index'
        ],
        [
            'text' => 'Bodegas',
            'url'  => '/warehouses',
            'id'   => 'warehouse-id',
            'classes'  => 'text-white',
            'icon' => 'fa fa-warehouse',
            //'can'  => 'warehouses.index'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For detailed instructions you can look the menu filters section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Menu-Configuration
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For detailed instructions you can look the plugins section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Plugins-Configuration
    |
    */

    'plugins' => [
        'Datatables' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js',
                    
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@11',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
        'AwesomeFont' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//kit.fontawesome.com/08def23c06.js',
                ],
            ],
        ],
        'BootstrapSelect' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css',
                ],
            ],
        ],
        'Ion.RangeSlider' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/js/ion.rangeSlider.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/css/ion.rangeSlider.min.css',
                ],
            ],
        ],
        'Select.DataTable' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/select/1.5.0/js/dataTables.select.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/select/1.5.0/css/select.dataTables.min.css',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    |
    | Here we change the IFrame mode configuration. Note these changes will
    | only apply to the view that extends and enable the IFrame mode.
    |
    | For detailed instructions you can look the iframe mode section here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/IFrame-Mode-Configuration
    |
    */

    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Livewire support.
    |
    | For detailed instructions you can look the livewire here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/wiki/Other-Configuration
    |
    */

    'livewire' => false,
];
