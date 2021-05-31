<template>
  <table class="table responsive mt-10">
    <!-- <caption>Authors</caption>-->
    <thead>
    <tr>
      <th scope="col" v-for="(header, index) in headers" :key="index" @click="sort(header)"
          :class="{sort: header.sort}">
        {{ header.title }}
      </th>
    </tr>
    </thead>
    <tbody v-if="sortedAuthors.length > 0">
    <tr v-for="author in sortedAuthors" :key="author.id">
      <td data-label="id" scope="row"> {{ author.id }}</td>
      <td data-label="full name" scope="row"> {{ author.fullName }}</td>
      <td data-label="books" scope="row"> {{ author.products }}</td>
      <td data-label="average price" scope="row"> {{ author.avgProductsPrice }}</td>
      <td data-label="action" scope="row">
        <app-button btn-class="fill" @click="showFormAuthor(author.id)">EDIT</app-button>
      </td>
    </tr>
    </tbody>
    <tr v-else><td colspan="5"><strong>No results match your search criteria</strong></td></tr>
  </table>
  <app-pagination
      :page-size="pageSize"
      :number-of-items="numberOfItems"
      :current-page="currentPage"
      @next-page="currentPage++"
      @prev-page="currentPage--"
      @current-page="currentPage = $event">
  </app-pagination>

  <teleport to="body">
    <app-modal v-if="showModalForm" @close="showModalForm = false" modalWidth="800px">
      <template #header>Edit Author Properties</template>
      <template #body>
        <div class="container">
          <author-form :author-id="authorId" @updated="showModalForm = $event"></author-form>
        </div>
      </template>
    </app-modal>
  </teleport>

</template>

<script>

import AppPagination from './PaginationForTable';
import AuthorForm from "@/components/AuthorForm";

export default {
  name: 'TableOfAuthors',
  components: {
    AppPagination,
    AuthorForm
  },
  props: {
    authors: {
      type: Array,
      require: true,
      default: () => []
    },
    search: {
      type: String,
      require: true,
      default: ''
    },
  },
  data() {
    return {
      pageSize: 12,
      currentPage: 1,
      currentSort: 'fullName',
      currentSortDir: 'asc',
      numberOfItems: 0,
      headers: [
        {name: 'id', title: 'id', sort: true},
        {name: 'fullName', title: 'full name', sort: true},
        {name: 'products', title: 'books', sort: true},
        {name: 'avgProductsPrice', title: 'avg price', sort: true},
        {name: 'action', title: 'action', sort: false}
      ],
      showModalForm: false,
      authorId: -1,
      foundedAuthors: []
    }
  },
  watch: {
    search(search) {
      this.foundedAuthors = this.searchedAuthors(search);
      this.numberOfItems = this.foundedAuthors.length;
      this.currentPage = 1;
    }
  },
  mounted() {
    this.foundedAuthors = this.authors;
    this.numberOfItems = this.authors.length;
  },
  computed: {
    sortedAuthors() {

      return this.foundedAuthors
          .map(function (item) {
            if (item.avgProductsPrice) item.avgProductsPrice = Number(parseFloat(item.avgProductsPrice).toFixed(2));
            return item;
          })
          .sort(this.compare)
          .filter((row, index) => {
            let start = (this.currentPage - 1) * this.pageSize;
            let end = this.currentPage * this.pageSize;
            if (index >= start && index < end) return true;
          });
    }
  },
  methods: {
    showFormAuthor(id) {
      this.showModalForm = true;
      this.authorId = id;
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
    },
    searchedAuthors(search) {
      return this.authors
          .slice(0)
          .filter(author => author.fullName.toLowerCase().includes(search.toLowerCase()));
    }
  }
}
</script>