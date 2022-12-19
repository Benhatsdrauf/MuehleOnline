<script>
    import Modal from "../lib/Modal.svelte";
    import authorizedRequest from "../../scripts/authorizedRequest";

    import Echo from "laravel-echo";
    import Pusher from "pusher-js";

    let pusher = Pusher;

    let echo = new Echo({
        broadcaster: "pusher",
        key: import.meta.env.VITE_PUSHER_APP_KEY,
        wsHost: "127.0.0.1",
        forceTLS: false,
        disableStatus: true,
    });

    echo.channel("player_ready").listen("PlayerReady", (e) => {
        console.log(e);
    });

    let showModal = false;
    let inviteLink = "";

    import { useNavigate } from "svelte-navigator";

    const navigate = useNavigate();

    function CopyToClipBoard() {
        navigator.clipboard.writeText(inviteLink);
    }

    async function StartGame() {
        let response = await authorizedRequest("game/create").catch((err) => {
            console.log(err);
            return;
        });

        if (response) {
            inviteLink = response.invite_link;
            showModal = true;
        }
    }

    async function Logout() {
        let response = await authorizedRequest("auth/logout").catch((err) => {
            console.log(err);
            return;
        });

        navigate("/");
    }
</script>

<h3>Home</h3>
<p>This is gonna be the home/statistics page after the user is logged in</p>

<button on:click={Logout}>Logout</button>

<button on:click={StartGame}> Play Now! </button>

{#if showModal}
    <Modal on:close={() => (showModal = false)}>
        <h1>This is the start game modal</h1>
        <div>
            <code>{inviteLink}</code>
            <button on:click={CopyToClipBoard}>Copy!</button>
            <p>
                Send the Link to a friend the game will start as soon as your
                friend joins
            </p>
        </div>
    </Modal>
{/if}

<style>
    code {
        font-family: Consolas, "courier new";
        color: cadetblue;
        background-color: #f1f1f1;
        padding: 2px;
        font-size: 105%;
    }
</style>
