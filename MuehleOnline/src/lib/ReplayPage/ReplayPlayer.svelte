<script>
  import {
    faBackward,
    faForward,
    faPause,
    faPlay,
    faRotateRight,
  } from "@fortawesome/free-solid-svg-icons";
  import Fa from "svelte-fa";
  import { createEventDispatcher } from "svelte";

  export let replaySpeed = 100;

  export let isPlay = false;

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

  function restart() {
    dispatch("restart", {});
  }
</script>

<div class="card" style="width: 18rem">
  <div class="card-body">
    <div class="d-flex flex-row">
      <p class="flex-fill"><b>Replay Speed:</b> {replaySpeed}%</p>
      <button class="btn" on:click={restart}>
        <Fa icon={faRotateRight} size="1.2x"/>
      </button>
    </div>
    <div class="d-flex flex-row">
      <input
        type="range"
        class="form-range"
        min="25"
        max="175"
        step="25"
        bind:value={replaySpeed}
      />
    </div>
    <div class="d-flex flex-row">
      <button class="btn flex-fill" on:click={backward}>
        <Fa icon={faBackward} />
      </button>
      {#if isPlay}
        <button class="btn flex-fill" on:click={pause}>
          <Fa icon={faPause} />
        </button>
      {:else}
        <button class="btn flex-fill" on:click={play}>
          <Fa icon={faPlay} />
        </button>
      {/if}
      <button class="btn flex-fill" on:click={forward}>
        <Fa icon={faForward} />
      </button>
    </div>
  </div>
</div>

<style>
  .card {
    border: 6px var(--color-black) solid;
    border-radius: 10px;
  }
</style>
