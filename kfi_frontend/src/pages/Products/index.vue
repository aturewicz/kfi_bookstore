<template>
  <section class="content margin-top-content">
    <div class="container">
      <app-card :card-border-radius="true">
        <template #header>Products</template>

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

        <app-button @click="showSearchProduct = !showSearchProduct">Filters</app-button>
        <search-input-form v-if="showSearchProduct"></search-input-form>
        <table-of-products v-if="hasProducts" :products="products"></table-of-products>
        <p class="items-not-found__paragraph" v-else> PRODUCTS NOT FOUND</p>
      </app-card>
    </div>
  </section>
</template>

<script>
import TableOfProducts from "@/pages/Products/TableOfProducts";
import SearchInputForm from "@/pages/Products/SearchInputForm";

export default {
  name: 'Products',
  data() {
    return {
      showSearchProduct: false
    }
  },
  components: {
    SearchInputForm,
    TableOfProducts
  },
  computed: {
    isLoading() {
      return this.$store.getters["product/isLoading"];
    },
    isSuccess() {
      return this.$store.getters["product/isSuccess"];
    },
    hasError() {
      return this.$store.getters["product/hasError"];
    },
    error() {
      return this.$store.getters["product/error"];
    },
    hasProducts() {
      return this.$store.getters["product/hasProducts"];
    },
    products() {
      return this.$store.getters["product/products"];
    }
  },
  created() {
    this.closeAlerts();
    this.$store.dispatch("product/fetchAll");
  },
  methods: {
    closeAlertError() {
      this.$store.dispatch("product/clearErrors");
    },
    closeAlertSuccess() {
      this.$store.dispatch("product/clearSuccess");
    },
    closeAlerts() {
      this.$store.dispatch("product/clearAlerts");
    }
  }
}
</script>