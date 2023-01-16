<script>
    import { AuthorizedRequest } from "../../scripts/request";
    import { onMount } from "svelte";
    import Circle from "../lib/Circle.svelte";
    import { positions } from "../../scripts/circlePositions";

    onMount(async () => {});

    let gameState = "initial";
</script>

<h3>gamePage</h3>
<p>That's what it's all about!</p>

<div class="board">
    <svg class="game-field">
        <rect
            x="5%"
            y="5%"
            width="90%"
            height="90%"
            fill="none"
            stroke="black"
            stroke-width="5"
        />
        <rect
            x="20%"
            y="20%"
            width="60%"
            height="60%"
            fill="none"
            stroke="black"
            stroke-width="5"
        />
        <rect
            x="35%"
            y="35%"
            width="30%"
            height="30%"
            fill="none"
            stroke="black"
            stroke-width="5"
        />
        <line
            x1="5%"
            y1="50%"
            x2="35%"
            y2="50%"
            stroke="black"
            stroke-width="5"
        />
        <line
            x1="65%"
            y1="50%"
            x2="95%"
            y2="50%"
            stroke="black"
            stroke-width="5"
        />
        <line
            x1="50%"
            y1="5%"
            x2="50%"
            y2="35%"
            stroke="black"
            stroke-width="5"
        />
        <line
            x1="50%"
            y1="65%"
            x2="50%"
            y2="95%"
            stroke="black"
            stroke-width="5"
        />

        {#each positions as position, i}
            <Circle
                status={gameState}
                x={position[0]}
                y={position[1]}
                index={i}
            />
        {/each}
    </svg>
</div>

<style>
    .board {
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .game-field {
        height: 80vh;
        width: 80vh;
        background: var(--color-board-background);
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>
