<template>
  <nav class="nav" ref="nav">
    <div class="container">
      <h1 class="logo">
        <router-link to="/">KFI Bookstore</router-link>
      </h1>
      <ul>
        <li>
          <router-link :to="{name: 'Authors'}">Authors</router-link>
        </li>
        <li>
          <router-link :to="{name: 'Products'}">Products</router-link>
        </li>
      </ul>
    </div>
  </nav>
</template>

<script>
export default {
  name: 'TheTopMenu',
  mounted() {
    window.addEventListener("scroll", this.fixNav);
  },
  unmounted() {
    window.removeEventListener("scroll", this.fixNav)
  },
  methods: {
    fixNav() {
      const nav = this.$refs["nav"];
      if (window.scrollY > nav.offsetHeight + 100) {
        nav.classList.add('active');
      } else {
        nav.classList.remove('active');
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.nav {
  position: fixed;
  background-color: $english-violet;
  top: 0;
  left: 0;
  right: 0;
  transition: all 0.3s ease-in-out;
  z-index: 10;
}

.nav .container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 10px 0;
  transition: all 0.3s ease-in-out;
}

.nav ul {
  display: flex;
  list-style-type: none;
  align-items: center;
  justify-content: center;
}

.nav a {
  color: #fff;
  text-decoration: none;
  padding: 7px 15px;
  transition: all 0.3s ease-in-out;
}

.nav.active {
  background-color: #fff;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
}

.nav.active a {
  color: #000;
}

.nav.active .container {
  padding: 2px 0;
}

.nav a:hover {
  color: $pink-lace;
  font-weight: bold;
}

.nav a.current {
  color: $pink-lavender;
  font-weight: bold;
}

.nav.active a.current {
  color: $glossy-grape;
}

.logo{
  white-space: nowrap;
}

/* transition style */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>