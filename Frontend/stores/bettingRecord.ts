import { defineStore } from "pinia";
import type { BettingRecord } from "~/types/bettingRecord";
import { useLoadingStore } from "~/stores/loading";

export const useBettingRecordStore = defineStore("records", {
  state: () => ({
    records: [] as BettingRecord[],
    pagination: {
      current_page: 1,
      last_page: 1,
      per_page: 30,
      total: 0,
    },
  }),

  actions: {
    async fetchTransaction(
      page: number = 1,
      limit: number = 40,
      year: number | null = null,
      month: number | null = null,
      from_date: string | null = null,
      to_date: string | null = null
    ) {
      const loading = useLoadingStore();
      loading.start("transactions");

      try {
        const query: Record<string, any> = { page, limit };
        if (year) query.year = year;
        if (month) query.month = month;
        if (from_date) query.from_date = from_date;
        if (to_date) query.to_date = to_date;

        const res: any = await useApiFetch("/betting/records", {
          method: "GET",
          query,
        });

        // ✅ Safely handle response data
        if (res?.records && Array.isArray(res.records)) {
          this.records = res.records;
        } else {
          this.records = [];
        }

        if (res?.pagination) {
          this.pagination = {
            current_page: res.pagination.current_page ?? 1,
            last_page: res.pagination.last_page ?? 1,
            per_page: res.pagination.per_page ?? 10,
            total: res.pagination.total ?? 0,
          };
        }
      } catch (err: any) {
        console.error("Error fetching betting records:", err);
        alert("⚠️ Failed to fetch betting records. Please try again.");
      } finally {
        loading.stop("transactions");
      }
    },
  },
});
