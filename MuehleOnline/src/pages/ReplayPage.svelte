<script>
  import { onMount } from "svelte";
  import { useNavigate } from "svelte-navigator";
  import { AuthorizedRequest } from "../../scripts/request";
  import ColorIndicator from "../lib/GamePage/ColorIndicator.svelte";
  import GameField from "../lib/GamePage/GameField.svelte";
  import { positions } from "../../scripts/circlePositions";
  import GamePosition from "../lib/GamePage/GamePosition.svelte";
  import ReplayPlayerInfo from "../lib/ReplayPage/ReplayPlayerInfo.svelte";
  import Stone from "../lib/GamePage/Stone.svelte";
  import ReplayPlayer from "../lib/ReplayPage/ReplayPlayer.svelte";
  import Navbar from "../lib/Navbar.svelte";
  import Loading from "../lib/Loading.svelte";
  import MoveLog from "../lib/ReplayPage/MoveLog.svelte";
  import { newMessage, oldMessages } from "../../scripts/MoveLogStore";

  const navigate = useNavigate();
  let opponent = {};
  let me = null;
  let playerHistory = [];
  let playerActiveHistory = [];
  let playerCurrentStones = [];

  let opponentHistory = [];
  let opponentActiveHistory = [];
  let opponentCurrentStones = [];

  let replaySpeed;
  let autoPlayInterval;
  let playReplay;
  let winReason = "";
  let playerHasTurn = false;

  $: replaySpeed, changeInterval();

  onMount(() => {
    let path = window.location.pathname;
    let invite_id = path.split("/")[2];

    let body = {
      invite_id: invite_id,
    };

    AuthorizedRequest("replay/get", body)
      .then((data) => {
        me = data.user;
        opponent = data.opponent;

        opponent.won = !me.won;
        winReason = data.game.end_reason;

        data.user_moves.forEach((move) => {
          playerHistory = [
            ...playerHistory,
            {
              old_pos: move.old_pos,
              new_pos: move.new_pos,
              created_at: new Date(move.created_at),
            }
          ];
        });


        data.opponent_moves.forEach((move) => {
          opponentHistory = [
            ...opponentHistory,
            {
              old_pos: move.old_pos,
              new_pos: move.new_pos,
              created_at: new Date(move.created_at),
            },
          ];
        });
        // add the los message to the losers history
        let losMsg = { old_pos: -2, new_pos: 0, created_at: new Date() };

        if (me.won) {
          opponentHistory = [...opponentHistory, losMsg];
        } else {
          playerHistory = [...playerHistory, losMsg];
        }
        resetMoveLogStore();
      })
      .catch((err) => {
        try {
          err.json().then((response) => {
            if (response.message == "Unauthenticated.") {
              navigate("/");
            }
          });
        } catch (exception) {
          //ShowMessageModal();
        }
      });
  });

  function resetMoveLogStore() {
    $newMessage = {
      isOpponent: me.is_white,
      oldPos: -2,
      newPos: 0,
    };

    $oldMessages = [];
  }

  function restart() {
    playerHistory = playerHistory.concat(playerActiveHistory);
    playerActiveHistory = [];
    playerCurrentStones = [];

    opponentHistory = opponentHistory.concat(opponentActiveHistory);
    opponentActiveHistory = [];
    opponentCurrentStones = [];

    resetMoveLogStore();
  }

  function forward() {
    playerHistory.sort(sortByDateAsc);
    opponentHistory.sort(sortByDateAsc);

    if (playerHistory.length == 0 && opponentHistory.length == 0) {
      playReplay = false;
      pause();
      return;
    }

    let playerDate = new Date(playerHistory[0]?.created_at);

    let opponentDate = new Date(opponentHistory[0]?.created_at);

    if (isNaN(playerDate.getTime())) {
      playerDate = new Date();
    }

    if (isNaN(opponentDate.getTime())) {
      opponentDate = new Date();
    }

    if (playerDate < opponentDate) {
      let current = playerHistory[0];

      playerActiveHistory = [...playerActiveHistory, current];

      //check if stone gets set or moved
      if (current.old_pos == null) {
        playerCurrentStones = [...playerCurrentStones, current.new_pos];
      } else {
        playerCurrentStones[playerCurrentStones.indexOf(current.old_pos)] =
          current.new_pos;
        playerCurrentStones = playerCurrentStones;
      }

      playerHistory.splice(0, 1);
      addMessage(false, current.old_pos, current.new_pos);
    } else {
      let current = opponentHistory[0];

      opponentActiveHistory = [...opponentActiveHistory, current];

      //check if stone gets set or moved
      if (current.old_pos == null) {
        opponentCurrentStones = [...opponentCurrentStones, current.new_pos];
      } else {
        opponentCurrentStones[opponentCurrentStones.indexOf(current.old_pos)] =
          current.new_pos;
        opponentCurrentStones = opponentCurrentStones;
      }

      opponentHistory.splice(0, 1);
      addMessage(true, current.old_pos, current.new_pos);
    }
  }

  function play() {
    playReplay = true;
    autoPlayInterval = setInterval(function () {
      forward();
    }, (1000 / replaySpeed) * 100);
  }

  function pause() {
    playReplay = false;
    clearInterval(autoPlayInterval);
  }

  function backward() {
    playerActiveHistory.sort(sortByDateDesc);
    opponentActiveHistory.sort(sortByDateDesc);

    if (playerActiveHistory.length == 0 && opponentActiveHistory.length == 0) {
      return;
    }

    let playerDate = new Date(playerActiveHistory[0]?.created_at);

    let opponentDate = new Date(opponentActiveHistory[0]?.created_at);

    if (isNaN(playerDate.getTime())) {
      playerDate = new Date(0);
    }

    if (isNaN(opponentDate.getTime())) {
      opponentDate = new Date(0);
    }

    if (playerDate > opponentDate) {
      let current = playerActiveHistory[0];

      playerHistory = [...playerHistory, current];

      //check if stone gets set or moved
      if (current.old_pos == null) {
        playerCurrentStones.splice(
          playerCurrentStones.indexOf(current.new_pos),
          1
        );
      } else {
        playerCurrentStones[playerCurrentStones.indexOf(current.new_pos)] =
          current.old_pos;
      }

      //trigger reactivity
      playerCurrentStones = playerCurrentStones;

      playerActiveHistory.splice(0, 1);
    } else {
      let current = opponentActiveHistory[0];

      opponentHistory = [...opponentHistory, current];

      //check if stone gets set or moved
      if (current.old_pos == null) {
        opponentCurrentStones.splice(
          opponentCurrentStones.indexOf(current.new_pos),
          1
        );
      } else {
        opponentCurrentStones[opponentCurrentStones.indexOf(current.new_pos)] =
          current.old_pos;
      }

      //trigger reactivity
      opponentCurrentStones = opponentCurrentStones;

      opponentActiveHistory.splice(0, 1);
    }
    removeMessage();
  }

  function sortByDateDesc(a, b) {
    if (a.created_at < b.created_at) {
      return 1;
    }

    if (a.created_at > b.created_at) {
      return -1;
    }

    return 0;
  }

  function sortByDateAsc(a, b) {
    if (a.created_at > b.created_at) {
      return 1;
    }

    if (a.created_at < b.created_at) {
      return -1;
    }

    return 0;
  }

  function changeInterval() {
    if (playReplay) {
      pause();
      play();
    }
  }

  function addMessage(isOpponent, oldPos, newPos) {
    if ($newMessage.oldPos == -2 && $oldMessages.length == 0) {
    } else {
      $oldMessages = [...$oldMessages, $newMessage];
    }

    $newMessage = {
      isOpponent: isOpponent,
      oldPos: oldPos,
      newPos: newPos,
    };
  }

  function removeMessage() {
    if ($oldMessages.length > 0) {
      let copyOldMessages = $oldMessages.slice().reverse();

      $newMessage = copyOldMessages[0];

      $oldMessages.splice($oldMessages.indexOf(copyOldMessages[0]));
      $oldMessages = $oldMessages;
    } else {
      $newMessage = { isOpponent: true, oldPos: -2, newPos: 0 };
    }
  }

  $: $newMessage, changePlayerHasTurn();

  function changePlayerHasTurn()
  {
    playerHasTurn = !$newMessage.isOpponent;
  }
