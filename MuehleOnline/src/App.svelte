<script>
  import { Router, Route } from "svelte-navigator";
  import LandingPage from "./pages/LandingPage.svelte";
  import HomePage from "./pages/HomePage.svelte";
  import GamePage from "./pages/GamePage.svelte";
  import LobbyPage from "./pages/LobbyPage.svelte";
  import Impressum from "./pages/Impressum.svelte";
  import ReplayPage from "./pages/ReplayPage.svelte";
</script>

<Router>
  <main>
    <Route path="home" primary={false}>
      <HomePage />
    </Route>

    <Route path="gamePage" primary={false}>
      <GamePage />
    </Route>

    <Route path="game/join/:id" let:params primary={false}>
      <LobbyPage />
    </Route>

    <Route path="replay/:id" let:params primary={false}>
      <ReplayPage />
    </Route>

    <Route primary={false}>
      <LandingPage />
    </Route>

    <Route path="impressum" primary={false}>
      <Impressum/>
    </Route>
  </main>
</Router>

<style>
</style>
