<script>
  import { oldMessages, newMessage } from "../../../scripts/MoveLogStore";

  export let playerName = "";
  export let opponentName = "";
  export let playerIsBlack = true;
</script>

<div class="card">
  <div class="card-body card-size">
    <h2>
      {#if $newMessage.action != ""}
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
      {/if}
    </h2>
    <hr />
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

  .card-size {
    width: 30vw;
    height: 76vh;
    overflow: auto;
  }
</style>
