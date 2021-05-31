<template>
  <form class="author-form" @submit.prevent="onSubmit" autocomplete="on">
    <label for="fullName">Full name</label>
    <input type="text" v-model="author.fullName" id="fullName" placeholder="Enter the full name of the author" required>
    <app-button btn-class="fill">SUBMIT</app-button>
  </form>
</template>

<script>
export default {
  name: 'AuthorForm',
  props: {
    authorId: {
      type: Number,
      require: true,
      default: -1
    }
  },
  data() {
    return {
      author: {
        id: -1,
        fullName: ''
      }
    }
  },
  mounted() {
    this.$store.dispatch("author/fetchAuthor", this.authorId).then(() => {
      this.author = Object.assign({}, this.$store.getters["author/author"]);
    });
  },

  methods: {
    onSubmit() {
      this.$store.dispatch("author/update", {
        id: this.author.id,
        fullName: this.author.fullName
      }).then(() => {
        this.$emit('updated');
      });
    },
  }
}
</script>

<style lang="scss">
.author-form {
  display: grid;
  grid-template-columns: auto;
  grid-template-rows:
      30px 50px
      40px;
  grid-row-gap: 0.5em;

  label {
    display: flex;
    align-items: flex-end;
    justify-content: flex-start;
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