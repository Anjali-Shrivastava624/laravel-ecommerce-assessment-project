class PushNotificationHandler {
    constructor() {
        this.publicKey = document.querySelector('meta[name="vapid-public-key"]')?.getAttribute('content');
        if (this.publicKey && 'serviceWorker' in navigator && 'PushManager' in window) {
            this.init();
        }
    }

    async init() {
        try {
            const registration = await navigator.serviceWorker.register('/sw.js');
            console.log('Service Worker registered');

            const permission = await Notification.requestPermission();
            if (permission === 'granted') {
                await this.subscribeUser(registration);
            }
        } catch (error) {
            console.error('Service Worker registration failed:', error);
        }
    }

    async subscribeUser(registration) {
        try {
            const subscription = await registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: this.urlB64ToUint8Array(this.publicKey)
            });

            await fetch('/notifications/subscribe', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    endpoint: subscription.endpoint,
                    keys: {
                        p256dh: this.arrayBufferToBase64(subscription.getKey('p256dh')),
                        auth: this.arrayBufferToBase64(subscription.getKey('auth'))
                    }
                })
            });

            console.log('Push notification subscription successful');
        } catch (error) {
            console.error('Failed to subscribe to push notifications:', error);
        }
    }

    urlB64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding)
            .replace(/-/g, '+')
            .replace(/_/g, '/');

        const rawData = window.atob(base64);
        const outputArray = new Uint8Array(rawData.length);

        for (let i = 0; i < rawData.length; ++i) {
            outputArray[i] = rawData.charCodeAt(i);
        }
        return outputArray;
    }

    arrayBufferToBase64(buffer) {
        const bytes = new Uint8Array(buffer);
        let binary = '';
        bytes.forEach(byte => binary += String.fromCharCode(byte));
        return window.btoa(binary);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new PushNotificationHandler();
});
