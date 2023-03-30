<script>
    import Modal from "../lib/Modal.svelte";
    import { LoginPanel, RegisterPanel } from "../lib/Panels";
    import { Tabs, TabList, TabPanel, Tab } from "../lib/Tabs";

    let showModal = false;
</script>

<!-- background animation -->
<div class="background">
    <div class="context">
        <h1 class="not-selectable">MuehleOnline</h1>
        <button type="button" class="cta btn btn-dark" on:click={() => (showModal = true)}>
            Login
        </button>
    </div>
    <ul class="circles">
        <li />
        <li />
        <li />
        <li />
        <li />
        <li />
        <li />
        <li />
        <li />
        <li />
        <li />
        <li />
        <li />
        <li />
        <li />
    </ul>
</div>
<!-- modal  -->
{#if showModal}
    <Modal on:close={() => (showModal = false)}>
        <h2 slot="header" class="mb-3">
            Login Or Register
        </h2>
        <Tabs>
            <TabList>
                <Tab><h5>Login</h5></Tab>
                <Tab><h5>Register</h5></Tab>
            </TabList>

            <TabPanel>
                <LoginPanel/>
            </TabPanel>

            <TabPanel>
                <RegisterPanel/>
            </TabPanel>
        </Tabs>
    </Modal>
{/if}

<style>
    
    * {
        margin: 0px;
        padding: 0px;
    }

    .context {
        position: relative;
        z-index: 5;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding-top: 20px;
        color: azure;
    }

    .cta {
        margin-top: 50px;
        width: 150px;
        height: 50px;
        font-size: larger;
    }

    .background {
        background: #4e54c8;
        background: -webkit-linear-gradient(to left, #8f94fb, #4e54c8);
        width: 100%;
        height: 100vh;
    }

    .circles {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }

    .circles li {
        position: absolute;
        display: block;
        list-style: none;
        width: 20px;
        height: 20px;
        background-image: url("../assets/MühleIcon.png");
        background-size: contain;
        animation: animate 25s linear infinite;
        bottom: -150px;
    }

    .circles li:nth-child(1) {
        left: 25%;
        width: 80px;
        height: 80px;
        animation-delay: 0s;
    }

    .circles li:nth-child(2) {
        left: 10%;
        width: 20px;
        height: 20px;
        animation-delay: 2s;
        animation-duration: 12s;
    }

    .circles li:nth-child(3) {
        left: 70%;
        width: 20px;
        height: 20px;
        animation-delay: 4s;
    }

    .circles li:nth-child(4) {
        left: 40%;
        width: 60px;
        height: 60px;
        animation-delay: 0s;
        animation-duration: 18s;
    }

    .circles li:nth-child(5) {
        left: 65%;
        width: 20px;
        height: 20px;
        animation-delay: 0s;
    }

    .circles li:nth-child(6) {
        left: 75%;
        width: 110px;
        height: 110px;
        animation-delay: 3s;
    }

    .circles li:nth-child(7) {
        left: 35%;
        width: 150px;
        height: 150px;
        animation-delay: 7s;
    }

    .circles li:nth-child(8) {
        left: 50%;
        width: 25px;
        height: 25px;
        animation-delay: 15s;
        animation-duration: 45s;
    }

    .circles li:nth-child(9) {
        left: 20%;
        width: 50px;
        height: 50px;
        animation-delay: 2s;
        animation-duration: 35s;
        background-image: url("../assets/Pizza_Pepperoni.png");
    }

    .circles li:nth-child(10) {
        left: 85%;
        width: 150px;
        height: 150px;
        animation-delay: 0s;
        animation-duration: 11s;
    }

    .circles li:nth-child(11) {
        left: 60%;
        width: 100px;
        height: 100px;
        animation-delay: 3s;
        animation-duration: 10s;
    }

    .circles li:nth-child(12) {
        left: 25%;
        width: 50px;
        height: 50px;
        animation-delay: 3s;
        animation-duration: 9s;
    }
    .circles li:nth-child(13) {
        left: 45%;
        width: 150px;
        height: 150px;
        animation-delay: 0s;
        animation-duration: 6s;
    }
    .circles li:nth-child(14) {
        left: 69%;
        width: 75px;
        height: 75px;
        animation-delay: 2s;
        animation-duration: 8s;
    }
    .circles li:nth-child(15) {
        left: 15%;
        width: 150px;
        height: 150px;
        animation-delay: 0s;
        animation-duration: 11s;
    }

    @keyframes animate {
        0% {
            transform: translateY(100) rotate(0deg);
            opacity: 1;
            border-radius: 0;
        }

        100% {
            transform: translateY(-1000px) rotate(720deg);
            opacity: 0;
        }
    }
</style>
