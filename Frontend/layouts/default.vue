

<template >
  <header>
    <FrontendNavNavbar />
  </header>

  <main class="bg-red-800 dark:bg-green-800 text-white mt mt-10 md:mt-14 pb-10 min-h-screen">
    <slot class="" />
    <FrontendFooter/>
  </main>

</template>


<style>

iframe[src*="tawk.to"] {
  display: none !important;
  visibility: hidden !important;
  pointer-events: none !important;
}

iframe[src*="about:blank"] {
  width: 100% !important;
  min-width: 90% !important;
  max-width: 600px !important;
}
</style>
