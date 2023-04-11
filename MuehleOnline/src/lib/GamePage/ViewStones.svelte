<script>
    import { draw } from "svelte/transition";
    import { quintOut } from "svelte/easing";

    export let x = 0;
    export let y = 0;
    export let isWhite = false;
</script>

<!-- svelte-ignore a11y-click-events-have-key-events -->
<circle
    on:click
    cx="{x}%"
    cy="{y}%"
    r="22"
    class:white={isWhite}
    class:black={!isWhite}
    in:draw={{ duration: 1000, easing: quintOut }}
    out:draw={{ duration: 1000, easing: quintOut }}
/>
<!-- svelte-ignore a11y-click-events-have-key-events -->
<circle
    on:click
    cx="{x}%"
    cy="{y}%"
    r="14"
    class:white={isWhite}
    class:black={!isWhite}
    class="innercircle"
/>
<!-- svelte-ignore a11y-click-events-have-key-events -->
<circle
    on:click
    cx="{x}%"
    cy="{y}%"
    r="4"
    class:white={isWhite}
    class:black={!isWhite}
    class="innercircle"
/>

<style>
    .white {
        fill: var(--color-steel-200);
    }

    .black {
        fill: var(--color-terra-dark);
    }

    circle {
        stroke: var(--color-black);
        stroke-width: 3px;
        filter: drop-shadow(0px 0px 2px var(--color-steel-200));
        cursor: pointer;
    }

    .innercircle {
        stroke: var(--color-black);
        stroke-width: 3px;
        filter: drop-shadow(0px 0px 2px var(--color-steel-900));
        cursor: pointer;
    }
</style>
