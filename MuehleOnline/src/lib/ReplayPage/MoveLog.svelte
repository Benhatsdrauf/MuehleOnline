<script>
  import { fade, fly } from "svelte/transition";
  import { oldMessages, newMessage } from "../../../scripts/MoveLogStore";

  import MoveLogEntry from "./MoveLogEntry.svelte";

  export let playerName = "";
  export let opponentName = "";
  export let playerIsBlack = true;

  let animateFlag = true;

  function MessageChange()
  {
    animateFlag = !animateFlag;
  }

  $: $newMessage, MessageChange();
</script>

<div class="card">
  <div class="card-body card-size">
    {#if $newMessage.oldPos != -2}
      {#if animateFlag}
        <h2 in:fly={{x: 100, duration: 200}}>
          <b>
            <MoveLogEntry
              {playerIsBlack}
              {playerName}
              {opponentName}
              Move={$newMessage}
            />
          </b>
        </h2>
      {:else}
        <h2 in:fly={{x: 100, duration: 200}}>
          <b>
            <MoveLogEntry
              {playerIsBlack}
              {playerName}
              {opponentName}
              Move={$newMessage}
            />
          </b>
        </h2>
      {/if}
    {:else}
      <h2 in:fly={{x: 100, duration: 200}} class="text-white">Game Start</h2>
    {/if}
    <hr />
    <div class="list-size">
      {#each $oldMessages.slice().reverse() as olderMessage (olderMessage)}
        <h5
          in:fly={{ y: -10, duration: 200 }}
          out:fly={{ y: -10, duration: 200 }}
        >
          <MoveLogEntry
            {playerIsBlack}
            {playerName}
            {opponentName}
            Move={olderMessage}
          />
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
