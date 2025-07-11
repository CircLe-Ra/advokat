import { animate, inView } from "motion";
import * as FilePond from 'filepond';
import FilePondPluginFileValidateType from 'filepond-plugin-file-validate-type';
import FilePondPluginFileValidateSize from 'filepond-plugin-file-validate-size';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';
import * as PusherPushNotifications from "@pusher/push-notifications-web";
import { Calendar } from "@fullcalendar/core";
import multiMonthPlugin from "@fullcalendar/multimonth";
import idLocale from '@fullcalendar/core/locales/id';

const beamsClient = new PusherPushNotifications.Client({
    instanceId: import.meta.env.VITE_PUSHER_BEAMS_INSTANCE_ID,
});


window.PusherPushNotifications = PusherPushNotifications;
window.beamsClient = beamsClient;
window.animate = animate;
window.inView = inView;
window.FilePond = FilePond;
window.FilePondPluginFileValidateType = FilePondPluginFileValidateType;
window.FilePondPluginFileValidateSize = FilePondPluginFileValidateSize;
window.FilePondPluginImagePreview = FilePondPluginImagePreview;
window.Calendar = Calendar;
window.multiMonthPlugin = multiMonthPlugin;
window.idLocale = idLocale;

window.addEventListener('livewire:navigating', () => {
    Livewire.dispatch('action-toast-closed');


});

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';
