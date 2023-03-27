<script>
    import { positions } from "../../../scripts/circlePositions";
    import { quintOut } from "svelte/easing";
    import { draw } from "svelte/transition";

    export let allMoveLines = [];
    export let possibleMoves = [];
</script>

{#each allMoveLines as line (line)}
    <line
        x1="{positions[line[0]][0]}%"
        y1="{positions[line[0]][1]}%"
        x2="{positions[line[1]][0]}%"
        y2="{positions[line[1]][1]}%"
        stroke={possibleMoves.includes(line[1]) ? "green" : "red"}
        stroke-width="5"
        in:draw={{ duration: 1500, easing: quintOut }}
    />
{/each}
