<script>
  import {
    faBackward,
    faForward,
    faPause,
    faPlay,
  } from "@fortawesome/free-solid-svg-icons";
  import Fa from "svelte-fa";
  import { createEventDispatcher } from "svelte";

  let isPlay = true;

  const dispatch = createEventDispatcher();

  function backward() {
    dispatch("backward", {});
  }

  function play() {
    isPlay = false;
    dispatch("play", {});
  }

  function pause() {
    isPlay = true;
    dispatch("pause", {});
  }

  function forward() {
    dispatch("forward", {});
  }
</script>

<div class="card" style="width: 18rem">
  <div class="card-body d-flex flex-row">
    <button class="btn flex-fill" on:click={backward}>
      <Fa icon={faBackward} />
    </button>
    {#if isPlay}
      <button class="btn flex-fill" on:click={play}>
        <Fa icon={faPlay} />
      </button>
    {:else}
      <button class="btn flex-fill" on:click={pause}>
        <Fa icon={faPause} />
      </button>
    {/if}
    <button class="btn flex-fill" on:click={forward}>
      <Fa icon={faForward} />
    </button>
  </div>
</div>

<style>
  .card {
    border: 6px var(--color-black) solid;
    border-radius: 10px;
  }
</style>
