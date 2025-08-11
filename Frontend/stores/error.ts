import { defineStore } from 'pinia'

export const useErrorStore = defineStore('error', {
  state: () => ({
    message: null as string | null,
    validationErrors: {} as Record<string, string[]>,
    statusCode: null as number | null
  }),
  actions: {
    setError(err: any) {
      this.statusCode = err?.response?.status || null;
      this.message = err?.response?._data?.message || err.message || 'Unknown error';
      this.validationErrors = err?.response?._data?.errors || {};

      console.log(this.validationErrors);
    },
    clearError() {
      this.statusCode = null;
      this.message = null;
      this.validationErrors = {};
    }
  }
});
