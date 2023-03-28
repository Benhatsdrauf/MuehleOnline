<script>
  import Fa from "svelte-fa";
  import { faClockRotateLeft } from "@fortawesome/free-solid-svg-icons";
  import Loading from "../Loading.svelte";
  import GameHistory from "./GameHistory.svelte";

  export let gameHistory = null;
</script>

<div class="card card-border">
  <div class="card-header bgc-secondary">
    <div class="row">
      <div class="col-auto">
        <Fa icon={faClockRotateLeft} color="#ffffff" size="2x" />
      </div>
      <div class="col c-text">
        <h3>Game History ({gameHistory?.length ?? ""})</h3>
      </div>
    </div>
  </div>
  <div class="card-body" />
  {#if gameHistory == null}
  <div class="mb-4">
    <Loading show={true} />
  </div>
  {:else if gameHistory.length === 0}
  <h6 class="text-center mb-4">No games found.</h6>
  {:else}
    <div class="container history">
      {#each gameHistory as game}
        <div class="mb-2">
          <GameHistory
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
