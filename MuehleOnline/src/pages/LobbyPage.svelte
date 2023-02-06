<script>
    import Modal from "../lib/Modal.svelte";
    import { LoginPanel, RegisterPanel } from "../lib/Panels";
    import { Tabs, TabList, TabPanel, Tab } from "../lib/Tabs";
    import { useNavigate } from "svelte-navigator";
    import {AuthorizedGetRequest} from "../../scripts/request";
    import { onMount } from "svelte";

    const navigate = useNavigate();
    let showModal = false;

    onMount(() => {        

        if (localStorage.getItem("token") === null) {
            showModal = true;
            return;
        }

        let path = window.location.pathname;

        AuthorizedGetRequest("game/join/"+path.split("/")[3])
        .then(function (response) {
        })
        .catch((err) => {
            console.log(err);
            return;
        });

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
                <LoginPanel/>
            </TabPanel>

            <TabPanel>
                <RegisterPanel/>
            </TabPanel>
        </Tabs>
    </Modal>
{/if}
