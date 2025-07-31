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
                            <li class=" text-white inline-block p-[15px]  col:pr-0 pl-0 col:pb-[0]"><i class="fa fa-map-marker pr-[10px] col:pt-[0] text-[#cbbc99] text-[20px]" aria-hidden="true"></i>Jl. Irian Seringgu Merauke  Papua Selatan - INDONESIA</li>
                            <li class="relative text-white inline-block p-[15px]  col:pr-0
                                before:absolute before:content-[''] before:left-0 before:top-[15px] before:w-[1px] before:h-[25px] before:bg-[rgba(255,255,255,.07)]  before:z-10 before:transform before:-translate-x-1/2 md:before:hidden">
                                <i class="fa fa-mobile pr-[10px] text-[#cbbc99] text-[20px]" aria-hidden="true"></i>+62 82398943737</li>
                            <li class="relative text-white inline-block p-[15px] col:pr-0  before:absolute before:content-[''] before:left-0 before:top-[15px] before:w-[1px] before:h-[25px] before:bg-[rgba(255,255,255,.07)] md:before:hidden  before:z-10 before:transform before:-translate-x-1/2 ">
                                <i class="fa fa-envelope pr-[10px] text-[#cbbc99] text-[15px]" aria-hidden="true"></i>kaitlynmogahai@gmail.com</li>
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
                    <div class="logo w-[255px] md:w-[200px]  sm:w-[180px] col:w-[160px]">
                        <a class="text-[45px] col:text-[25px] font-bold flex items-center text-white" href="/">
                            <img class="" src="img/logo-text.png" alt="">
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

    <footer class="relative bg-[#151a30] z-10">
        <div class="pt-[100px] pb-[90px] md:py-[90px] md:pb-[20px] overflow-hidden relative -z-10">
            <div class="wraper">
                <div class="grid grid-cols-4 md:grid-cols-2 sm:grid-cols-1 gap-3">
                    <div class="w-[420px] xl:w-[355px] lg:w-[297px] pr-[125px] md:mb-[40px] lg:pr-0 relative text-left">
                        <div class="mb-7">
                            <a class="text-[45px] font-bold flex items-center text-white" href="index.html">
                                <img class="" src="img/logo-text.png" alt="">
                        </div>
                        <p class="text-white text-[16px] mb-[10px]">
                            Contrary to popular belief, Lorem Ipsum is not simply random text.
                            It has roots in a piece of classical Latin literature
                        </p>
                        <ul class="overflow-hidden pt-[15px]">
                            <li class="text-white float-left group "><a class="text-white transition-all group-hover:text-[#FFE600]" href="#"><i class="ti-facebook"></i></a></li>
                            <li class="text-white float-left group ml-[15px]"><a class="text-white transition-all group-hover:text-[#c0b596]" href="#"><i class="ti-twitter-alt"></i></a></li>
                            <li class="text-white float-left group ml-[15px]"><a class="text-white transition-all group-hover:text-[#c0b596]" href="#"><i class="ti-linkedin"></i></a></li>
                            <li class="text-white float-left group ml-[15px]"><a class="text-white transition-all group-hover:text-[#c0b596]" href="#"><i class="ti-pinterest"></i></a></li>
                            <li class="text-white float-left group ml-[15px]"><a class="text-white transition-all group-hover:text-[#c0b596]" href="#"><i class="ti-vimeo-alt"></i></a></li>
                        </ul>
                    </div>
                    <div class="w-[200px] md:w-full ml-auto md:mb-[40px] lg:pr-0 relative text-left ">
                        <div class="mb-7">
                            <h3 class="text-[28px] font-medium  font-heading-font text-white capitalize">Quick Link</h3>
                        </div>
                        <ul>
                            <li class="relative mb-[8px] block"><a class=" text-white hover:text-[#c0b596] transition-all" href="index.html">Home</a></li>
                            <li class="relative mb-[8px] block"><a class=" text-white hover:text-[#c0b596] transition-all" href="practice-details.html">Practice Area</a></li>
                            <li class="relative mb-[8px] block"><a class=" text-white hover:text-[#c0b596] transition-all" href="practice-details.html">Recent Case</a></li>
                            <li class="relative mb-[8px] block"><a class=" text-white hover:text-[#c0b596] transition-all" href="Attorneys-single.html">Our Team</a></li>
                            <li class="relative mb-[8px] block"><a class=" text-white hover:text-[#c0b596] transition-all" href="blog.html">Our Blog</a></li>
                        </ul>
                    </div>
                    <div class="pl-[15px] md:pl-[0px] md:mb-[40px] lg:pr-0 relative text-left ">
                        <div class="mb-7">
                            <h3 class="text-[28px] font-medium  font-heading-font text-white capitalize"></h3>
                        </div>

                    </div>
                    <div class=" md:mb-[40px] lg:pr-0 relative text-left ">
                        <div class="mb-7">
                            <h3 class="text-[28px] font-medium  font-heading-font text-white capitalize">Kontak Kami</h3>
                        </div>
                        <ul>
                            <li class="relative mb-[8px] block text-white">Head Office Address</li>
                            <li class="relative mb-[8px] block text-white">Jl. Irian Seringgu - Merauke</li>
                            <li class="relative mb-[8px] block text-white">Papua Selatan - INDONESIA</li>
                            <li class="relative mb-[8px] block text-white">Phone: +62 82248220406</li>
                            <li class="relative mb-[8px] block text-white">Email: kaitlynmogahai@gmail.com</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="wraper">
            <div class=" border-t-1 border-[rgba(192,181,150,.3)] relative">
                <div class="h-[1px] absolute left-[15px] top-0 bg-[#ffffff0d] w-[calc(100%+30px)]"></div>
                <p class="text-center text-white text-[14px] py-[20px]">Privacy Policy | &copy; 2025 KAI AND RIEL. All rights reserved</p>
            </div>
        </div>
    </footer>

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
