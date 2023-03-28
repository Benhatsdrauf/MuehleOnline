<script>
  import { faCircleExclamation, faCircleInfo, faTriangleExclamation } from "@fortawesome/free-solid-svg-icons";
  import Fa from "svelte-fa";
  import Modal from "./Modal.svelte";

    export let isError = false;
    export let message = "";
    export let showModal = false;

</script>

{#if showModal}
<Modal on:close={() => (showModal = false)}>
    <div slot="header">
      <div class="row align-center">
        {#if isError}
          <div class="col-auto">
            <Fa icon={faTriangleExclamation} color="var(--color-error)" size="2.7x" />
          </div>
          <div class="col">
            <h3><b>Error</b></h3>
          </div>
        {:else}
          <div class="col-auto">
            <Fa icon={faCircleInfo} color="var(--color-info)" size="2.7x" />
          </div>
          <div class="col">
            <h3><b>Info</b></h3>
          </div>
        {/if}
      </div>
      <hr>
    </div>
    <div class="row mt-2">
      <div class="col">
        <h5>{message}</h5>
      </div>
    </div>
    <div class="row justify-content-end">
      <div class="col-auto">
        <button
          class="btn btn-outline-primary"
          on:click={() => (showModal = false)}>OK</button
        >
      </div>
    </div>
  </Modal>
{/if}