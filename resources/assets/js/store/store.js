import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export const store = new Vuex.Store({
  state: {
      user: {
        name: '',
      },
      connected: false,
      game: null,
      cells: [],
      currentSign: null,
      currentPlayer: null,
      playerSign: null,
      otherSign: null,
      selectedCells: [],
      computerPossibleChoices: [],
      winner: null,
      gameOn: false,
      playMode: null,
      player2: 'Player2',
      p2IsSet: false
  },

  getters: {
      user: (state) => state.user,
      connected: (state) => state.connected,
      game: (state) => state.game,
      cells: (state) => state.cells,
      currentSign: (state) => state.currentSign,
      currentPlayer: (state) => state.currentPlayer,
      playerSign: (state) => state.playerSign,
      otherSign: (state) => state.otherSign,
      selectedCells: (state) => state.selectedCells,
      computerPossibleChoices: (state) => state.computerPossibleChoices,
      winner: (state) => state.winner,
      gameOn: (state) => state.gameOn,
      playMode: (state) => state.playMode,
      player2: (state) => state.player2,
      p2IsSet: (state) => state.p2IsSet
  },

  actions: {
      //Créer un nouveau board + récuperer le board
      newRound({commit})
      {
          commit('killAll');
          commit('newRound');
      },

      //L'utilisateur peut choisir X/O avant de lancer le jeu
      chooseSign({commit, dispatch}, sign)
      {
          commit('chooseSign', sign);
          commit('firstTurn');
          dispatch('initializeBoard');
      },

      //initialiser le board XO
      async initializeBoard({commit, state, dispatch})
      {
          commit('initializeWinner');
          commit('initializeGameOn');
          commit('initializeComputerPossibleChoices');
          commit('initializeSelectedCells');
          commit('firstTurn');

          const currentPlayer = state.currentPlayer;
          if(currentPlayer == 'PC') {
            commit('initializeCurrentSignAsOther');
            await dispatch('computerTurn');
          } else {
            commit('initializeCurrentSignAsPlayer');
          }
      },

      //le tour du player!
      async userTurn({commit, state, dispatch}, index)
      {
          const gameOn = state.gameOn;
          const cellsSelected = state.cells[index].selected;
          if(gameOn && !cellsSelected) {
            await dispatch('selectCell', index);
            commit('flipTurn');
            dispatch('hasGameEnded');
            if(state.gameOn != false && state.playMode == 'PC') {
              dispatch('computerTurn');
            }
          }
          dispatch('hasGameEnded');
      },

      //le tour de l'ordi
      async computerTurn({commit, state, dispatch})
      {
          const gameOn = state.gameOn;
          if(gameOn) {
            const index = Math.floor(Math.random()*state.computerPossibleChoices.length);
            const num = state.computerPossibleChoices[index].num;
            await dispatch('selectCell', num-1);
            commit('flipTurn');
          }
          dispatch('hasGameEnded');
      },

      //Selectionner une case
      selectCell({commit ,state}, index)
      {
          const cellsSelected = state.cells[index].selected;

          if(!cellsSelected) {
            commit('setCellSelectedTrue', index);
            commit('setCellSignCurrent', index);
            commit('addToSelectedCells', index);

            const connected = state.connected;
            if(connected) {
              axios.post('/cells/update', {
                  id: state.cells[index].id,
                  sign: state.cells[index].sign
              });
            }
          }
      },

      //A chaque tour une vérification s'il y a un gagnant
      verifyWinner({commit, state})
      {
          //Les trio gagnants
          let combos = [
            [state.cells[0], state.cells[1], state.cells[2]],
            [state.cells[3], state.cells[4], state.cells[5]],
            [state.cells[6], state.cells[7], state.cells[8]],
            [state.cells[0], state.cells[3], state.cells[6]],
            [state.cells[1], state.cells[4], state.cells[7]],
            [state.cells[2], state.cells[5], state.cells[8]],
            [state.cells[0], state.cells[4], state.cells[8]],
            [state.cells[2], state.cells[4], state.cells[6]]
          ];
          for(let index = 0; index < combos.length; index++) {
            if(combos[index][0].sign != '' && combos[index][0].sign == combos[index][1].sign && combos[index][0].sign == combos[index][2].sign) {
              const winnerSign = combos[index][0].sign;
              const playerSign = state.playerSign;
              const otherSign = state.otherSign;
              //les cases gagnantes
              combos[index][0].winner = true;
              combos[index][1].winner = true;
              combos[index][2].winner = true;
              //qui a gagné?
              if(winnerSign == playerSign) {
                commit('setWinnerUserName');
              } else if(winnerSign == otherSign) {
                commit('setWinnerOther');
              }
              commit('emptyAfterEnd');
              commit('saveWinner');
            }
          }
      },

      //Vérifier si le jeu est terminé (toutes les cases sont remplie ou il y a un gagnant)
      async hasGameEnded({commit, state, dispatch})
      {
          await dispatch('verifyWinner');
          const winner = state.winner;
          if(!winner) {
            const selectedCellsLength = state.selectedCells.length;
            if(selectedCellsLength == 9) {
              commit('setWinnerDraw');
              commit('emptyAfterEnd');
              commit('saveWinner');
            }
          }
      },

  },

  mutations: {
      setWinnerUserName(state)
      {
          state.winner = state.user.name;
      },

      setWinnerOther(state)
      {
          state.playMode == 'PC' ? state.winner = 'PC' : state.winner = state.player2;
      },

      setWinnerDraw(state)
      {
          state.winner = 'draw';
      },

      saveWinner(state)
      {
        if(state.connected) {
          if(state.winner == state.user.name) {
            axios.post('/game/winner', {
              id: state.game.id,
              mode: state.playMode,
              against: state.player2,
              won: true
            });
          } else {
            axios.post('/game/winner', {
              id: state.game.id,
              mode: state.playMode,
              against: state.player2,
              won: false
            });
          }
        }
      },
      //////

      //Initializer:
      initializeWinner(state)
      {
          state.winner = '';
      },
      initializeGameOn(state)
      {
          state.gameOn = true;
      },
      initializeComputerPossibleChoices(state)
      {
          state.computerPossibleChoices = state.cells;
      },
      initializeSelectedCells(state)
      {
          state.selectedCells = [];
      },
      initializeCurrentSignAsOther(state)
      {
          state.currentSign = state.otherSign;
      },
      initializeCurrentSignAsPlayer(state)
      {
          state.currentSign = state.playerSign;
      },
      //////

      //For chooseSign action
      chooseSign(state, sign)
      {
          state.playerSign = sign;
          state.playerSign == 'X' ? state.otherSign = 'O' : state.otherSign = 'X';
      },

      //Qui commence???
      firstTurn(state)
      {
          let random = Math.floor(Math.random()*2);
          random == 1 ? state.currentPlayer = state.user.name : (state.playMode == 'PC' ? state.currentPlayer = 'PC' : state.currentPlayer = state.player2);
      },

      //switcher le role après chaque tour
      flipTurn(state)
      {
          state.currentSign == 'X' ? state.currentSign = 'O' : state.currentSign = 'X';
          if(state.playMode == 'PC') {
              state.currentPlayer == 'PC' ? state.currentPlayer = state.user.name : state.currentPlayer = 'PC';
          } else if(state.playMode == '2P') {
              state.currentPlayer == state.player2 ? state.currentPlayer = state.user.name : state.currentPlayer = state.player2;
          }
      },
      //////

      setCellSelectedTrue(state, index)
      {
          state.cells[index].selected = true;
      },
      setCellSignCurrent(state, index)
      {
          state.cells[index].sign = state.currentSign;
      },

      //un array pour garder le history => afin de créer un array de possibiliés restantes pour l'ordi
      addToSelectedCells(state, index)
      {
          state.selectedCells.push(index);
          //Then find the unselected cells
          let possibilities = [];
          for(let index = 0; index < state.cells.length; index++) {
            if(state.cells[index].selected != true) {
              possibilities.push(state.cells[index]);
            }
          }
          state.computerPossibleChoices = possibilities;
      },
      //////

      //Vider (user actuel et sign actuel) après le gain ou la perte
      emptyAfterEnd(state)
      {
          state.gameOn = false;
          state.currentSign = '';
          state.currentPlayer = '';
      },

      //Vider toutes les variables pour redémmarer
      killAll(state)
      {
          state.winner = '';
          state.gameOn = false;
          state.computerPossibleChoices = state.cells;
          state.selectedCells = [];
          state.currentPlayer = '';
          state.playerSign = '';
          state.otherSign = '';
          state.currentSign = '';
      },

      //Créer un nouveau board + récuperer le board
      newRound(state)
      {
        if(window.location.pathname == '/test') {
          axios.get('/test/get').then((res) => {
            state.cells = res.data.cells
          }).catch(error => {
              console.log(error.response)
          });
          //This commented code is for a non db use
          // state.cells = [
          //     {selected: false, sign: '', num: 1},
          //     {selected: false, sign: '', num: 2},
          //     {selected: false, sign: '', num: 3},
          //     {selected: false, sign: '', num: 4},
          //     {selected: false, sign: '', num: 5},
          //     {selected: false, sign: '', num: 6},
          //     {selected: false, sign: '', num: 7},
          //     {selected: false, sign: '', num: 8},
          //     {selected: false, sign: '', num: 9},
          // ];
          state.user.name = 'Mr.Who';
          state.connected = false;
          state.game = {};
        } else if(window.location.pathname == '/game') {
          axios.get('/game/new').then((res) => {
            state.user = res.data.user,
            state.connected = res.data.connected,
            state.game = res.data.game,
            state.cells = res.data.cells
          }).catch(error => {
              console.log(error.response)
          });
        }
      },
  },

});
