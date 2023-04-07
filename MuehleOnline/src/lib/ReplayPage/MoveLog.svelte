<script>
  import { fade, fly } from "svelte/transition";
  import { oldMessages, newMessage } from "../../../scripts/MoveLogStore";

  import MoveLogEntry from "./MoveLogEntry.svelte";

  export let playerName = "";
  export let opponentName = "";
  export let playerIsBlack = true;
  export let winReason = "";

  let animateFlag = true;

  function MessageChange()
  {
    animateFlag = !animateFlag;
  }

  $: $newMessage, MessageChange();
</script>

<div class="card">
  <div class="card-body card-size">
    {#if $newMessage.oldPos == -2 && $oldMessages.length == 0}
    <!--Game Start-->
    <h2 in:fly={{x: 100, duration: 200}}>
      <span class="white">
        {(playerIsBlack)? opponentName : playerName}
      </span>
      <span class="text-primary">
        has first move
      </span>
    </h2>
    {:else if $newMessage.oldPos == -2 && $oldMessages.length > 0}
    <!--Game End (show los message)-->
        <h2 in:fly={{x: 100, duration: 200}}><span class="white los-underline" class:brown={$newMessage.isOpponent && !playerIsBlack}>
          {($newMessage.isOpponent) ? opponentName : playerName}
        </span> 
        <span class="text-primary">
          {winReason}
        </span></h2>
    {:else}
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

  .white {
    color: var(--color-white) !important;
  }

  .brown {
    color: var(--color-terra) !important;
  }

  .los-underline {
    text-decoration: underline var(--color-los) 4px;
    text-underline-offset: 5px;
  }
</style>
