<template>
  <div class="collapse-group" :class="{ 'collapse-group--open': isOpen }">
    <div class="collapse-group__header" @click="toggle()">
      <span class="collapse-group__title">{{ config.display }}</span>
      <small
        v-if="config.instructions"
        v-html="$options.filters.markdown(config.instructions)"
        class="help-block collapse-group__instructions"
      ></small>
    </div>

    <div class="collapse-group__body" ref="collapse_body">
      <publish-fields-container>
        <publish-field
          v-for="field in fields"
          v-show="showField(field)"
          :key="field.handle"
          :config="field"
          :value="values[field.handle]"
          :meta="meta[field.handle]"
          :errors="errors[field.handle]"
          :read-only="readOnly"
          :can-toggle-label="canToggleLabels"
          :name-prefix="namePrefix"
          @input="updated(field.handle, $event)"
          @meta-updated="$emit('meta-updated', field.handle, $event)"
          @synced="$emit('synced', field.handle)"
          @desynced="$emit('desynced', field.handle)"
          @focus="$emit('focus')"
          @blur="$emit('blur')"
        />
      </publish-fields-container>
    </div>
  </div>
</template>

<script>
import ValidatesFieldConditions from "../../../vendor/statamic/cms/resources/js/components/field-conditions/ValidatorMixin.js";

export default {
  mixins: [Fieldtype, ValidatesFieldConditions],

  inject: ["storeName"],

  data() {
    return {
      isOpen: false
    };
  },

  mounted() {
    console.log("test");
  },

  methods: {
    updated(handle, value) {
      let group = JSON.parse(JSON.stringify(this.values));
      group[handle] = value;
      this.update(group);
    },

    open() {
      this.isOpen = true;
    },

    close() {
      this.isOpen = false;
    },

    toggle() {
      if (this.isOpen) {
        this.close();
      } else {
        this.open();
      }
    }
  },
  computed: {
    state() {
      return this.$store.state.publish[this.storeName];
    },

    values() {
      // merge default values with "real" values
      return { ...this.meta.defaults, ...this.value };
    },

    errors() {
      return this.state.errors;
    },

    fields() {
      return this.config.fields;
    }
  }
};
</script>
