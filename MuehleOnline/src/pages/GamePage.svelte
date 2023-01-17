<script>
    import { AuthorizedRequest } from "../../scripts/request";
    import { onMount } from "svelte";
    import Circle from "../lib/Circle.svelte";
    import { positions } from "../../scripts/circlePositions";
    import Stone from "../lib/Stone.svelte";

    onMount(async () => {});

    let gameState = "initial";

    let playerStones = [null, null, null, null, null, null, null, null, null];

    function playerAction(pos) {
        playerStones[playerStones.indexOf(null)] = pos;
        console.log(playerStones);
    }
</script>

<div class="container-fluid bgc-primary ">
    <h3>gamePage</h3>
    <p>That's what it's all about!</p>
    
    <div class="row">
        <div class="col-auto">
            <svg class="none-played">
                {#each playerStones.filter((x) => x === null) as stone, i}
                    <Stone
                        x={50}
                        y={20 + (5 * i)}
                        isWhite={true}
                    />
                {/each}
            </svg>
        </div>
        <div class="col d-flex justify-content-center">
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
                        on:click={() => playerAction(i)}
                    />
                {/each}
    
                {#each playerStones.filter((x) => x != null) as stone}
                    <Stone
                        x={positions[stone][0]}
                        y={positions[stone][1]}
                        isWhite={true}
                    />
                {/each}
            </svg>
        </div>
        <div class="col-auto">
            <svg class="none-played">
                {#each playerStones.filter((x) => x === null) as stone, i}
                <Stone
                    x={50}
                    y={20 + (5 * i)}
                    isWhite={false}
                />
            {/each}
            </svg>
        </div>
    </div>
</div>

<style>
    .game-field {
        height: 80vh;
        width: 80vh;
        background: var(--color-board-background);
        border: solid 6px var(--color-black);
    }

    .none-played {
        height: 80vh;
        width: 10vw;
        background: var(--color-board-background);
        border: solid 6px var(--color-black);
    }
</style>
