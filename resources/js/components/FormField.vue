<template>
  <default-field :field="field">
    <template slot="field">
      <div v-if="value" class="display-icon mb-4">
        <span class="relative inline-block p-8 border" style="border-color: #e0e0e0;">
          <i :class="value + ' js-icon'"></i>

          <span class="close-icon" @click="clear">
            <i class="fa fa-times-circle"></i>
          </span>
        </span>
      </div>
      <input
        :id="field.name"
        type="hidden"
        class="w-full form-control form-input form-input-bordered"
        :class="errorClasses"
        :placeholder="field.name"
        v-model="value"
      />

      <transition name="fade">
        <modal v-if="modalOpen" @close="closeModal" class="fontawesome-modal">
          <div class="bg-white rounded-lg shadow-lg">
            <div class="px-8 py-6 border-b relative" style="border-color: #e0e0e0;">
              <heading :level="2" class="mb-0">{{ __('Select Icon') }}</heading>
              <a href="#" class="fontawesome-close" @click.prevent="closeModal">
                <i class="fa fa-times"></i>
              </a>
            </div>
            <div class="px-8 py-4 border-b" style="border-color: #e0e0e0; background: #fafafa;">
              <div class="flex flex-wrap -mx-4">
                <div class="w-1/3 px-4">
                  <select id="type" class="w-full form-control form-select" v-model="filter.type">
                    <option value disabled="disabled">Select a type</option>
                    <option value="all">All</option>
                    <option
                      v-for="def in definitions"
                      :value="stringToDefinition(def)"
                      v-html="def"
                    ></option>
                  </select>
                </div>
                <div class="w-2/3 px-4">
                  <input
                    type="text"
                    id="search"
                    class="w-full form-control form-input form-input-bordered"
                    placeholder="Search icons"
                    v-model="filter.search"
                  />
                </div>
              </div>
            </div>
            <div class="px-8 py-6 fontawesome-inner">
              <div v-if="loading">Loading...</div>
              <div
                class="flex flex-wrap items-stretch -mx-2"
                v-else-if="icons.length > 0 && !loading"
              >
                <div
                  v-for="icon in icons"
                  v-if="((filter.type == '' || filter.type == 'all') || (filter.type == icon.prefix)) && icon.show"
                  class="flex items-center justify-center text-center px-2 w-1/4 cursor-pointer mb-4"
                  style="outline: 1px solid #e0e0e0;outline-offset: -.5rem;"
                  @click="saveIcon(icon)"
                >
                  <div :data-class="icon.prefix+' fa-'+icon.iconName" class="p-4">
                    <i :class="icon.prefix+' fa-'+icon.iconName"></i>
                    <span class="icon-name" v-html="icon.iconName"></span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </modal>
      </transition>

      <button
        class="btn btn-default btn-primary"
        @click.prevent="toggleModal"
        v-text="addButtonText"
      ></button>

      <p v-if="hasError" class="my-2 text-danger">{{ firstError }}</p>
    </template>
  </default-field>
</template>

<script>
import { FormField, HandlesValidationErrors } from "laravel-nova";

