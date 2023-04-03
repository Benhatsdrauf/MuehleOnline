<script>
  import {
    AuthorizedRequest,
    AuthorizedGetRequest,
  } from "../../scripts/request";
  import { onMount } from "svelte";
  import { positions } from "../../scripts/circlePositions";
  import Stone from "../lib/GamePage/Stone.svelte";
  import Navbar from "../lib/Navbar.svelte";
  import { useNavigate } from "svelte-navigator";
  import PlayerInfo from "../lib/GamePage/PlayerInfo.svelte";
  import { echo, leaveChannel } from "../../scripts/echo";
  import Modal from "../lib/Modal.svelte";
  import {
    getPossibleMoves,
    getAllMoves,
    GetStonesInMill,
  } from "../../scripts/gameLogic";
  import GameField from "../lib/GamePage/GameField.svelte";
  import PossibleMoveLines from "../lib/GamePage/PossibleMoveLines.svelte";
  import GamePosition from "../lib/GamePage/GamePosition.svelte";
  import ColorIndicator from "../lib/GamePage/ColorIndicator.svelte";
  import Fa from "svelte-fa";
  import { faBolt, faCrown } from "@fortawesome/free-solid-svg-icons";
  import { confetti } from "@neoconfetti/svelte";
  import MessageModal from "../lib/MessageModal.svelte";
  import GameStatus from "../lib/GamePage/GameStatus.svelte";
  import Loading from "../lib/Loading.svelte";

  const navigate = useNavigate();

  let showModal = false;

  let playerStones = [null, null, null, null, null, null, null, null, null];
  let opponentStones = [null, null, null, null, null, null, null, null, null];
  let opponentStonesInMill = [];
  let allMoves = [];
  let possibleMoves = [];
  let allMoveLines = [];
  let selectedStone = null;

  let ttm = new Date();
  let canSet = true;
  let canDelete = false;
  let isWhite = false;
  let yourTurn = false;
  let deletionToken;
  let me = null;
  let opponent = {};
  let whiteMoves;
  let blackMoves;

  let showGameOverModal = false;
  let gameOverMessage = "";
  let isWinner = false;

  let messageModalmessage = "";
  let messageModalShow = false;
  let messageModalIsError = true;

  echo
    .channel("gameover." + localStorage.getItem("hashedToken"))
    .listen("GameOverEvent", (e) => {
      showGameOverModal = true;
      gameOverMessage = e.message;
      isWinner = e.won;
      leaveChannel("gameover." + localStorage.getItem("hashedToken"));
    });

  echo
    .channel("move." + localStorage.getItem("hashedToken"))
    .listen("MoveEvent", (e) => {
      let oldPos = e.oldPos == null ? null : Number(e.oldPos);
      let newPos = Number(e.newPos);
      ttm = new Date(e.ttm);

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
        ttm = new Date(data.ttm);

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
      .catch((err) => {
        try {
          err.json().then((response) => {
            if (response.message == "Unauthenticated.") {
              navigate("/");
            }
          });
        } catch (exception) {
          ShowMessageModal();
        }
      });
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
    } else {
      for (let i = 0; i < 24; i++) {
        if (!allStones.includes(i)) {
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

        ttm = new Date(response.ttm);

        if (response.deletion_token != "") {
          canDelete = true;
          deletionToken = response.deletion_token;
          opponentStonesInMill = GetStonesInMill(opponentStones);
        } else {
          yourTurn = false;
        }
      })
      .catch((err) => {
        try {
          err.json().then((response) => {
            if (response.errors.game) {
              ShowMessageModal(response.errors.game);
            }
          });
        } catch (exception) {
          ShowMessageModal();
        }
      });
  }

  function moveStone(pos) {
    if (selectedStone == null) return;

    let oldPos = selectedStone;
    playerStones = playerStones.filter((x) => x != oldPos);
    playerStones.push(pos);

    AuthorizedRequest("game/stone/move", {
      old_position: oldPos,
      new_position: pos,
    })
      .then((response) => {

        ttm = new Date(response.ttm);

        if (response.deletion_token != "") {
          canDelete = true;
          deletionToken = response.deletion_token;
          selectedStone = null;
          opponentStonesInMill = GetStonesInMill(opponentStones);
        } else {
          yourTurn = false;
        }
      })
      .catch((err) => {
        try {
          err.json().then((response) => {
            if (response.errors.game) {
              ShowMessageModal(response.errors.game);
            }
          });
        } catch (exception) {
          ShowMessageModal();
        }
      });
    clearVariables();
  }

  function RemoveStone(pos) {
    if (opponentStonesInMill.includes(pos)) {
      return;
    }

    AuthorizedRequest("game/stone/delete", {
      position: pos,
      deletion_token: deletionToken,
    })
      .then((response) => {
        ttm = new Date(response.ttm);

        let index = opponentStones.indexOf(pos);
        opponentStones[index] = -1;
        deletionToken = "";
        yourTurn = false;
        canDelete = false;
        opponentStonesInMill = [];
      })
      .catch((err) => {
        try {
          err.json().then((response) => {
            if (response.errors.game) {
              ShowMessageModal(response.errors.game);
            }
          });
        } catch (exception) {
          ShowMessageModal();
        }
      });
  }

  function quitGame() {
    AuthorizedGetRequest("game/quit")
      .then(() => {
        navigate("/home");
      })
      .catch((err) => {
        try {
          err.json().then((response) => {
            if (response.errors.game) {
              if (response.message == "Unauthenticated.") {
                navigate("/");
              }
            }
          });
        } catch (exception) {
          ShowMessageModal();
        }
      });
  }

  function ShowMessageModal(
    message = "Faild to connect to server, please try again later.",
    IsError = true
  ) {
    messageModalmessage = message;
    messageModalIsError = IsError;
    messageModalShow = true;
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
      }}>OK</button
    >
  </Modal>
{/if}

