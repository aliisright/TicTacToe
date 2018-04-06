<template>
  <div id="preferences-component" class="signChoiceBox" v-show="!playerSign || !playMode">
              <div class="signChoiceBox_logo">
                  <img src="img/logo.png" width="300px">
              </div>
              <div class="signChoiceBox_choices" v-show="playMode == '2P' && !p2IsSet">
                <div>
                  <input class="form-control" v-model="player2" placeholder="nom du 2e joueur">
                </div>
                <div>
                  <button class="btn btn-warning ml-2" @click.prevent="setPlayer2()">valider</button>
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
</template>

<script>
import { mapGetters } from 'vuex'
import { mapActions } from 'vuex'
export default {
  data() {
    return {
      player2: 'Player2',
    }
  },

  methods: {
    ...mapActions([
        'chooseSign',
    ]),

    setPlayMode(mode)
    {
        this.$store.state.playMode = mode;
        if(mode == 'PC') {
          this.setPlayer2Name();
        }
    },

    setPlayer2Name()
    {
        this.$store.state.p2IsSet = true;
    },

    setPlayer2()
    {
      this.$store.state.player2 = this.player2;
      this.setPlayer2Name();
    },

  },

  computed: {
    ...mapGetters([
      'playMode', 'p2IsSet', 'player2', 'playerSign',
    ]),
  },
}
</script>
