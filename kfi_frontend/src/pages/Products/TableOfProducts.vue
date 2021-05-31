<template>
  <table class="table responsive mt-10">
    <!-- <caption>Products</caption>-->
    <thead>
    <tr>
      <th scope="col" v-for="(header, index) in headers" :key="index" @click="sort(header)"
          :class="{sort: header.sort}">
        {{ header.title }}
      </th>
    </tr>
    </thead>
    <tbody>
    <tr v-for="product in sortedProducts" :key="product.ean">
      <td data-label="name" scope="row"> {{ product.name }}</td>
      <td data-label="stock" scope="row"> {{ product.stock }}</td>
      <td data-label="price" scope="row"> {{ product.price }}</td>
      <td data-label="ean" scope="row"> {{ product.ean }}</td>
      <td data-label="availability" scope="row">
        <span :class="['dot', product.stock > 0 ? 'green' : 'red']"></span>
      </td>
      <td data-label="action" scope="row">
        <app-button btn-class="fill" @click="showFormProduct(product.ean)">EDIT</app-button>
      </td>
    </tr>
    </tbody>
  </table>
  <app-pagination
      :page-size="pageSize"
      :number-of-items="products.length"
      :current-page="currentPage"
      @next-page="currentPage++"
      @prev-page="currentPage--"
      @current-page="currentPage = $event">
  </app-pagination>

  <teleport to="body">
    <app-modal v-if="showModalForm" @close="showModalForm = false" modalWidth="800px">
      <template #header>Edit Product Properties</template>
      <template #body>
        <div class="container">
          <product-form :ean="ean" @updated="showModalForm = $event"></product-form>
        </div>
      </template>
    </app-modal>
  </teleport>

</template>

<script>

import AppPagination from './PaginationForTable';
import ProductForm from "@/components/ProductForm";

export default {
  name: 'TableOfProducts',
  components: {
    AppPagination,
    ProductForm
  },
  props: {
    products: {
      type: Array,
      require: true,
      default: () => []
    }
  },
  data() {
    return {
      pageSize: 15,
      currentPage: 1,
      currentSort: 'name',
      currentSortDir: 'asc',
      headers: [
        {name: 'name', title: 'name', sort: true},
        {name: 'stock', title: 'stock', sort: true},
        {name: 'price', title: 'price', sort: true},
        {name: 'ean', title: 'ean', sort: false},
        {name: 'availability', title: 'availability', sort: false},
        {name: 'action', title: 'action', sort: false}
      ],
      showModalForm: false,
      ean: ''
    }
  },
  computed: {
    sortedProducts() {
      return this.products
          .slice(0)
          .sort(this.compare)
          .filter((row, index) => {
            let start = (this.currentPage - 1) * this.pageSize;
            let end = this.currentPage * this.pageSize;
            if (index >= start && index < end) return true;
          });
    }
  },
  methods: {
    showFormProduct(ean) {
      this.showModalForm = true;
      this.ean = ean;
    },
    compare(a, b) {
      let modifier = 1;
      if (this.currentSortDir === 'desc') modifier = -1;
      if (a[this.currentSort] < b[this.currentSort]) return -1 * modifier;
      if (a[this.currentSort] > b[this.currentSort]) return 1 * modifier;
      return 0;
    },
    sort(column) {
      if (column.sort === false) {
        return;
      }
      if (column.name === this.currentSort) {
        this.currentSortDir = this.currentSortDir === 'asc' ? 'desc' : 'asc';
      }
      this.currentSort = column.name;
      this.currentPage = 1;
    }
  }
}
</script>

<style lang="scss">
//@import "../../styles/table.scss";

.dot {
  height: 25px;
  width: 25px;
  border-radius: 50%;
  display: inline-block;

  &.red {
    background-color: #ff5555;
  }

  &.green {
    background-color: #22a822;
  }
}

</style>