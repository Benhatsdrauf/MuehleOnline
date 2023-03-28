<script>
    import Circle from "./Circle.svelte";
    export let isPossible = false;
    export let isDisabled = false;
    export let position = [];
</script>

<Circle x={position[0]} y={position[1]} {isPossible} {isDisabled} on:click />
