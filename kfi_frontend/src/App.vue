<template>
  <the-top-menu v-if="!$route.meta.hideNavbar"/>
  <div class="wrapper">
    <router-view v-slot="{ Component }">
      <transition name="fade" mode="out-in">
        <component :is="Component"/>
      </transition>
    </router-view>
  </div>
</template>

<script>
import TheTopMenu from "./components/TheTopMenu";

export default {
  name: 'App',
  components: {
    TheTopMenu
  }
}
</script>

<style lang="scss">
@import "./styles/app.scss";
</style>
