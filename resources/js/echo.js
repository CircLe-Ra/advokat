import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    wsHost: import.meta.env.VITE_PUSHER_HOST,
    wsPort: import.meta.env.VITE_PUSHER_PORT,
    wssPort: import.meta.env.VITE_PUSHER_PORT,
    enabledTransports: ["ws", "wss"],
});

if(document.querySelector('meta[name="user-id"]')){
    window.Echo.private(`App.Models.User.${document.querySelector('meta[name="user-id"]').content}`)
        .notification((notification) => {
            console.log(notification);
            Livewire.dispatch('update-notif');
            if(notification.role === 'admin'){
                Livewire.dispatch('update-message-admin');
            }else if(notification.role === 'client'){
                console.log(notification);
                Livewire.dispatch('update-message-client');
            }
        });
}
