<script>
    import { getContext } from "svelte";
    import { TABS } from "../Tabs/Tabs.svelte";

    const tab = {};
    const { registerTab, selectTab, selectedTab } = getContext(TABS);

    registerTab(tab);
</script>

<button class:selected={$selectedTab === tab} on:click={() => selectTab(tab)} class="me-3">
    <slot />
</button>

<style>
    button {
        background: none;
        border: none;
        border-bottom: 2px solid white;
        border-radius: 0;
        margin: 0;
        color: #ccc;
    }

    .selected {
        border-bottom: 2px solid teal;
        color: #333;
    }
    
</style>
