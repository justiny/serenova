<template>
  <div class="es-col-xs-6">
    <div class="es-row xs-mb-50">
      <div class="es-col-xs-6 es-col-sm-3 es-col-md-2 es-offset-md-1">
        <div class="flex align-center">
          <label for="usage" class="text-16 text-dark-10">Usage Models</label>
          <div class="es-pricing-tooltip xs-ml-5"><span class="tooltip-target text-16-akk-bold text-white pointer xs-ml-5" v-tooltip.top="'In a Named agent model, customers pay by the number of unique agents that access CxEngage anytime during the billing cycle. A Concurrent model bills according to the maxiumum number of users that access CxEngage at the same time.'">?</span></div>
        </div>
        <div class="select-wrapper">
          <select class="select" id="usage" v-model="activeUsage">
            <option :value="item.toLowerCase()" class="text-20 text-dark-10" v-for="(item, i) in usage" :key="i">{{ item }}</option>
          </select>
        </div>
      </div>
      <div class="es-col-xs-6 es-col-sm-3 es-col-md-2 xs-mt-15 sm-mt-0">
        <label for="price" class="text-16 text-dark-10">Currency</label>
        <div class="select-wrapper">
          <select class="select" id="price" v-model="activeCurrency">
            <option :value="item" class="text-20 text-dark-10" v-for="(item, i) in currency" :key="i">{{ item }}</option>
          </select>
        </div>
      </div>
      <div class="es-col-xs-6 es-pricing-tabs flex flex-wrap justify-space-around xs-mt-30">
        <button :class="['es-pricing-item text-center xs-pt-15 xs-pb-15 fill-dark-50 text-white text-15-bold caps', activeMobile == 'essential' ? '-active' : '']"  @click="activeMobile = 'essential'" tabindex="0" role="button" aria-labelledby="Show essential tab">Essential</button>
        <button :class="['es-pricing-item text-center xs-pt-15 xs-pb-15 fill-dark-50 text-white text-15-bold caps', activeMobile == 'pro' ? '-active' : '']"  @click="activeMobile = 'pro'" tabindex="0" role="button" aria-labelledby="Show pro tab">Pro</button>
        <button :class="['es-pricing-item text-center xs-pt-15 xs-pb-15 fill-dark-50 text-white text-15-bold caps', activeMobile == 'ultimate' ? '-active' : '']"  @click="activeMobile = 'ultimate'" tabindex="0" role="button" aria-labelledby="Show ultimate tab">Ultimate</button>
      </div>
    </div>
    <div class="es-pricing-table es-pricing-table-desktop flex flex-wrap">
      <div class="es-pricing-col es-span-xs-6 es-span-sm-2" v-if="(activeMobile == 'essential' && windowWidth < 753) || windowWidth >= 753">
        <div class="es-pricing-col-inner text-center xs-pt-20 xs-pb-20 xs-pr-20 xs-pl-20 fill-dark-80 text-dark-10">
          <h2 class="title-1">Essential</h2>
          <p class="text-14-italic xs-mb-20 sm-mb-40">The foundational contact center solution</p>
          <h3 class="title-4">{{ setPrice("essential") }}</h3>
          <p class="text-14-italic">per {{ activeUsage }} agent per month</p>
          <p class="text-16 xs-mt-5 xs-mb-20">Billed Annually</p>
          <a href="/contact" class="button button-large button-blue text-15-bold decoration-none xs-mb-20">Contact Us</a>
        </div>
        <div class="es-pricing-col-top fill-white xs-pt-40 xs-pr-20 xs-pb-40 xs-pl-20">
          <h4 class="title-2 text-dark-10 xs-mb-10">Essential comes with:</h4>
          <ul class="list-unstyled">
            <li class="text-20 text-dark-30" v-for="(item, i) in essentialItems" :key="i" v-html="item"></li>
          </ul>
        </div>
        <div class="es-pricing-col-middle fill-white xs-pb-40 xs-pl-20 xs-pr-20">
          <hr>
          <h4 class="title-2 text-dark-10 xs-mt-20">Essential Add-on Features:</h4>
          <ul class="list-unstyled xs-pt-20">
            <li class="text-16 xs-mb-5 flex justify-space-between" v-for="(item, i) in setFeatures('essential')" :key="i">
              <span class="text-dark-30">{{ item.feature }}</span>
              <span class="text-dark-10 text-16-akk-bold text-right">{{activeItem.symbol}}{{ item.cost }}</span>
            </li>
          </ul>
        </div>
        <div class="es-pricing-col-bottom xs-pb-40 xs-pl-20 xs-pr-20 fill-white">
          <div class="es-span-xs-6 text-center">
            <a href="/contact" class="text-15-medium text-blue link-blue caps decoration-none" >Contact Us</a>
          </div>
          <div class="es-span-xs-6 xs-mt-20 text-center">
            <a href="/contact" class="button button-large button-blue text-15-bold decoration-none" >See It In Action</a>
          </div>
        </div>
      </div>

      <div class="es-pricing-col es-pricing-col-featured relative es-span-xs-6 es-span-sm-2" v-if="(activeMobile == 'pro' && windowWidth < 753) || windowWidth >= 753">
        <div class="es-pricing-col-inner text-center xs-pt-20 xs-pb-20 xs-pr-20 xs-pl-20 fill-blue-dark text-white">
          <h2 class="title-1">Pro</h2>
          <p class="text-14-italic xs-mb-20 sm-mb-40">For more advanced reporting and powerful APIs</p>
          <h3 class="title-4">{{ setPrice("pro") }}</h3>
          <p class="text-14-italic">per {{ activeUsage }} agent per month</p>
          <p class="text-16 xs-mt-5 xs-mb-20">Billed Annually</p>
          <a href="/contact" class="button button-large button-blue text-15-bold decoration-none xs-mb-20">Contact Us</a>
        </div>
        <div class="es-pricing-col-top fill-white xs-pt-40 xs-pr-20 xs-pb-40 xs-pl-20">
          <h4 class="title-2 text-dark-10 xs-mb-10">Pro comes with:</h4>
          <ul class="list-unstyled">
            <li class="text-20 text-dark-30" v-for="(item, i) in proItems" :key="i" v-html="item"></li>
          </ul>
        </div>
        <div class="es-pricing-col-middle fill-white xs-pb-40 xs-pl-20 xs-pr-20">
          <hr>
          <h4 class="title-2 text-dark-10 xs-mt-20">Pro Add-on Features:</h4>
          <ul class="list-unstyled xs-pt-20">
            <!-- here -->
            <li class="text-16 xs-mb-5 flex justify-space-between" v-for="(item, i) in setFeatures('pro')" :key="i">
              <span class="text-dark-30">{{ item.feature }}</span>
              <span class="text-dark-10 text-16-akk-bold text-right">{{activeItem.symbol}}{{ item.cost }}</span>
            </li>
          </ul>
        </div>
        <div class="es-pricing-col-bottom xs-pb-40 xs-pl-20 xs-pr-20 fill-white">
          <div class="es-span-xs-6 text-center">
            <a href="/contact" class="text-15-medium text-blue link-blue caps decoration-none" >Contact Us</a>
          </div>
          <div class="es-span-xs-6 xs-mt-20 text-center">
            <a href="/contact" class="button button-large button-blue text-15-bold decoration-none" >See It In Action</a>
          </div>
        </div>
      </div>

      <div class="es-pricing-col es-span-xs-6 es-span-sm-2" v-if="(activeMobile == 'ultimate' && windowWidth < 753) || windowWidth >= 753">
        <div class="es-pricing-col-inner text-center xs-pt-20 xs-pb-20 xs-pr-20 xs-pl-20 fill-dark-80 text-dark-10">
          <h2 class="title-1">Ultimate</h2>
          <p class="text-14-italic xs-mb-20 sm-mb-40">All channels</p>
          <h3 class="title-4">{{ setPrice("ultimate") }}</h3>
          <p class="text-14-italic">per {{ activeUsage }} agent per month</p>
          <p class="text-16 xs-mt-5 xs-mb-20">Billed Annually</p>
          <a href="/contact" class="button button-large button-blue text-15-bold decoration-none xs-mb-20">Contact Us</a>
        </div>
        <div class="es-pricing-col-top fill-white xs-pt-40 xs-pr-20 xs-pb-40 xs-pl-20">
          <h4 class="title-2 text-dark-10 xs-mb-10">Ultimate comes with:</h4>
          <ul class="list-unstyled">
            <li class="text-20 text-dark-30" v-for="(item, i) in ultimateItems" :key="i" v-html="item"></li>
          </ul>
        </div>
        <div class="es-pricing-col-middle fill-white xs-pb-40 xs-pl-20 xs-pr-20">
          <hr>
          <h4 class="title-2 text-dark-10 xs-mt-20">Ultimate Add-on Features:</h4>
          <ul class="list-unstyled xs-pt-20">
            <!-- here -->
            <li class="text-16 xs-mb-5 flex justify-space-between" v-for="(item, i) in setFeatures('ultimate')" :key="i">
              <span class="text-dark-30">{{ item.feature }}</span>
              <span class="text-dark-10 text-16-akk-bold text-right">{{activeItem.symbol}}{{ item.cost }}</span>
            </li>
          </ul>
        </div>
        <div class="es-pricing-col-bottom xs-pb-40 xs-pl-20 xs-pr-20 fill-white">
          <div class="es-span-xs-6 text-center">
            <a href="/contact" class="text-15-medium text-blue link-blue caps decoration-none" >Contact Us</a>
          </div>
          <div class="es-span-xs-6 xs-mt-20 text-center">
            <a href="/contact" class="button button-large button-blue text-15-bold decoration-none" >See It In Action</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import pricingData from "../PricingData.json";
