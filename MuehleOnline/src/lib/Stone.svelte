<script>
    import { draw, fade } from "svelte/transition";
    import { quintOut } from "svelte/easing";

    export let x = 0;
    export let y = 0;
    export let isWhite = false;
    export let isSelected = false;
    export let isDisabled = false;
</script>

<circle
    on:click
    cx="{x}%"
    cy="{y}%"
    r="22"
    class:selected={isSelected}
    class:white={isWhite}
    class:black={!isWhite}
    class:disabled={isDisabled}
    in:draw={{ duration: 1000, easing: quintOut }}
    out:draw={{ duration: 1000, easing: quintOut }}
/>

<style>
    .white {
        fill: var(--color-steel-200);
    }

    .black {
        fill: var(--color-terra-dark);
    }

    .selected {
        stroke: var(--color-white);
    }

    .disabled {
        pointer-events: none;
    }

    circle {
        stroke: var(--color-black);
        stroke-width: 3px;
        filter: drop-shadow(0px 0px 2px var(--color-steel-200));
        cursor: pointer;
    }
</style>
