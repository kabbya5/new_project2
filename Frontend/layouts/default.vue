

<template >
  <header>
    <FrontendNavNavbar />
  </header>

  <main class="bg-slate-200/60 dark:bg-gray-900 text-gray-900 dark:text-gray-100 mt mt-10 md:mt-18">
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
