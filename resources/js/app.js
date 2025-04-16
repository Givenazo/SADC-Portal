import './bootstrap';
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// --- Laravel Echo & Push Notifications ---
import Echo from 'laravel-echo';
window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    wsHost: window.location.hostname,
    wsPort: 6001,
    forceTLS: false,
    disableStats: true,
});

// Helper: Show browser notification
function showNotification(message) {
    if (Notification.permission === 'granted') {
        new Notification('SADC Portal', { body: message });
    } else if (Notification.permission !== 'denied') {
        Notification.requestPermission().then(permission => {
            if (permission === 'granted') {
                new Notification('SADC Portal', { body: message });
            }
        });
    }
}

// Listen for public events
window.Echo.channel('videos')
    .listen('.video.uploaded', (e) => {
        showNotification('New video uploaded: ' + e.video.title);
    });

// Listen for private user events (video downloaded)
if (window.Laravel && window.Laravel.userId) {
    window.Echo.private('user.' + window.Laravel.userId)
        .listen('.video.downloaded', (e) => {
            showNotification('Your video was downloaded: ' + e.video.title);
        });
}

// Listen for country blocking events
window.Echo.channel('countries')
    .listen('.country.blocked', (e) => {
        showNotification('A country was blocked: ' + e.country.name);
    });

// Request notification permission on page load
if (window.Notification && Notification.permission !== 'granted') {
    Notification.requestPermission();
}
