<script>
  import { coordinates } from "../../../scripts/circlePositions";
  import { oldMessages, newMessage } from "../../../scripts/MoveLogStore";
  import MoveLogEntry from "./MoveLogEntry.svelte";

  export let playerName = "";
  export let opponentName = "";
  export let playerIsBlack = true;
</script>

<div class="card">
  <div class="card-body card-size">
    {#if $newMessage.oldPos != -2}
      <h2>
        <b>
          <MoveLogEntry playerIsBlack={playerIsBlack} playerName={playerName} opponentName={opponentName} Move={$newMessage}/>
        </b>
      </h2>
    {:else}
      <h2 class="text-white">Game Start</h2>
    {/if}
    <hr />
    <div class="list-size">
      {#each $oldMessages.slice().reverse() as olderMessage}
        <h5>
          <MoveLogEntry playerIsBlack={playerIsBlack} playerName={playerName} opponentName={opponentName} Move={olderMessage}/>
        </h5>
      {/each}
    </div>
  </div>
</div>

<style>
  .card {
    border: 6px var(--color-black) solid;
    border-radius: 10px;
    background-color: var(--color-dark-grey);
    color: var(--color-steel-400);
  }

  hr {
    border: 2px white solid;
  }

  .list-size {
    height: 65vh;
    overflow-y: auto;
  }

  .card-size {
    width: 34vw;
  }
</style>
