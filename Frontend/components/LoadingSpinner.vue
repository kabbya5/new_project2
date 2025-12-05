<template>
    <div class="w-full h-[200px] flex items-center justify-center bg">
        <span class="loader"></span>
    </div>
</template>

<style scoped>
.loader {
  width: 30px;
  height: 30px;
  border: 2px solid;
  border-color: #333 transparent #333 transparent;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

/* Dark mode spinner */
.dark .loader {
  border-color: #fff transparent #fff transparent;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }
  100% {
    transform: rotate(360deg);
  }
}
</style>