<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;
use Pusher\PushNotifications\PushNotifications;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth'])->group(callback: function () {
    Route::get('/goto', function () {
        if(auth()->user()->hasRole('staf')){
            return redirect()->route('staff.dashboard');
        }elseif(auth()->user()->hasRole('klien')){
            return redirect()->route('client.dashboard');
        }elseif(auth()->user()->hasRole('pimpinan')){
            return redirect()->route('leader.dashboard');
        }
    })->name('goto');

    Route::middleware(['role:klien'])->group(function () {
        Volt::route('client/dashboard', 'client.dashboard')->name('client.dashboard');
        Volt::route('client/case', 'client.case')->name('client.case');
        Volt::route('client/case/personal-data', 'client.personal-data')->name('client.case.personal-data');
        Volt::route('client/case/{case}/detail-case', 'client.detail-case')->name('client.case.detail-case');
    });

    Route::middleware(['role:staf'])->group(function () {
        Volt::route('staff/dashboard', 'staff.dashboard')->name('staff.dashboard');
        Volt::route('staff/master-data/lawyers', 'staff.master-data.lawyer')->name('staff.master-data.lawyer');
        Volt::route('staff/master-data/users', 'staff.master-data.user')->name('staff.master-data.user');
        Volt::route('staff/master-data/roles', 'staff.master-data.role')->name('staff.master-data.role');

        Volt::route('staff/case/validation/{status}', 'staff.case.validation')->name('staff.case.validation');
        Volt::route('staff/case/{id}/{status}', 'staff.case.detail-case')->name('staff.case.detail-case');
    });

    Route::middleware(['role:leader'])->group(function () {
        Volt::route('leader/dashboard', 'leader.dashboard')->name('leader.dashboard');
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
