<script>
  import { faCircleExclamation, faCircleInfo } from "@fortawesome/free-solid-svg-icons";
  import Fa from "svelte-fa";
  import Modal from "./Modal.svelte";

    export let isError = false;
    export let message = "";
    export let showModal = false;

</script>

{#if showModal}
<Modal on:close={() => (showModal = false)}>
    <div slot="header">
      <div class="row">
        {#if isError}
          <div class="col-auto">
            <Fa icon={faCircleExclamation} color="var(--color-error)" size="1.6x" />
          </div>
          <div class="col">
            <h4><b>Error</b></h4>
          </div>
        {:else}
          <div class="col-auto">
            <Fa icon={faCircleInfo} color="var(--color-info)" size="2x" />
          </div>
          <div class="col">
            <h4><b>Info</b></h4>
          </div>
        {/if}
      </div>
    </div>
    <div class="row mt-3">
      <div class="col">
        <h6>{message}</h6>
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