<template>
  <div class="autocomplete">
      <label v-if="isLabel" :for="name">Publisher</label>
      <input
          :id="id"
          type="search"
          :value="modelValue"
          autocomplete="off"
          @input="onChange($event)"
          @keydown.down="onArrowDown"
          @keydown.up="onArrowUp"
          @keydown.enter="onEnter"
      />
      <ul v-show="isOpen" class="autocomplete-results">
        <li class="loading" v-if="isLoading">Loading results...</li>
        <li
            v-else
            v-for="(result, index) in suggestions"
            :key="index"
            @click="setResult(result.name)"
            class="autocomplete-result"
            :class="{ 'is-active': index === arrowCounter }"
        >
          {{ result.name }}
        </li>
      </ul>
  </div>
</template>

<script>
export default {
  name: 'AppAutocomplete',
  props: {
    isLabel: {
      type: Boolean,
      required: false,
      default: false,
    },
    id: {
      type: String,
      required: true,
      default: Math.random().toString(36).substring(7)
    },
    suggestions: {
      type: Array,
      required: false,
      default: () => [],
    },
    modelValue: {
      type: String,
      required: true,
      default: ''
    },
    isAsync: {
      type: Boolean,
      required: false,
      default: false,
    },
  },
  data() {
    return {
      isOpen: false,
      isLoading: false,
      arrowCounter: -1,
    }
  },
  mounted() {
    document.addEventListener('click', this.handleClickOutside)
  },
  unmounted() {
    document.removeEventListener('click', this.handleClickOutside)
  },
  methods: {
    setResult(result) {
      this.$emit('update:modelValue', result);
      this.isOpen = false;
    },
    //When the user changes input
    onChange(e) {
      if (this.open === false) {
        this.open = true;
      }
      this.$emit('update:modelValue', e.target.value);

      if (this.isAsync) {
        this.isLoading = true;
      } else {
        this.isOpen = true;
      }
    },
    handleClickOutside(event) {
      if (!this.$el.contains(event.target)) {
        this.isOpen = false;
        this.arrowCounter = -1;
      }
    },
    onArrowDown() {
      if (this.arrowCounter < this.suggestions.length) {
        this.arrowCounter = this.arrowCounter + 1;
      }
    },
    onArrowUp() {
      if (this.arrowCounter > 0) {
        this.arrowCounter = this.arrowCounter - 1;
      }
    },
    onEnter() {
      this.$emit('update:modelValue', this.suggestions[this.arrowCounter].name);
      this.isOpen = false;
      this.arrowCounter = -1;
    },
  }

};
</script>

<style lang="scss" scoped>
.autocomplete {
  position: relative;
}

.autocomplete input {
  width: 100%;
}

.autocomplete-results {
  padding: 0;
  margin: 0;
  border: 1px solid #eeeeee;
  background-color: #ffffff;
  height: 120px;
  overflow: auto;
}

.autocomplete-result {
  list-style: none;
  text-align: left;
  padding: 4px 2px;
  cursor: pointer;
}

.autocomplete-result.is-active,
.autocomplete-result:hover {
  background-color: $english-violet;
  color: white;
}

input[type=search] {
  width: 100%;
  box-sizing: border-box;
  border: 1px solid $glossy-grape;
  font-size: 16px;
  background-color: #fff;
  background-image: url('../../assets/loupe.png');
  background-position: 10px 10px;
  background-repeat: no-repeat;
  padding: 12px 20px 12px 40px;
  color: #453a55ff;

}

input[type=search]:focus {
  border: 1px solid $glossy-grape;
  border-radius: 4px;
  outline: none;
}
</style>