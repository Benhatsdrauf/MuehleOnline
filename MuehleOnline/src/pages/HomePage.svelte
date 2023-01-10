<script>
  import Modal from "../lib/Modal.svelte";
  import { AuthorizedRequest } from "../../scripts/request";

  import Fa from "svelte-fa";
  import { faCat, faChartColumn } from "@fortawesome/free-solid-svg-icons";
  import { faCopy } from "@fortawesome/free-regular-svg-icons";

  import Echo from "laravel-echo";
  import Pusher from "pusher-js";

  let pusher = Pusher;

  let echo = new Echo({
    broadcaster: "pusher",
    authEndpoint: "http://localhost:5000/broadcasting/auth",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    wsHost: "127.0.0.1",
    wsPort: "6001",
    forceTLS: false,
    disableStatus: true,
  });

  echo
    .channel("player_ready." + localStorage.getItem("hashedToken"))
    .listen("PlayerReady", (e) => {
      console.log(e);
      alert(e);
    });

  let showModal = false;
  let inviteLink = "";

  import { useNavigate } from "svelte-navigator";

  const navigate = useNavigate();

  function CopyToClipBoard() {
    navigator.clipboard.writeText(inviteLink);
  }

  async function StartGame() {
    let response = await AuthorizedRequest("game/create").catch((err) => {
      console.log(err);
      return;
    });

    if (response) {
      inviteLink = response.invite_link;
      showModal = true;
    }
  }

  async function Logout() {
    let response = await AuthorizedRequest("auth/logout").catch((err) => {
      console.log(err);
      return;
    });

    navigate("/");
  }
</script>

<nav class="navbar bgc-secondary">
  <a class="navbar-brand" href="#">
    <Fa icon={faCat} color="#ffffff" size="1.6x"/>
  </a>
  <button
    class="btn btn-outline-danger my-2 my-sm-0"
    type="button"
    on:click={Logout}>Logout</button
  >
</nav>

<div class="container-fluid bgc-primary">
  <div class="row">
    <div class="col">
      <h3>Home</h3>
    </div>
  </div>
  <div class="row">
    <div class="col">
      <p>
        This is gonna be the home/statistics page after the user is logged in
      </p>
      <button on:click={StartGame}> Play Now! </button>
    </div>
    <div class="col">
      <div class="card card-border">
        <div class="card-header bgc-secondary">
            <div class="row">
                <div class="col-auto">
                    <Fa icon={faChartColumn} color="#ffffff" size="2x"/>
                </div>
                <div class="col c-text">
                    <h3>Statistics</h3>
                </div>
            </div>
        </div>
        <div class="card-body" />
        <p>Here could be some game statistics</p>
      </div>
    </div>
  </div>
</div>

{#if showModal}
  <Modal on:close={() => (showModal = false)}>
    <h1>This is the start game modal</h1>
    <div>
      <div class="input-group mb-3">
        <input type="text" class="form-control" value={inviteLink} />
        <div class="input-group-append">
          <button
            class="btn btn-outline-secondary"
            type="button"
            on:click={CopyToClipBoard}
          >
            <Fa icon={faCopy} color="#000000" />
          </button>
        </div>
      </div>
      <p>
        Send the Link to a friend the game will start as soon as your friend
        joins
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

  .card-border {
    border-color: var(--color-dark-grey);
  }
</style>
