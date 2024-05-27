<template>
    <div class="pagination">
      <button  @click="changePage(pagination.prev_page_url)" :disabled="!pagination.prev_page_url">Previous</button>
      
      <span v-if="pagination.current_page > 3">...</span>
      
      <button class="btn btn-primary" v-for="page in pagesToShow" 
              :key="page" 
              @click="changePage(page.url)" 
              :class="{ active: page.label == pagination.current_page }">
        {{ page.label }}
      </button>
      
      <span v-if="pagination.current_page < pagination.last_page - 2">...</span>
      
      <button class="btn btn-primary" @click="changePage(pagination.next_page_url)" :disabled="!pagination.next_page_url">Next</button>
    </div>
  </template>
  
  <script>
  export default {
    props: {
      data: {
        type: Object,
        required: true
      }
    },
    computed: {
      pagination() {
        return this.data;
      },
      pagesToShow() {
        const range = [];
        const start = Math.max(1, this.pagination.current_page - 2);
        const end = Math.min(this.pagination.last_page, this.pagination.current_page + 2);
        
        for (let i = start; i <= end; i++) {
          range.push({
            url: `${this.pagination.path}?page=${i}`,
            label: i
          });
        }
        
        return range;
      }
    },
    methods: {
      changePage(url) {
        if (url) {
          this.$emit('pagination-change-page', url);
        }
      }
    }
  }
  </script>
  
  <style>
  .pagination {
    display: flex;
    align-items: center;
  }
  .pagination button {
    margin: 0 5px;
    padding: 5px 10px;
  }
  .pagination button.active {
    font-weight: bold;
  }
  .pagination span {
    margin: 0 5px;
  }
  </style>