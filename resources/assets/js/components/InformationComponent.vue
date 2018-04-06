<template>
  <div id="information-component" class="info-section col-md-4 text-center" v-show="playerSign && playMode">
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
              <a v-if="connected" href="/home"><button class="btn btn-danger">Quitter</button></a>
              <a v-if="!connected" href="/"><button class="btn btn-danger">Quitter</button></a>
            </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { mapActions } from 'vuex'
export default {
  methods: {
    ...mapActions([
        'newRound', 'chooseSign',
    ]),
  },

  computed: {
    ...mapGetters([
      'playerSign','playMode', 'player2', 'user', 'winner', 'game', 'currentPlayer', 'currentSign', 'connected'
    ]),
  }
}
</script>
