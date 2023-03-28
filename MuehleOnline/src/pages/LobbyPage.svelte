<script>
  import Modal from "../lib/Modal.svelte";
  import { LoginPanel, RegisterPanel } from "../lib/Panels";
  import { Tabs, TabList, TabPanel, Tab } from "../lib/Tabs";
  import { useNavigate } from "svelte-navigator";
  import { AuthorizedGetRequest, Request } from "../../scripts/request";
  import { onMount } from "svelte";
  import { hash } from "../../scripts/hash";
  import Fa from "svelte-fa";
  import { faKey, faUser } from "@fortawesome/free-solid-svg-icons";
  import MessageModal from "../lib/MessageModal.svelte";
  import Loading from "../lib/Loading.svelte";

  const navigate = useNavigate();
  let showCard = false;
  let path = window.location.pathname;

  onMount(() => {
    if (localStorage.getItem("token") === null) {
      showCard = true;
      return;
    }

    RegisterUsertoGame();
  });

  function RegisterUsertoGame() {
    AuthorizedGetRequest("game/join/" + path.split("/")[3])
      .then((response) => {
        navigate("/gamePage");
      })
      .catch((err) => {
        try {
          err.json().then((response) => {
            if (response.errors.guid) {
              messageModalMessage = response.errors.guid;
              messageModalIsError = true;
              messageModalShow = true;
            }
          });
        } catch (exception) {
          messageModalMessage =
            "Faild to connect to server, please try again later.";
          messageModalIsError = true;
          messageModalShow = true;
        }
      });
  }

  let userName = "";
  let password = "";
  let errorMessages = [];

  let messageModalMessage = "";
  let messageModalShow = false;
  let messageModalIsError = true;

  function Login() {
    errorMessages = [];

    const data = {
      name: userName,
      pw: password,
    };

    Request("auth/login", data)
      .then((response) => {
        localStorage.setItem("token", response.token);

        let splitToken = response.token.split("|")[1];
        hash(splitToken).then((hash) => {
          localStorage.setItem("hashedToken", hash);
        });
        RegisterUsertoGame();
      })
      .catch((err) => {
        try {
          err.json().then((response) => {
            for (const property in response.errors) {
              errorMessages = [
                ...errorMessages,
                {
                  field: property,
                  message: response.errors[property],
                },
              ];
            }
          });
        } catch (exception) {
          errorMessages = [
            ...errorMessages,
            {
              field: "server",
              message:
                "Could not connect to the server, please try again later.",
            },
          ];
        }
      });
  }
  async function Register() {
    errorMessages = [];

    const data = {
      name: userName,
      pw: password,
    };

    Request("auth/register", data)
      .then((response) => {
        localStorage.setItem("token", response.token);

        let splitToken = response.token.split("|")[1];

        hash(splitToken).then((hash) => {
          localStorage.setItem("hashedToken", hash);
        });
        RegisterUsertoGame();
      })
      .catch((err) => {
        try {
          err.json().then((response) => {
            for (const property in response.errors) {
              errorMessages = [
                ...errorMessages,
                {
                  field: property,
                  message: response.errors[property],
                },
              ];
            }
          });
        } catch (exception) {
          errorMessages = [
            ...errorMessages,
            {
              field: "server",
              message:
                "Could not connect to the server, please try again later.",
            },
          ];
        }
      });
  }
</script>

<!-- background animation -->
<div class="background">
    <div class="context">
        <div class="container-fluid">
            <div class="row">
              <div class="col d-flex justify-content-center mt-5">
                {#if showCard}
                <div class="card w-50">
                  <div class="card-header">
                    <h1><b>Join Game</b></h1>
                  </div>
                  <div class="card-body">
                    <Tabs>
                      <TabList>
                        <Tab>Login</Tab>
                        <Tab>Register</Tab>
                      </TabList>
          
                      <TabPanel>
                        <div class="container-fluid">
                          <div class="row mb-3">
                            <div class="col">
                              <h1>Login</h1>
                            </div>
                          </div>
                          <div class="row mb-3">
                            <div class="col">
                              <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text h-100">
                                    <Fa icon={faUser} />
                                  </span>
                                </div>
                                <input
                                  type="text"
                                  class="form-control"
                                  placeholder="Username"
                                  bind:value={userName}
                                />
                              </div>
                              <div class="form-text text-danger">
                                {errorMessages.find((x) => x.field == "name")?.message ??
                                  ""}
                              </div>
                            </div>
                          </div>
                          <div class="row mb-3">
                            <div class="col">
                              <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text h-100">
                                    <Fa icon={faKey} />
                                  </span>
                                </div>
                                <input
                                  type="password"
                                  class="form-control"
                                  placeholder="Password"
                                  bind:value={password}
                                />
                              </div>
                              <div class="form-text text-danger">
                                {errorMessages.find((x) => x.field == "pw")?.message ??
                                  ""}
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-auto">
                              <button
                                type="button"
                                class="btn btn-outline-primary"
                                on:click={Login}>Login</button
                              >
                            </div>
                            <div class="col-auto text-danger">
                              {errorMessages.find((x) => x.field == "server")?.message ??
                                ""}
                            </div>
                          </div>
                        </div>
                      </TabPanel>
                      <TabPanel>
                        <div class="container-fluid">
                          <div class="row mb-3">
                            <div class="col">
                              <h1>Register</h1>
                            </div>
                          </div>
                          <div class="row mb-3">
                            <div class="col">
                              <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text h-100">
                                    <Fa icon={faUser} />
                                  </span>
                                </div>
                                <input
                                  type="text"
                                  class="form-control"
                                  placeholder="Username"
                                  bind:value={userName}
                                />
                              </div>
                              <div class="form-text text-danger">
                                {errorMessages.find((x) => x.field == "name")?.message ??
                                  ""}
                              </div>
                            </div>
                          </div>
                          <div class="row mb-3">
                            <div class="col">
                              <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text h-100">
                                    <Fa icon={faKey} />
                                  </span>
                                </div>
                                <input
                                  type="password"
                                  class="form-control"
                                  placeholder="Password"
                                  bind:value={password}
                                />
                              </div>
                              <div class="form-text text-danger">
                                {errorMessages.find((x) => x.field == "pw")?.message ??
                                  ""}
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-auto">
                              <button
                                type="button"
                                class="btn btn-outline-primary"
                                on:click={Register}>Register</button
                              >
                            </div>
                            <div class="col-auto text-danger">
                              {errorMessages.find((x) => x.field == "server")?.message ??
                                ""}
                            </div>
                          </div>
                        </div>
                      </TabPanel>
                    </Tabs>
                  </div>
                </div>
                {:else}
                <Loading show={true}/>
                {/if}
              </div>
            </div>
          </div>
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
<MessageModal
  bind:showModal={messageModalShow}
  isError={messageModalIsError}
  message={messageModalMessage}
/>


<style>
    

    .context {
        position: relative;
        z-index: 5;
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
        background-image: url("../assets/Mühle2.png");
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