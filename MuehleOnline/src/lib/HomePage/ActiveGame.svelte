<script>
  import Countdown from "../Countdown.svelte";
  import Fa from "svelte-fa";
  import { faGamepad } from "@fortawesome/free-solid-svg-icons";
  import { navigate } from "svelte-navigator";
  import { AuthorizedGetRequest } from "../../../scripts/request";

  export let ttm;
  export let visible = false;
</script>

{#if visible}
  <div class="card card-border">
    <div class="card-header bgc-secondary">
      <div class="row">
        <div class="col-auto">
          <Fa icon={faGamepad} color="#ffffff" size="2x" />
        </div>
        <div class="col c-text">
          <h3>Active Game</h3>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col">
          <p>You still have one active game.</p>
        </div>
      </div>
      <div class="row">
        <div class="col-auto">
          <p>You have <Countdown date={ttm} /> to join left.</p>
        </div>
        <div class="col-auto">
          <button
            type="button"
            class="btn btn-success"
            on:click={() => {
              navigate("/gamePage");
            }}>Re-join</button
          >
        </div>
        <div class="col-auto">
          <button type="button" class="btn btn-danger" on:click>Quit</button>
        </div>
      </div>
    </div>
  </div>
{/if}
