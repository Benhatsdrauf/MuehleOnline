<script>
  import Fa from "svelte-fa";
  import { faCat } from "@fortawesome/free-solid-svg-icons";
</script>

<nav class="navbar bgc-secondary">
  <div class="container-fluid">
    <a class="navbar-brand c-text me-auto" href="*">
      <Fa icon={faCat} color="#ffffff" size="1.6x" />
      MühleOnline
    </a>

    <slot />
  </div>
</nav>
