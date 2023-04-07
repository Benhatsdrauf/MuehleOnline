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
  import MessageModal from "../lib/MessageModal.svelte";
  import { faPlay } from "@fortawesome/free-solid-svg-icons";

  echo
    .channel("player_ready." + localStorage.getItem("hashedToken"))
    .listen("PlayerReady", (e) => {
      if (e.ready) {
        leaveChannel("player_ready." + localStorage.getItem("hashedToken"));
        navigate("/gamePage");
      }
    });

  let showGameCodeModal = false;
  let inviteLink = "";
  let activeGame = false;
  let username = "";
  let elo = 1000;
  let gameHistory = null;
  let ttm = new Date();
  let statistics = null;
  let navigate = useNavigate();

  let showMessageModal = false;
  let modalMessage = "";
  let messageModalError = true;

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
        try {
          err.json().then((e) => {
            if (e.errors.game) {
              modalMessage = e.errors.game;
              messageModalError = true;
              showMessageModal = true;
            }
          });
        } catch (exception) {
          modalMessage = "Faild to connect to server, please try again later.";
          messageModalError = true;
          showMessageModal = true;
        }
      });
  }

  function LoadUserData() {
    AuthorizedGetRequest("user/info")
      .then((response) => {
        username = response.user.name;
        elo = response.user.elo;
        activeGame = response.game.active;
        gameHistory = response.history;
        ttm = new Date(response.game.time_to_move);
        statistics = response.statistic;
      })
      .catch((err) => {
        try {
          err.json().then((e) => {
            if (e.message == "Unauthenticated.") {

              localStorage.clear();
              navigate("/");
            }
          });
        } catch (exception) {
          modalMessage = "Faild to connect to server, please try again later.";
          messageModalError = true;
          showMessageModal = true;
        }
      });
  }

  function Logout() {
    AuthorizedRequest("auth/logout")
      .then((response) => {
        navigate("/");
      })
      .catch((err) => {
        try {
          err.json().then((e) => {
            console.log(e);
          });
        } catch (exception) {
          modalMessage = "Faild to connect to server, please try again later.";
          messageModalError = true;
          showMessageModal = true;
        }
      });
  }

  function CopyToClipBoard() {
    navigator.clipboard.writeText(inviteLink);
  }

  function StartGame() {
    AuthorizedRequest("game/create")
      .then((response) => {
        inviteLink = response.invite_link;
        showGameCodeModal = true;
      })
      .catch((err) => {
        try {
          err.json().then((e) => {
            if (e.errors.game) {
              modalMessage = e.errors.game;
              messageModalError = true;
              showMessageModal = true;
            }
          });
        } catch (exception) {
          modalMessage = "Faild to connect to server, please try again later.";
          messageModalError = true;
          showMessageModal = true;
        }
      });
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
      src="https://api.dicebear.com/5.x/initials/svg?backgroundType=gradientLinear&backgroundType=gradientLinear&seed={username}"
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
          <div class="card card-border">
            <div class="card-header bgc-secondary">
              <div class="row">
                <div class="col-auto">
                  <Fa icon={faPlay} color="#ffffff" size="2x" />
                </div>
                <div class="col c-text">
                  <h3>Start Game</h3>
                </div>
              </div>
            </div>
            <div class="card-body">
              <p>Click here to get a invite link for your friends.</p>
              <button class="btn btn-outline-primary" on:click={StartGame}
                >Play Now!</button
              >
            </div>
          </div>
        {/if}
      </div>

      <Statistics dataObject={statistics} />
    </div>
    <div class="col">
      <HistoryComponent {gameHistory} />
    </div>
  </div>
</div>

{#if showGameCodeModal}
  <Modal on:close={() => (showGameCodeModal = false)}>
    <h1 slot="header">Here is your new game code:</h1>
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

<MessageModal
  isError={messageModalError}
  message={modalMessage}
  bind:showModal={showMessageModal}
/>

<style>
</style>
