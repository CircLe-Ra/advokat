<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="NEXTINNOVATION.">
    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="manifest" href="favicon/site.webmanifest">

    <title>KAI AND RIEL - LAW FIRM</title>
    <link href="assets/css/themify-icons.css" rel="stylesheet">
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/flaticon.css" rel="stylesheet">
    <link href="assets/css/magnific-popup.css" rel="stylesheet">
    <link href="assets/css/animate.css" rel="stylesheet">
    <link href="assets/css/owl.carousel.css" rel="stylesheet">
    <link href="assets/css/owl.theme.css" rel="stylesheet">
    <link href="assets/css/slick.css" rel="stylesheet">
    <link href="assets/css/slick-theme.css" rel="stylesheet">
    <link href="assets/css/swiper.min.css" rel="stylesheet">
    <link href="assets/css/owl.transitions.css" rel="stylesheet">
    <link href="assets/css/jquery.fancybox.css" rel="stylesheet">
    <link href="assets/css/odometer-theme-default.css" rel="stylesheet">
    <link href="assets/css/global.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
</head>

<body>
<div class="page-wrapper">
    <div class="preloader">
        <div class="vertical-centered-box">
            <div class="content">
                <div class="loader-circle"></div>
                <div class="loader-line-mask">
                    <div class="loader-line"></div>
                </div>
                <img src="img/logo.png" alt="" width="50">
            </div>
        </div>
    </div>

    <header id="home" class=" relative z-[111] mx-auto">
        <div class="bg-[#272c3f] pt-[5px]">
            <div class="wraper relative before:absolute before:w-full before:h-[1px] before:bg-[rgba(255,255,255,.07)] before:bottom-0 before:left-0 before:content-['']">
                <div class="grid grid-cols-12">
                    <div class="col-span-10 md:col-span-12">
                        <ul class="text-left md:text-center">
                            <li class=" text-white inline-block p-[15px]  col:pr-0 pl-0 col:pb-[0]"><i class="fa fa-map-marker pr-[10px] col:pt-[0] text-[#cbbc99] text-[20px]" aria-hidden="true"></i>121 King Street, Merauke , Indonesia </li>
                            <li class="relative text-white inline-block p-[15px]  col:pr-0
                                before:absolute before:content-[''] before:left-0 before:top-[15px] before:w-[1px] before:h-[25px] before:bg-[rgba(255,255,255,.07)]  before:z-10 before:transform before:-translate-x-1/2 md:before:hidden">
                                <i class="fa fa-mobile pr-[10px] text-[#cbbc99] text-[20px]" aria-hidden="true"></i>3164-5456854</li>
                            <li class="relative text-white inline-block p-[15px] col:pr-0  before:absolute before:content-[''] before:left-0 before:top-[15px] before:w-[1px] before:h-[25px] before:bg-[rgba(255,255,255,.07)] md:before:hidden  before:z-10 before:transform before:-translate-x-1/2 "><i class="fa fa-clock-o pr-[10px] text-[#cbbc99] text-[20px]" aria-hidden="true"></i>9AM - PM</li>
                        </ul>
                    </div>
                    <div class="col-span-2 md:col-span-12 mt-[5px]">
                        @if (Route::has('login'))
                            <div class="text-right md:text-center md:mb-[15px]">
                                @auth
                                    <a
                                        href="{{ route('goto') }}"
                                        class="theme-btn md:text-[14px] md:py-[8px] md:px-[22px]"
                                    >
                                        Dashboard
                                    </a>
                                @else
                                    <a
                                        href="{{ route('login') }}"
                                        class="theme-btn md:text-[14px] md:py-[8px] md:px-[22px]"
                                    >
                                        Masuk
                                    </a>

                                    @if (Route::has('register'))
                                        <a
                                            href="{{ route('register') }}"
                                            class="theme-btn md:text-[14px] md:py-[8px] md:px-[22px]"
                                        >
                                            Daftar
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-[#272c3f] relative">
            <div class="wraper">
                <div class="flex items-center justify-between">
                    <!-- mobile-menu -->
                    <div id="dl-menu" class="dl-menuwrapper">
                        <button class="dl-trigger">Open Menu</button>
                        <ul class="dl-menu">
                            <li>
                                <a href="#">Home</a>
                                <ul class="dl-submenu">
                                    <li><a href="index.html">Home style one</a></li>
                                    <li><a href="index-2.html">Home style two</a></li>
                                    <li><a href="index-3.html">Home style three</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="about.html">About</a>
                            </li>
                            <li>
                                <a href="#">Practice Area</a>
                                <ul class="dl-submenu">
                                    <li><a href="practice.html">Practice areas</a></li>
                                    <li><a href="practice-details.html">Practice areas single</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">Cases</a>
                                <ul class="dl-submenu">
                                    <li><a href="case-stadies.html">Cases</a></li>
                                    <li><a href="case-stadies-details.html">Case single</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#">News</a>
                                <ul class="dl-submenu">
                                    <li><a href="blog.html">Blog right sidebar</a></li>
                                    <li><a href="blog-left-sidebar.html">Blog left sidebar</a></li>
                                    <li><a href="blog-fullwidth.html">Blog fullwidth</a></li>
                                    <li>
                                        <a href="#">Blog details</a>
                                        <ul class="dl-submenu">
                                            <li>
                                                <a href="blog-single.html">Blog details right sidebar</a>
                                            </li>
                                            <li>
                                                <a href="blog-single-left-sidebar.html">Blog details left sidebar</a>
                                            </li>
                                            <li>
                                                <a href="blog-single-fullwidth.html">Blog details fullwidth</a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li><a href="#">Pages</a>
                                <ul class="dl-submenu">
                                    <li><a href="Attorneys.html">Attorneys</a></li>
                                    <li><a href="Attorneys-single.html">Attorney Single</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="contact.html">Contact</a>
                            </li>
                        </ul>
                    </div>
                    <div class="logo w-[255px] md:w-[200px] md:mx-auto sm:w-[180px] col:w-[160px]">
                        <a class="text-[45px] col:text-[25px] font-bold flex items-center text-white" href="/">
                            <img class="w-20" src="img/logo.png" alt="">
                        </a>
                    </div>

                    <ul class="md:hidden block lg-[-10px]">
                        <li class="relative inline-block">
                            <a href="#home" class="relative group text-[15px] lg:text-[14px] py-[35px] xl:py-[30px] px-[18px] xl:px-[6px] text-white block uppercase font-base-font font-normal hover:text-[#c0b596] transition-all
                               ">Beranda</a>
                        </li>
                        <li class="relative inline-block">
                            <a href="#lawyer" class="relative group text-[15px] lg:text-[14px] py-[35px] xl:py-[30px] px-[18px] xl:px-[6px] text-white block uppercase font-base-font font-normal hover:text-[#c0b596] transition-all
                               ">Pengacara</a>
                        </li>
                        <li class="relative inline-block">
                            <a href="#vision-mission" class="relative group text-[15px] lg:text-[14px] py-[35px] xl:py-[30px] px-[18px] xl:px-[6px] text-white block uppercase font-base-font font-normal hover:text-[#c0b596] transition-all
                               ">Visi & Misi</a>
                        </li>
                        <li class="relative inline-block">
                            <a href="#about-us" class="relative group text-[15px] lg:text-[14px] py-[35px] xl:py-[30px] px-[18px] xl:px-[6px] text-white block uppercase font-base-font font-normal hover:text-[#c0b596] transition-all
                               ">Tentang Kami</a>
                        </li>
                        <li class="relative inline-block">
                            <a href="#contact" class="relative group text-[15px] lg:text-[14px] py-[35px] xl:py-[30px] px-[18px] xl:px-[6px] text-white block uppercase font-base-font font-normal hover:text-[#c0b596] transition-all
                               ">Kontak Kami</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    {{ $slot }}

</div>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/modernizr.custom.js"></script>
<script src="assets/js/jquery.dlmenu.js"></script>
<script src="assets/js/jquery-plugin-collection.js"></script>
<script src="assets/js/node-index.js"></script>
<script src="assets/js/moving-animation.js"></script>
<script src="assets/js/script.js"></script>
@fluxScripts
</body>
</html>
