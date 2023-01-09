<script>
    import { io } from "socket.io-client";
    const socket = io("http://localhost:6001");

    socket.on("connect", () => {
        console.log(socket.id); // x8WIv7-mJelg7on_ALbx
    });
</script>

<h3>gamePage</h3>
<p>That's what it's all about!</p>
