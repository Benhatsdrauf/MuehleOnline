<script>
    import Modal from "../lib/Modal.svelte";
    import { LoginPanel, RegisterPanel } from "../lib/Panels";
    import { Tabs, TabList, TabPanel, Tab } from "../lib/Tabs";
    import { useNavigate } from "svelte-navigator";
    import { onMount } from "svelte";

    const navigate = useNavigate();
    let showModal = false;

    onMount(() => {
        if (localStorage.getItem("token") === null) {
            showModal = true;
            return;
        }

        navigate("/gamePage");
    });
</script>

{#if showModal}
    <Modal on:close={() => (showModal = false)}>
        <Tabs>
            <TabList>
                <Tab>Login</Tab>
                <Tab>Register</Tab>
            </TabList>

            <TabPanel>
                <LoginPanel navigateTo="/gamePage" />
            </TabPanel>

            <TabPanel>
                <RegisterPanel navigateTo="/gamePage" />
            </TabPanel>
        </Tabs>
    </Modal>
{/if}

<style>
</style>
