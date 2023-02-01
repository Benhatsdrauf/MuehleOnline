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
  import {
    getPossibleMoves,
    getAllMoves,
    GetStonesInMill,
  } from "../../scripts/gameLogic";
  import GameField from "../lib/GameField.svelte";
  import { draw, fade } from "svelte/transition";
  import { quintOut } from "svelte/easing";
  import { empty } from "svelte/internal";

  const navigate = useNavigate();

  let gameState = "initial";
  let showModal = false;

  let playerStones = [null, null, null, null, null, null, null, null, null];
  let opponentStones = [null, null, null, null, null, null, null, null, null];
  let opponentStonesInMill = [];
  let allMoves = [];
  let possibleMoves = [];
  let allMoveLines = [];
  let selectedStone = null;

  let canSet = true;
  let canDelete = false;
  let isWhite = false;
  let yourTurn = false;
  let deletionToken;
  let me = {};
  let opponent = {};
  let whiteMoves;
  let blackMoves;
  let showGameOverModal = false;
  let gameOverMessage = "";

  echo
    .channel("opponent_quit." + localStorage.getItem("hashedToken"))
    .listen("Quit", (e) => {
      if (e.quit) {
        leaveChannel("opponent_quit." + localStorage.getItem("hashedToken"));
        showModal = true;
      }
    });

    echo
    .channel("gameover." + localStorage.getItem("hashedToken"))
    .listen("GameOverEvent", (e) => {
      showGameOverModal = true;
      gameOverMessage = e.message;
      leaveChannel("gameover." + localStorage.getItem("hashedToken"));
    });

  echo
    .channel("move." + localStorage.getItem("hashedToken"))
    .listen("MoveEvent", (e) => {
      let oldPos = e.oldPos == null ? null : Number(e.oldPos);
      let newPos = Number(e.newPos);

      if (newPos == -1) {
        let index = playerStones.indexOf(oldPos);
        playerStones[index] = Number(e.newPos);
      } else {
        let index = opponentStones.indexOf(oldPos);
        opponentStones[index] = Number(e.newPos);
      }

      // its only my turn if i dont have to wait for a deletion
      yourTurn = !e.waitForDelete;

      leaveChannel("move." + localStorage.getItem("hashedToken"));
    });

  onMount(() => {
    AuthorizedGetRequest("game/data")
      .then((data) => {
        isWhite = data.user.is_white;
        yourTurn = data.user.has_turn;
        me = data.user;
        opponent = data.opponent;
        whiteMoves = data.white_moves;
        blackMoves = data.black_moves;
        deletionToken = data.user.deletion_token;

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

        if (deletionToken != "") {
          canDelete = true;
          selectedStone = null;
          opponentStonesInMill = GetStonesInMill(opponentStones);
        }

        playerStones.includes(null) ? (canSet = true) : (canSet = false);
      })
      .catch();

  });

  function clearVariables() {
    // clear possible moves and lines for now
    allMoves = [];
    possibleMoves = [];
    allMoveLines = [];
  }

  function selectStone(pos) {
    clearVariables();
    let allStones = playerStones.concat(opponentStones);

    if (playerStones.filter((x) => x != -1).length > 3) {
      possibleMoves = getPossibleMoves(pos, allStones);
      allMoves = getAllMoves(pos);

      for (let i = 0; i < allMoves.length; i++) {
        allMoveLines.push([pos, allMoves[i]]);
      }

      selectedStone = pos;
    }
    else
    {
      for(let i = 0; i < 24; i++)
      {
        if(!allStones.includes(i))
        {
          possibleMoves.push(i);
        }
      }

      selectedStone = pos;

    }
  }

  function setStone(pos) {
    playerStones[playerStones.indexOf(null)] = pos;
    if (!playerStones.includes(null)) canSet = false;

    AuthorizedGetRequest("game/stone/set/" + pos)
      .then((response) => {
        if (response != "") {
          canDelete = true;
          deletionToken = response;
          opponentStonesInMill = GetStonesInMill(opponentStones);
        } else {
          yourTurn = false;
        }
      })
      .catch((err) => {
        console.log(err);
      });
  }

  async function moveStone(pos) {
    if (selectedStone == null) return;
    console.log("Move method");

    let oldPos = selectedStone;
    playerStones = playerStones.filter((x) => x != oldPos);
    playerStones.push(pos);

    await AuthorizedRequest("game/stone/move", {
      old_position: oldPos,
      new_position: pos,
    })
      .then((response) => {
        if (response) {
          canDelete = true;
          deletionToken = response;
          selectedStone = null;
          opponentStonesInMill = GetStonesInMill(opponentStones);
        } else {
          yourTurn = false;
        }
      })
      .catch((err) => {
        console.log(err);
      });

    clearVariables();
  }

  async function RemoveStone(pos) {
    // implement deletion logic
    await AuthorizedRequest("game/stone/delete", {
      position: pos,
      deletion_token: deletionToken,
    })
      .then((response) => {
        let index = opponentStones.indexOf(pos);
        opponentStones[index] = -1;
        deletionToken = "";
        yourTurn = false;
        canDelete = false;
      })
      .catch((err) => {
        console.log(err);
      });
    opponentStonesInMill = [];
  }

  async function quitGame() {
    AuthorizedGetRequest("game/quit")
      .then(() => {
        navigate("/home");
      })
      .catch((err) => {
        console.log(err);
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

{#if showGameOverModal}
  <Modal on:close={() => (showModal = false)}>
    <h3>Game Over</h3>
    <p>{gameOverMessage}</p>
    <button
      on:click={() => {
        navigate("/home");
      }}>Ok</button
    >
  </Modal>
{/if}

<div class="container-fluid bgc-primary h-100">
  <div class="row pt-4">
    <div class="col-auto">
      <PlayerInfo user={me} hasTurn={yourTurn} />
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
        <!-- Line's for showing possible moves -->
        {#each allMoveLines as line (line)}
          <line
            x1="{positions[line[0]][0]}%"
            y1="{positions[line[0]][1]}%"
            x2="{positions[line[1]][0]}%"
            y2="{positions[line[1]][1]}%"
            stroke={possibleMoves.includes(line[1]) ? "green" : "red"}
            stroke-width="5"
            in:draw={{ duration: 1500, easing: quintOut }}
          />
        {/each}

        <!-- Game positino circles -->
        {#each positions as position, i (i)}
          <Circle
            status={gameState}
            x={position[0]}
            y={position[1]}
            index={i}
            isPossible={possibleMoves.includes(i)}
            isDisabled={(!possibleMoves.includes(i) && !canSet) ||
              playerStones.includes(i) ||
              opponentStones.includes(i) ||
              !yourTurn}
            on:click={canSet ? () => setStone(i) : () => moveStone(i)}
          />
        {/each}

        <!-- player stones -->
        {#each playerStones.filter((x) => x != null && x != -1) as stone (stone)}
          <Stone
            x={positions[stone][0]}
            y={positions[stone][1]}
            {isWhite}
            isSelected={stone == selectedStone}
            isDisabled={canSet || !yourTurn}
            on:click={() => selectStone(stone)}
          />
        {/each}

        <!-- opponent stones -->
        {#each opponentStones.filter((x) => x != null && x != -1) as stone}
          <Stone
            x={positions[stone][0]}
            y={positions[stone][1]}
            isWhite={!isWhite}
            isDisabled={!canDelete}
            isDeletable={canDelete && !opponentStonesInMill.includes(stone)}
            on:click={() => RemoveStone(stone)}
          />
        {/each}
      </svg>
    </div>
    <div class="col-auto">
      <PlayerInfo user={opponent} hasTurn={!yourTurn} />
      <svg class="none-played mt-2">
        {#each opponentStones.filter((x) => x === null) as stone, i}
          <Stone x={50} y={20 + 5 * i} isWhite={!isWhite} />
        {/each}
        <text class="unplayed-number" x="50%" y="90%">
          {opponentStones.filter((x) => x === null).length}
        </text>
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
