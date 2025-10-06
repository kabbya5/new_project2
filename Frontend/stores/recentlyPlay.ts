import { defineStore } from "pinia";
import { useLoadingStore } from '~/stores/loading';
import type { GameForm } from "~/types/gameForm";
import type { Game } from '~/types/games';

export const useRecentlyPlay = defineStore('recentlyPlay', {
  state: () => ({
    games: [] as Game[],
  }),

  actions: {
    async fetchGames(page = 1, limit = 12) {
        const loading = useLoadingStore(); 
        loading.start('recentlyPlay');
        try{
            const data = await useApiFetch(`/games/recenly/play?page=${page}&limit=${limit}`);
            if (data && data.games) {
                this.games = data.games;
            }
        }catch(error){
            alert(error);
        }finally {
            loading.stop('recentlyPlay');
        }
    },

    async storeGame(gameForm:FormData | GameForm){
      try {
            const response = await useApiFetch(`/admin/games/store`,{
                method:'POST',
                body:gameForm,
            });

          if (response.success && response.game?.launch_url) {
                navigateTo(response.game.launch_url, { external: true });
            }
      }catch(error){
          return false;
      }
    },
}
});
