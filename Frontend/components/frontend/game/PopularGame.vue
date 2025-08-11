<template>
  <div class="container mx-auto">
    <h1 class="text-3xl font-bold mb-6">হট গেমস</h1>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 lg:grid-cols-4 gap-6">
      <div class="bg-white shadow rounded overflow-hidden">
        <NuxtImg
          src="images/cricket.png"
          alt="BB88 Super Ace"
          class="w-full h-40 object-cover"
        />
        <div class="p-4">
          <h2 class="text-lg font-semibold">BB88 Super Ace</h2>
          <p class="text-sm text-gray-500">JILI</p>
        </div>
      </div>

      <div class="bg-white shadow rounded overflow-hidden">
        <NuxtImg
          src="images/cricket.png"
          alt="BB88 Crash Cricket"
          class="w-full h-40 object-cover"
        />
        <div class="p-4">
          <h2 class="text-lg font-semibold">BB88 Crash Cricket</h2>
          <p class="text-sm text-gray-500">JILI</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// কোনো স্ক্রিপ্ট দরকার নেই এখন, তবে ভবিষ্যতে ডেটা ফেচ করলে এখানে ব্যবহার করতে পারো
</script>

<style scoped>
/* অতিরিক্ত কাস্টম স্টাইল চাইলে এখানে লিখতে পারো */
</style>