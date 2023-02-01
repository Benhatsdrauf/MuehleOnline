import Echo from "laravel-echo";
import Pusher from "pusher-js";

export let pusher = Pusher;

export let echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    wsHost: import.meta.env.VITE_SERVER_HOST_NAME,
    wsPort: import.meta.env.VITE_SERVER_WS_PORT,
    forceTLS: false,
    disableStatus: true,
});

export function leaveChannel(channelName) {
    echo.leaveChannel(
        `${channelName}.${localStorage.getItem("hashedToken")}`
    );
}