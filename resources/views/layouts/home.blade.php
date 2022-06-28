<?php
$ver_js = \App\Helpers\General::get_version_js();
$ver_css = \App\Helpers\General::get_version_css();
?>
<!DOCTYPE html>
<html lang="en_US" data-currency="VND">
<head>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta charset="utf-8">
    <title> @yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="@yield('description')">
    <meta name="robots" content="index, follow">
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <meta property="og:title" content="@yield('title')">
    <meta property="og:type" content="website">
    <meta property="og:image" content="img/social_share_room.jpg">
    <meta property="og:description" content="@yield('description')">
    <link rel="icon" type="image/vnd.microsoft.icon" href="favicon.ico?v=33">
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    
    @loadLocalCSS(/html/assets/css/home.css)
    @loadLocalCSS(/html/assets/css/sale/footer.css)
    @loadLocalCSS(/html/assets/css/sale/contactUs.css)
    @yield('custom_styles')
    @loadLocalJS(/html/assets/js/aight.2.1.0.js)
    <script>
        trackingVendors = [];
        trackingVendors.push({
            name: "trackingVendorGoogle",
            enabled: 1
        });
        trackingVendors.push({
            name: "trackingVendorDynamicYield",
            enabled: 1
        });
    </script>
</head><!--/head-->
<body id="index" class="index-index theme-silver page-signin" data-modules="trackingListener home" data-countrycode="">
    <!--/header-->
    @include("layouts.partials.header")

    <!--/content-->
    <div class="contents">
        @yield('content')
    </div>

    <!--/footer-->
    @include("layouts.partials.footer")
    @loadLocalJS(/html/assets/js/home.js)
    @loadLocalJS(/html/assets/js/frontend.js)
    @loadLocalJS(/html/assets/js/contactUs.js)
    @yield('custom_scripts')
    <div id="mobile_menu_overlay"></div>
</body>
</html>
