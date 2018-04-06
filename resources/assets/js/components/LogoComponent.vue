<template>
  <div id="logo-component" class="col-md-4 logo-section">
        <div class="logo-neon alis-logo">
          <img src="img/logo.png" width="300px">
        </div>
          <div class="logo-neon logo-xo-icons">
            <img class="o-logo-icon" v-show="o_icon" src="img/o-icon.png" width="100">
            <img class="o-logo-icon icon-off" v-show="!o_icon" src="img/o-icon-inactive.png" width="100">
            <img class="x-logo-icon" v-show="x_icon" src="img/x-icon.png" width="100px">
            <img class="x-logo-icon icon-off" v-show="!x_icon" src="img/x-icon-inactive.png" width="100">
            <img class="o-logo-icon" v-show="o_icon" src="img/o-icon.png" width="100">
            <img class="o-logo-icon icon-off" v-show="!o_icon" src="img/o-icon-inactive.png" width="100">
          </div>
            <div class="logo-neon logo-xo-icons">
            <img class="x-logo-icon" v-show="x_icon" src="img/x-icon.png" width="100px">
            <img class="x-logo-icon icon-off" v-show="!x_icon" src="img/x-icon-inactive.png" width="100">
            <img class="o-logo-icon" v-show="o_icon" src="img/o-icon.png" width="100">
            <img class="o-logo-icon icon-off" v-show="!o_icon" src="img/o-icon-inactive.png" width="100">
            <img class="x-logo-icon" v-show="x_icon" src="img/x-icon.png" width="100px">
            <img class="x-logo-icon icon-off" v-show="!x_icon" src="img/x-icon-inactive.png" width="100">
          </div>
          <div class="logo-neon logo-xo-icons">
            <img class="o-logo-icon" v-show="o_icon" src="img/o-icon.png" width="100px">
            <img class="o-logo-icon icon-off" v-show="!o_icon" src="img/o-icon-inactive.png" width="100">
            <img class="x-logo-icon" v-show="x_icon" src="img/x-icon.png" width="100">
            <img class="x-logo-icon icon-off" v-show="!x_icon" src="img/x-icon-inactive.png" width="100">
            <img class="o-logo-icon" v-show="o_icon" src="img/o-icon.png" width="100px">
            <img class="o-logo-icon icon-off" v-show="!o_icon" src="img/o-icon-inactive.png" width="100">
          </div>
  </div>
</template>

<script>
export default {
    name: 'LogoComponent',
    data() {
      return {
        x_icon: false,
        o_icon: true
      }
    },

    methods: {
      lightsOn: function()
      {
          this.x_icon = !this.x_icon;
          this.o_icon = !this.o_icon;
      },

      lights: function()
      {
          setInterval(this.lightsOn, 1000);
      },
    },

    mounted() {
      this.lights();
    }
}
</script>
