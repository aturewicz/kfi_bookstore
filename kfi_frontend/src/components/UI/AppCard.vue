<template>
  <div :class="['card', {'card-border-radius':cardBorderRadius}]">
    <div :class="['card-header', { 'card-header-border-radius':cardBorderRadius}]">
      <slot name="header"></slot>
    </div>
    <div class="card-body">
      <slot></slot>
    </div>
  </div>
</template>

<script>
export default {
  name: "AppCard",
  props: {
    cardBorderRadius: {
      type: Boolean,
      default: false,
      require: false
    }
  }
}
</script>

<style lang="scss" scoped>
$card-border-radius: 15px;

.card {
  width: 100%;
  background: #fff;
  border: 1px solid #ccc;
  margin-top: 20px;
  margin-bottom: 20px;
  box-shadow: 0 0 40px -10px rgba(0, 0, 0, 0.25);
}

.card-border-radius {
  border-radius: $card-border-radius;
}

.card-header {
  font-size: 1.5rem;
  text-align: left;
  padding: 20px 20px;
  background-color: $periwinkle-crayola;
  color: #fff;
  font-weight: bold;
}

.card-header-border-radius {
  border-radius: $card-border-radius $card-border-radius 0 0;
}

.card-body {
  padding: 30px 20px;
  text-align: left;
  overflow: auto;
}
</style>