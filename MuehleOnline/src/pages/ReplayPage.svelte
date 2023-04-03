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

  const navigate = useNavigate();
  let opponent = {};
  let me = {};
  let playerHistory = [];
  let playerStones = [];
  let playerCurrent = [];

  let opponentHistory = [];
  let opponentStones = [];
  let opponentCurrent = [];

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

  function forward() {
    playerHistory.sort(sortByDateAsc);
    opponentHistory.sort(sortByDateAsc);

    if (playerHistory.length == 0 && opponentHistory.length == 0) {
      return;
    }

    let playerDate = new Date(playerHistory[0]?.created_at);

    let opponentDate = new Date(opponentHistory[0]?.created_at);

    if(isNaN(playerDate.getTime()))
    {
      playerDate = new Date(0);
    }

    if(isNaN(opponentDate.getTime()))
    {
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
    console.log(playerCurrent);
    console.log(opponentCurrent);
  }

  function pause() {}

  function backward() {
    playerStones.sort(sortByDateDesc);
    opponentStones.sort(sortByDateDesc);

    if (playerStones.length == 0 && opponentStones.length == 0) {
      return;
    }

    let playerDate = new Date(playerStones[0]?.created_at);

    let opponentDate = new Date(opponentStones[0]?.created_at);

    if(isNaN(playerDate.getTime()))
    {
      playerDate = new Date(0);
    }

    if(isNaN(opponentDate.getTime()))
    {
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
      } else {
        playerCurrent[playerCurrent.indexOf(oldPos)] = newPos;
        playerCurrent = playerCurrent;
      }
    }
    else
    {
      if (oldPos == null) {
        opponentCurrent = [...opponentCurrent, newPos];
      } else {
        opponentCurrent[opponentCurrent.indexOf(oldPos)] = newPos;
        opponentCurrent = opponentCurrent;
      }
    }
  }

  function StoneBackward(oldPos, newPos, isPlayer)
  {
    if(isPlayer)
    {
      if(oldPos == null)
      {
        playerCurrent.splice(playerCurrent.indexOf(newPos), 1);
      }
      else
      {
        playerCurrent[playerCurrent.indexOf(newPos)] = oldPos;
      }

      playerCurrent = playerCurrent;
    }
    else
    {
      if(oldPos == null)
      {
        opponentCurrent.splice(opponentCurrent.indexOf(newPos), 1);
      }
      else
      {
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
</script>

<div class="container-fluid bgc-primary h-100">
  <div class="row">
    <div class="col flex-column d-flex">
      <svg class="game-field align-self-center">
        <GameField />
        <!-- Line's for showing possible moves -->
        <!--<PossibleMoveLines {allMoveLines} {possibleMoves} />-->

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
    <div class="col-auto">
      <ColorIndicator isWhite={me.is_white} />
      <ReplayPlayerInfo user={me} hasTurn={false} />
    </div>
    <div class="col-auto">
      <ColorIndicator isWhite={!me.is_white} />
      <ReplayPlayerInfo user={opponent} hasTurn={false} />
    </div>
    <div class="col">
      <ReplayPlayer
        on:forward={forward}
        on:play={play}
        on:pause={pause}
        on:backward={backward}
      />
    </div>
  </div>
  <div class="col">
    <!--
              <PlayerInfo user={me} hasTurn={yourTurn} />
              <svg class="none-played mt-2">
                {#each playerStones.filter((x) => x === null) as stone, i}
                  <Stone x={50} y={20 + 5 * i} {isWhite} />
                {/each}
                <text class="unplayed-number" x="50%" y="90%"
                  >{playerStones.filter((x) => x === null).length}</text
                >
              </svg>
          -->
  </div>
</div>
