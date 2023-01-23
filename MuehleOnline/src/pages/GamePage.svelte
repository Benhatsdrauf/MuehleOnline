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
  import { getPossibleMoves } from "../../scripts/gameLogic";
  import GameField from "../lib/GameField.svelte";

  const navigate = useNavigate();

  let gameState = "initial";
  let showModal = false;

  let playerStones = [null, null, null, null, null, null, null, null, null];
  let opponentStones = [null, null, null, null, null, null, null, null, null];
  let possibleMoves = [];

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
      if (e.quit) {
        leaveChannel("opponent_quit");
        showModal = true;
      }
    });

  // echo
  //   .channel("move." + localStorage.getItem("hashedToken"))
  //   .listen("Move", (e) => {
  //     console.log(e);
  //     // check new position and override in array
  //     //e.newPos,
  //     //e.oldPos,

  //     leaveChannel("move");
  //   });

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
    if (playerStones.includes(null)) {
      playerStones[playerStones.indexOf(null)] = pos;
      console.log(playerStones);
    } else {
      possibleMoves = getPossibleMoves(
        pos,
        playerStones.concat(opponentStones)
      );
      console.log("position", pos);
      console.log("possible moves", possibleMoves);
      console.log(
        "occupied positions moves",
        playerStones.concat(opponentStones)
      );
    }

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
    <h3>Your opponent left the game</h3>
    <button
      on:click={() => {
        navigate("/home");
      }}>Ok</button
    >
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
        <GameField />

        {#each positions as position, i}
          <Circle
            status={gameState}
            x={position[0]}
            y={position[1]}
            index={i}
            isPossible={possibleMoves.includes(i)}
            on:click={() => playerAction(i)}
          />
        {/each}

        <!-- onclick noch fallsch muss nicht den inddx sondern den index der position übergeben -->
        {#each playerStones.filter((x) => x != null && x != -1) as stone, i}
          <Stone
            x={positions[stone][0]}
            y={positions[stone][1]}
            {isWhite}
            on:click={() => playerAction(stone)}
          />
        {/each}

        {#each opponentStones.filter((x) => x != null && x != -1) as stone}
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
