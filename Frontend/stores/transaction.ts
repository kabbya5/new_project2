import { defineStore } from "pinia";

export const useTransactionStore = defineStore("transaction", {
  state: () => ({
    transactions: [] as any[],   // transaction list
    loading: false,              // loading state
    error: null as string | null,// error message
    pagination: {                // pagination info
      page: 1,
      limit: 1,
      total: 0,
      total_page:0,
    },
  }),

  actions: {
    async fetchTransaction(
      page = 1,
      limit: number = 1,
      type: null| string = null,
      user_id: null|number,
      year: number | null = null,
      month: number | null = null,
      from_date: string | null = null,
      to_date: string | null = null,
    ) {
        this.loading = true;
        this.error = null;
        
        const loading = useLoadingStore(); 
        loading.start('transaction');

        try {
            const query: any = {page,limit,type};
            if (user_id) query.user_id = user_id;
            if (year) query.year = year;
            if (month) query.month = month;
            if (from_date) query.from_date = from_date;
            if (to_date) query.to_date = to_date;

            const res: any = await useApiFetch("/transactions", {
                method: "GET",
                query,
            });

            this.transactions = res.transactions || [];
            this.pagination.page = page;
            this.pagination.limit = limit;
            this.pagination.total = res.total || 0;
            this.pagination.total_page = res.total_page;
        } catch (err: any) {
            alert("Failed to fetch transactions");
        } finally {
            loading.stop('transaction');
        }
    },
  },
});
