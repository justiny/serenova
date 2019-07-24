window.$ = window.jQuery = require("jquery");

import "./components/venobox.js";
import "./components/navScroll.js";
import "./components/searchToggle.js";
import "./components/mobileNav.js";
import "./components/plyr.js";
import "./components/underlineMagic.js";
import "./components/iFrameResizer.js";
import "./components/sectionSpacing.js";
import "./components/URLParamUpdater.js";

import PricingTable from "./components/PricingTable.vue";
import Vue from "vue";
import VTooltip from "v-tooltip";

Vue.use(VTooltip);

if (document.getElementById('app')) {
  new Vue({
    render: h => h(PricingTable)
  }).$mount("#app");
}
