<script>
  import Fa from "svelte-fa";
  import { faClockRotateLeft } from "@fortawesome/free-solid-svg-icons";
  import Loading from "../Loading.svelte";
  import GameHistory from "../GameHistory.svelte";

  export let gameHistory = [];
  export let username = "";
</script>

<div class="card card-border">
  <div class="card-header bgc-secondary">
    <div class="row">
      <div class="col-auto">
        <Fa icon={faClockRotateLeft} color="#ffffff" size="2x" />
      </div>
      <div class="col c-text">
        <h3>Game History</h3>
      </div>
    </div>
  </div>
  <div class="card-body" />
  {#if gameHistory.length == 0}
    <Loading show={true} />
  {:else}
    <div class="container history">
      {#each gameHistory as game}
        <div class="mb-2">
          <GameHistory
            count={gameHistory.indexOf(game) + 1}
            won={game.won}
            opponent={game.opponent}
            playtime={game.play_time}
            start_date={game.start_date}
            elo={game.elo}
          />
        </div>
      {/each}
    </div>
  {/if}
</div>

<style>
      .history {
    height: 56vh;
    overflow-y: auto;
  }
</style>
