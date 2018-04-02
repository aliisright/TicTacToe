<template>
    <div>
            <div class="grid m-auto">
                <div class="cell"
                :class="{winner: !gameOn && cell.winner == true}"
                v-for="cell in cells"
                @click.prevent="userTurn(cell.num-1)">

                    <img v-show="cell.sign == 'X'" src="img/x-icon.png" width="100px">
                    <img v-show="cell.sign == 'O'" src="img/o-icon.png" width="100px">

                </div>
            </div>
    </div>

</template>

<script>
    import LogoComponent from './GameComponents/LogoComponent.vue'
    export default {
        name: 'GameComponent',
        components: {
          LogoComponent,
        },
        data() {
          return {
            user: null,
            cells: [],
            currentSign: '',
            currentPlayer: '',
            playerSign: null,
            PcSign: '',
            selectedCells: [],
            computerPossibleChoices: [],
            winner: '',
            gameOn: false,
          }
        },
        methods: {
          //le tour du player!
          userTurn(index)
          {
            if(this.gameOn != false) {
              this.selectCell(index);
              this.flipTurn();
              this.hasGameEnded();
              if(this.gameOn != false) {
                this.computerTurn();
              }
            }
            this.hasGameEnded();
          },

          //le tour de l'ordi
          computerTurn()
          {
              if(this.gameOn != false) {
                let index = Math.floor(Math.random()*this.computerPossibleChoices.length);
                let num = this.computerPossibleChoices[index].num;
                this.selectCell(num-1);
                this.flipTurn();
              }
              this.hasGameEnded();
          },

          //Selectionner une case
          selectCell(index)
          {
              if(this.cells[index].selected != true) {
                this.cells[index].selected = true;
                this.cells[index].sign = this.currentSign;
                this.addToSelectedCells(index);

                axios.post('/cells/update', {
                    id: this.cells[index].id,
                    sign: this.cells[index].sign
                });
              }
          },

          //un array pour garder le history => afin de créer un array de possibiliés restantes pour l'ordi
          addToSelectedCells(index)
          {
              this.selectedCells.push(index);
              //Then find the unselected cells
              let possibilities = [];
              for(let index = 0; index < this.cells.length; index++) {
                if(this.cells[index].selected != true) {
                  possibilities.push(this.cells[index]);
                }
              }
              this.computerPossibleChoices = possibilities;
          },

          //switcher le role après chaque tour
          flipTurn()
          {
              this.currentSign == 'X' ? this.currentSign = 'O' : this.currentSign = 'X';
              this.currentPlayer == 'PC' ? this.currentPlayer = 'You' : this.currentPlayer = 'PC';
          },

          //Vérifier si le jeu est terminé (toutes les cases sont remplie ou il y a un gagnant)
          hasGameEnded()
          {
              this.verifyWinner();
              if(this.winner == '') {
                if(this.selectedCells.length == 9) {
                  this.winner = 'draw';
                  this.emptyAfterEnd();
                }
              } else {
                this.emptyAfterEnd();
              }
          },

          //A chaque tour une vérification s'il y a du gain
          verifyWinner()
          {
              //Les trio gagnants
              let combos = [
                [this.cells[0], this.cells[1], this.cells[2]],
                [this.cells[3], this.cells[4], this.cells[5]],
                [this.cells[6], this.cells[7], this.cells[8]],
                [this.cells[0], this.cells[3], this.cells[6]],
                [this.cells[1], this.cells[4], this.cells[7]],
                [this.cells[2], this.cells[5], this.cells[8]],
                [this.cells[0], this.cells[4], this.cells[8]],
                [this.cells[2], this.cells[4], this.cells[6]]
              ];
              for(let index = 0; index < combos.length; index++) {
                if(combos[index][0].sign != "" && combos[index][0].sign == combos[index][1].sign && combos[index][0].sign == combos[index][2].sign) {
                  var winnerSign = combos[index][0].sign;
                  //les cases gagnantes
                  combos[index][0].winner = true;
                  combos[index][1].winner = true;
                  combos[index][2].winner = true;
                  //qui a gagné?
                  if(winnerSign == this.playerSign) {
                    this.winner = 'You';
                  } else if(winnerSign == this.pcSign) {
                    this.winner = 'PC';
                  }
                  this.emptyAfterEnd();
                }
              }
          },




          //Qui commence???
          firstTurn()
          {
              let random = Math.floor(Math.random()*2);
              random == 1 ? this.currentPlayer = 'You' : this.currentPlayer = 'PC';
          },

          //Vider (user actuel et sign actuel) après le gain ou la perte
          emptyAfterEnd()
          {
              this.gameOn = false;
              this.currentSign = '';
              this.currentPlayer = '';
          },

          //initialiser le board XO
          initializeBoard()
          {
              this.winner = '';
              this.gameOn = true;
              this.computerPossibleChoices = this.cells;
              this.selectedCells = [];
              this.firstTurn();
              if(this.currentPlayer == 'PC') {
                this.currentSign = this.pcSign;
                this.computerTurn();
              } else {
                this.currentSign = this.playerSign;
              }
          },


          //L'utilisateur peut choisir X/O avant de lancer le jeu
          chooseSign(sign)
          {
              if(this.connected && this.user && this.game)
              {
                this.playerSign = sign;
                this.playerSign == 'X' ? this.pcSign = 'O' : this.pcSign = 'X';
                this.initializeBoard();
              }
          },

          //Vider toutes les variables pour redémmarer
          killAll()
          {
              this.winner = '';
              this.gameOn = false;
              this.computerPossibleChoices = this.cells;
              this.selectedCells = [];
              this.currentPlayer = '';
              this.playerSign = '';
              this.pcSign = '';
              this.currentSign = '';
          },

          //Créer un nouveau board + récuperer le board
          newGame()
          {
            this.killAll()
            axios.get('game/new').then((res) => {
              this.user = res.data.user,
              this.connected = res.data.connected,
              this.game = res.data.game,
              this.cells = res.data.cells
            });
          }
        },
        mounted() {
          this.newGame();
        }
    }
</script>
