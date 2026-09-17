<template>
    <main>
         <div class="header_top_menu pt-2 pb-2 bg_color">
      <div class="container">
        <div class="row">
          <div class="col-lg-8 col-sm-8">
            <div class="header_top_menu_address">
              <div class="header_top_menu_address_inner">
                <ul>
                  <li>
                    <a href="mailto:techr7129@gmail.com"><i class="fa fa-envelope-o"></i>techr7129@gmail.com</a>
                  </li>
                  <li>
                    <a href="https://www.google.com/maps/search/?api=1&amp;query=3506+S+61st+Philadelphia+PA+19153" target="_blank" rel="noopener noreferrer"><i class="fa fa-map-marker"></i>3506 S 61st, Philadelphia, PA 19153</a>
                  </li>
                  <li>
                    <a href="tel:+12159890101"><i class="fa fa-phone"></i>+1 (215) 989-0101</a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-sm-4">
            <div class="header_top_menu_icon">
              <div class="header_top_menu_icon_inner">
                <ul>
                  <li>
                    <a href="#"><i class="fa fa-facebook"></i></a>
                  </li>
                  <li>
                    <a href="#"><i class="bi bi-twitter-x"></i></a>
                  </li>
                  <li>
                    <a href="#"><i class="fa fa-pinterest"></i></a>
                  </li>
                  <li>
                    <a href="#"><i class="fa fa-linkedin"></i></a>
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!--==================================================-->
    <!-- End	Techno Header Top Menu Area Css -->
    <!--===================================================-->

    <!--==================================================-->
    <!-- Start Techno Main Menu Area -->
    <!--==================================================-->
    <div id="sticky-header" class="techno_nav_manu d-md-none d-lg-block d-sm-none d-none" :class="{ sticky: isScrolled }">
      <div class="container">
        <div class="row align-items-center">
          <div class="menu">
            <router-link to="/" class="logo">
              <img :src="isScrolled ? '/assets/images/logo_blanc.png' : '/assets/images/logo_norma.png'" alt="Revoryx &amp; Partners">
            </router-link>
            <ul class="clearfix">
              <li>
                <router-link to="/">{{ _e(`header.vue_1789383437179_15`, `Home`) }}</router-link>
               
              </li>
              <li>
                <router-link to="/about">{{ _e(`header.vue_1789383437179_16`, `About`) }}</router-link>
              </li>
              <li>
                <router-link to="/service">{{ _e(`header.vue_1789383437179_17`, `Service`) }}</router-link>
               
              </li>
                <li>
                    <router-link to="/project">{{ _e(`header.vue_1789383437179_18`, `Project`) }}</router-link>
                </li>
              <li>
                <router-link to="/blog">{{ _e(`header.vue_1789383437179_19`, `Blog`) }}</router-link>
              </li>
             
              <li>
                <router-link to="/contact">{{ _e(`header.vue_1789383437179_20`, `Contact`) }}</router-link>
              </li>
              <div class="donate-btn-header">
                <router-link to="/contact" class="dtbtn">{{ _e(`header.vue_1789383437179_21`, `Get A Quote`) }}</router-link>
              </div>
              <li class="header-language">
                <Switcher />
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>

    <header class="mobile-site-header" :class="{ 'mobile-site-header--open': mobileMenuOpen }">
      <div class="mobile-site-header__bar">
        <router-link to="/" class="mobile-site-header__logo" @click="mobileMenuOpen = false">
          <img :src="'/assets/images/logo_norma.png'" alt="Revoryx &amp; Partners">
        </router-link>
        <button class="mobile-site-header__toggle" type="button" :aria-expanded="mobileMenuOpen" aria-label="Toggle navigation" @click="mobileMenuOpen = !mobileMenuOpen">
          <i :class="mobileMenuOpen ? 'bi bi-x-lg' : 'bi bi-list'"></i>
        </button>
      </div>
      <nav v-if="mobileMenuOpen" class="mobile-site-header__nav" aria-label="Mobile navigation">
        <router-link to="/" @click="mobileMenuOpen = false">{{ _e(`header.vue_1789383437179_15`, `Home`) }}</router-link>
        <router-link to="/about" @click="mobileMenuOpen = false">{{ _e(`header.vue_1789383437179_16`, `About`) }}</router-link>
        <router-link to="/service" @click="mobileMenuOpen = false">{{ _e(`header.vue_1789383437179_17`, `Service`) }}</router-link>
        <router-link to="/project" @click="mobileMenuOpen = false">{{ _e(`header.vue_1789383437179_18`, `Project`) }}</router-link>
        <router-link to="/blog" @click="mobileMenuOpen = false">{{ _e(`header.vue_1789383437179_19`, `Blog`) }}</router-link>
        <router-link to="/contact" @click="mobileMenuOpen = false">{{ _e(`header.vue_1789383437179_20`, `Contact`) }}</router-link>
        <router-link to="/contact" class="mobile-site-header__cta" @click="mobileMenuOpen = false">{{ _e(`header.vue_1789383437179_21`, `Get A Quote`) }} <i class="bi bi-arrow-right"></i></router-link>
        <div class="mobile-site-header__language"><Switcher /></div>
      </nav>
    </header>
    </main>
