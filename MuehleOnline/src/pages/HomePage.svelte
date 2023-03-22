<script>
  import Modal from "../lib/Modal.svelte";
  import {
    AuthorizedGetRequest,
    AuthorizedRequest,
  } from "../../scripts/request";
  import { onMount } from "svelte";
  import { useNavigate } from "svelte-navigator";
  import Navbar from "../lib/Navbar.svelte";
  import { echo, leaveChannel } from "../../scripts/echo";

  import Fa from "svelte-fa";
  import { faCopy } from "@fortawesome/free-regular-svg-icons";
  import Statistics from "../lib/HomePage/Statistics.svelte";
  import ActiveGame from "../lib/HomePage/ActiveGame.svelte";
  import HistoryComponent from "../lib/HomePage/HistoryComponent.svelte";
  import StartGameComponent from "../lib/HomePage/StartGameComponent.svelte";

  echo
    .channel("player_ready." + localStorage.getItem("hashedToken"))
    .listen("PlayerReady", (e) => {
      if (e.ready) {
        leaveChannel("player_ready." + localStorage.getItem("hashedToken"));
        navigate("/gamePage");
      }
    });

  let showModal = false;
  let showErrorModal = false;
  let inviteLink = "";
  let activeGame = false;
  let username = "";
  let elo = 1000;
  let gameHistory = [];
  let ttm = new Date();
  let statistics = null;

  onMount(() => {
    LoadUserData();
  });

  async function quitGame() {
    AuthorizedGetRequest("game/quit")
      .then(() => {
        activeGame = false;
        LoadUserData();
      })
      .catch((err) => {
        console.log(err);
      });
  }

  async function LoadUserData() {
    await AuthorizedGetRequest("user/info")
      .then((response) => {
        username = response.user.name;
        elo = response.user.elo;
        activeGame = response.game.active;
        gameHistory = response.history;
        ttm = new Date(response.game.time_to_move);
        statistics = response.statistic;
      })
      .catch((err) => {
        console.log(err);
      });
  }

  const navigate = useNavigate();

  async function StartGame() {
    let response = await AuthorizedRequest("game/create").catch((err) => {
      showErrorModal = true;
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

  function CopyToClipBoard() {
    navigator.clipboard.writeText(inviteLink);
  }
</script>

<Navbar>
  <span class="navbar-text c-text me-2">
    {elo}
  </span>
  <span class="navbar-text c-text me-2">
    {username}
  </span>
  <div class="me-3">
    <img
      class="round-img"
      src="https://api.dicebear.com/5.x/initials/svg?seed={username}"
      alt="profile"
      width="30px"
      height="30px"
    />
  </div>
  <button class="btn btn-outline-danger" type="button" on:click={Logout}>
    Logout
  </button>
</Navbar>

<div class="container-fluid bgc-primary h-100">
  <div class="row mb-5" />
  <div class="row">
    <div class="col-5">
      <div class="mb-3">
        {#if activeGame}
          <ActiveGame on:click={quitGame} {ttm} visible={activeGame} />
        {:else}
          <StartGameComponent
            bind:showModal
            bind:inviteLink
            bind:showErrorModal
          />
        {/if}
      </div>

      <Statistics dataObject={statistics} />
    </div>
    <div class="col">
      <HistoryComponent {gameHistory} {username} />
    </div>
  </div>
</div>

{#if showModal}
  <Modal on:close={() => (showModal = false)}>
    <h1 slot="header">Here is your new game code</h1>
    <div class="mt-4">
      <div class="input-group mb-2">
        <input type="text" class="form-control" value={inviteLink} />
        <div class="input-group-append">
          <button class="btn btn-link" type="button" on:click={CopyToClipBoard}>
            <Fa icon={faCopy} color="#000000" />
          </button>
        </div>
      </div>
      <p>
        Send the Link to a friend the game will start as soon as your friend
        joins.
      </p>
    </div>
  </Modal>
{/if}

{#if showErrorModal}
  <Modal on:close={() => (showErrorModal = false)}>
    <h3>Please finish ongoing games first.</h3>
    <button on:click={() => (showErrorModal = false)}>Ok</button>
  </Modal>
{/if}

<style>
</style>
