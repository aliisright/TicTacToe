<template>
    <div>

    </div>
</template>

<script>
    export default {
        name: 'BoardComponent',
        props: ['cells', 'currentTurn', 'filledCells', 'winner', 'gameOn'],

        data() {
          return {

          }
        },

        methods: {
            initialize()
            {
                for(var cell = 0; cell<this.cells; cell++) {
                  this.cells[cell].selected = false;
                  this.cells[cell].sign = '';
                }
                this.currentTurn = ''; //???
                this.winner = '';
                this.gameOn = true;
            }
        },


        mounted() {
          this.initialize();
        }
    }
</script>
