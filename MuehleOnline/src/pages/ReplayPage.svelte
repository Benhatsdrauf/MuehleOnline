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

  let opponentHistory = [];
  let opponentStones = [];

  onMount(() => {
    let path = window.location.pathname;
    let invite_id = path.split("/")[2];

    console.log(invite_id);

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

        playerStones = playerHistory;
        opponentStones = opponentHistory;

        console.log(playerStones);
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
        {#each playerStones as stone (stone)}
          <Stone
            x={positions[stone.new_pos][0]}
            y={positions[stone.new_pos][1]}
            isWhite={me.is_white}
            isSelected={false}
            isDisabled={true}
            on:click={() => {}}
          />
        {/each}

        <!-- opponent stones -->
        {#each opponentStones as stone (stone)}
          <Stone
            x={positions[stone.new_pos][0]}
            y={positions[stone.new_pos][1]}
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
        <ReplayPlayer/>
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
