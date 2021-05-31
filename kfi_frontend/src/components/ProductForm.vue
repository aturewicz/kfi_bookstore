<template>
  <form class="product-form" @submit.prevent="onSubmit" autocomplete="on">
    <label for="name">Product name</label>
    <input type="text" v-model="product.name" id="name" placeholder="Enter the product name" required>

    <label for="price">Price (PLN)</label>
    <input type="text" v-model.number="product.price" id="price" placeholder="Enter the price" required>
    <label for="stock">Stock</label>
    <input type="text" v-model.number="product.stock" id="stock" placeholder="Enter the stock" required>

    <label for="publisher">Publisher</label>
      <app-autocomplete id="publisher" :suggestions="publishers"
                        v-model="product.publisher.name"></app-autocomplete>


    <app-button btn-class="fill">SUBMIT</app-button>
  </form>
</template>

<script>
import axios from "axios";

export default {
  name: 'ProductForm',
  props: {
    ean: {
      type: String,
      require: true,
      default: ''
    }
  },
  data() {
    return {
      product: {
        name: '',
        price: 0,
        stock: 0,
        publisher: {
          id: -1,
          name: ''
        }
      },
      publishers: [],
      errors: [],
      searchAfterEnteringLetters: 2
    }
  },
  watch: {
    'product.publisher.name': function (search) {
      if (search.length <= this.searchAfterEnteringLetters) {
        return;
      }
      axios.get(`/api/publisher/search/${search}`).then(response => {
        this.publishers = response.data;
      });
    }
  },
  mounted() {
    this.$store.dispatch("product/fetchProduct", this.ean).then(() => {
      this.product = Object.assign({}, this.$store.getters["product/product"]);
    });
  },

  methods: {
    onSubmit() {
      this.$store.dispatch("product/update", {
        ean: this.product.ean,
        name: this.product.name,
        price: this.product.price,
        stock: this.product.stock,
        publisher: this.product.publisher.name
      }).then(() => {
        this.$emit('updated');
      });
    },
  }
}
</script>

<style lang="scss">
.product-form {
  display: grid;
  grid-template-columns: auto;
  grid-template-rows:
      30px 50px
      30px 50px
      30px 50px
      30px 50px
      40px;
  grid-row-gap: 0.1em;

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