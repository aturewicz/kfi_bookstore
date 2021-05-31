<template>
  <transition name="modal">
    <div class="modal-mask">
      <div class="modal-wrapper">
        <app-card class="modal-container" :style="{width: modalWidth}">
          <template #header>
            <div class="modal-header">
              <span class="close" @click="$emit('close')">&times;</span>
              <slot name="header">
                default header
              </slot>
            </div>
          </template>
          <div class="modal-body">
            <slot name="body">
              default body
            </slot>
          </div>
        </app-card>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  name: "AppModal",
  props: {
    modalWidth: {
      type: String,
      require: false,
      default: '450px'
    }
  }
}
</script>

<style lang="scss" scoped>

.modal-mask {
  display: block; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 10; /* Sit on top */
  padding-top: 150px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0, 0, 0); /* Fallback color */
  background-color: rgba(0, 0, 0, 0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-wrapper {
  background-color: #fefefe;
  margin: auto;
  //padding: 20px;
  border: 1px solid #888;
  max-width: 800px;
  position: relative;
  //width: 80%;
}

.modal-container {
  position: absolute;
  width: 100%;
  top: 0;
  left: 0;
}

/* The Close Button */
.close {
  color: #ffffff;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}

</style>