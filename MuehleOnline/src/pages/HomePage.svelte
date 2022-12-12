<script>
    import Modal from "../lib/Modal.svelte";
    import authorizedRequest from "../../scripts/authorizedRequest";

    let showModal = false;
    let inviteLink = "";

    import { useNavigate } from "svelte-navigator";

    const navigate = useNavigate();

    function CopyToClipBoard() {
        navigator.clipboard.writeText(inviteLink);
    }

    async function StartGame() {
        let response = await authorizedRequest(
            "http://localhost:420/start-game"
        ).catch((err) => {
            console.log(err);
            return;
        });

        if (response) {
            inviteLink = response.invite_link;
            showModal = true;
        }
    }

    async function Logout() {
        let response = await authorizedRequest(
            "http://localhost:420/logout"
        ).catch((err) => {
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
