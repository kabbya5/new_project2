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
      searchQuery:string |null = null,
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
            if (status) query.status = status;
            if (searchQuery) query.searchQuery = searchQuery;

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

    async store(form:FormData):Promise<void>{
      const loading = useLoadingStore(); 
      loading.start('transaction');

      try{
        const res :any = await useApiFetch('/transactions/store',{
          method:'POST',
          body:form
        });

        if(res.transaction){
            this.transactions.push(res.transaction);
        }
      }catch(error){
        alert('Error storing transaction');
      }finally{
        loading.stop('transaction');
      }
    },

    findTransaction(transaction_id: number) {
      return this.transactions.find(transaction => transaction.id === transaction_id);
    }, 

    async update(transaction_id:number, form:FormData){
      const loading = useLoadingStore(); 
      loading.start('transaction');

      if (form instanceof FormData) {
        form.append('_method', 'PUT');
      }

      try{
        const res :any = await useApiFetch('/transactions/update/'+transaction_id,{
          method:'POST',
          body:form
        });

        if(res.transaction){
            const index = this.transactions.findIndex(t => t.id === transaction_id);
            if (index !== -1) {
                this.transactions[index] = res.transaction;
            }
        }
      }catch(error){
        alert('Error storing transaction');
      }finally{
        loading.stop('transaction');
      }
    },

    async approval(transaction_id:number,){
      const loading = useLoadingStore(); 
      loading.start('transaction');

      try{
        const res :any = await useApiFetch('/transactions/approval/'+transaction_id,{
          method:'POST',
        });

        if(res.transaction){
            const index = this.transactions.findIndex(t => t.id === transaction_id);
            if (index !== -1) {
                this.transactions[index] = res.transaction;
            }
        }
      }catch(error){
        alert('Error storing transaction');
      }finally{
        loading.stop('transaction');
      }
    },

    async delete(transaction_id: number) {
      try {
          const response: any = await useApiFetch(`/admin/transactions/delete/${transaction_id}`, {
              method: 'DELETE',
          });

          if (response && response.success) {
              // Remove the deleted text from state
              this.transactions = this.transactions.filter(t => t.id !== transaction_id);
              return true;
          }

          return false;
      } catch (error) {
          console.error(error);
          return false;
      }
    }
  },
  
});
