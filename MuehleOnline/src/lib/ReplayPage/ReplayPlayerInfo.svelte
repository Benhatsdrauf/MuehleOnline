<script>
  import { faBolt, faCrown } from "@fortawesome/free-solid-svg-icons";
  import Fa from "svelte-fa";

  export let user = {};
  export let hasTurn = false;
</script>

<div class="card" class:highlight={hasTurn} style="max-width: 10vw;">
  <div class="card-header bgc-secondary" style="padding-left: 8px !important;">
    <div class="row">
      <div class="col-auto" style="padding-right: 4px;">
        <img
          class="round-img"
          src="https://api.dicebear.com/5.x/initials/svg?backgroundType=gradientLinear&seed={user.name}"
          alt="profile"
          width="30px"
          height="30px"
        />
      </div>
      <div class="col c-text">
        <h4 class="text-nowrap text-truncate">{user.name}</h4>
      </div>
    </div>
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col">
        {#if user.won}
          <p>
            <Fa icon={faCrown} color="var(--color-win)" size="1.2x" />
            <b>Won</b>
          </p>
        {:else}
          <p>
            <Fa icon={faBolt} color="var(--color-los)" size="1.2x" />
            <b>Lost</b>
          </p>
        {/if}
      </div>
    </div>
    <div class="row">
      <div class="col">
        <p>
          <b>Elo:</b>
          <span class:text-win={user.won} class:text-los={!user.won}>
            {user.elo}
          </span>
        </p>
      </div>
    </div>
  </div>
</div>

<style>
  .highlight {
    border: 6px var(--color-success-light) solid !important;
  }

  .card {
    border: 6px var(--color-black) solid;
    border-radius: 10px;
  }

  .text-los {
    color: var(--color-los);
  }

  .text-win {
    color: var(--color-win);
  }
</style>
