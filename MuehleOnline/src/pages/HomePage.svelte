<script>
  import Modal from "../lib/Modal.svelte";
  import {
    AuthorizedGetRequest,
    AuthorizedRequest,
  } from "../../scripts/request";
  import { onMount } from "svelte";
  import { useNavigate } from "svelte-navigator";
  import GameHistory from "../lib/GameHistory.svelte";
  import Navbar from "../lib/Navbar.svelte";
  import { echo, leaveChannel } from "../../scripts/echo";

  import Fa from "svelte-fa";
  import {
    faCat,
    faChartColumn,
    faGamepad,
    faClockRotateLeft,
    faRotateLeft,
  } from "@fortawesome/free-solid-svg-icons";
  import { faCopy } from "@fortawesome/free-regular-svg-icons";

  echo
    .channel("player_ready." + localStorage.getItem("hashedToken"))
    .listen("PlayerReady", (e) => {
      console.log(e);
      if (e.ready) {
        leaveChannel("player_ready");
        navigate("/gamePage");
      }
    });

  let showModal = false;
  let inviteLink = "";
  let activeGame = false;
  let username = "";
  let elo = 1000;
  let gameHistory = [];

  onMount(() => {
    LoadUserData();
  });

  async function quitGame() {
    AuthorizedGetRequest("game/quit")
      .then(() => {
        activeGame = false;
        LoadUserData();
      })
      .catch((e) => {
        console.log(e);
      });
  }

  async function LoadUserData() {
    await AuthorizedGetRequest("user/info")
      .then((response) => {
        username = response.user.name;
        elo = response.user.elo;
        activeGame = response.game.active;
        gameHistory = response.history;
      })
      .catch((err) => {
        console.log(err);
      });
  }

  const navigate = useNavigate();

  function CopyToClipBoard() {
    navigator.clipboard.writeText(inviteLink);
  }

  async function StartGame() {
    let response = await AuthorizedRequest("game/create").catch((err) => {
      alert("Please finish ongoing games first.");
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

<Navbar>
  <span class="navbar-text c-text me-2">
    {elo}
  </span>
  <span class="navbar-text c-text me-2">
    {username}
  </span>
  <div class="me-3">
    <img
      src="https://api.dicebear.com/5.x/initials/svg?seed={username}"
      alt="profile"
      width="30px"
      height="30px"
    />
  </div>
  <button class="btn btn-outline-danger" type="button" on:click={Logout}
    >Logout</button
  >
</Navbar>

<div class="container-fluid bgc-primary h-100">
  <div class="row">
    <div class="col">
      <h3>Home</h3>
    </div>
  </div>
  <div class="row mb-3">
    <div class="col-5">
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
              <Fa icon={faChartColumn} color="#ffffff" size="2x" />
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
  <div class="row">
    <div class="col-5">
      {#if activeGame}
        <div class="card card-border">
          <div class="card-header bgc-secondary">
            <div class="row">
              <div class="col-auto">
                <Fa icon={faGamepad} color="#ffffff" size="2x" />
              </div>
              <div class="col c-text">
                <h3>Active Game</h3>
              </div>
            </div>
          </div>
          <div class="card-body" />
          <div class="row">
            <div class="col">
              <p>You still have one active game.</p>
            </div>
          </div>
          <div class="row">
            <div class="col-auto">
              <p>You have 2 min to join left.</p>
            </div>
            <div class="col-auto">
              <button
                type="button"
                class="btn btn-success"
                on:click={() => {
                  navigate("/gamePage");
                }}>Re-join</button
              >
            </div>
            <div class="col-auto">
              <button type="button" class="btn btn-danger" on:click={quitGame}
                >Quit</button
              >
            </div>
          </div>
        </div>
      {/if}
    </div>
    <div class="col">
      <div class="card card-border">
        <div class="card-header bgc-secondary">
          <div class="row">
            <div class="col-auto">
              <Fa icon={faClockRotateLeft} color="#ffffff" size="2x" />
            </div>
            <div class="col c-text">
              <h3>Game History</h3>
            </div>
          </div>
        </div>
        <div class="card-body" />
        <div class="container history">
          {#each gameHistory as game}
            <div class="mb-2">
              <GameHistory
                {username}
                count={gameHistory.indexOf(game) + 1}
                won={game.won}
                opponent={game.opponent}
                playtime={game.play_time}
                start_date={game.start_date}
                elo={game.elo}
              />
            </div>
          {/each}
        </div>
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
          <button class="btn btn-link" type="button" on:click={CopyToClipBoard}>
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
  .card-border {
    border-color: var(--color-dark-grey);
  }

  .card-body {
    padding-left: 10px;
  }

  .history {
    height: 300px;
    overflow-y: auto;
  }

  .border-left {
    border-left: solid var(--color-white) 2px;
  }
</style>
