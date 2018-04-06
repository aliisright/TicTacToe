<template>
  <div id="board-component" class="col-md-4 board-section">
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
import { mapGetters } from 'vuex'
import { mapActions } from 'vuex'
export default {
  methods: {
    ...mapActions([
      'userTurn',
    ]),
  },

  computed: {
    ...mapGetters([
      'winner', 'gameOn', 'cells',
    ])
  }

}
</script>
