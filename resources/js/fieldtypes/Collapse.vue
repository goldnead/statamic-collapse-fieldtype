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
        <set-field
          v-for="field in fields"
          v-show="showField(field)"
          :key="field.handle"
          :field="field"
          :meta="meta.defaults[field.handle]"
          :value="values[field.handle]"
          :parent-name="name"
          :set-index="0"
          :errors="errors(field.handle)"
          :error-key="errorKey(field.handle)"
          :read-only="isReadOnly"
          @updated="updated(field.handle, $event)"
          @meta-updated="metaUpdated(field.handle, $event)"
          @focus="$emit('focus')"
          @blur="$emit('blur')"
        />
      </publish-fields-container>
    </div>
  </div>
</template>

<script>
import ValidatesFieldConditions from "../../../vendor/statamic/cms/resources/js/components/field-conditions/ValidatorMixin.js";
import SetField from "../../../vendor/statamic/cms/resources/js/components/fieldtypes/replicator/Field.vue";

export default {
  mixins: [Fieldtype, ValidatesFieldConditions],

  inject: ["storeName"],

  components: { SetField },

  mounted() {
    console.log(this);
  },

  data() {
    return {
      isOpen: false
    };
  },

  methods: {
    updated(handle, value) {
      let group = JSON.parse(JSON.stringify(this.values));
      group[handle] = value;
      this.update(group);
    },

    metaUpdated(handle, value) {
      let meta = JSON.parse(JSON.stringify(this.meta));
      meta.defaults[handle] = value;
      this.updateMeta(meta);
    },

    open() {
      this.isOpen = true;
    },

    close() {
      this.isOpen = false;
    },

    errorKey(handle) {
      return `${this.handle}.${handle}`;
    },

    errors(handle) {
      const state = this.$store.state.publish[this.storeName];
      if (!state) return [];
      return state.errors[this.errorKey(handle)] || [];
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

    oldErrors() {
      return this.state.errors;
    },

    fields() {
      return this.config.fields;
    }
  }
};
</script>
