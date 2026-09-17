<template>
    <main v-if="service" class="service-detail">
        <section class="service-detail__hero">
            <div class="service-detail__overlay"></div>
            <div class="container service-detail__hero-content">
                <router-link class="service-detail__back" to="/service"
                    ><i class="fa fa-long-arrow-left"></i>{{ _e(`single_service.vue_1789429729220_200`, `All services`) }} </router-link>
                <div class="service-detail__icon">
                    <i :class="service.icon"></i>
                </div>
                <p class="service-detail__kicker">
                    REVORYX &amp; PARTNERS / SERVICES
                </p>
                <h1>{{ service.title }}</h1>
                <p>{{ service.short }}</p>
                <div class="service-detail__hero-actions">
                    <a
                        href="#request-service"
                        class="service-detail__hero-button">{{ _e(`single_service.vue_1789429729220_219`, `Request a service`) }}
                        <i class="fa fa-long-arrow-right"></i></a
                    ><span
                        ><i class="fa fa-check-circle"></i>{{ _e(`single_service.vue_1789429729220_202`, `Built around your goals`) }}</span>
                </div>
            </div>
        </section>
        <section class="service-detail__body section-space">
            <div class="container">
                <div class="row align-items-start">
                    <div class="col-lg-7">
                        <p class="service-detail__kicker">{{ _e(`single_service.vue_1789429729220_203`, `A FOCUSED APPROACH`) }}</p>
                        <h2>{{ _e(`single_service.vue_1789429729220_204`, `Technology that serves your next move`) }}</h2>
                        <div class="service-detail__rule"></div>
                        <p class="service-detail__lead">
                            {{ service.description }}
                        </p>
                        <p>{{ _e(`single_service.vue_1789429729220_205`, `We work closely with your team to create a solution that is clear to use, ready to evolve and connected to the outcomes that matter most. Every detail is shaped around usability, performance and long-term value.`) }}
                        </p>
                        <div class="service-detail__metrics">
                            <div>
                                <strong>01</strong><span> {{ _e(`single_service.vue_1789429729220_206`, `Clear direction`) }}</span>
                            </div>
                            <div>
                                <strong>02</strong><span>{{ _e(`single_service.vue_1789429729220_207`, `Focused delivery`) }}</span>
                            </div>
                            <div>
                                <strong>03</strong><span>{{ _e(`single_service.vue_1789429729220_208`, `Long-term value`) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="service-detail__visual">
                            <img
                                :src="'/assets/images/blog1.jpg'"
                                alt="Digital service visual"
                            />
                            <div class="service-detail__visual-badge">
                                <i :class="service.icon"></i
                                ><span>{{ _e(`single_service.vue_1789429729220_209`, `Designed to perform`) }}</span>
                            </div>
                        </div>
                        <div class="service-detail__panel">
                            <h3>{{ _e(`single_service.vue_1789429729220_210`, `What you get`) }}</h3>
                            <ul>
                                <li
                                    v-for="point in service.points"
                                    :key="point"
                                >
                                    <i class="fa fa-check"></i>{{ point }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="service-detail__capabilities">
            <div class="container">
                <div class="section-heading text-center">
                    <p class="service-detail__kicker">{{ _e(`single_service.vue_1789429729220_211`, `THE VALUE WE BRING`) }}</p>
                    <h2>{{ _e(`single_service.vue_1789429729220_212`, `More than delivery, a better way forward`) }}</h2>
                    <p>{{ _e(`single_service.vue_1789429729220_213`, `We connect the right expertise, process and technology to make your project easier to move.`) }}
                    </p>
                </div>
                <div class="row">
                    <div
                        v-for="capability in capabilities"
                        :key="capability.title"
                        class="col-lg-4 mb-4"
                    >
                        <article class="capability-card">
                            <i :class="capability.icon"></i>
                            <h3>{{ capability.title }}</h3>
                            <p>{{ capability.text }}</p>
                        </article>
                    </div>
                </div>
            </div>
        </section>
        <section class="service-detail__steps section-space">
            <div class="container">
                <div class="section-heading text-center">
                    <p class="service-detail__kicker">{{ _e(`single_service.vue_1789429729220_214`, `OUR METHOD`) }}</p>
                    <h2>{{ _e(`single_service.vue_1789429729220_215`, `From challenge to confident delivery`) }}</h2>
                </div>
                <div class="row">
                    <div
                        v-for="step in steps"
                        :key="step.number"
                        class="col-lg-4 mb-4"
                    >
                        <article class="detail-step">
                            <span>{{ step.number }}</span>
                            <h3>{{ step.title }}</h3>
                            <p>{{ step.text }}</p>
                        </article>
                    </div>
                </div>
            </div>
        </section>
        <section id="request-service" class="service-detail__cta">
            <div class="container text-center">
                <p class="service-detail__kicker">{{ _e(`single_service.vue_1789429729220_216`, `READY WHEN YOU ARE`) }}</p>
                <h2>{{ _e(`single_service.vue_1789429729220_217`, `Let’s turn your next challenge into progress.`) }}</h2>
                <p>{{ _e(`single_service.vue_1789429729220_218`, `Tell us what you want to improve. We will help you find the right starting point.`) }}
                </p>
                <router-link to="/contact" class="service-detail__cta-button"
                    > {{ _e(`single_service.vue_1789429729220_219`, `Request a service`) }} <i class="fa fa-long-arrow-right"></i
                ></router-link>
            </div>
        </section>
    </main>
</template>

<script setup>
import { computed } from "vue";
import { useRoute } from "vue-router";
import i18n from "../../plugins/i18n.js";
import { services } from "../data/services.js";

const _e = (key, fallback = key) => (
    i18n.global.te(key) ? i18n.global.t(key) : fallback
);

const route = useRoute();
const service = computed(() =>
    services.find((item) => item.slug === route.params.slug),
);
const steps = [
    {
        number: "01",
        title: _e(`single_service.vue_1789429729220_220`, `Understand`),
        text: _e(`single_service.vue_1789429729220_221`, `We clarify your goals, users and constraints before proposing a direction.`),
    },
    {
        number: "02",
        title: _e(`single_service.vue_1789429729220_222`, `Create`),
        text: _e(`single_service.vue_1789429729221_223`, `We design and build a focused solution with clear communication throughout.`),
    },
    {
        number: "03",
        title: _e(`single_service.vue_1789429729221_224`, `Grow`),
        text: _e(`single_service.vue_1789429729221_225`, `We measure the result and help your solution evolve as your needs change.`),
    },
];
const capabilities = [
    {
        title: _e(`single_service.vue_1789429729221_226`, `Strategic clarity`),
        icon: "flaticon-information",
        text: _e(`single_service.vue_1789429729221_227`, `A clear direction that connects your digital project to the way your organization actually works.`),
    },
    {
        title: _e(`single_service.vue_1789429729221_228`, `Human experience`),
        icon: "flaticon-interaction",
        text: _e(`single_service.vue_1789429729221_229`, `Thoughtful interfaces and flows designed to feel natural for the people who use them.`),
    },
    {
        title: _e(`single_service.vue_1789429729221_230`, `Built to evolve`),
        icon: "flaticon-analytics",
        text: _e(`single_service.vue_1789429729221_231`, `A solid foundation that can adapt as your audience, objectives and opportunities grow.`),
    },
];
</script>

<style scoped>
.section-space {
    padding: 100px 0;
}
.service-detail__hero {
    position: relative;
    min-height: 560px;
    display: flex;
    align-items: center;
    overflow: hidden;
    background: url("/assets/images/blog1.jpg") center / cover;
}
.service-detail__overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        105deg,
        rgba(3, 25, 65, 0.96),
        rgba(12, 90, 219, 0.52)
    );
}
.service-detail__hero-content {
    position: relative;
    z-index: 1;
    color: #fff;
    animation: serviceReveal 0.8s ease both;
}
.service-detail__back {
    display: inline-block;
    margin-bottom: 42px;
    color: #fff;
    font-weight: 700;
}
.service-detail__icon {
    display: grid;
    place-items: center;
    width: 92px;
    height: 92px;
    margin-bottom: 20px;
    border: 8px solid rgba(255, 255, 255, 0.22);
    border-radius: 50%;
    color: #0c5adb;
    background: #fff;
    font-size: 38px;
    animation: detailFloat 4s ease-in-out infinite;
}
.service-detail__hero-actions {
    display: flex;
    align-items: center;
    gap: 24px;
    flex-wrap: wrap;
    margin-top: 30px;
}
.service-detail__hero-actions span {
    color: rgba(255, 255, 255, 0.8);
    font-size: 14px;
}
.service-detail__hero-actions span i {
    margin-right: 6px;
    color: #65d5a0;
}
.service-detail__hero-button,
.service-detail__cta-button {
    display: inline-block;
    padding: 14px 24px;
    color: #fff;
    background: #0c5adb;
    font-weight: 700;
}
.service-detail__hero-button:hover,
.service-detail__cta-button:hover {
    color: #fff;
    background: #00247e;
}
@keyframes detailFloat {
    0%,
    100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-9px);
    }
}
@keyframes serviceReveal {
    from {
        opacity: 0;
        transform: translateY(24px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
.service-detail__kicker {
    margin: 0 0 12px;
    color: #0c5adb;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
}
.service-detail__hero .service-detail__kicker {
    color: #65d5a0;
}
.service-detail__hero h1 {
    max-width: 820px;
    margin: 0 0 18px;
    color: #fff;
    font-size: clamp(40px, 6vw, 72px);
    line-height: 1.08;
}
.service-detail__hero p:last-child {
    max-width: 650px;
    color: rgba(255, 255, 255, 0.84);
    font-size: 19px;
}
.service-detail__body h2,
.section-heading h2,
.service-detail__cta h2 {
    margin: 0 0 18px;
    color: #232323;
    font-size: clamp(30px, 4vw, 46px);
}
.service-detail__rule {
    width: 55px;
    height: 4px;
    margin: 20px 0;
    background: #0c5adb;
}
.service-detail__body p {
    line-height: 1.85;
}
.service-detail__metrics {
    display: flex;
    gap: 28px;
    margin-top: 30px;
}
.service-detail__metrics strong,
.service-detail__metrics span {
    display: block;
}
.service-detail__metrics strong {
    color: #0c5adb;
    font-size: 30px;
}
.service-detail__metrics span {
    color: #232323;
    font-size: 13px;
    font-weight: 700;
}
.service-detail__visual {
    position: relative;
    min-height: 270px;
    margin-bottom: 28px;
    overflow: hidden;
    background: #eef3fb;
}
.service-detail__visual img {
    display: block;
    width: 100%;
    height: 270px;
    object-fit: cover;
    object-position: right top;
    opacity: 1;
}
.service-detail__visual-badge {
    position: absolute;
    right: 18px;
    bottom: 18px;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 15px;
    color: #fff;
    background: #0c5adb;
    font-size: 13px;
    font-weight: 700;
}
.service-detail__visual-badge i {
    font-size: 22px;
}
.service-detail__panel {
    padding: 32px;
    border: 1px solid #e1eaf7;
    border-top: 4px solid #0c5adb;
    background: #f7faff;
}
.service-detail__panel h3 {
    margin: 0 0 20px;
    color: #232323;
    font-size: 23px;
}
.service-detail__panel ul {
    padding: 0;
    margin: 0;
    list-style: none;
}
.service-detail__panel li {
    display: flex;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid #dce7f5;
    color: #465466;
}
.service-detail__panel li:last-child {
    border-bottom: 0;
}
.service-detail__panel li i {
    color: #0c5adb;
}
.service-detail__capabilities {
    padding: 100px 0 75px;
    background: #fff;
}
.service-detail__capabilities .section-heading > p:last-child {
    font-size: 17px;
}
.capability-card {
    height: 100%;
    padding: 32px 28px;
    border: 1px solid #e1eaf7;
    border-radius: 6px;
    background: #fff;
    box-shadow: 0 8px 25px rgba(8, 35, 80, 0.06);
    transition:
        transform 0.35s ease,
        border-color 0.35s ease;
}
.capability-card:hover {
    border-color: #0c5adb;
    transform: translateY(-8px);
}
.capability-card > i {
    display: block;
    margin-bottom: 22px;
    color: #0c5adb;
    font-size: 34px;
}
.capability-card h3 {
    margin: 0 0 10px;
    color: #232323;
    font-size: 21px;
}
.capability-card p {
    margin: 0;
    line-height: 1.75;
}
.service-detail__steps {
    background: #f7faff;
}
.section-heading {
    max-width: 700px;
    margin: 0 auto 48px;
}
.detail-step {
    height: 100%;
    padding: 30px;
    border: 1px solid #e1eaf7;
    background: #fff;
    box-shadow: 0 8px 25px rgba(8, 35, 80, 0.06);
    transition:
        transform 0.35s ease,
        box-shadow 0.35s ease;
}
.detail-step:hover {
    box-shadow: 0 18px 35px rgba(12, 90, 219, 0.14);
    transform: translateY(-8px);
}
.detail-step span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 52px;
    height: 52px;
    margin-bottom: 22px;
    border-radius: 50%;
    color: #fff;
    background: #0c5adb;
    font-weight: 700;
}
.detail-step h3 {
    margin: 0 0 10px;
    color: #232323;
    font-size: 21px;
}
.detail-step p {
    margin: 0;
    line-height: 1.75;
}
.service-detail__cta {
    padding: 100px 0;
    background: linear-gradient(110deg, #061d43, #0c5adb);
}
.service-detail__cta h2 {
    color: #fff;
}
.service-detail__cta p:not(.service-detail__kicker) {
    max-width: 560px;
    margin: 0 auto 26px;
    color: rgba(255, 255, 255, 0.8);
    font-size: 17px;
}
.service-detail__cta a {
    display: inline-block;
    padding: 14px 26px;
    color: #fff;
    background: #0c5adb;
    font-weight: 700;
}
@media (max-width: 575px) {
    .section-space {
        padding: 65px 0;
    }
    .service-detail__panel {
        margin-top: 30px;
    }
    .service-detail__metrics {
        gap: 16px;
        flex-wrap: wrap;
    }
    .service-detail__hero-actions {
        align-items: flex-start;
        flex-direction: column;
    }
    .service-detail__visual img {
        height: 220px;
    }
}
</style>
