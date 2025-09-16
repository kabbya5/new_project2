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
        loading.start('game');
        try{
            const data = await useApiFetch('/games/index');
            if (data && data.games) {
                this.games = data.games;
            }
        }catch(error){
            alert(error);
        }finally {
            loading.stop('game');
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

    async playGame(id:number){
        const loading = useLoadingStore(); 
        loading.start('game');
        try{
           const response = useApiFetch('/game/play/' + id);
           if (response.status === 1 && response.game_url) {
                window.open(response.game_url, '_blank');
            } else {
                alert(response.msg || 'Failed to launch game')
            }
        }catch(error){
            alert(error);
        }
        loading.stop('game');
    },

    findGame(game_id:number){
        var game = this.games.find(game => game.id === game_id);

        if(game){
            return game;
        }
    },

    async updateGame(gameId: number, gameForm: FormData | GameForm) {
        if (gameForm instanceof FormData) {
            gameForm.append('_method', 'PUT');
        }
        try {
            const response = await useApiFetch(`/admin/game/update/${gameId}`, {
                method: 'POST', // অথবা 'PUT' যদি তোমার API তে PUT লাগে
                body: gameForm,
            });

            if (response && response.game) {
                // store এর ভিতরে পুরোনো provider replace করা
                const index = this.games.findIndex(p => p.id === gameId);
                if (index !== -1) {
                    this.games[index] = response.game;
                }
            }
        } catch (error) {
            alert(error);
        }
    },
}
});
