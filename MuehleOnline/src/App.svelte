<script>
  import { Router, Route, Link } from "svelte-navigator";
  import LandingPage from "./pages/LandingPage.svelte";
  import HomePage from "./pages/HomePage.svelte";
  import GamePage from "./pages/GamePage.svelte";
</script>


<Router>
  <header>
    <nav class="navigation">
      <Link to="/">LandingPage</Link>
      <Link to="home">Home</Link>
      <Link to="gamePage">About</Link>
    </nav>
  </header>

  <main>

    <Route path="home">
      <HomePage />
    </Route>

    <Route path="gamePage">
      <GamePage />
    </Route>

    <Route>
      <LandingPage />
    </Route>
  </main>
</Router>


<style>
  .navigation {
   display: flex;
   flex-direction: row;
   justify-content: space-around;
  }
</style>
