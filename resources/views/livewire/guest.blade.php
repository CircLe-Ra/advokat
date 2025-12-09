<?php

use Livewire\Volt\Component;

new
#[\Livewire\Attributes\Layout('components.layouts.app.guest')]
class extends Component {
    //
    public function mount(): void
    {

    }
}; ?>

<div>
    <section class="hero relative h-[790px] lg:h-[500px] col:h-[450px] overflow-hidden z-20">
        <div class="hero-slider">
            <div class="slide  relative h-full bg-cover bg-no-repeat z-10  after:absolute after:content-['']
                    after:w-full after:h-full after:left-0 after:top-0 after:opacity-70 after:z-10">
                <img class="w-full object-cover h-[790px] lg:h-[500px]" src="assets/images/home.png" alt="" >
                <div class="wraper">
{{--                    <div class="slide-caption w-[700px] mt-[245px] lg:mt-[110px] md:mt-[120px] col:mt-[100px] absolute top-0 z-20">--}}
{{--                        <p class="text-white text-[20px] font-light col:text-[18px]">The Most Talented Law Frim</p>--}}
{{--                        <h2 class="text-white text-[60px] leading-[90px] lg:text-[42px] lg:leading-[56px]--}}
{{--                             col:text-[25px] col:leading-[35px] font-normal my-[33px]"><span>We Fight For Your Justice</span> <br> <span>As Like A Friend.</span></h2>--}}
{{--                        <div class="btns">--}}
{{--                            <div class="btn-style">--}}
{{--                                <a href="contact.html" class="bg-[#c0b596] relative cursor-pointer text-[16px]--}}
{{--                                     font-semibold text-white px-[38px] py-[12px]  capitalize inline-block mt-[6px]--}}
{{--                                     transition ease-in-out duration-300 hover:bg-[#d4c291]--}}
{{--                                    col:mb-[5px] col:mt-[15px] col:text-[15px] col:px-[18px] col:py-[8px]--}}
{{--                                    before:absolute before:content-[''] before:w-[85px] before:h-[2px] before:left-[-60px]--}}
{{--                                     before:top-[50%]  before:bg-white before:transform before:translate-y-[-50%]--}}
{{--                                     before:transition-all hover:before:left-[-95px] md:before:hidden">Contact us now</a>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                </div>
            </div>

        </div>
    </section>
    <div class="bg-[#f1f1f1] pt-[50px] pb-[30px]">
        <div class="wraper">
            <div class="grid grid-cols-12 gap-3">
                <div class="col-span-4 md:col-span-6 sm:col-span-12 mb-5">
                    <div class="bg-white flex items-center sm:mx-[30px] col:mx-0">
                        <div class="bg-[#c0b596] h-[100px] col:h-[80px] col:w-[80px] w-[135px] text-center px-[18px] col:p-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 50 50"><path fill="currentColor" d="M.295 27.581a6.457 6.457 0 0 0 12.805 0zM35.182 40.58a1 1 0 0 1-.998 1.003H15.528c-.548 0-1-.45-1-1.003a1 1 0 0 1 1-.993h18.655c.552 0 .999.444.999.993m-20.545 1.514h20.437v2.887H14.637zM36.9 27.581a6.46 6.46 0 0 0 6.402 5.626a6.45 6.45 0 0 0 6.399-5.626zm12.449-2.009l-5.243-7.222h2.803c.682 0 1.231-.559 1.231-1.25c0-.685-.55-1.238-1.231-1.238H32.061a7.35 7.35 0 0 0-5.634-4.732V7.233c0-.693-.556-1.246-1.245-1.246L25.066 6l-.116-.013a1.24 1.24 0 0 0-1.243 1.246v3.895a7.35 7.35 0 0 0-5.632 4.732H3.224c-.677 0-1.229.553-1.229 1.238c0 .692.552 1.25 1.229 1.25h2.675L.655 25.57H0v1.334h13.398V25.57h-.658l-5.242-7.22h12.169c0-.282.031-.559.073-.824c.043-.125.072-.252.072-.383a5.32 5.32 0 0 1 3.895-3.933v13.697h-.052c-.107 5.152-2.558 9.645-6.194 12.17h15.214c-3.637-2.525-6.086-7.018-6.199-12.17h-.048V13.21a5.32 5.32 0 0 1 3.894 3.933q.008.196.075.383c.04.266.065.542.065.824h12.042l-5.244 7.222h-.654v1.334H50v-1.334zm-43.184 0H1.98l4.185-5.765zm1.071 0v-5.765l4.185 5.765zm35.532 0h-4.187l4.187-5.765zm1.066 0v-5.765l4.19 5.765zM7.941 14.124a1.243 1.243 0 0 1-2.485 0c0-.686.558-1.246 1.245-1.246c.684-.001 1.24.56 1.24 1.246m36.604-.066c0 .691-.556 1.239-1.242 1.239a1.234 1.234 0 0 1-1.242-1.239a1.243 1.243 0 1 1 2.484 0"/></svg>
                        </div>
                        <div class="pl-[24px]">
                            <span class="text-[#ada282] text-[16px] font-normal">Buat Janji</span>
                            <h3 class="text-[#333] text-[27px] col:text-[22px] font-medium">Pertemuan</h3>
                        </div>
                    </div>
                </div>
                <div class="col-span-4 md:col-span-6 sm:col-span-12 mb-5">
                    <div class="bg-white flex items-center sm:mx-[30px] col:mx-0">
                        <div class="bg-[#c0b596] h-[100px] col:h-[80px] col:w-[80px] w-[135px] text-center px-[18px] col:p-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path stroke-dasharray="64" stroke-dashoffset="64" d="M8 3c0.5 0 2.5 4.5 2.5 5c0 1 -1.5 2 -2 3c-0.5 1 0.5 2 1.5 3c0.39 0.39 2 2 3 1.5c1 -0.5 2 -2 3 -2c0.5 0 5 2 5 2.5c0 2 -1.5 3.5 -3 4c-1.5 0.5 -2.5 0.5 -4.5 0c-2 -0.5 -3.5 -1 -6 -3.5c-2.5 -2.5 -3 -4 -3.5 -6c-0.5 -2 -0.5 -3 0 -4.5c0.5 -1.5 2 -3 4 -3Z"><animate fill="freeze" attributeName="stroke-dashoffset" dur="0.6s" values="64;0"/><animateTransform id="lineMdPhoneCallLoop0" fill="freeze" attributeName="transform" begin="0.6s;lineMdPhoneCallLoop0.begin+2.7s" dur="0.5s" type="rotate" values="0 12 12;15 12 12;0 12 12;-12 12 12;0 12 12;12 12 12;0 12 12;-15 12 12;0 12 12"/></path><path stroke-dasharray="4" stroke-dashoffset="4" d="M15.76 8.28c-0.5 -0.51 -1.1 -0.93 -1.76 -1.24M15.76 8.28c0.49 0.49 0.9 1.08 1.2 1.72"><animate fill="freeze" attributeName="stroke-dashoffset" begin="lineMdPhoneCallLoop0.begin+0s" dur="2.7s" keyTimes="0;0.111;0.259;0.37;1" values="4;0;0;4;4"/></path><path stroke-dasharray="6" stroke-dashoffset="6" d="M18.67 5.35c-1 -1 -2.26 -1.73 -3.67 -2.1M18.67 5.35c0.99 1 1.72 2.25 2.08 3.65"><animate fill="freeze" attributeName="stroke-dashoffset" begin="lineMdPhoneCallLoop0.begin+0.2s" dur="2.7s" keyTimes="0;0.074;0.185;0.333;0.444;1" values="6;6;0;0;6;6"/></path></g></svg>
                        </div>
                        <div class="pl-[24px]">
                            <span class="text-[#ada282] text-[16px] font-normal">Saran Dari Ahli</span>
                            <h3 class="text-[#333] text-[27px] col:text-[22px] font-medium">Gratis</h3>
                        </div>
                    </div>
                </div>
                <div class="col-span-4 md:col-span-6 sm:col-span-12 mb-5">
                    <div class="bg-white flex items-center sm:mx-[30px] col:mx-0">
                        <div class="bg-[#c0b596] h-[100px] col:h-[80px] col:w-[80px] w-[135px] text-center px-[18px] col:p-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 20 20"><path fill="currentColor" d="M12.5 4.5a2.5 2.5 0 1 1-5 0a2.5 2.5 0 0 1 5 0m5 .5a2 2 0 1 1-4 0a2 2 0 0 1 4 0m-13 2a2 2 0 1 0 0-4a2 2 0 0 0 0 4M6 9.25C6 8.56 6.56 8 7.25 8h5.5a1.25 1.25 0 0 1 1.23 1.024a5.5 5.5 0 0 0-3.73 8.968A4 4 0 0 1 6 14zm8.989-.229c1.139.1 2.178.548 3.011 1.236V9.25C18 8.56 17.44 8 16.75 8h-2.129c.2.298.33.646.367 1.021M5 9.25c0-.463.14-.892.379-1.25H3.25C2.56 8 2 8.56 2 9.25V13a3 3 0 0 0 3.404 2.973A5 5 0 0 1 5 14zm14 5.25a4.5 4.5 0 1 1-9 0a4.5 4.5 0 0 1 9 0m-4-2a.5.5 0 0 0-1 0V14h-1.5a.5.5 0 0 0 0 1H14v1.5a.5.5 0 0 0 1 0V15h1.5a.5.5 0 0 0 0-1H15z"/></svg>
                        </div>
                        <div class="pl-[24px]">
                            <span class="text-[#ada282] text-[16px] font-normal">Sangat Mudah</span>
                            <h3 class="text-[#333] text-[27px] col:text-[22px] font-medium">Bergabung</h3>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <section id="lawyer" class=" relative py-[100px] md:py-[90px]">
        <div class="wraper">
            <div class="col-span-12 text-center">
                <div class="mb-[60px]">
                    <span class="text-[16px] text-[#ada282]">BERTEMU AHLI KAMI</span>
                    <h2 class=" text-[36px] md:text-[26px] font-medium text-[#333] pb-[20px] relative before:absolute before:content-[''] before:left-[50%] before:bottom-0 before:transform before:-translate-x-1/2 before:w-[60px] before:h-[3px] before:bg-[#c0b596]">Pengacara Terbaik</h2>
                </div>
            </div>
            <div class="grid grid-cols-12 team-slider relative owl-carousel">
                <div class="col-span-4 md:col-span-6 ">
                    <div class="overflow-hidden relative shadow-[1px_1px_5px_rgba(0,0,5,8%)] group">
                        <div class="expert-img">
                            <img class="w-full" src="assets/images/lawyer/gabriel.png" alt="">
                        </div>
                        <div class="relative text-center mt-[-40px] bg-[#f5f5f5] transform translate-y-[40px] transition ease-in-out duration-300 group-hover:translate-y-0 group-hover:bg-[#fff]">
                            <h3 class="text-[24px] col:text-[20px] font-medium pt-[30px] pb-[8px]  font-heading-font"><a href="#" class="text-[#282e3f] hover:text-[#ada282] transition-all">Gabriel Naftali Jawok Epin, S.H <br />(Wakil) </a></h3>
                            <span class="text-[#ada282] text-[16px] font-normal">Business Lawyer</span>
                            <ul class="flex justify-center my-[20px] transition ease-in-out">
                                <li class="px-[10px] "><a href="#" class="text-[#c0b596] transition-all hover:text-[#999]"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                                <li class="px-[10px] "><a href="#" class="text-[#c0b596] transition-all hover:text-[#999]"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                                <li class="px-[10px] "><a href="#" class="text-[#c0b596] transition-all hover:text-[#999]"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-span-4 md:col-span-6 ">
                    <div class="overflow-hidden relative shadow-[1px_1px_5px_rgba(0,0,5,8%)] group">
                        <div class="expert-img">
                            <img class="w-full" src="assets/images/lawyer/kaitanus.png" alt="">
                        </div>
                        <div class="relative text-center mt-[-40px] bg-[#f5f5f5]  translate-y-[40px] transition ease-in-out duration-300 group-hover:translate-y-0 group-hover:bg-[#fff]">
                            <h3 class="text-[24px] col:text-[20px] font-medium pt-[30px] pb-[8px]  font-heading-font"><a href="#" class="text-[#282e3f] hover:text-[#ada282] transition-all">Kaitanus F.X Mogahai, S.H <br />(Ketua)</a></h3>
                            <span class="text-[#ada282] text-[16px] font-normal">Family Lawyer</span>
                            <ul class="flex justify-center my-[20px] transition ease-in-out">
                                <li class="px-[10px] "><a href="#" class="text-[#c0b596] transition-all hover:text-[#999]"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                                <li class="px-[10px] "><a href="#" class="text-[#c0b596] transition-all hover:text-[#999]"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                                <li class="px-[10px] "><a href="#" class="text-[#c0b596] transition-all hover:text-[#999]"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-span-4 md:col-span-6">
                    <div class="overflow-hidden relative shadow-[1px_1px_5px_rgba(0,0,5,8%)] group">
                        <div class="expert-img">
                            <img class="w-full" src="assets/images/lawyer/rivard.png" alt="">
                        </div>
                        <div class="relative text-center mt-[-40px] bg-[#f5f5f5] transform translate-y-[40px] transition ease-in-out duration-300  group-hover:translate-y-0 group-hover:bg-[#fff]">
                            <h3 class="text-[24px] col:text-[20px] font-medium pt-[30px] pb-[8px]  font-heading-font"><a href="#" class="text-[#282e3f] hover:text-[#ada282] transition-all">Rivard Mahue, S.H <br />&nbsp;</a></h3>
                            <span class="text-[#ada282] text-[16px] font-normal">Criminal Lawyer</span>
                            <ul class="flex justify-center my-[20px] transition ease-in-out">
                                <li class="px-[10px] "><a href="#" class="text-[#c0b596] transition-all hover:text-[#999]"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                                <li class="px-[10px] "><a href="#" class="text-[#c0b596] transition-all hover:text-[#999]"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                                <li class="px-[10px] "><a href="#" class="text-[#c0b596] transition-all hover:text-[#999]"><i class="fa fa-linkedin" aria-hidden="true"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="vision-mission" class="bg-[#f5f5f5] pt-[95px] pb-[70px]">
        <div class="wraper">
            <div class="col-span-12 text-center">
                <div class="mb-[60px]">
                    <span class="text-[16px] text-[#ada282]">PENCAPAIAN</span>
                    <h2 class=" text-[36px] md:text-[26px] font-medium text-[#333] pb-[20px] relative before:absolute before:content-[''] before:left-[50%] before:bottom-0 before:transform before:-translate-x-1/2 before:w-[60px] before:h-[3px] before:bg-[#c0b596]">Visi & Misi</h2>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-2">
                <div class="col-span-4 md:col-span-6 sm:col-span-12 mb-5">
                    <div class="group flex sm:mx-[80px] col:mx-0">
                        <div class="h-[80px] w-[80px]">
                            <div class="h-[80px] w-[80px] leading-[75px] border border-[rgba(192, 181, 150, .5)] rounded-[50%] text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="78" height="78" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"><path d="M17 12a5 5 0 1 1-5-5"/><path d="M14 2.2q-.97-.198-2-.2C6.477 2 2 6.477 2 12s4.477 10 10 10s10-4.477 10-10q-.002-1.03-.2-2"/><path d="m12.03 11.962l4.553-4.553m3.157-3.064l-.553-1.988a.48.48 0 0 0-.761-.24c-1.436 1.173-3 2.754-1.723 5.247c2.574 1.2 4.044-.418 5.17-1.779a.486.486 0 0 0-.248-.775z"/></g></svg>
                            </div>
                        </div>
                        <div class="pl-[40px]">
                            <h3 class="text-[#373737] text-[28px] col:text-[20px] font-medium pb-[10px] relative mb-[10px]
                            before:absolute before:content-[''] before:left-0 before:bottom-0 before:w-[30px] before:h-[2px] before:bg-[#c0b596]">Visi</h3>
                            <p class="text-[#777] text-[20px] font-normal">Menjadi kantor pengacara yang terkemuka dalam memberikan layanan hukum yang profesional, berintegritas, dan berorientasi pada keadilan serta kepentingan terbaik klien.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-span-4 md:col-span-6 sm:col-span-12 mb-5">
                    <div class="group flex sm:mx-[80px] col:mx-0">
                        <div class="h-[80px] w-[80px]">
                            <div class="h-[80px] w-[80px] leading-[75px] border border-[rgba(192, 181, 150, .5)] rounded-[50%] text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="78" height="78" viewBox="0 0 24 24"><mask id="lineMdCompassLoop0"><g fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"><path stroke-dasharray="64" stroke-dashoffset="64" d="M12 3c4.97 0 9 4.03 9 9c0 4.97 -4.03 9 -9 9c-4.97 0 -9 -4.03 -9 -9c0 -4.97 4.03 -9 9 -9Z"><animate fill="freeze" attributeName="stroke-dashoffset" dur="0.6s" values="64;0"/></path><path fill="#fff" stroke="none" d="M11 11L12 12L13 13L12 12z" transform="rotate(-180 12 12)"><animate fill="freeze" attributeName="d" begin="0.6s" dur="0.3s" values="M11 11L12 12L13 13L12 12z;M10.2 10.2L17 7L13.8 13.8L7 17z"/><animateTransform attributeName="transform" dur="9s" repeatCount="indefinite" type="rotate" values="-180 12 12;0 12 12;0 12 12;0 12 12;0 12 12;270 12 12;-90 12 12;0 12 12;-180 12 12;-35 12 12;-40 12 12;-45 12 12;-45 12 12;-110 12 12;-135 12 12;-180 12 12"/></path><circle cx="12" cy="12" r="1" fill="#000" fill-opacity="0" stroke="none"><animate fill="freeze" attributeName="fill-opacity" begin="0.9s" dur="0.15s" values="0;1"/></circle></g></mask><rect width="24" height="24" fill="currentColor" mask="url(#lineMdCompassLoop0)"/></svg>
                            </div>
                        </div>
                        <div class="pl-[40px]">
                            <h3 class="text-[#373737] text-[28px] col:text-[20px] font-medium pb-[10px] relative mb-[10px]
                            before:absolute before:content-[''] before:left-0 before:bottom-0 before:w-[30px] before:h-[2px] before:bg-[#c0b596]">Misi</h3>
                            <ul class="space-y-2 list-none">
                                <li class="flex items-start">
                                    <span class="font-semibold mr-2 text-[#777] text-[20px]">1. </span>
                                    <span class="text-[#777] text-[20px]">
                                      Menyediakan layanan hukum yang tepat, berlandaskan prinsip‑prinsip keadilan, dan sesuai dengan standar etika profesi.
                                    </span>
                                </li>
                                <li class="flex items-start">
                                    <span class="font-semibold mr-2 text-[#777] text-[20px]">2. </span>
                                    <span class="text-[#777] text-[20px]">
                                      Memastikan setiap klien mendapatkan perlindungan hukum yang maksimal melalui representasi yang berkompeten dan bertanggung jawab.
                                    </span>
                                </li>
                                <li class="flex items-start">
                                    <span class="font-semibold mr-2 text-[#777] text-[20px]">3. </span>
                                    <span class="text-[#777] text-[20px]">
                                      Membangun hubungan jangka panjang dengan klien berdasarkan kepercayaan, kerahasiaan, dan komunikasi yang terbuka.
                                    </span>
                                </li>
                                <li class="flex items-start">
                                    <span class="font-semibold mr-2 text-[#777] text-[20px]">4. </span>
                                    <span class="text-[#777] text-[20px]">
                                      Menjunjung tinggi integritas dan transparansi dalam setiap langkah dan keputusan hukum.
                                    </span>
                                </li>
                                <li class="flex items-start">
                                    <span class="font-semibold mr-2 text-[#777] text-[20px]">5. </span>
                                    <span class="text-[#777] text-[20px]">
                                      Melaksanakan tugas advokasi dengan dedikasi penuh untuk mencapai hasil terbaik bagi klien, baik melalui proses litigasi maupun non‑litigasi.
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="about-us" class="pt-[120px] pb-[100px] md:pt-[90px] md:pb-[50px]">
        <div class="wraper">
            <div class="grid grid-cols-12 items-center gap-3">
                <div class="col-span-6 md:col-span-12">
                    <div class="relative mb-[20px]">
                        <div class=" relative max-w-[530px] before:absolute before:bg-[#c0b596] before:w-full before:h-full before:content-[''] before:-z-10 pb-10">
                            <img class="w-full" src="img/logo.png" alt>
                        </div>
                    </div>
                </div>
                <div class="col-span-6 md:col-span-12">
                    <div class="mb-[20px]">
                        <h2 class="text-[#282e3f] text-[32px]">Tentang Kami</h2>
                        <p class="text-[#666] text-[15px] leading-[28px] mb-[30px]">Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at ,</p>
                        <p class="text-[#666] text-[15px] leading-[28px] mb-[30px]"> and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum</p>
                        <div class="mb-[50px] col:mb-[20px]">
                            <a href="about.html" class="bg-[#c0b596] cursor-pointer text-[16px] font-semibold text-white px-[38px] py-[10px]  capitalize inline-block mt-[6px] transition ease-in-out duration-300 hover:bg-[#d4c291]
                                col:mb-[5px] col:mt-[15px] col:text-[15px] col:px-[18px] col:py-[8px]
                                ">More About Us..</a>
                        </div>
                        <div class="signature">
                            <img  src="assets/images/about/1.png" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
