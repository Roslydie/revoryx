<template>
    <footer class="site-footer">
        <div class="site-footer__main">
            <div class="container">
                <div class="site-footer__grid">
                    <section class="site-footer__identity">
                        <router-link to="/" class="site-footer__logo">
                            <img :src="'/assets/images/Logo_revoryx_clair.png'" alt="Revoryx &amp; Partners">
                        </router-link>
                        <p>{{ _e(`footer.vue_1789383437172_0`, `We identify where your marketing is losing revenue, and we fix it.`) }}</p>
                        <div class="site-footer__socials" aria-label="Social networks">
                            <a href="#" aria-label="Facebook"><i class="fa fa-facebook"></i></a>
                            <a href="#" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
                            <a href="#" aria-label="LinkedIn"><i class="fa fa-linkedin"></i></a>
                        </div>
                    </section>

                    <nav class="site-footer__navigation" aria-label="Footer navigation">
                        <h2 class="site-footer__title"> {{ _e(`footer.vue_1789383437172_1`, `Site menu`) }}</h2>
                        <ul>
                            <li><router-link to="/">{{ _e(`header.vue_1789383437179_15`, `Home`) }}</router-link></li>
                            <li><router-link to="/about">{{ _e(`header.vue_1789383437179_16`, `About`) }}</router-link></li>
                            <li><router-link to="/service">{{ _e(`header.vue_1789383437179_17`, `Service`) }}</router-link></li>
                            <li v-if="false"><router-link to="/project">{{ _e(`header.vue_1789383437179_18`, `Project`) }}</router-link></li>
                            <li v-if="false"><router-link to="/blog">{{ _e(`header.vue_1789383437179_19`, `Blog`) }}</router-link></li>
                            <li><router-link to="/contact">{{ _e(`header.vue_1789383437179_20`, `Contact`) }}</router-link></li>
                        </ul>
                    </nav>

                    <section class="site-footer__contact">
                        <h2 class="site-footer__title"> {{ _e(`footer.vue_1789383437172_9`, `Let's connect`) }}</h2>
                        <p>3506 S 61st<br>Philadelphia, PA 19153</p>
                        <a href="tel:+12159890101">+1 (215) 989-0101</a>
                        <a href="mailto:contact@revoryxandpartners.com">contact@revoryxandpartners.com</a>
                    </section>

                    <section class="site-footer__newsletter">
                        <h2 class="site-footer__title"> {{ _e(`footer.vue_1789383437172_10`, `Stay in the loop`) }}</h2>
                        <p>{{ _e(`footer.vue_1789383437173_11`, `A monthly breakdown of the most common revenue leaks in small and medium-sized businesses, without any marketing jargon.`) }}</p>
                        <form class="site-footer__form" @submit.prevent="subscribeToNewsletter">
                            <label for="footer-newsletter-email">{{ _e(`footer.vue_1789383437173_12`, `Subscribe to our newsletter`) }}</label>
                            <div class="site-footer__form-row">
                                <input v-model.trim="newsletterEmail" id="footer-newsletter-email" type="email" name="email" :placeholder="_e(`footer.vue_1791227296594_100`, `Your email address`)" autocomplete="email" required :disabled="newsletterLoading">
                                <button type="submit" aria-label="Subscribe" :disabled="newsletterLoading"><i class="fa fa-long-arrow-right"></i></button>
                            </div>
                            <p v-if="newsletterMessage" class="site-footer__feedback site-footer__feedback--success">{{ newsletterMessage }}</p>
                            <p v-if="newsletterError" class="site-footer__feedback site-footer__feedback--error">{{ newsletterError }}</p>
                        </form>
                    </section>
                </div>

                <div class="site-footer__bottom">
                    <p>&copy; {{ currentYear }} Revoryx &amp; Partners. {{ _e(`footer.vue_1789383437173_13`, `All rights reserved.`) }}</p>
                    <router-link to="/policy">{{ _e(`footer.vue_1789383437173_14`, `Privacy Policy`) }}</router-link>
                </div>
            </div>
        </div>
    </footer>
</template>

<script setup>
import { ref } from 'vue';
import { getCurrentLocale } from '../../plugins/i18n.js';
import { postData } from '../../plugins/axios.js';

