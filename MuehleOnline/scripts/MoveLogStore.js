import { writable } from "svelte/store";

export let oldMessages = writable([]);
export let newMessage = writable({
    isOpponent: true,
    action: "",
    coordinate: ""
});