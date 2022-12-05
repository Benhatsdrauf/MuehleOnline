<script>
    let userName = "";
    let password = "";
    let confirmPassword = "";
</script>

<h1>Register Panel</h1>

<input type="text" placeholder="Username" bind:value={userName} />
<input type="password" placeholder="Password" bind:value={password} />
<input
    type="password"
    placeholder="confirm Password"
    bind:value={confirmPassword}
/>
<button type="button">Register</button>

<style>
</style>
