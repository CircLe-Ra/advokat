<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use Pusher\PushNotifications\PushNotifications;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('guest');
})->name('home');

Route::middleware(['auth'])->group(callback: function () {
    Route::get('/goto', function () {
        if(auth()->user()->hasRole('staf')){
            return redirect()->route('staff.dashboard');
        }elseif(auth()->user()->hasRole('klien')){
            return redirect()->route('client.dashboard');
        }elseif(auth()->user()->hasRole('pimpinan')){
            return redirect()->route('leader.dashboard');
        }elseif(auth()->user()->hasRole('pengacara')){
            return redirect()->route('lawyer.dashboard');
        }
    })->name('goto');

    Route::middleware(['role:klien'])->group(function () {
        Volt::route('client/dashboard', 'client.dashboard')->name('client.dashboard');
        Volt::route('client/case', 'client.case')->name('client.case');
        Volt::route('client/case/personal-data', 'client.personal-data')->name('client.case.personal-data');
        Volt::route('client/case/{case}/detail-case', 'client.detail-case')->name('client.case.detail-case');
        Volt::route('client/case/{case}/{status}/handling', 'client.handling.pages')->name('client.case.handling');
        Volt::route('client/chat/{staff?}', 'client.chat')->name('client.chat');
    });

    Route::middleware(['role:staf'])->group(function () {
        Volt::route('staff/dashboard', 'staff.dashboard')->name('staff.dashboard');
        Volt::route('staff/master-data/lawyers', 'staff.master-data.lawyer')->name('staff.master-data.lawyer');
        Volt::route('staff/master-data/users', 'staff.master-data.user')->name('staff.master-data.user');
        Volt::route('staff/master-data/roles', 'staff.master-data.role')->name('staff.master-data.role');

        Volt::route('staff/case/validation/{status}', 'staff.case.validation')->name('staff.case.validation');
        Volt::route('staff/case/{id}/{status}', 'staff.case.detail-case')->name('staff.case.detail-case');
        Volt::route('staff/active-case', 'staff.active.case')->name('staff.active.case');
        Volt::route('staff/active-case/{id}/{status}', 'staff.active.pages')->name('staff.active.page');
        Volt::route('staff/active-case/{id}/{status}/detail', 'staff.active.detail-case')->name('staff.active.detail-case');
        Volt::route('staff/chat/{client?}', 'staff.chat')->name('staff.chat');
    });

    Route::middleware(['role:pimpinan'])->group(function () {
        Volt::route('leader/dashboard', 'leader.dashboard')->name('leader.dashboard');
        Volt::route('leader/case/validation/{status}', 'leader.case.validation')->name('leader.case.validation');
        Volt::route('leader/case/{id}/{status}', 'leader.case.detail-case')->name('leader.case.detail-case');
    });

    Route::middleware(['role:pengacara'])->group(function () {
        Volt::route('lawyer/dashboard', 'lawyer.dashboard')->name('lawyer.dashboard');
        Volt::route('lawyer/case', 'lawyer.case')->name('lawyer.case');
        Volt::route('lawyer/case/{case}/{status}', 'lawyer.pages')->name('lawyer.case.page');
    });


    Volt::route('chat', 'chat')->name('chat');

    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');

    Route::get('/pusher/beams-auth', function (Request $request) {
        $userID = $request->user_id;
        $beamsClient = new PushNotifications([
            'instanceId' => env('PUSHER_BEAMS_INSTANCE_ID'),
            'secretKey' => env('PUSHER_BEAMS_SECRET_KEY'),
        ]);
        $beamsToken = $beamsClient->generateToken($userID);
        return response()->json($beamsToken);
    });
});


require __DIR__.'/auth.php';
