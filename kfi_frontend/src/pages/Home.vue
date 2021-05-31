<template>
  <div id="homepage">
    <div class="container-fluid hover-left" ref="container">
      <div
          @mouseenter="addHover('hover-left')"
          @mouseleave="removeHover('hover-left')"
          class="split left"
          ref="left"
      >
        <h1>WRITERS</h1>
        <router-link :to="{name: 'Authors'}" class="btn">SHOW ME</router-link>
      </div>
      <div
          @mouseenter="addHover('hover-right')"
          @mouseleave="removeHover('hover-right')"
          class="split right"
          ref="right"
      >
        <h1>BOOKS</h1>
        <router-link :to="{name: 'Products'}" class="btn">SHOW ME</router-link>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Home',
  methods: {
    addHover(className) {
      this.$refs["container"].classList.add(className);
    },
    removeHover(className) {
      this.$refs["container"].classList.remove(className);
    }
  }
}
</script>

<style lang="scss">
@import "../styles/home.scss";
</style>