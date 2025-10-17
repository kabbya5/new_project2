import { defineStore } from "pinia";

export const useTransactionStore = defineStore("transaction", {
  state: () => ({
    transactions: [] as any[],   
    loading: false,              
    error: null as string | null,
    pagination: {
      current_page: 1,
      last_page: 1,
      per_page: 1,
      total: 0,
    }
  }),

  actions: {
    async fetchTransaction(
      page = 1,
      limit: number = 1,
      type: null| string = null,
      year: number | null = null,
      month: number | null = null,
      from_date: string | null = null,
      to_date: string | null = null,
      status: string | null = null,
    ) {
        this.loading = true;
        this.error = null;
        
        const loading = useLoadingStore(); 
        loading.start('transaction');

        try {
            const query: any = {page,limit,type};
            if (year) query.year = year;
            if (month) query.month = month;
            if (from_date) query.from_date = from_date;
            if (to_date) query.to_date = to_date;

            const res: any = await useApiFetch("/transactions", {
                method: "GET",
                query,
            });

            if (res.transactions) this.transactions = res.transactions;

            if (res.pagination) {
              this.pagination = {
                current_page: res.pagination.current_page,
                last_page: res.pagination.last_page,
                per_page: res.pagination.per_page,
                total: res.pagination.total,
              };
            }
        } catch (err: any) {
            alert("Failed to fetch transactions");
        } finally {
            loading.stop('transaction');
        }
    },
  },
});
