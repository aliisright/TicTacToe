<template>
    <div class="text-white">
      <div class="signChoiceBox" v-show="!playerSign">
        <div class="signChoiceBox_logo">
            <div class="logo-neon alis-logo">
              <img src="img/alis-icon.png" width="100px">
            </div>
            <div class="logo-neon tictactoe-logo">
              <img src="img/tictactoe-icon.png" width="300px">
            </div>
        </div>
        <div class="signChoiceBox_choices">
            <div class="sign-choice-cell cell" @click.prevent="chooseSign('X')">
                <img src="img/x-icon.png" width="100px">
            </div>
            <h2 class="mx-3">ou</h2>
            <div class="sign-choice-cell cell" @click.prevent="chooseSign('O')">
                <img src="img/o-icon.png" width="100px">
            </div>
        </div>
      </div>

      <div v-show="playerSign" class="play-zone row">
        <div class="logo-section col-md-4">
            <LogoComponent></LogoComponent>
        </div>

        <div class="info-section col-md-4 text-center" v-show="playerSign">
          <div>
            <h1 v-if="winner == 'You'">Bravo! t'as gagné</h1>
            <h1 v-if="winner == 'PC'">XO-3000 a gagné!</h1>
            <h1 v-if="winner == 'draw'">:/ pas de gagnant!</h1>

            <p v-if="currentPlayer == 'You'">
              A toi! c'est ton tour | <img v-show="currentSign == 'X'" src="img/x-icon.png" width="30px"><img v-show="currentSign == 'O'" src="img/o-icon.png" width="30px">
            </p>
            <p v-if="currentPlayer == 'PC'">
              XO-3000 joue | <img v-show="currentSign == 'X'" src="img/x-icon.png" width="30px"><img v-show="currentSign == 'O'" src="img/o-icon.png" width="30px">
            </p>

            <button class="btn btn-warning" @click.prevent="restart()">Relancer</button>
          </div>

        </div>

        <div class="col-md-4 board-section">
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

      </div>
    </div>

</template>

<script>
    import LogoComponent from './GameComponents/LogoComponent.vue'
    export default {
        name: 'GameComponent',
        data() {
          return {
            cells: [
                {num: 1, selected: false, sign: '', winner: false},
                {num: 2, selected: false, sign: '', winner: false},
                {num: 3, selected: false, sign: '', winner: false},

                {num: 4, selected: false, sign: '', winner: false},
                {num: 5, selected: false, sign: '', winner: false},
                {num: 6, selected: false, sign: '', winner: false},

                {num: 7, selected: false, sign: '', winner: false},
                {num: 8, selected: false, sign: '', winner: false},
                {num: 9, selected: false, sign: '', winner: false}
            ],
            currentSign: '',
            currentPlayer: '',
            playerSign: null,
            PcSign: '',
            //playMode: 'PC',

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
          initialize()
          {
              for(let cell in this.cells) {
                this.cells[cell].selected = false;
                this.cells[cell].sign = '';
                this.cells[cell].winner = false;
              }
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

          restart()
          {
              this.killAll();
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

          //L'utilisateur peut choisir X/O avant de lancer le jeu
          chooseSign(sign)
          {
              this.playerSign = sign;
              this.playerSign == 'X' ? this.pcSign = 'O' : this.pcSign = 'X';
              this.initialize();
          },
        },

        mounted() {
        }
    }
</script>
