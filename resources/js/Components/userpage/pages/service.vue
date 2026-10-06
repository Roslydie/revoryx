<template>
    <main class="services-page">
        <section class="services-hero">
            <div class="services-hero__overlay"></div>
            <div class="container services-hero__content">
                <p class="services-kicker">MARKETING PERFORMANCE & REVENUE RECOVERY</p>
                <h1>{{ _e(`service.vue_1789424760549_191`, `Four Steps to Recover the Income You're Already Losing`) }}</h1>
                <p>{{ _e(`service.vue_1789424760549_192`, `A data-driven analysis, a prioritized action plan, and system improvements so your marketing finally delivers the results it should, without an additional budget.`) }}</p>
            </div>
        </section>

        <section class="services-list section-space">
            <div class="container">
                <div class="section-heading text-center">
                    <p class="services-kicker">{{ _e(`service.vue_1789424760549_193`, `OUR OFFERS`) }}</p>
                    <h2>{{ _e(`service.vue_1789424760549_194`, `One method, four steps`) }}</h2>
                    <p>{{ _e(`service.vue_1789424760549_195`, `Each step answers a single question: Where are you now, and what needs to change?`) }}</p>
                </div>
                <div class="row">
                    <div v-for="service in services" :key="service.slug" class="col-12 col-md-6 mb-4">
                        <article class="service-card">
                            <div class="service-card__icon"><i :class="service.icon"></i></div>
                            <p class="service-card__number">{{ String(services.indexOf(service) + 1).padStart(2, '0') }}</p>
                            <h3>{{ service.title }}</h3>
                            <p>{{ service.short }}</p>
                            <router-link class="service-card__link" :to="`/service/${service.slug}`">
                               {{ _e(`service.vue_1789424760549_196`, `Explore service`) }} <i class="fa fa-long-arrow-right"></i>
                            </router-link>
                        </article>
                    </div>
                </div>
            </div>
        </section>

        <section class="services-cta">
            <div class="container text-center">
                <p class="services-kicker">{{ _e(`service.vue_1789424760549_197`, `READY WHEN YOU ARE`) }}</p>
                <h2>{{ _e(`service.vue_1789424760549_198`, `Let's see where you're losing revenue.`) }}</h2>
                <router-link class="services-cta__button" to="/contact">{{ _e(`service.vue_1789424760549_199`, `Request an audit`) }} <i class="fa fa-long-arrow-right"></i></router-link>
            </div>
        </section>
    </main>
</template>

<script setup>
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { getServices } from '../data/services.js';

const { locale } = useI18n();
const services = computed(() => {
    locale.value;
    return getServices();
});
</script>

<style scoped>
.services-page { color: #616161; }
.section-space { padding: 100px 0; }
.services-hero { position: relative; min-height: 500px; display: flex; align-items: center; background: url('/assets/images/service_hero.png') center / cover; }
.services-hero__overlay { position: absolute; inset: 0; background: linear-gradient(90deg, rgba(3, 25, 65, .94), rgba(11, 37, 69, .46)); }
.services-hero__content { position: relative; z-index: 1; color: #fff; }
.services-hero h1 { max-width: 800px; margin: 0 0 20px; color: #fff; font-size: clamp(40px, 6vw, 74px); line-height: 1.08; }
.services-hero p:not(.services-kicker) { max-width: 620px; margin: 0; color: rgba(255, 255, 255, .84); font-size: 19px; }
.services-kicker { margin: 0 0 13px; color: #0B2545; font-size: 13px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; }
.services-hero .services-kicker, .services-cta .services-kicker { color: #6B7280; }
.section-heading { max-width: 700px; margin: 0 auto 48px; }
.section-heading h2, .services-cta h2 { margin: 0 0 18px; color: #232323; font-size: clamp(30px, 4vw, 45px); line-height: 1.2; }
.section-heading > p:last-child { margin: 0; font-size: 17px; line-height: 1.75; }
.services-list { background: #f7faff; }
.service-card { position: relative; height: 100%; padding: 35px 30px 30px; border: 1px solid #e1eaf7; border-radius: 0; background: #fff; box-shadow: 0 8px 25px rgba(8, 35, 80, .06); transition: transform .35s ease, box-shadow .35s ease; }
.service-card:hover { box-shadow: 0 18px 35px rgba(11, 37, 69, .15); transform: translateY(-8px); }
.service-card__icon { display: flex; align-items: center; justify-content: center; width: 74px; height: 74px; margin-bottom: 28px; border-radius: 50%; color: #fff; background: #6B7280; font-size: 32px; }
.service-card__number { position: absolute; top: 18px; right: 22px; margin: 0; color: #dce9fb; font-size: 25px; font-weight: 700; }
.service-card h3 { margin: 0 0 13px; color: #232323; font-size: 21px; }
.service-card > p:not(.service-card__number) { min-height: 78px; margin: 0 0 22px; line-height: 1.75; }
.service-card__link { color: #0B2545; font-weight: 700; }
.service-card__link i { margin-left: 7px; transition: margin-left .25s ease; }
.service-card__link:hover { color: #00247e; }
.service-card__link:hover i { margin-left: 12px; }
.services-cta { padding: 90px 0; color: #fff; background: linear-gradient(110deg, #061d43, #0B2545); }
.services-cta h2 { color: #fff; }
.services-cta__button { display: inline-block; padding: 13px 25px; border-radius: 3px; color: #fff; background: #0B2545; font-weight: 700; }
.services-cta__button:hover { color: #fff; background: #00247e; }
@media (max-width: 767px) {
    .services-hero { min-height: 440px; }
    .section-space { padding: 70px 0; }
    .service-card > p:not(.service-card__number) { min-height: auto; }
}
</style>
