import Echo from "laravel-echo";
import Pusher from "pusher-js";

export let pusher = Pusher;

export let echo = new Echo({
    broadcaster: "pusher",
    authEndpoint: "http://localhost:5000/broadcasting/auth",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    wsHost: "127.0.0.1",
    wsPort: "6001",
    forceTLS: false,
    disableStatus: true,
});

export function leaveChannel(channelName) {
    echo.leaveChannel(
        `${channelName}.${localStorage.getItem("hashedToken")}`
    );
}