export default {
  mixins: [FormField, HandlesValidationErrors],

  props: ["resourceName", "resourceId", "field"],

  data: () => ({
    loading: false,
    library: {},
    icons: [],
    value: "",
    definitions: [],
    modalOpen: false,
    defaultIconObj: {},
    filter: {
      type: "",
      search: "",
    },
  }),

  beforeMount() {
    let arr = {};
    const { library } = require("@fortawesome/fontawesome-svg-core");
    const fab = require("@fortawesome/free-brands-svg-icons").fab;

    if (this.pro) {
      const fas = require("@fortawesome/pro-solid-svg-icons").fas;
      const far = require("@fortawesome/pro-regular-svg-icons").far;
      const fal = require("@fortawesome/pro-light-svg-icons").fal;
      const fad = require("@fortawesome/pro-duotone-svg-icons").fad;
      require("@fortawesome/fontawesome-pro/js/fontawesome.js");

      library.add(fas, far, fab, fal, fad);

      arr.far = far;
      arr.fas = fas;
      arr.fab = fab;
      arr.fal = fal;
      arr.fad = fad;
    } else {
      const fas = require("@fortawesome/free-solid-svg-icons").fas;
      const far = require("@fortawesome/free-regular-svg-icons").far;
      require("@fortawesome/fontawesome-free/js/fontawesome.js");

      library.add(fas, far, fab);

      arr.far = far;
      arr.fas = fas;
      arr.fab = fab;
    }

    for (let key in arr) {
      this.definitions.push(this.definitionToString(key));

      for (let i in arr[key]) {
        let icon = arr[key][i];

        if (this.canShowIcon(icon)) {
          icon.show = true;
          this.icons.push(icon);
        }
      }
    }
  },

  mounted() {
    this.icons.sort((a, b) =>
      a.iconName > b.iconName ? 1 : b.iconName > a.iconName ? -1 : 0
    );

    // Set default icon object
    if (this.defaultIcon && this.defaultIconType) {
      let i = this.icons.filter(
        (icon) =>
          icon.prefix === this.defaultIconType &&
          icon.iconName === this.defaultIcon
      );

      if (i[0]) {
        this.defaultIconObj = i[0];
      }
    }
  },

  computed: {
    pro() {
      return this.field.pro || false;
    },
    defaultIcon() {
      return this.field.default_icon || "";
    },
    defaultIconType() {
      return this.field.default_icon_type || "";
    },
    addButtonText() {
      return this.field.add_button_text || "Add Icon";
    },
    enforceDefaultIcon() {
      return this.field.enforce_default_icon || false;
    },
    defaultIconOutput() {
      return this.defaultIconType + " fa-" + this.defaultIcon;
    },
  },

  methods: {
    canShowIcon(icon) {
      if (typeof this.field.only !== "undefined") {
        if (this.field.only.indexOf(icon.iconName) === -1) {
          return false;
        }
      }

      return !icon.iconName ||
        !icon.prefix ||
        icon.iconName === "font-awesome-logo-full"
        ? false
        : true;
    },

    clear() {
      if (
        this.enforceDefaultIcon &&
        this.defaultIcon &&
        this.defaultIconType &&
        this.defaultIconObj.iconName
      ) {
        this.value = this.defaultIconOutput;
        this.saveIcon(this.defaultIconObj);
      } else {
        this.value = "";
      }

      this.clearFilter();
    },

    clearFilter() {
      this.filter.type = "";
      this.filter.search = "";
    },

    closeModal() {
      this.modalOpen = false;

      this.clearFilter();
    },

    toggleModal() {
      this.modalOpen = !this.modalOpen;

      this.clearFilter();
    },

    saveIcon(icon) {
      if (this.$el.getElementsByClassName("js-icon").length > 0) {
        this.$el
          .getElementsByClassName("js-icon")[0]
          .setAttribute(
            "class",
            "js-icon " + icon.prefix + " fa-" + icon.iconName
          );
      }

      this.value = icon.prefix + " fa-" + icon.iconName;

      this.filter.type = "";
      this.filter.search = "";

      this.closeModal();
    },

    /*
     * Convert the class to string
     */
    definitionToString(def) {
      switch (def) {
        case "far":
          return "Regular";
          break;
        case "fas":
          return "Solid";
          break;
        case "fab":
          return "Brands";
          break;
        case "fal":
          return "Light";
          break;
        case "fad":
          return "Duotone";
          break;
      }
    },

    /*
     * Convert the string to class method
     */
    stringToDefinition(str) {
      switch (str) {
        case "Regular":
          return "far";
          break;
        case "Solid":
          return "fas";
          break;
        case "Brands":
          return "fab";
          break;
        case "Light":
          return "fal";
          break;
        case "Duotone":
          return "fad";
          break;
      }
    },

    /*
     * Set the initial, internal value for the field.
     */
    setInitialValue() {
      this.value = this.field.value || this.defaultIconOutput;
    },

    /**
     * Fill the given FormData object with the field's internal value.
     */
    fill(formData) {
      formData.append(
        this.field.attribute,
        this.value || this.defaultIconOutput
      );
    },

    /**
     * Update the field's internal value.
     */
    handleChange(value) {
      this.value = value;
    },

    search() {
      let keyword = this.filter.search.toUpperCase();
      for (let i in this.icons) {
        if (keyword == "") {
          this.icons[i].show = true;
        } else {
          let alt = keyword.replace("-", " ");
          let name = this.icons[i].iconName.toUpperCase();
          let nameAlt = name.replace("-", " ");

          if (
            name.includes(keyword) ||
            name.indexOf(keyword) !== -1 ||
            nameAlt.includes(alt) ||
            nameAlt.indexOf(alt) !== -1
          ) {
            this.icons[i].show = true;
          } else {
            this.icons[i].show = false;
          }
        }
      }

      this.$nextTick(function () {
        this.loading = false;
      });
    },
  },
  watch: {
    "filter.search": {
      handler(val) {
        this.loading = true;

        this.search();
      },
    },
    "filter.type": {
      handler(val) {
        this.loading = true;

        this.$nextTick(function () {
          this.loading = false;
        });
      },
    },
  },
};
</script>

<style>
.display-icon svg {
  width: 4rem;
  height: 4rem;
}
.display-icon i {
  font-size: 4rem;
}

.display-icon:hover .close-icon {
  display: block;
}

.close-icon {
  display: none;
  position: absolute;
  top: 0;
  right: 0;

  opacity: 0.75;
  cursor: pointer;

  transition: all 0.2s ease-in-out;

  transform: translate(50%, -50%);
}

.close-icon:hover {
  opacity: 1;
}

.close-icon svg {
  width: 1.5rem !important;
  height: 1.5rem !important;
}

.close-icon i {
  font-size: 1.5rem !important;
}

.svg-inline--fa.fa-w-12 > path,
.svg-inline--fa.fa-w-14 > path,
.svg-inline--fa.fa-w-16 > path,
.svg-inline--fa.fa-w-18 > path,
.svg-inline--fa.fa-w-20 > path {
  fill: #3c4655;
}

.svg-inline--fa.fa-w-12,
.svg-inline--fa.fa-w-14,
.svg-inline--fa.fa-w-16,
.svg-inline--fa.fa-w-18,
.svg-inline--fa.fa-w-20 {
  width: 1.75em;
  height: 2em;
}

.svg-inline--fa.fa-w-20 {
  width: 2.5em;
}

.svg-inline--fa.fa-w-18 {
  width: 2.25em;
}

.svg-inline--fa.fa-w-16 {
  width: 2em;
}

.svg-inline--fa.fa-w-12 {
  width: 1.5em;
}

.fontawesome-modal > div:first-child {
  flex-basis: 0;
  height: 100%;
  flex-direction: column;
}

.fontawesome-modal > div:first-child > div {
  position: relative;
  max-height: 80%;
  overflow: hidden;
  width: 80%;
  margin: 0 auto;
  display: flex;
  flex-grow: 1;
}

.fontawesome-modal > div:first-child > div > div {
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  width: 100%;
  left: 0;
  height: 100%;
}

.fontawesome-inner {
  height: 80%;
  overflow: scroll;
}

.fontawesome-close {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  right: 1.5rem;
  font-size: 1.5rem;
  color: #3c4655;
}

.icon-name {
  display: block;
  font-size: 12px;
  margin-top: 0.5em;
  background: #fafafa;
  padding: 0.2em;
}
</style>
