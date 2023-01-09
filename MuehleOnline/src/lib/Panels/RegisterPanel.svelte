<script>
    import {Request} from "../../../scripts/request";
    import { useNavigate } from "svelte-navigator";

    export let navigateTo;

    const navigate = useNavigate();

    let userName = "";
    let password = "";

    async function Register() {
        const data = {
            name: userName,
            pw: password,
        };

        let response = await Request(
            "auth/register",
            data
        ).catch((err) => {
            console.log(err);
            return;
        });

        localStorage.setItem("token", response.token);
        navigate(navigateTo);

        console.log(localStorage.getItem("token"));
    }
</script>

<h1>Register Panel</h1>

<div class="contianer">
    <input type="text" placeholder="Username" bind:value={userName} />
    <input type="password" placeholder="Password" bind:value={password} />
    <button type="button" on:click={Register}>Register</button>
</div>

<style>
    .contianer {
        display: flex;
        gap: 16px;
    }
</style>
