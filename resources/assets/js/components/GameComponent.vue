<template>
    <div>
      <!-- Préférences -->
      <div class="signChoiceBox" v-show="!playerSign || !playMode">
        <div class="signChoiceBox_logo">
            <div class="logo-neon alis-logo">
              <img src="img/alis-icon.png" width="100px">
            </div>
            <div class="logo-neon tictactoe-logo">
              <img src="img/tictactoe-icon.png" width="300px">
            </div>
        </div>
        <div class="signChoiceBox_choices" v-show="playMode == '2P' && !p2IsSet">
          <div>
            <input class="form-control" v-model="player2" placeholder="nom du 2e joueur">
          </div>
          <div>
            <button class="btn btn-warning ml-2" @click.prevent="setPlayer2Name()">valider</button>
          </div>
        </div>
        <div class="signChoiceBox_choices" v-show="!playMode">
          <div class="sign-choice-cell cell" @click.prevent="setPlayMode('2P')">
             2 joueurs
          </div>
          <h2 class="mx-3">ou</h2>
          <div class="sign-choice-cell cell" @click.prevent="setPlayMode('PC')">
              contre le PC
          </div>
        </div>
        <div class="signChoiceBox_choices"  v-show="!playerSign && playMode && p2IsSet">
            <div class="sign-choice-cell cell" @click.prevent="chooseSign('X')">
                <img src="img/x-icon.png" width="100px">
            </div>
            <h2 class="mx-3">ou</h2>
            <div class="sign-choice-cell cell" @click.prevent="chooseSign('O')">
                <img src="img/o-icon.png" width="100px">
            </div>
        </div>
      </div>

      <!-- Animated logo -->
      <div v-show="playerSign" class="play-zone row">
        <div class="logo-section col-md-4">
            <LogoComponent></LogoComponent>
        </div>

        <!-- Information box -->
        <div class="info-section col-md-4 text-center" v-show="playerSign && playMode">
          <div class="vs_phrase">
            <h1 v-if="playMode == '2P'">{{user.name}} vs {{player2}}</h1>
            <h1 v-if="playMode == 'PC'">{{user.name}} vs XO-3000</h1>
          </div>
          <div>
            <h1 v-if="winner == this.user.name && user.name">Bravo {{user.name}}! t'as gagné</h1>
            <h1 v-if="playMode == 'PC' && winner == 'PC'">XO-3000 a gagné!</h1>
            <h1 v-if="playMode == '2P' && winner == this.player2">{{this.player2}} a gagné!</h1>
            <h1 v-if="winner == 'draw'">:/ pas de gagnant!</h1>
            <p v-if="game.id">Partie N°{{game.id}}</p>
            <p v-if="!winner">
              Le tour de {{currentPlayer}} | <img v-show="currentSign == 'X'" src="img/x-icon.png" width="30px"><img v-show="currentSign == 'O'" src="img/o-icon.png" width="30px">
            </p>

            <button class="btn btn-warning" @click.prevent="newRound()">Nouvelle partie</button>
            <button class="btn btn-warning" @click="this.location.reload()">Réinitialiser</button>
            <a href="/home"><button class="btn btn-danger">Quitter</button></a>
          </div>

        </div>

        <!-- XO Board -->
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
        components: {
          LogoComponent,
        },
        data() {
          return {
            user: null,
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
            player2: '',
            p2IsSet: false
          }
        },


        methods: {
          //le tour du player!
          userTurn(index)
          {
              if(this.gameOn != false && !this.cells[index].selected) {
                this.selectCell(index);
                this.flipTurn();
                this.hasGameEnded();
                if(this.gameOn != false && this.playMode == 'PC') {
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
              if(this.playMode == 'PC') {
                  this.currentPlayer == 'PC' ? this.currentPlayer = this.user.name : this.currentPlayer = 'PC';
              } else if(this.playMode == '2P') {
                  this.currentPlayer == this.player2 ? this.currentPlayer = this.user.name : this.currentPlayer = this.player2;
              }
          },

          //Vérifier si le jeu est terminé (toutes les cases sont remplie ou il y a un gagnant)
          hasGameEnded()
          {
              this.verifyWinner();
              if(!this.winner) {
                if(this.selectedCells.length == 9) {
                  this.winner = 'draw';
                  this.emptyAfterEnd();
                  this.saveWinner();
                }
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
                    this.winner = this.user.name;
                  } else if(winnerSign == this.otherSign) {
                    this.playMode == 'PC' ? this.winner = 'PC' : this.winner = this.player2;
                  }
                  this.emptyAfterEnd();
                  this.saveWinner();
                }
              }
          },




          //Qui commence???
          firstTurn()
          {
              let random = Math.floor(Math.random()*2);
              random == 1 ? this.currentPlayer = this.user.name : (this.playMode == 'PC' ? this.currentPlayer = 'PC' : this.currentPlayer = this.player2);
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
                this.currentSign = this.otherSign;
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
                this.playerSign == 'X' ? this.otherSign = 'O' : this.otherSign = 'X';
                this.initializeBoard();
              }
          },

          //choix de mode de jeu 2 joueur ou contre l'ordi
          setPlayMode(mode)
          {
            this.playMode = mode;
            if(mode == 'PC') {
              this.setPlayer2Name();
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
              this.otherSign = '';
              this.currentSign = '';
          },

          //Créer un nouveau board + récuperer le board
          newRound()
          {
            this.killAll()
            axios.get('game/new').then((res) => {
              this.user = res.data.user,
              this.connected = res.data.connected,
              this.game = res.data.game,
              this.cells = res.data.cells
            });
          },

          saveWinner()
          {
            if(this.winner == this.user.name) {
              axios.post('/game/winner', {
                id: this.game.id,
                mode: this.playMode,
                against: this.player2,
                won: true
              });
            } else {
              axios.post('/game/winner', {
                id: this.game.id,
                mode: this.playMode,
                against: this.player2,
                won: false
              });
            }
          },

          newGame()
          {
            player2: '';
            playMode: null;
            p2IsSet: false;
            this.newRound();
          },

          setPlayer2Name()
          {
            this.p2IsSet = true;
          }
        },
        mounted() {
          this.newRound();
        }
    }
</script>
