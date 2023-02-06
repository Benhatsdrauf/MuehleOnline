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
    <table class="table">
      <tbody>
        <tr>
          <th scope="row">Won</th>
          <td>{dataObject.won}</td>
        </tr>
        <tr>
          <th scope="row">Lost</th>
          <td>{dataObject.lost}</td>
        </tr>
        <tr>
          <th scope="row">Moves</th>
          <td>{dataObject.moves}</td>
        </tr>
        <tr>
          <th scope="row">Kills</th>
          <td>{dataObject.kills}</td>
        </tr>
        <tr>
          <th scope="row">Deaths</th>
          <td>{dataObject.deaths}</td>
        </tr>
      </tbody>
    </table>
    {/if}
  </div>
</div>