</script>

<Navbar>
  <div class="col-auto">
    <button
      type="button"
      class="btn btn-outline-danger"
      on:click={() => {
        navigate("/home");
      }}>Back</button
    >
  </div>
</Navbar>

<div
  class="bgc-primary h-100 w-100 d-flex flex-wrap flex-row pt-5 justify-content-center"
>
  {#if me == null}
    <div class="align-self-center">
      <Loading show={true} />
    </div>
  {:else}
    <div class="me-5">
      <MoveLog playerName={me.name} opponentName={opponent.name} {winReason} playerIsBlack={!me.is_white}/>
    </div>
    <div>
      <svg class="game-field">
        <GameField />
        <!-- Game position circles -->
        {#each positions as position, i (i)}
          <GamePosition
            {position}
            isPossible={false}
            isDisabled={true}
            on:click={() => {}}
          />
        {/each}

        <!-- player stones -->
        {#each playerCurrentStones.filter((x) => x != -1) as stone (stone)}
          <Stone
            x={positions[stone][0]}
            y={positions[stone][1]}
            isWhite={me.is_white}
            isSelected={false}
            isDisabled={true}
            on:click={() => {}}
          />
        {/each}

        <!-- opponent stones -->
        {#each opponentCurrentStones.filter((x) => x != -1) as stone (stone)}
          <Stone
            x={positions[stone][0]}
            y={positions[stone][1]}
            isWhite={!me.is_white}
            isSelected={false}
            isDisabled={true}
            on:click={() => {}}
          />
        {/each}
      </svg>
    </div>

    <div class="d-flex flex-column ms-5">
      <div class="d-flex flex-row">
        <div>
          <ColorIndicator isWhite={!me.is_white} />
          <div class="mt-3">
            <ReplayPlayerInfo user={opponent} hasTurn={playerHasTurn} />
          </div>
        </div>
        <div class="ms-5">
          <ColorIndicator isWhite={me.is_white} />
          <div class="mt-3">
            <ReplayPlayerInfo user={me} hasTurn={!playerHasTurn} />
          </div>
        </div>
      </div>
      <div class="mt-4 align-self-center">
        <ReplayPlayer
          bind:isPlay={playReplay}
          bind:replaySpeed
          on:restart={restart}
          on:forward={forward}
          on:play={play}
          on:pause={pause}
          on:backward={backward}
        />
      </div>
    </div>
  {/if}
</div>