</template>

<script setup>
import { onBeforeUnmount, onMounted, ref } from 'vue';
import { RouterLink } from 'vue-router';
import Switcher from './switcher.vue';

const isScrolled = ref(false);
const mobileMenuOpen = ref(false);

const updateScrollState = () => {
  isScrolled.value = window.scrollY > 10;
};

onMounted(() => {
  updateScrollState();
  window.addEventListener('scroll', updateScrollState, { passive: true });
});

onBeforeUnmount(() => {
  window.removeEventListener('scroll', updateScrollState);
});
</script>

<style scoped>
.techno_nav_manu.sticky {
  background: #061d43;
}

.techno_nav_manu.sticky .menu > ul > li > a {
  color: #fff;
}

.techno_nav_manu.sticky .menu > ul > li > ul {
  background: #061d43;
}

.techno_nav_manu.sticky .menu > ul > li > ul a {
  color: #fff;
}

.mobile-site-header {
  display: none;
}

@media (max-width: 991.98px) {
  .header_top_menu {
    display: none;
  }

  .mobile-site-header {
    position: relative;
    z-index: 1050;
    display: block;
    background: #fff;
    box-shadow: 0 4px 18px rgba(6, 29, 67, .1);
  }

  .mobile-site-header__bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-height: 72px;
    padding: 10px 20px;
  }

  .mobile-site-header__logo img {
    display: block;
    width: auto;
    max-width: 165px;
    height: 45px;
    object-fit: contain;
  }

  .mobile-site-header__toggle {
    display: grid;
    width: 44px;
    height: 44px;
    place-items: center;
    border: 0;
    border-radius: 7px;
    color: #fff;
    background: #123b6d;
    font-size: 23px;
    cursor: pointer;
  }

  .mobile-site-header__nav {
    display: flex;
    flex-direction: column;
    padding: 6px 20px 20px;
    border-top: 1px solid #e7edf3;
    background: #fff;
  }

  .mobile-site-header__nav > a {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 4px;
    border-bottom: 1px solid #edf1f4;
    color: #123b6d;
    font-size: 14px;
    font-weight: 700;
  }

  .mobile-site-header__nav > a.router-link-active {
    color: #0c5adb;
  }

  .mobile-site-header__nav .mobile-site-header__cta {
    justify-content: center;
    gap: 8px;
    margin-top: 16px;
    border: 0;
    border-radius: 6px;
    color: #fff;
    background: #0c5adb;
  }

  .mobile-site-header__language {
    display: flex;
    justify-content: flex-end;
    padding-top: 16px;
  }

  .mobile-site-header__language :deep(.lang-switcher select) {
    min-width: 100px;
    padding: 8px 24px 8px 10px;
    border: 1px solid #dce5f1;
    border-radius: 4px;
    color: #123b6d;
    background: #fff;
    font-size: 12px;
    font-weight: 700;
  }
}

@media (max-width: 420px) {
  .mobile-site-header__bar { padding-right: 14px; padding-left: 14px; }
  .mobile-site-header__nav { padding-right: 14px; padding-left: 14px; }
}

.header-language {
  display: flex;
  align-items: center;
}

.header-language :deep(.lang-switcher select) {
  min-width: 58px;
  padding: 8px 24px 8px 10px;
  border: 1px solid #dce5f1;
  border-radius: 3px;
  color: #232323;
  background: #fff;
  font-size: 12px;
  font-weight: 700;
  cursor: pointer;
}

.techno_nav_manu.sticky .header-language :deep(.lang-switcher select) {
  border-color: rgba(255, 255, 255, .35);
  color: #fff;
  background: transparent;
}

.techno_nav_manu .menu > ul > li > a.router-link-active {
  position: relative;
}

.techno_nav_manu .menu > ul > li > a.router-link-active::after {
  position: absolute;
  right: 0;
  bottom: 13px;
  left: 0;
  height: 2px;
  background: #0c5adb;
  content: '';
}

.techno_nav_manu.sticky .menu > ul > li > a.router-link-active::after {
  background: #fff;
}
</style>