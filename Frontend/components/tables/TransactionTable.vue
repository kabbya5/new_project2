<template>
    <div class="rounded-xl shadow-md">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left">
                <thead>
                <tr class="border-b dark:border-gray-700 text-gray-500 dark:text-gray-400">
                    <th class="py-2 px-4">Transactions</th>
                    <th class="py-2 px-4">Amount</th>
                    <th class="py-2 px-4">Date</th>
                    <th class="py-2 px-4">Category</th>
                    <th class="py-2 px-4">Account</th>
                    <th class="py-2 px-4"></th>
                </tr>
                </thead>
                <tbody class="text-gray-800 dark:text-gray-200">
                <tr v-for="(item, index) in transactions" :key="index" class="border-b dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800">
                    <td class="py-3 px-4 flex items-center gap-2">
                    <div class="bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300 font-semibold w-8 h-8 flex items-center justify-center rounded-full uppercase text-xs">
                        {{ item.initials }}
                    </div>
                    {{ item.name }}
                    </td>
                    <td class="py-3 px-4" :class="item.amount > 0 ? 'text-green-500' : 'text-red-500'">
                    {{ item.amount > 0 ? '+' : '-' }}${{ Math.abs(item.amount).toFixed(2) }}
                    </td>
                    <td class="py-3 px-4">{{ item.date }}</td>
                    <td class="py-3 px-4">
                    <span :class="item.categoryColor + ' px-2 py-1 text-xs rounded-full bg-opacity-10 font-medium'">
                        {{ item.category }}
                    </span>
                    </td>
                    <td class="py-3 px-4">
                    <div class="flex flex-col">
                        <span class="font-semibold">{{ item.card }}</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Expired in {{ item.expiry }}</span>
                    </div>
                    </td>
                    <td class="py-3 px-4">
                    ✏️
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
export default {
  data() {
    return {
      transactions: [
        { initials: 'SP', name: 'Shopify', amount: -18.49, date: 'Aug 18', category: 'Food & Drinks', categoryColor: 'text-red-500', card: 'Visa 9487', expiry: '24/10' },
        { initials: 'GG', name: 'Gacha Games', amount: -199.99, date: 'Jun 22', category: 'Finance', categoryColor: 'text-blue-500', card: 'Mastercard 1212', expiry: '24/10' },
        { initials: 'ST', name: 'Stripe', amount: 20.99, date: 'May 11', category: 'Entertainment', categoryColor: 'text-pink-500', card: 'Visa 9487', expiry: '24/10' },
        { initials: 'FM', name: 'Figma', amount: -15.00, date: 'Apr 20', category: 'Groceries', categoryColor: 'text-yellow-500', card: 'Visa 9487', expiry: '24/10' },
        { initials: 'GG', name: 'GG Fried Chicken', amount: -45.05, date: 'Apr 18', category: 'Subscription', categoryColor: 'text-purple-500', card: 'Mastercard 1212', expiry: '24/10' },
        { initials: 'UF', name: 'Uber Foods', amount: -50.25, date: 'Mar 8', category: 'Income', categoryColor: 'text-green-500', card: 'Mastercard 1212', expiry: '24/10' },
        { initials: 'PP', name: 'PayPal', amount: 487.88, date: 'Mar 2', category: 'Electronics', categoryColor: 'text-indigo-500', card: 'Visa 9487', expiry: '24/10' },
        { initials: 'GI', name: 'Genshin Impact', amount: -9999.99, date: 'Feb 19', category: 'Gadget', categoryColor: 'text-teal-500', card: 'Mastercard 1212', expiry: '24/10' },
      ]
    }
  }
}
</script>
