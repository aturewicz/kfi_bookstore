<template>
  <div class="container">
    <form class="search-form" @submit.prevent="search" autocomplete="off">
      <label for="author">Author</label>
      <app-autocomplete id="author" :suggestions="authors" v-model="author"></app-autocomplete>

      <label for="publisher">Publisher</label>
      <app-autocomplete id="publisher" :suggestions="publishers" v-model="publisher"></app-autocomplete>
      <app-button>Search</app-button>
      <app-button @click="clear">Clear</app-button>
    </form>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: 'SearchInputForm',
  data() {
    return {
      author: '',
      publisher: '',
      authors: [],
      publishers: [],
      searchAfterEnteringLetters: 3
    }
  },
  watch: {
    author: function (search) {
      if (search.length <= this.searchAfterEnteringLetters) {
        return
      }
      axios.get(`/api/author/search/${search}`).then(response => {
        this.authors = response.data;
      });
    },
    publisher: function (search) {
      if (search.length <= this.searchAfterEnteringLetters) {
        return
      }
      axios.get(`/api/publisher/search/${search}`).then(response => {
        this.publishers = response.data;
      });
    }
  },
  methods: {
    search() {
      if (this.author.length === 0 && this.publisher.length === 0) return this.showAll();
      this.$store.dispatch("product/search", {'author': this.author, 'publisher': this.publisher});
    },
    showAll() {
      this.$store.dispatch("product/fetchAll");
    },
    clear() {
      this.author = '';
      this.publisher = '';
      this.showAll();
    }
  }
}
</script>

<style lang="scss" scoped>
.search-form {
  display: grid;
  grid-template-columns: auto;
  grid-template-rows: 30px 60px 30px 60px 50px 50px;
  grid-row-gap: 0.1em;
  column-gap: 0.5em;

  label {
    display: flex;
    align-items: flex-end;
    justify-content: flex-start;
  }

  label,.autocomplete {
    grid-column: 1/3;
  }
}
</style>