const currentYear = new Date().getFullYear();
const newsletterEmail = ref('');
const newsletterLoading = ref(false);
const newsletterMessage = ref('');
const newsletterError = ref('');

const subscribeToNewsletter = async () => {
    newsletterLoading.value = true;
    newsletterMessage.value = '';
    newsletterError.value = '';

    try {
        const response = await postData('/newsletter/subscribe', {
            email: newsletterEmail.value,
            language: getCurrentLocale() === 'en' ? 'en' : 'fr',
        });

        newsletterMessage.value = response.message;
        newsletterEmail.value = '';
    } catch (error) {
        newsletterError.value = error.response?.data?.message || 'Unable to subscribe right now.';
    } finally {
        newsletterLoading.value = false;
    }
};
</script>

<style scoped>
.site-footer {
    color: #b9c5d9;
        background-color: #061735;
        background-image: linear-gradient(rgba(6, 23, 53, .82), rgba(6, 23, 53, .88)), url('/assets/images/call-bg.png');
        background-position: center;
        background-size: cover;
}

.site-footer__main {
    padding: 78px 0 0;
}

.site-footer__grid {
    display: grid;
    grid-template-columns: 1.35fr .8fr 1fr 1.35fr;
    gap: 48px;
}

.site-footer__logo {
    display: inline-block;
    margin-bottom: 24px;
}

.site-footer__logo img {
    width: 270px;
}

.site-footer__identity > p,
.site-footer__newsletter > p,
.site-footer__contact > p {
    margin: 0;
    color: #e4ebf7;
    font-size: 16px;
    font-weight: 600;
    line-height: 1.8;
}

.site-footer__title {
    margin: 5px 0 22px;
    color: #fff;
    font-size: 21px;
    font-weight: 800;
}

.site-footer__navigation ul {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 10px 18px;
    margin: 0;
    padding: 0;
    list-style: none;
}

.site-footer a {
    color: #e4ebf7;
    font-size: 16px;
    font-weight: 600;
    transition: color .2s ease;
}

.site-footer a:hover {
    color: #fff;
}

.site-footer__contact a {
    display: block;
    margin-top: 12px;
}

.site-footer__socials {
    display: flex;
    gap: 18px;
    margin-top: 24px;
}

.site-footer__socials a {
    color: #fff;
    font-size: 16px;
    font-weight: 400;
}

.site-footer__form {
    margin-top: 22px;
}

.site-footer__form label {
    display: block;
    margin-bottom: 9px;
    color: #fff;
    font-size: 15px;
    font-weight: 700;
}

.site-footer__form-row {
    display: flex;
    border-bottom: 1px solid rgba(255, 255, 255, .45);
}

.site-footer__form input {
    min-width: 0;
    flex: 1;
    padding: 11px 0;
    color: #fff;
    background: transparent;
    border: 0;
    outline: 0;
}

.site-footer__form input::placeholder {
    color: #b9c5d9;
}

.site-footer__form button {
    width: 42px;
    color: #fff;
    background: transparent;
    border: 0;
    cursor: pointer;
}

.site-footer__form button:disabled,
.site-footer__form input:disabled {
    cursor: wait;
    opacity: .65;
}

.site-footer__feedback {
    margin: 10px 0 0;
    font-size: 13px;
    line-height: 1.5;
}

.site-footer__feedback--success {
    color: #9ee2bd;
}

.site-footer__feedback--error {
    color: #ffb8b0;
}

.site-footer__bottom {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    margin-top: 62px;
    padding: 20px 0;
    border-top: 1px solid rgba(255, 255, 255, .15);
    color: #e4ebf7;
    font-size: 14px;
    font-weight: 600;
}

.site-footer__bottom p {
    margin: 0;
}

@media (max-width: 991px) {
    .site-footer__grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 575px) {
    .site-footer__main {
        padding-top: 58px;
    }

    .site-footer__grid {
        grid-template-columns: 1fr;
        gap: 34px;
    }

    .site-footer__bottom {
        align-items: flex-start;
        flex-direction: column;
        gap: 8px;
        margin-top: 42px;
    }
}
</style>