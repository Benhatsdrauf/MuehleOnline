<script>
  import { oldMessages, newMessage } from "../../../scripts/MoveLogStore";

  export let playerName = "";
  export let opponentName = "";
  export let playerIsBlack = true;
</script>

<div class="card">
  <div class="card-body card-size">
    {#if $newMessage.action != ""}
      <h2>
        <b
          ><span
            class="white"
            class:brown={!$newMessage.isOpponent && playerIsBlack}
          >
            {$newMessage.isOpponent ? opponentName : playerName}
          </span>{$newMessage.action}
          <span class="text-primary">
            {$newMessage.coordinate}
          </span></b
        >
      </h2>
    {:else}
      <h2 class="text-white">Move Log</h2>
    {/if}
    <hr />
    <div class="list-size">
      {#each $oldMessages.slice().reverse() as olderMessage}
        <h5>
          <span
            class="white"
            class:brown={!olderMessage.isOpponent && playerIsBlack}
          >
            {olderMessage.isOpponent ? opponentName : playerName}
          </span>{olderMessage.action}
          <span class="text-primary">
            {olderMessage.coordinate}
          </span>
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

  .white {
    color: var(--color-white) !important;
  }

  .brown {
    color: var(--color-terra-dark) !important;
  }

  hr {
    border: 2px white solid;
  }

  .list-size {
    height: 65vh;
    overflow-y: auto;
  }

  .card-size
  {
    width: 35vw;
  }
</style>
