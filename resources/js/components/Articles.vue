<style lang="css">
body {
  background: #eee;
}
</style>
<template>
  <div>
    <div class="row">
      <div class="col-md-4">
        <div style="border:none" class="sticky-top shadow card card-body">
          <h4>Create an Article</h4>
          <hr />
          <form @submit.prevent="addArticle" class="form">
            <div class="form-group">
              <input v-model="article.title" type="text" placeholder="Title" class="form-control" />
            </div>
            <div class="form-group">
              <textarea v-model="article.body" type="text" placeholder="Body" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-primary w-100">Save</button>
          </form>
        </div>
      </div>
      <div class="col-md-8">
        <hr />
        <nav aria-label="Page navigation example">
          <ul class="pagination">
            <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item">
              <a
                @click="fetchArticles(pagination.prev_page_url)"
                class="page-link"
                href="#"
              >Previous</a>
            </li>

            <li class="page-item disabled">
              <a
                class="page-link text-dark"
                href="#"
              >Page {{pagination.current_page}} of {{pagination.last_page}}</a>
            </li>

            <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item">
              <a @click="fetchArticles(pagination.next_page_url)" class="page-link" href="#">Next</a>
            </li>
          </ul>
        </nav>
        <hr />

        <div
          v-for="article in articles"
          style="border:none"
          v-bind:key="article.id"
          class="card card-body mb-2"
        >
          <h3>{{article.title}}</h3>
          <p>{{article.body}}</p>
          <hr />

          <div class="btn-group">
            <button @click="editArticle(article)" class="btn btn-info">Edit</button>
            <button @click="deleteArticle(article.id)" class="btn btn-primary">Delete</button>
          </div>
        </div>
        <hr />
        <nav aria-label="Page navigation example">
          <ul class="pagination">
            <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item">
              <a
                @click="fetchArticles(pagination.prev_page_url)"
                class="page-link"
                href="#"
              >Previous</a>
            </li>

            <li class="page-item disabled">
              <a
                class="page-link text-dark"
                href="#"
              >Page {{pagination.current_page}} of {{pagination.last_page}}</a>
            </li>

            <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item">
              <a @click="fetchArticles(pagination.next_page_url)" class="page-link" href="#">Next</a>
            </li>
          </ul>
        </nav>
        <hr />
      </div>
    </div>
  </div>
</template>

<script>
import { maxHeaderSize } from "http";
export default {
  data() {
    return {
      articles: [],
      article: {
        id: "",
        title: "",
        body: ""
      },
      article_id: "",
      pagination: {},
      edit: false
    };
  },
  created() {
    this.fetchArticles();
  },
  methods: {
    fetchArticles(page_url) {
      let vm = this;
      page_url = page_url || "/api/articles";
      fetch(page_url)
        .then(res => res.json())
        .then(res => {
          this.articles = res.data;
          vm.makePagination(res.meta, res.links);
        })
        .catch(err => console.log(err));
    },

    makePagination(meta, links) {
      let pagination = {
        current_page: meta.current_page,
        last_page: meta.last_page,
        next_page_url: links.next,
        prev_page_url: links.prev
      };
      this.pagination = pagination;
    },

    deleteArticle(id) {
      if (confirm("Are you sure?")) {
        fetch(`api/article/${id}`, {
          method: "delete"
        })
          .then(res => res.json())
          .then(data => {
            this.fetchArticles();
            alert("Article Removed");
          })
          .catch(err => console.log(err));
      }
    },
    addArticle() {
      if (this.edit === false) {
        //add
        fetch("api/article", {
          method: "POST",
          body: JSON.stringify(this.article),
          headers: {
            "content-type": "application/json"
          }
        })
          .then(res => res.json())
          .then(data => {
            this.article.title = "";
            this.article.body = "";

            this.fetchArticles();
            alert("Article Added Successfully");
          })
          .catch(err => console.log(err));
      } else {
        fetch("api/article", {
          method: "PUT",
          body: JSON.stringify(this.article),
          headers: {
            "content-type": "application/json"
          }
        })
          .then(res => res.json())
          .then(data => {
            this.article.title = "";
            this.article.body = "";

            this.fetchArticles();
            alert("Article Updated!");
          })
          .catch(err => console.log(err));
      }
    },
    editArticle(article) {
      this.edit = true;
      this.article.id = article.id;
      this.article.article_id = article.id;
      this.article.title = article.title;
      this.article.body = article.body;
    }
  }
};
</script>