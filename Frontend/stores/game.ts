import { defineStore } from "pinia";
import { useLoadingStore } from '~/stores/loading';
import type { GameForm } from "~/types/gameForm";
import type { Game } from '~/types/games';

export const useGameStore = defineStore('game', {
  state: () => ({
    games: [] as Game[],
  }),

  actions: {
    async fetchGames() {
      const loading = useLoadingStore(); 
        loading.start('category');
        try{
            const data = await useApiFetch('/games/index');
            if (data && data.games) {
                this.games = data.games;
            }
        }catch(error){
            alert(error);
        }finally {
            loading.stop('category');
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
