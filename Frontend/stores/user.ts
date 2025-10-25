import { defineStore } from "pinia";
import type { User } from "~/types/user";
import { useLoadingStore } from "~/stores/loading";

export const useUserStore = defineStore("users", {
  state: () => ({
    users: [] as User[],
    pagination: {
      current_page: 1,
      last_page: 1,
      per_page: 30,
      total: 0,
    },
  }),

  actions: {
    async fetchUser(
      page: number = 1,
      limit: number = 40,
      type: string|null = null,
      search: string| null = null,
      from_date: string | null = null,
      to_date: string | null = null
    ) {
      const loading = useLoadingStore();
      loading.start("users");

      try {
        const query: Record<string, any> = { page, limit };
        if (type) query.type = type;
        if (search) query.search = search;
        if (from_date) query.from_date = from_date;
        if (to_date) query.to_date = to_date;

        const res: any = await useApiFetch("/all/users", {
          method: "GET",
          query,
        });

        // ✅ Safely handle response data
        if (res?.users && Array.isArray(res.users)) {
          this.users = res.users;
        } else {
          this.users = [];
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
        console.error("Error fetching users:", err);
        alert("⚠️ Failed to fetch users. Please try again.");
      } finally {
        loading.stop("users");
      }
    },
  },
});
