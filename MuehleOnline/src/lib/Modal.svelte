<script>
  import { createEventDispatcher, onDestroy } from "svelte";

  import Fa from "svelte-fa";
  import { faX } from "@fortawesome/free-solid-svg-icons";

  const dispatch = createEventDispatcher();
  const close = () => dispatch("close");

  let modal;

  const handle_keydown = (e) => {
    if (e.key === "Escape") {
      close();
      return;
    }

    if (e.key === "Tab") {
      // trap focus
      const nodes = modal.querySelectorAll("*");
      const tabbable = Array.from(nodes).filter((n) => n.tabIndex >= 0);

      let index = tabbable.indexOf(document.activeElement);
      if (index === -1 && e.shiftKey) index = 0;

      index += tabbable.length + (e.shiftKey ? -1 : 1);
      index %= tabbable.length;

      tabbable[index].focus();
      e.preventDefault();
    }
  };
</script>

<svelte:window on:keydown={handle_keydown} />

<div class="custom-modal-background" on:click={close} />

<div class="custom-modal card-border" role="dialog" aria-modal="true" bind:this={modal}>
  <div class="row">
    <div class="col">
      <slot name="header" />
    </div>
    <div class="col-auto">
      <!-- svelte-ignore a11y-autofocus -->
      <button type="button" class="btn btn-danger" autofocus on:click={close}>
        <Fa icon={faX} />
      </button>
    </div>
  </div>
  <slot />
</div>

<style>
  .custom-modal-background {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.3);
  }

  .custom-modal {
    z-index: 10;
    position: absolute;
    left: 50%;
    top: 30%;
    width: calc(100vw - 4em);
    max-width: 45em;
    max-height: calc(100vh - 4em);
    overflow: auto;
    transform: translate(-50%, -50%);
    padding: 1em;
    border-radius: 0.2em;
    background: white;
    border: 1px solid;
  }
</style>
