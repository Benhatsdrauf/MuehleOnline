<script>
  import {
    AuthorizedRequest,
    AuthorizedGetRequest,
  } from "../../scripts/request";
  import { onMount } from "svelte";
  import Circle from "../lib/Circle.svelte";
  import { positions } from "../../scripts/circlePositions";
  import Stone from "../lib/Stone.svelte";
  import Navbar from "../lib/Navbar.svelte";
  import { useNavigate } from "svelte-navigator";
  import PlayerInfo from "../lib/PlayerInfo.svelte";
  import { echo, leaveChannel } from "../../scripts/echo";
  import Modal from "../lib/Modal.svelte";

  const navigate = useNavigate();

  let gameState = "initial";
  let showModal = false;

  let playerStones = [null, null, null, null, null, null, null, null, null];
  let opponentStones = [null, null, null, null, null, null, null, null, null];

  let isWhite = false;
  let yourTurn = false;
  let me = {};
  let opponent = {};
  let whiteMoves;
  let blackMoves;

  echo
    .channel("opponent_quit." + localStorage.getItem("hashedToken"))
    .listen("Quit", (e) => {
      console.log(e);
      if (e.ready) {
        leaveChannel("opponent_quit");
        showModal = true;
      }
    });

  onMount(() => {
    AuthorizedGetRequest("game/data")
      .then((data) => {
        isWhite = data.user.is_white;
        yourTurn = data.user.yourTurn;
        me = data.user;
        opponent = data.opponent;
        whiteMoves = data.white_moves;
        blackMoves = data.black_moves;

        if (isWhite == true) {
          playerStones = whiteMoves;
          opponentStones = blackMoves;
        } else {
          playerStones = blackMoves;
          opponentStones = whiteMoves;
        }

        while (playerStones.length < 9) {
          playerStones.push(null);
        }

        while (opponentStones.length < 9) {
          opponentStones.push(null);
        }
      })
      .catch();
  });

  function playerAction(pos) {
    playerStones[playerStones.indexOf(null)] = pos;
    console.log(playerStones);

    AuthorizedGetRequest("game/stone/set/" + pos)
      .then()
      .catch();
  }

  async function quitGame() {
    AuthorizedGetRequest("game/quit")
      .then(() => {
        navigate("/home");
      })
      .catch((e) => {
        console.log(e);
      });
  }
</script>

<Navbar>
  <div class="col-auto">
    <button type="button" class="btn btn-outline-danger" on:click={quitGame}
      >Quit Game</button
    >
  </div>
</Navbar>

{#if showModal}
  <Modal on:close={() => (showModal = false)}>
    <h1>Your opponent left the game</h1>
    <button
      on:click={() => {
        navigate("/home");
      }}
    />
  </Modal>
{/if}

<div class="container-fluid bgc-primary">
  <div class="row pt-4">
    <div class="col-auto">
      <PlayerInfo user={me} />
      <svg class="none-played mt-2">
        {#each playerStones.filter((x) => x === null) as stone, i}
          <Stone x={50} y={20 + 5 * i} {isWhite} />
        {/each}
        <text class="unplayed-number" x="50%" y="90%"
          >{playerStones.filter((x) => x === null).length}</text
        >
      </svg>
    </div>
    <div class="col d-flex justify-content-center">
      <svg class="game-field">
        <rect
          x="5%"
          y="5%"
          width="90%"
          height="90%"
          fill="none"
          stroke="black"
          stroke-width="5"
        />
        <rect
          x="20%"
          y="20%"
          width="60%"
          height="60%"
          fill="none"
          stroke="black"
          stroke-width="5"
        />
        <rect
          x="35%"
          y="35%"
          width="30%"
          height="30%"
          fill="none"
          stroke="black"
          stroke-width="5"
        />
        <line
          x1="5%"
          y1="50%"
          x2="35%"
          y2="50%"
          stroke="black"
          stroke-width="5"
        />
        <line
          x1="65%"
          y1="50%"
          x2="95%"
          y2="50%"
          stroke="black"
          stroke-width="5"
        />
        <line
          x1="50%"
          y1="5%"
          x2="50%"
          y2="35%"
          stroke="black"
          stroke-width="5"
        />
        <line
          x1="50%"
          y1="65%"
          x2="50%"
          y2="95%"
          stroke="black"
          stroke-width="5"
        />

        {#each positions as position, i}
          <Circle
            status={gameState}
            x={position[0]}
            y={position[1]}
            index={i}
            on:click={() => playerAction(i)}
          />
        {/each}

        {#each playerStones.filter((x) => x != null) as stone}
          <Stone x={positions[stone][0]} y={positions[stone][1]} {isWhite} />
        {/each}

        {#each opponentStones.filter((x) => x != null) as stone}
          <Stone
            x={positions[stone][0]}
            y={positions[stone][1]}
            isWhite={!isWhite}
          />
        {/each}
      </svg>
    </div>
    <div class="col-auto">
      <PlayerInfo user={opponent} />
      <svg class="none-played mt-2">
        {#each opponentStones.filter((x) => x === null) as stone, i}
          <Stone x={50} y={20 + 5 * i} isWhite={!isWhite} />
        {/each}
        <text class="unplayed-number" x="50%" y="90%"
          >{opponentStones.filter((x) => x === null).length}</text
        >
      </svg>
    </div>
  </div>
</div>

<style>
  .game-field {
    height: 80vh;
    width: 80vh;
    background: var(--color-board-background);
    border: solid 6px var(--color-black);
  }

  .none-played {
    height: 50vh;
    width: 10vw;
    background: var(--color-board-background);
    border: solid 6px var(--color-black);
  }

  .unplayed-number {
    font-size: 20px;
    font-weight: bold;
  }
</style>