export default {
  data() {
    return {
      named: [
        {
          cur: "USD",
          essential: pricingData.pricing.named.USD.essential,
          pro: pricingData.pricing.named.USD.pro,
          ultimate: pricingData.pricing.named.USD.ultimate,
          symbol: pricingData.pricing.named.USD.symbol
        },
        {
          cur: "AUD",
          essential: pricingData.pricing.named.AUD.essential,
          pro: pricingData.pricing.named.AUD.pro,
          ultimate: pricingData.pricing.named.AUD.ultimate,
          symbol: pricingData.pricing.named.AUD.symbol
        },
        {
          cur: "NZD",
          essential: pricingData.pricing.named.NZD.essential,
          pro: pricingData.pricing.named.NZD.pro,
          ultimate: pricingData.pricing.named.NZD.ultimate,
          symbol: pricingData.pricing.named.NZD.symbol
        },
        {
          cur: "GBP",
          essential: pricingData.pricing.named.GBP.essential,
          pro: pricingData.pricing.named.GBP.pro,
          ultimate: pricingData.pricing.named.GBP.ultimate,
          symbol: pricingData.pricing.named.GBP.symbol
        },
        {
          cur: "EUR",
          essential: pricingData.pricing.named.EUR.essential,
          pro: pricingData.pricing.named.EUR.pro,
          ultimate: pricingData.pricing.named.EUR.ultimate,
          symbol: pricingData.pricing.named.EUR.symbol
        }
      ],
      concurrent: [
        {
          cur: "USD",
          essential: pricingData.pricing.concurrent.USD.essential,
          pro: pricingData.pricing.concurrent.USD.pro,
          ultimate: pricingData.pricing.concurrent.USD.ultimate,
          symbol: pricingData.pricing.concurrent.USD.symbol
        },
        {
          cur: "AUD",
          essential: pricingData.pricing.concurrent.AUD.essential,
          pro: pricingData.pricing.concurrent.AUD.pro,
          ultimate: pricingData.pricing.concurrent.AUD.ultimate,
          symbol: pricingData.pricing.concurrent.AUD.symbol
        },
        {
          cur: "NZD",
          essential: pricingData.pricing.concurrent.NZD.essential,
          pro: pricingData.pricing.concurrent.NZD.pro,
          ultimate: pricingData.pricing.concurrent.NZD.ultimate,
          symbol: pricingData.pricing.concurrent.NZD.symbol
        },
        {
          cur: "GBP",
          essential: pricingData.pricing.concurrent.GBP.essential,
          pro: pricingData.pricing.concurrent.GBP.pro,
          ultimate: pricingData.pricing.concurrent.GBP.ultimate,
          symbol: pricingData.pricing.concurrent.GBP.symbol
        },
        {
          cur: "EUR",
          essential: pricingData.pricing.concurrent.EUR.essential,
          pro: pricingData.pricing.concurrent.EUR.pro,
          ultimate: pricingData.pricing.concurrent.EUR.ultimate,
          symbol: pricingData.pricing.concurrent.EUR.symbol
        }
      ],
      essentialItems: pricingData.info.essential,
      proItems: pricingData.info.pro,
      ultimateItems: pricingData.info.ultimate,
      usage: ['Named', 'Concurrent'],
      currency: ['USD', 'AUD', 'NZD', 'GBP', 'EUR'],
      activeUsage: "named",
      activeCurrency: "USD",
      activeItem: null,
      activeMobile: "essential",
      windowWidth: document.documentElement.clientWidth,
      showTip: false
    }
  },

  created() {
    this.setActive();
  },

  mounted() {
    window.addEventListener('resize', () => {
      this.windowWidth = document.documentElement.clientWidth;
    });
  },

  watch: {
    activeUsage() {
      this.setActive();
    },

    activeCurrency() {
      this.setActive();
    }
  },

  methods: {

    tooltip() {
      this.showTip = !this.showTip;
    },

    setActive(type) {
      if (this.activeUsage == "named") {
        let activeItem = this.named.find(item => {
          return item.cur == this.activeCurrency;
        })

        this.activeItem = activeItem;
      }

      if (this.activeUsage == "concurrent") {
        let activeItem = this.concurrent.find(item => {
          return item.cur == this.activeCurrency;
        })

        this.activeItem = activeItem;
      }
    },

    setPrice(type) {

      switch (type) {
        case "essential":
          return this.activeItem.symbol + this.activeItem.essential.price;
          break;
        case "pro":
          return this.activeItem.symbol + this.activeItem.pro.price;
          break;
        case "ultimate":
          return this.activeItem.symbol + this.activeItem.ultimate.price;
          break;
      }
    },

    setFeatures(type) {
      switch (type) {
        case "essential":
          return this.activeItem.essential.additional;
          break;
        case "pro":
          return this.activeItem.pro.additional;
          break;
        case "ultimate":
          return this.activeItem.ultimate.additional;
          break;
      }
    }
  }
}
</script>

