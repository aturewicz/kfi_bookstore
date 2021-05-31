<template>
  <div :class="['alert', alertType]" v-if="show">
    <span v-if="closeBtn" class="btn close" @click="$emit('close')">&times;</span>
    <strong v-if="showType">{{ alertType }}! </strong>
    <slot></slot>
  </div>
</template>

<script>
export default {
  name: 'AppAlert',
  props: {
    alertType: {
      type: String,
      default: '',
    },
    closeBtn: {
      type: Boolean,
      default: true
    },
    showType: {
      type: Boolean,
      default: true
    },
    show: {
      type: Boolean,
      default: true
    }
  }
}
</script>

<style lang="scss" scoped>

.alert {
  padding: 20px;
  color: white;
  opacity: 1;
  transition: opacity 0.6s;
  margin-bottom: 15px;

  &.error {
    background-color: #f44336;
  }

  &.success {
    background-color: #04AA6D;
  }

  &.info {
    background-color: #2196F3;
  }

  &.warning {
    background-color: #ff9800;
  }

  strong {
    text-transform: capitalize;
  }

  .btn.close {
    margin-left: 15px;
    color: white;
    font-weight: bold;
    float: right;
    font-size: 22px;
    line-height: 20px;
    cursor: pointer;
    transition: 0.3s;
  }

  .btn.close:hover {
    color: black;
  }
}

</style>