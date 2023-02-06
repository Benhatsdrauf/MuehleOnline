<script>
  import Fa from "svelte-fa";
  import { faChartColumn } from "@fortawesome/free-solid-svg-icons";
  import Loading from "../Loading.svelte";

  export let dataObject = null;
</script>

<div class="card card-border">
  <div class="card-header bgc-secondary">
    <div class="row">
      <div class="col-auto">
        <Fa icon={faChartColumn} color="#ffffff" size="2x" />
      </div>
      <div class="col c-text">
        <h3>Statistics</h3>
      </div>
    </div>
  </div>
  <div class="card-body">
    {#if dataObject == null}
      <Loading show="{true}"/>
    {:else}
      <div class="row">
        <div class="col">
            <p>Won: {dataObject.won}</p>
        </div>
        <div class="col">
            <p>Lost: {dataObject.lost}</p>
        </div>
    </div>
    <div class="row">
        <div class="col-auto">
            <p>Moves: {dataObject.moves}</p>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <p>Kills: {dataObject.kills}</p>
        </div>
        <div class="col">
            <p>Deaths: {dataObject.deaths}</p>
        </div>
    </div>
    {/if}
  </div>
</div>
