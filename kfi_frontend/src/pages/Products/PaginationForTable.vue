<template>
  <div class="pagination-container" v-show="pages.length > 1">
    <div class="pagination">
      <a @click.prevent="prevPage">&laquo;</a>
      <a @click.prevent="loadPage(page)" v-for="page in pages" :key="page" :class="{active: page === currentPage}">
        {{ page }}
      </a>
      <a @click.prevent="nextPage">&raquo;</a>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AppPagination',
  props: {
    numberOfItems: {
      type: Number,
      require: true,
      default: 0
    },
    pageSize: {
      type: Number,
      require: true,
      default: 1
    },
    currentPage: {
      type: Number,
      require: true,
      default: 1
    },
  },
  computed: {
    pages() {
      let p = [];
      let totalPages;
      totalPages = Math.ceil(this.numberOfItems / this.pageSize);
      for (let i = 0; i < totalPages; i++) p.push(i + 1);
      return p;
    },
  },
  methods: {
    nextPage: function () {
      if ((this.currentPage * this.pageSize) < this.numberOfItems) this.$emit('next-page');
    },
    prevPage: function () {
      if (this.currentPage > 1) this.$emit('prev-page');
    },
    loadPage: function (n) {
      this.$emit('current-page', n);
    },
  }
}
</script>