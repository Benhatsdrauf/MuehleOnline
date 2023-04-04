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
  let playerStones = [];
  let playerCurrent = [];

  let opponentHistory = [];
  let opponentStones = [];
  let opponentCurrent = [];

  let replaySpeed;
  let autoPlayInterval;
  let playReplay;

  $: replaySpeed, changeInterval();

  onMount(() => {
    let path = window.location.pathname;
    let invite_id = path.split("/")[2];

    resetMoveLogStore();

    let body = {
      invite_id: invite_id,
    };

    AuthorizedRequest("replay/get", body)
      .then((data) => {
        me = data.user;
        opponent = data.opponent;

        opponent.won = !me.won;

        playerHistory = data.user_moves;
        opponentHistory = data.opponent_moves;

        //playerStones = playerHistory;
        //opponentStones = opponentHistory;
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


  function resetMoveLogStore()
  {
    $newMessage = {
      isOpponent: false,
      action: "",
      coordinate: ""
    }

    $oldMessages = [];
  }

  function restart() {
    playerHistory = playerHistory.concat(playerStones);
    playerStones = [];
    playerCurrent = [];

    opponentHistory = opponentHistory.concat(opponentStones);
    opponentStones = [];
    opponentCurrent = [];

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
      playerDate = new Date(0);
    }

    if (isNaN(opponentDate.getTime())) {
      opponentDate = new Date();
    }

    if (playerDate < opponentDate) {
      playerStones = [...playerStones, playerHistory[0]];
      let current = playerHistory[0];
      StoneForward(current.old_pos, current.new_pos, true);

      playerHistory.splice(0, 1);
    } else {
      opponentStones = [...opponentStones, opponentHistory[0]];
      let current = opponentHistory[0];
      StoneForward(current.old_pos, current.new_pos, false);

      opponentHistory.splice(0, 1);
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
    playerStones.sort(sortByDateDesc);
    opponentStones.sort(sortByDateDesc);

    if (playerStones.length == 0 && opponentStones.length == 0) {
      return;
    }

    let playerDate = new Date(playerStones[0]?.created_at);

    let opponentDate = new Date(opponentStones[0]?.created_at);

    if (isNaN(playerDate.getTime())) {
      playerDate = new Date(0);
    }

    if (isNaN(opponentDate.getTime())) {
      opponentDate = new Date();
    }

    if (playerDate > opponentDate) {
      playerHistory = [...playerHistory, playerStones[0]];

      let current = playerStones[0];
      StoneBackward(current.old_pos, current.new_pos, true);
      playerStones.splice(0, 1);
      playerStones = playerStones;
    } else {
      opponentHistory = [...opponentHistory, opponentStones[0]];

      let current = opponentStones[0];
      StoneBackward(current.old_pos, current.new_pos, false);
      opponentStones.splice(0, 1);
      opponentStones = opponentStones;
    }
  }

  function StoneForward(oldPos, newPos, isPlayer) {
    if (isPlayer) {
      if (oldPos == null) {
        playerCurrent = [...playerCurrent, newPos];
        addMessage(false, "set a stone to", newPos);
      } else {
        playerCurrent[playerCurrent.indexOf(oldPos)] = newPos;
        playerCurrent = playerCurrent;

        if (newPos == -1) {
          addMessage(true, "removed a stone from position", oldPos);
        } else {
          addMessage(false, "moved a stone from " + oldPos + " to", newPos);
        }
      }
    } else {
      if (oldPos == null) {
        opponentCurrent = [...opponentCurrent, newPos];
        addMessage(true, "set a stone to", newPos);
      } else {
        opponentCurrent[opponentCurrent.indexOf(oldPos)] = newPos;
        opponentCurrent = opponentCurrent;

        if (newPos == -1) {
          addMessage(false, "removed a stone from position", oldPos);
        } else {
          addMessage(true, "moved a stone from " + oldPos + " to", newPos);
        }
      }
    }
  }

  function StoneBackward(oldPos, newPos, isPlayer) {
    removeMessage();
    if (isPlayer) {
      if (oldPos == null) {
        playerCurrent.splice(playerCurrent.indexOf(newPos), 1);
      } else {
        playerCurrent[playerCurrent.indexOf(newPos)] = oldPos;
      }

      playerCurrent = playerCurrent;
    } else {
      if (oldPos == null) {
        opponentCurrent.splice(opponentCurrent.indexOf(newPos), 1);
      } else {
        opponentCurrent[opponentCurrent.indexOf(newPos)] = oldPos;
      }
      opponentCurrent = opponentCurrent;
    }
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

  function addMessage(isOpponent, action, newCoordinate) {
    if ($newMessage.action != "") {
      $oldMessages = [...$oldMessages, $newMessage];
    }

    $newMessage = {
      isOpponent: isOpponent,
      action: action,
      coordinate: newCoordinate,
    };
  }

  function removeMessage() {
    if ($oldMessages.length > 0) {
      let copyOldMessages = $oldMessages.slice().reverse();
      console.log(copyOldMessages);

      $newMessage = copyOldMessages[0];

      $oldMessages.splice($oldMessages.indexOf(copyOldMessages[0]));
      $oldMessages = $oldMessages;

    } else {
      $newMessage = { isOpponent: true, action: "", coordinate: "" };
    }
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

<div class="bgc-primary h-100 w-100 d-flex flex-row p-5 justify-content-center">
  {#if me == null}
    <div class="align-self-center">
      <Loading show={true} />
    </div>
  {:else}
    <div class="me-5">
      <MoveLog playerName={me.name} opponentName={opponent.name} />
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
        {#each playerCurrent.filter((x) => x != -1) as stone (stone)}
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
        {#each opponentCurrent.filter((x) => x != -1) as stone (stone)}
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
            <ReplayPlayerInfo user={opponent} hasTurn={false} />
          </div>
        </div>
        <div class="ms-5">
          <ColorIndicator isWhite={me.is_white} />
          <div class="mt-3">
            <ReplayPlayerInfo user={me} hasTurn={false} />
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
