<script>
  import GameField from "../lib/GamePage/GameField.svelte";


  let path = window.location.pathname;
  let invite_id = path.split("/")[2];

  console.log(invite_id);
</script>

<div class="container-fluid bgc-primary h-100">
  <div class="col flex-column d-flex">
    <svg class="game-field align-self-center">
      <GameField />
      <!-- Line's for showing possible moves -->
      <!--<PossibleMoveLines {allMoveLines} {possibleMoves} />-->

      <!-- Game position circles -->
      <!--
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
          -->

      <!-- player stones -->
      <!--
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
  
          -->
    </svg>
  </div>
  <div class="col-auto">
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
