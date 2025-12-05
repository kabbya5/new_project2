<template>
    <AdminDepositNumberForm v-if="isModalOpen" :number_id="currentId" @close="isModalOpen=false"/>

    <div class="bg p-3">
        <div class="flex justify-between items-center">
            <h2> Game Balance </h2>
        </div>

        <div class="my-5">
            <div class="overflow-x-auto w-full">
                <table class="divide-y min-w-full table-fixed divide-gray-200 dark:divide-gray-700 rounded-lg min-w-full">
                    <thead class="bg-card">
                    <tr>
                        <th  class="px-6 py-3 text-left text-xs font-medium  uppercase tracking-wider">
                            Provider 
                        </th>

                        <th  class="px-6 py-3 text-left text-xs font-medium  uppercase tracking-wider">
                            Balance 
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="wallet in wallets" :key="wallet.name">
                            <td class="px-6 py-2  text-sm">{{ wallet.name }}</td>
                            <td class="px-6 py-2  text-sm">{{ wallet.amount }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    definePageMeta({
        middleware: ['auth', 'admin'],
        layout:'admin',
    })

    type Wallet = {
        name:string,
        amount:number,
    }

    const wallets = ref<Wallet[]>([]);

    onMounted (async () =>{
        try {
            const res = await useApiFetch('/admin/game/balance');

            if(res.status === 1){
                wallets.value = Object.entries(res.data).map(([name, amount]) =>({
                    name, 
                    amount:Number(amount),
                }))
            } else {
                alert('Invalid API response')
                
            } 
        }
        catch (err) {
            alert('Invalid API response')
        }         
    });

</script>

