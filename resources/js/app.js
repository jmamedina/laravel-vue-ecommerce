// Import necessary modules and configurations
// 必要なモジュールと設定をインポートする
import './bootstrap';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse'
import { get, post } from "./http.js";

// Register Alpine.js plugins
// Alpine.jsのプラグインを登録する
Alpine.plugin(collapse)

window.Alpine = Alpine;

// Initialize Alpine.js when it's ready
// Alpine.jsが準備できたら初期化する
document.addEventListener("alpine:init", async () => {

  // Define Alpine.js data properties and functions
  // Alpine.jsのデータプロパティと関数を定義する
  Alpine.data("toast", () => ({
    visible: false,
    delay: 5000,
    percent: 0,
    interval: null,
    timeout: null,
    message: null,
    type: null,
    close() {
      this.visible = false;
      clearInterval(this.interval);
    },
    show(message, type = 'success') {
      this.visible = true;
      this.message = message;
      this.type = type;

      if (this.interval) {
        clearInterval(this.interval);
        this.interval = null;
      }
      if (this.timeout) {
        clearTimeout(this.timeout);
        this.timeout = null;
      }

      this.timeout = setTimeout(() => {
        this.visible = false;
        this.timeout = null;
      }, this.delay);
      const startDate = Date.now();
      const futureDate = Date.now() + this.delay;
      this.interval = setInterval(() => {
        const date = Date.now();
        this.percent = ((date - startDate) * 100) / (futureDate - startDate);
        if (this.percent >= 100) {
          clearInterval(this.interval);
          this.interval = null;
        }
      }, 30);
    },
  }));

  // Define Alpine.js data properties and functions for product item
  // 商品アイテムのAlpine.jsのデータプロパティと関数を定義する
  Alpine.data("productItem", (product) => {
    return {
      product,
      addToCart(quantity = 1) {
        post(this.product.addToCartUrl, { quantity })
          .then(result => {
            this.$dispatch('cart-change', { count: result.count })
            this.$dispatch("notify", {
              message: "The item was added into the cart",
            });
          })
          .catch(response => {
            console.log(response);
            this.$dispatch('notify', {
              message: response.message || 'Server Error. Please try again.',
              type: 'error'
            })
          })
      },
      removeItemFromCart() {
        post(this.product.removeUrl)
          .then(result => {
            this.$dispatch("notify", {
              message: "The item was removed from cart",
            });
            this.$dispatch('cart-change', { count: result.count })
            this.cartItems = this.cartItems.filter(p => p.id !== product.id)
          })
      },
      changeQuantity() {
        post(this.product.updateQuantityUrl, { quantity: product.quantity })
          .then(result => {
            this.$dispatch('cart-change', { count: result.count })
            this.$dispatch("notify", {
              message: "The item quantity was updated",
            });
          })
          .catch(response => {
            this.$dispatch('notify', {
              message: response.message || 'Server Error. Please try again.',
              type: 'error'
            })
          })
      },
    };
  });
});

// Start Alpine.js
// Alpine.jsを起動する
Alpine.start();
