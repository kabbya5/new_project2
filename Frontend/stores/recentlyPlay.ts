import { defineStore } from "pinia";
import { useLoadingStore } from '~/stores/loading';
import type { GameForm } from "~/types/gameForm";
import type { Game } from '~/types/games';

export const useRecentlyPlay = defineStore('recentlyPlay', {
  state: () => ({
    games: [] as Game[],
  }),

  actions: {
    async fetchGames() {
      const loading = useLoadingStore(); 
        loading.start('recentlyPlay');
        try{
            const data = await useApiFetch('games/recenly/play');
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

          if (response && response.game) {
              this.games.push(response.game);
              return true;
          }
      }catch(error){
          return false;
      }
    },
}
});
