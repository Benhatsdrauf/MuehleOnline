<script>
  import { faMagnifyingGlass } from "@fortawesome/free-solid-svg-icons";
  import Fa from "svelte-fa";
  import { coordinates } from "../../../scripts/circlePositions";
  export let playerIsBlack = false;
  export let opponentName = "";
  export let playerName = "";

  export let Move = {
    oldPos: -2,
    newPos: 0,
    isOpponent: false
  };
</script>

<div>
    <span class="white" class:brown={Move.isOpponent && playerIsBlack}>
        {Move.isOpponent ? opponentName : playerName}
      </span>
      {#if Move.oldPos == null}
        set stone to
        <span class="text-primary">
          {coordinates[Move.newPos]}
        </span>
      {:else if Move.newPos == -1}
        removed stone from
        <span class="text-primary">
          {coordinates[Move.oldPos]}
        </span>
      {:else}
        moved stone from
        <span class="text-primary">
          {coordinates[Move.oldPos]}
        </span>
        to
        <span class="text-primary">
          {coordinates[Move.newPos]}
        </span>
      {/if}
      <button class="btn">

    </button>
</div>


<style>
      .white {
    color: var(--color-white) !important;
  }

  .brown {
    color: var(--color-terra) !important;
  }
</style>
