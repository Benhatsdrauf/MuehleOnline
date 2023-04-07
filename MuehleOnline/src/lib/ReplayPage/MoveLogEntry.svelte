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
  <!-- && newPos != -1 means delete and so it has to switch the user-->
    <span class="white" class:brown={(Move.isOpponent && !playerIsBlack)  && Move.newPos != -1}>
        {Move.isOpponent && Move.newPos != -1 ? opponentName : playerName}
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
</div>


<style>
      .white {
    color: var(--color-white) !important;
  }

  .brown {
    color: var(--color-terra) !important;
  }
</style>