{#if showGameOverModal}
  <div
    class="confetti-pos"
    stageWidth={600}
    use:confetti={{ particleCount: 500, force: 0.7, duration: 10000 }}
  />
  <Modal on:close={() => (showModal = false)}>
    <div slot="header">
      <div class="row">
        {#if isWinner}
          <div class="col-auto">
            <Fa icon={faCrown} color="var(--color-win)" size="1.6x" />
          </div>
          <div class="col">
            <h4><b>Victory</b></h4>
          </div>
        {:else}
          <div class="col-auto">
            <Fa icon={faBolt} color="var(--color-los)" size="2x" />
          </div>
          <div class="col">
            <h4><b>Loss</b></h4>
          </div>
        {/if}
      </div>
    </div>
    <div class="row mt-3">
      <div class="col">
        <h6>{gameOverMessage}</h6>
      </div>
    </div>
    <div class="row justify-content-end">
      <div class="col-auto">
        <button
          class="btn btn-outline-primary"
          on:click={() => {
            navigate("/home");
          }}>OK</button
        >
      </div>
    </div>
  </Modal>
{/if}

<div class="container-fluid bgc-primary h-100">
  {#if me == null}
  <div class="row pt-5">
    <Loading show={true}/>
  </div>
  {:else}
  <div class="row pt-4">
    <div class="col-auto">
      <div class="mb-2">
        <ColorIndicator isWhite={!isWhite} />
      </div>
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
    <div class="col flex-column d-flex">
      <div class="align-self-center mb-2 status-box">
        <GameStatus {ttm} {yourTurn} {isWhite} />
      </div>

      <svg class="game-field align-self-center">
        <GameField />
        <!-- Line's for showing possible moves -->
        <PossibleMoveLines {allMoveLines} {possibleMoves} />

        <!-- Game position circles -->
        {#each positions as position, i (i)}
          <GamePosition
            {position}
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
        {#each opponentStones.filter((x) => x != null && x != -1) as stone (stone)}
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
      <div class="mb-2">
        <ColorIndicator {isWhite} />
      </div>
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
  </div>
  {/if}
</div>

<MessageModal
  bind:showModal={messageModalShow}
  isError={messageModalIsError}
  message={messageModalmessage}
/>

<style>

  .confetti-pos {
    z-index: 10;
    position: absolute;
    left: 50%;
    top: 20%;
  }
</style>
