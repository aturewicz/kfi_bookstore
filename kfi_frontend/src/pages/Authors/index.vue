<template>
  <section class="content margin-top-content">
    <div class="container">
      <app-card :card-border-radius="true">
        <template #header>Authors</template>

        <app-alert v-if="hasError" alert-type="error" :close-btn="true" @close="closeAlertError">
          <div v-if="error.response.data">
            <p><strong>{{ error.response.data.title }}</strong></p>
            <p v-for="(err, key) in error.response.data.errors.children" :key="key">
              <span v-for="(er, index) in err.errors" :key="index">{{ key }}: {{ er }}</span>
            </p>
          </div>
        </app-alert>
        <app-alert v-if="isSuccess" alert-type="success" :close-btn="true" @close="closeAlertSuccess">
          <p>Update was successful!</p>
        </app-alert>

        <app-loader v-if="isLoading"></app-loader>
        <app-button @click="showSearchAuthor = !showSearchAuthor">Filters</app-button>
        <div class="container" v-if="showSearchAuthor">
          <form class="search-author-form mt-10" autocomplete="off">
            <label class="search-author-form__label" for="author">Author</label>
            <input class="search-author-form__input" type="search" id="author" v-model="author"/>
            <app-button @click.prevent="clear" class="search-author-form__button">Clear filter</app-button>
          </form>
        </div>

        <table-of-authors v-if="hasAuthors" :authors="authors" :search="author"></table-of-authors>
        <p class="items-not-found__paragraph" v-else> AUTHORS NOT FOUND</p>
      </app-card>
    </div>
  </section>
</template>

<script>
import TableOfAuthors from "@/pages/Authors/TableOfAuthors";

export default {
  name: 'Authors',
  data() {
    return {
      showSearchAuthor: false,
      author: ''
    }
  },
  components: {
    TableOfAuthors
  },
  computed: {
    isLoading() {
      return this.$store.getters["author/isLoading"];
    },
    isSuccess() {
      return this.$store.getters["author/isSuccess"];
    },
    hasError() {
      return this.$store.getters["author/hasError"];
    },
    error() {
      return this.$store.getters["author/error"];
    },
    hasAuthors() {
      return this.$store.getters["author/hasAuthors"];
    },
    authors() {
      return this.$store.getters["author/authors"];
    }
  },
  created() {
    this.closeAlerts();
    this.$store.dispatch("author/fetchAll");
  },
  methods: {
    closeAlertError() {
      this.$store.dispatch("author/clearErrors");
    },
    closeAlertSuccess() {
      this.$store.dispatch("author/clearSuccess");
    },
    closeAlerts() {
      this.$store.dispatch("author/clearAlerts");
    },
    clear() {
      this.author = '';
    }
  }
}
</script>

<style lang="scss">
.search-author-form {
  display: grid;
  grid-template-columns: auto auto auto;
  grid-template-rows: 30px 45px;
  grid-row-gap: 0.1em;
  column-gap: 0.5em;
  margin-bottom: 10px;
  grid-template-areas:
      "label label label"
      "input input button";

  .search-author-form__label {
    display: flex;
    align-items: flex-end;
    justify-content: flex-start;
  }

  .search-author-form__label {
    grid-area: label;
  }

  .search-author-form__input {
    grid-area: input;
  }

  .search-author-form__button {
    grid-area: button;
  }

  input {
    width: 100%;
    box-sizing: border-box;
    border: 1px solid $glossy-grape;
    font-size: 16px;
    padding: 12px;
    color: #453a55ff;
    outline: none;

    &:focus {
      border: 1px solid $glossy-grape;
      border-radius: 4px;
      outline: none;
    }
  }
}
</style>