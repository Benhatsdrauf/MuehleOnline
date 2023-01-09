<script>
    import {Request} from "../../../scripts/request";
    import { useNavigate } from "svelte-navigator";
    import {hash} from "../../../scripts/hash";

    const navigate = useNavigate();
    let userName = "";
    let password = "";

    async function Login() {
        const data = {
            name: userName,
            pw: password,
        };

        let response = await Request("auth/login", data).catch((err) => {
            console.log(err);
            return;
        });

        localStorage.setItem("token", response.token);
        navigate("home");

        let splitToken = response.token.split("|")[1];
        localStorage.setItem("hashedToken", await hash(splitToken));    
    }
</script>

<h1>Login Panel</h1>

<div class="container">
    <input type="text" placeholder="Username" bind:value={userName} />
    <input type="password" placeholder="Password" bind:value={password} />
    <button type="button" on:click={Login}>Login</button>
</div>

<style>
    .container {
        display: flex;
        gap: 16px;
    }
</style>
