<template>
  <section class="content margin-top-content">
    <div class="container">
      <app-card :card-border-radius="true">
        <template #header>Page not found!</template>

        <app-alert alert-type="error" :close-btn="false">
          <h3>
            Page not found!<br>
            Go to <router-link class="return_link" to="/">Homepage</router-link>!
          </h3>
        </app-alert>

      </app-card>
    </div>
  </section>
</template>

<style scoped>
.return_link {
  color: white;
}
</style>