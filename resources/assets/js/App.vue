<template>
  <div id="app">
    <preferences-component></preferences-component>

    <div v-show="playerSign" class="play-zone row">
        <logo-component></logo-component>
        <information-component></information-component>
        <board-component></board-component>
    </div>
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import { mapActions } from 'vuex'
import PreferencesComponent from './components/PreferencesComponent'
import LogoComponent from './components/LogoComponent'
import InformationComponent from './components/InformationComponent'
import BoardComponent from './components/BoardComponent'
export default {
  name: 'App',
  components: {
    'preferences-component': PreferencesComponent,
    'logo-component': LogoComponent,
    'information-component': InformationComponent,
    'board-component': BoardComponent,
  },

  computed: {
    ...mapGetters([
      'winner', 'gameOn', 'computerPossibleChoices', 'selectedCells', 'currentPlayer', 'playerSign', 'otherSign', 'currentSign',
    ]),
  },

  methods: {
    ...mapActions([
      'newRound',
    ]),
  },

  mounted()
  {
     this.newRound();
  }
}
</script>

<style>

</style>
