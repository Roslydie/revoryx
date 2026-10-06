<template>
    <main class="about-page">
        <section class="about-hero">
            <div class="about-hero__overlay"></div>
            <div class="container about-hero__content">
                <p class="about-kicker">{{ _e(`about.vue_1790616495697_65`, `MARKETING PERFORMANCE & REVENUE RECOVERY`) }}</p>
                <h1>{{ _e(`about.vue_1789391771434_22`, `Find the leak before selling the solution`) }} </h1>
                <p>{{ _e(`about.vue_1789391771434_23`, `We identify where your revenue is being lost between marketing and sales, quantify it, and fix the system without making you spend more on advertising.`) }}</p>
                <router-link class="about-button" to="/contact"> {{ _e(`about.vue_1789391771434_24`, `Request an audit`) }} <i class="fa fa-long-arrow-right"></i></router-link>
            </div>
        </section>

        <section class="about-intro section-space">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <div class="about-image-wrap">
                            <img :src="'/assets/images/about.png'" alt="Deux collègues discutant d'un diagramme de tunnel de conversion sur ordinateur.">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <p class="about-kicker"> {{ _e(`about.vue_1789391771434_25`, `ABOUT US`) }} </p>
                        <h2>{{ _e(`about.vue_1789391771434_26`, `The problem is never a lack of leads`) }}</h2>
                        <div class="about-rule"></div>
                        <p>{{ _e(`about.vue_1789391771434_27`, `Revoryx helps companies that are already investing in marketing recover the revenue they lose between customer acquisition, lead nurturing, and the sale.`) }}</p>
                        <p>{{ _e(`about.vue_1789391771434_28`, `Our approach is methodical: we identify where performance is declining, quantify the cost in dollars, and then fix the system not just the symptom.`) }}</p>
                        <div class="about-stat-row">
                            <div><strong>01</strong><span>{{ _e(`about.vue_1789391771434_29`, `A Quantitative Analysis`) }}</span>
                            <p>{{ _e(`about.vue_1791228482832_126`, `Each leak is measured in terms of dollars lost before it is corrected.`) }}</p>
                            </div>
                            <div><strong>02</strong><span>{{ _e(`about.vue_1789391771434_30`, `Targeted correction`) }}</span>
                            <p>{{ _e(`about.vue_1791228482833_128`, `We focus on what has the greatest impact, not on everything at once.`) }}</p>
                            </div>
                            <div><strong>03</strong><span>{{ _e(`about.vue_1789391771434_31`, `A measured result`) }}</span>
                            <p>{{ _e(`about.vue_1791228482833_130`, `Recovered revenue is tracked on a month-by-month basis.`) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="about-pillars section-space">
            <div class="container">
                <div class="section-heading text-center">
                    <p class="about-kicker"> {{ _e(`about.vue_1789391771434_32`, `OUR DIRECTION`) }} </p>
                    <h2 style="color: #0B2545;">{{ _e(`about.vue_1789391771434_33`, `Mission, vision and objectives`) }}</h2>
                    <p>{{ _e(`about.vue_1789391771434_34`, `Three principles guide every decision we make.`) }}</p>
                </div>
                <div class="row">
                    <div v-for="pillar in pillars" :key="pillar.title" class="col-lg-4 col-md-6 mb-4">
                        <article class="pillar-card">
                            <div class="pillar-card__icon"><i :class="pillar.icon"></i></div>
                            <h3>{{ pillar.title }}</h3>
                            <p>{{ pillar.text }}</p>
                        </article>
                    </div>
                </div>
            </div>
        </section>

        <section id="method" class="about-process section-space">
            <div class="container">
                <div class="section-heading text-center">
                    <p class="about-kicker"> {{ _e(`about.vue_1789391771434_35`, `HOW WE WORK`) }} </p>
                    <h2 style="color: #0B2545;">{{ _e(`about.vue_1789391771434_36`, `A Four-Step Method for Recovering Lost Income`) }}</h2>
                </div>
                <div class="process-grid">
                    <article v-for="(step, index) in processSteps" :key="step.title" class="process-card">
                        <span class="process-card__number">{{ String(index + 1).padStart(2, '0') }}</span>
                        <div class="process-card__icon"><i :class="step.icon"></i></div>
                        <h3>{{ step.title }}</h3>
                        <p>{{ step.text }}</p>
                    </article>
                </div>
            </div>
        </section>

        <section class="about-why section-space">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-5 mb-4 mb-lg-0">
                        <p class="about-kicker"> {{ _e(`about.vue_1789391771434_37`, `WHY CHOOSE US`) }} </p>
                        <h2>{{ _e(`about.vue_1789391771434_38`, `We'll never tell you to increase your budget before checking what it is right now`) }}</h2>
                        <p>{{ _e(`about.vue_1789391771434_39`, `We combine rigorous auditing with practical implementation to ensure that every dollar already invested finally yields the return it should.`) }}</p>
                    </div>
                    <div class="col-lg-7">
                        <div class="why-list">
                            <div v-for="reason in reasons" :key="reason.title" class="why-item">
                                <span class="why-item__check"><i class="fa fa-check"></i></span>
                                <div><h3>{{ reason.title }}</h3><p>{{ reason.text }}</p></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="about-cta">
            <div class="container text-center">
                <p class="about-kicker"> {{ _e(`about.vue_1789391771434_40`, `READY TO FIND THE LEAK?`) }} </p>
                <h2>{{ _e(`about.vue_1789391771434_41`, `Let's see where you're losing revenue.`) }}</h2>
                <router-link class="about-button" to="/contact">{{ _e(`about.vue_1789391771434_42`, `Request an audit`) }} <i class="fa fa-long-arrow-right"></i></router-link>
            </div>
        </section>
    </main>
</template>

<script setup>
import { computed } from 'vue';
import i18n from '../../plugins/i18n.js';

const _e = (key, fallback = key) => (
    i18n.global.te(key) ? i18n.global.t(key) : fallback
);

const pillars = computed(() => [
    {
        title: _e(`about.vue_1789391771434_43`, `Our mission`),
        icon: 'flaticon-process',
        text: _e(`about.vue_1789391771434_44`, `Identify where marketing performance is declining, quantify the lost revenue, and correct the systems, processes, and customer journeys responsible for this loss.`),
    },
    {
        title: _e(`about.vue_1789391771434_45`, `Our vision`),
        icon: 'flaticon-global-1',
        text: _e(`about.vue_1789391771434_46`, `To become the go to partner for small and medium-sized businesses that are investing in their growth but are not realizing the full value of their marketing efforts.`),
    },
    {
        title: _e(`about.vue_1789391771434_47`, `Our Belief`),
        icon: 'flaticon-chart',
        text: _e(`about.vue_1789391771434_48`, `A company should never have to spend more on marketing to make up for a flawed conversion system.`),
    },
]);

const processSteps = computed(() => [
    { title: _e(`about.vue_1789391771434_49`, `Diagnose`), icon: 'flaticon-information', text: _e(`about.vue_1789391771434_50`, `We map out your customer journey from the first click to the completed sale to pinpoint exactly where revenue is lost.`) },
    { title: _e(`about.vue_1789391771434_51`, `Quantify`), icon: 'flaticon-analytics', text: _e(`about.vue_1789391771435_52`, `We quantify each leak in terms of dollars lost and prioritize fixes based on impact and ease of implementation.`) },
    { title: _e(`about.vue_1789391771435_53`, `Execute`), icon: 'flaticon-code', text: _e(`about.vue_1789391771435_54`, `We implement fixes, from tracking to intake, including CRM automations.`) },
    { title: _e(`about.vue_1789391771435_55`, `Measure`), icon: 'flaticon-analytics', text: _e(`about.vue_1789391771435_56`, `We track the revenue recovered and make ongoing adjustments.`) },
]);

const reasons = computed(() => [
    { title: _e(`about.vue_1789391771435_57`, `Evidence Before Recommendation`), text: _e(`about.vue_1789391771435_58`, `Nothing is corrected without first being quantified. Every technical decision is linked to a clear business impact.`) },
    { title: _e(`about.vue_1789391771435_59`, `Honesty About the Real Problem`), text: _e(`about.vue_1789391771435_60`, `If the problem isn't your advertising budget, we'll tell you so even if it means selling less.`) },
    { title: _e(`about.vue_1789391771435_61`, `The system, not the symptom`), text: _e(`about.vue_1789391771435_62`, `We never make isolated corrections. We correct the entire process, from acquisition to sale.`) },
    { title: _e(`about.vue_1789391771435_63`, `Precision in every detail`), text: _e(`about.vue_1789391771435_64`, `We will stay until the recovered revenue is verified, not just promised.`) },
]);
</script>

<style scoped>
.about-page { color: #616161; }
.section-space { padding: 100px 0; }
.about-hero { position: relative; min-height: 560px; display: flex; align-items: center; background: url('/assets/images/slider/hero.png') center / cover; }
.about-hero__overlay { position: absolute; inset: 0; background: rgba(11, 37, 69, .65); }

.about-hero__content { position: relative; z-index: 1; color: #fff; }
.about-hero h1 { max-width: 760px; margin: 0 0 20px; color: #fff; font-size: clamp(40px, 6vw, 74px); line-height: 1.08; }
.about-hero p:not(.about-kicker) { max-width: 600px; margin-bottom: 30px; color: rgba(255, 255, 255, .84); font-size: 19px; }
.about-kicker { margin: 0 0 13px; color: #0B2545; font-size: 13px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; }
.about-hero .about-kicker, .about-cta .about-kicker { color: #6B7280; }
.about-button { display: inline-block; padding: 13px 25px; border-radius: 0; color: #fff; background: #0B2545; font-weight: 700; transition: transform .3s ease, background .3s ease; }
.about-button:hover { color: #fff; background: #6B7280; transform: translateY(-3px); }
.about-image-wrap { position: relative; padding: 0 35px 30px 0; }
.about-image-wrap > img:first-child { display: block; width: 100%; }
.about-image-wrap__circle { position: absolute; right: 0; bottom: 0; width: 30%; animation: aboutFloat 5s ease-in-out infinite; }
@keyframes aboutFloat { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
.about-intro h2, .about-why h2, .section-heading h2, .about-cta h2 { margin: 0 0 18px; color: #232323; font-size: clamp(30px, 4vw, 45px); line-height: 1.2; }
.about-intro p, .about-why > .container p { line-height: 1.8; }
.about-rule { width: 55px; height: 4px; margin: 20px 0; background: #0B2545; }
.about-stat-row { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 0; margin-top: 26px; }
.about-stat-row > div { min-width: 0; padding: 20px 16px; text-align: center; }
.about-stat-row strong, .about-stat-row span { display: block; }
.about-stat-row strong { margin-bottom: 7px; color: #0B2545; font-size: 27px; }
.about-stat-row span { color: #232323; font-size: 14px; font-weight: 700; }
.about-stat-row p { margin: 8px 0 0; color: #616161; font-size: 14px; line-height: 1.6; }
.about-pillars { background: #f7faff; }
.section-heading { max-width: 700px; margin: 0 auto 48px; }
.section-heading > p:last-child { margin: 0; font-size: 17px; }
.pillar-card { height: 100%; padding: 35px 30px; border: 1px solid #e1eaf7; border-radius: 0; background: #fff; box-shadow: 0 8px 25px rgba(8, 35, 80, .06); text-align: center; transition: transform .35s ease, box-shadow .35s ease; }
.pillar-card:hover { box-shadow: 0 18px 35px rgba(11, 37, 69, .15); transform: translateY(-8px); }
.pillar-card__icon, .process-card__icon { display: flex; align-items: center; justify-content: center; width: 74px; height: 74px; margin: 0 auto 20px; border-radius: 50%; color: #fff; background: #6B7280; font-size: 32px; }
.pillar-card h3, .process-card h3, .why-item h3 { margin: 0 0 12px; color: #232323; font-size: 21px; }
.pillar-card p, .process-card p, .why-item p { margin: 0; line-height: 1.75; }
.process-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; }
.process-card { position: relative; padding: 32px 22px; border-top: 3px solid #0B2545; background: #fff; box-shadow: 0 8px 25px rgba(8, 35, 80, .08); text-align: center; transition: transform .35s ease; }
.process-card:hover { transform: translateY(-8px); }
.process-card__number { position: absolute; top: 12px; right: 15px; color: #dce9fb; font-size: 25px; font-weight: 700; }
.about-process { background: #fff; }
.about-why { background: #f7faff; }
.why-list { display: grid; grid-template-columns: 1fr 1fr; gap: 22px; }
.why-item { display: flex; gap: 14px; padding: 22px; border-left: 3px solid #0B2545; background: #fff; }
.why-item__check { flex: 0 0 28px; display: grid; place-items: center; width: 28px; height: 28px; border-radius: 50%; color: #fff; background: #0B2545; }
.why-item h3 { font-size: 18px; }
.why-item p { font-size: 14px; }
.about-cta { padding: 90px 0; color: #fff; background: linear-gradient(110deg, #061d43, #0B2545); }
.about-cta h2 { color: #fff; }
@media (max-width: 991px) { .process-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 575px) { .section-space { padding: 65px 0; } .about-hero { min-height: 500px; } .about-stat-row, .why-list { grid-template-columns: 1fr; display: grid; } .process-grid { grid-template-columns: 1fr; } .about-image-wrap { padding-right: 20px; } }
</style>