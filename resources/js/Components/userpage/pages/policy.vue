
<template>
    <main class="policy-page">
        <section class="policy-hero">
            <div class="policy-hero__overlay"></div>
            <div class="container policy-hero__content">
                <p class="policy-kicker">REVORYX &amp; PARTNERS</p>
                <h1>{{ _e(`policy.vue_1789433978343_238`, `Privacy built on clarity`) }}</h1>
                <p>
                    {{ _e(`policy.vue_1789433978343_239`, `We respect your information and explain how we collect, use and protect it when you interact with our digital services.`) }}
                </p>
                <p class="policy-updated">
                    <i class="fa fa-calendar-o"></i>
                    {{ _e(`policy.vue_1789433978343_240`, `Last updated: September 10, 2026`) }}
                </p>
            </div>
        </section>

        <section ref="policyContent" class="policy-content section-space">
            <div class="container">
                <div class="row align-items-start">

                    <!-- LEFT SIDE -->
                    <div class="col-lg-4 mb-5 mb-lg-0">
                        <div
                            ref="policySummary"
                            class="policy-summary"
                            :style="summaryStyle"
                        >
                            <p class="policy-kicker">
                                {{ _e(`policy.vue_1789433978343_241`, `YOUR PRIVACY MATTERS`) }}
                            </p>

                            <h2>
                                {{ _e(`policy.vue_1789433978343_242`, `A straightforward approach to your data`) }}
                            </h2>

                            <div class="policy-rule"></div>

                            <p>
                                {{ _e(`policy.vue_1789433978343_243`, `This Privacy Policy applies to the Revoryx &amp; Partners website and describes the information we may receive when you browse our website, contact us or work with us.`) }}
                            </p>

                            <a
                                class="policy-summary__link"
                                href="mailto:techr7129@gmail.com"
                            >
                                {{ _e(`policy.vue_1789433978343_244`, `Have a question? Contact us`) }}
                                <i class="fa fa-long-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- RIGHT SIDE -->
                    <div class="col-lg-8">
                        <div class="policy-sections">

                            <article
                                v-for="(section, index) in sections"
                                :key="section.title"
                                class="policy-section"
                            >
                                <span class="policy-section__number">
                                    {{ String(index + 1).padStart(2, '0') }}
                                </span>

                                <div>
                                    <h3>{{ section.title }}</h3>

                                    <p
                                        v-for="paragraph in section.paragraphs"
                                        :key="paragraph"
                                    >
                                        {{ paragraph }}
                                    </p>

                                    <ul v-if="section.items">
                                        <li
                                            v-for="item in section.items"
                                            :key="item"
                                        >
                                            {{ item }}
                                        </li>
                                    </ul>
                                </div>
                            </article>

                        </div>
                    </div>

                </div>
            </div>
        </section>

        <section ref="policyCallout" class="policy-callout">
            <div class="container policy-callout__inner">
                <div>
                    <p class="policy-kicker">
                        {{ _e(`policy.vue_1789433978343_245`, `NEED MORE INFORMATION?`) }}
                    </p>

                    <h2>
                        {{ _e(`policy.vue_1789433978343_246`, `We are here to answer your questions.`) }}
                    </h2>

                    <p>
                        {{ _e(`policy.vue_1789433978343_247`, `For any privacy request or question about this policy, contact our team directly.`) }}
                    </p>
                </div>

                <a
                    class="policy-callout__button"
                    href="mailto:techr7129@gmail.com"
                >
                    {{ _e(`policy.vue_1789433978343_248`, `Email our team`) }}
                    <i class="fa fa-envelope-o"></i>
                </a>
            </div>
        </section>
    </main>
</template>

<script setup>
import { computed, ref, onMounted, onBeforeUnmount } from 'vue';
import i18n from '../../plugins/i18n.js';

const _e = (key, fallback = key) => (
    i18n.global.te(key) ? i18n.global.t(key) : fallback
);

const policyContent = ref(null);
const policySummary = ref(null);
const policyCallout = ref(null);

const summaryOffset = ref(0);

const summaryStyle = computed(() => {
    if (summaryOffset.value <= 0) {
        return {};
    }

    return {
        transform: `translateY(${summaryOffset.value}px)`,
    };
});

const handleScroll = () => {
    if (!policyContent.value || !policySummary.value) {
        return;
    }

    // Sur mobile/tablette, on désactive le mouvement.
    if (window.innerWidth < 992) {
        summaryOffset.value = 0;
        return;
    }

    const sectionRect = policyContent.value.getBoundingClientRect();

    // Distance entre le haut de la section et le haut de la fenêtre.
    const scrollInsideSection = Math.max(0, -sectionRect.top);

    /*
     * Le résumé accompagne progressivement le scroll.
     *
     * Le facteur 0.35 permet d'obtenir un mouvement plus doux :
     * la colonne gauche descend progressivement au lieu de rester
     * complètement fixe.
     */
    const movement = scrollInsideSection * 0.50;

    // Le mouvement continue pendant le défilement de la section suivante.
    const movementEnd = policyCallout.value
        ? policyCallout.value.offsetTop + policyCallout.value.offsetHeight
        : policyContent.value.offsetTop + policyContent.value.offsetHeight;
    const maxMovement = movementEnd - policyContent.value.offsetTop - policySummary.value.offsetHeight - 70;

    summaryOffset.value = Math.min(
        Math.max(0, movement),
        Math.max(0, maxMovement)
    );
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll, { passive: true });
    window.addEventListener('resize', handleScroll);

    handleScroll();
});

onBeforeUnmount(() => {
    window.removeEventListener('scroll', handleScroll);
    window.removeEventListener('resize', handleScroll);
});

const sections = computed(() => [
    {
        title: _e(`policy.vue_1789433978343_249`, `Information we collect`),
        paragraphs: [
            _e(`policy.vue_1789433978343_250`, `We may collect information you choose to provide, such as your name, email address, phone number, company details and the content of your message when you contact us about a service, project or partnership.`),
            _e(`policy.vue_1789433978343_251`, `When you browse the website, limited technical information may also be collected automatically, including your IP address, browser type, device information, pages visited and approximate usage times.`)
        ],
    },
    {
        title: _e(`policy.vue_1789433978343_252`, `How we use your information`),
        paragraphs: [
            _e(`policy.vue_1789433978343_253`, `We use information only when it is relevant to operating and improving Revoryx & Partners, responding to requests and delivering the services you ask us to provide. This may include to:`)
        ],
        items: [
            _e(`policy.vue_1789433978343_254`, `Respond to questions, quote requests and other messages.`),
            _e(`policy.vue_1789433978343_255`, `Understand your needs and prepare a suitable project discussion or proposal.`),
            _e(`policy.vue_1789433978343_256`, `Maintain, secure and improve the performance of our website and services.`),
            _e(`policy.vue_1789433978343_257`, `Meet legal, regulatory or accounting obligations when required.`)
        ],
    },
    {
        title: _e(`policy.vue_1789433978343_258`, `Cookies and analytics`),
        paragraphs: [
            _e(`policy.vue_1789433978343_259`, `Our website may use essential cookies or similar technologies to support basic functionality, remember preferences and understand how the website is used. Where third-party analytics or embedded services are used, those providers may process technical information according to their own privacy policies. You can manage cookies through your browser settings.`)
        ],
    },
    {
        title: _e(`policy.vue_1789433978343_260`, `Sharing and data security`),
        paragraphs: [
            _e(`policy.vue_1789433978343_261`, `We do not sell or rent your personal information. We may share information with trusted service providers who help us host, secure, maintain or operate the website and our services. They are expected to use the information only for the agreed purpose.`),
            _e(`policy.vue_1789433978343_262`, `We take reasonable technical and organizational measures to protect information against unauthorized access, loss or misuse. No internet transmission or storage system can be guaranteed to be completely secure.`)
        ],
    },
    {
        title: _e(`policy.vue_1789433978343_263`, `Your choices and rights`),
        paragraphs: [
            _e(`policy.vue_1789433978343_264`, `Depending on where you live, you may have rights to request access to, correction of or deletion of your personal information, or to object to or limit certain processing. You may also withdraw consent where processing is based on consent. To make a request, email us using the address below. We may need to verify your identity before completing it.`)
        ],
    },
    {
        title: _e(`policy.vue_1789433978343_265`, `Retention and policy changes`),
        paragraphs: [
            _e(`policy.vue_1789433978343_266`, `We keep personal information only for as long as it is reasonably necessary for the purposes described here, to maintain business records or to meet legal requirements. We may update this policy when our services or legal obligations change. The date at the top of this page shows when it was last revised.`)
        ],
    },
    {
        title: _e(`policy.vue_1789433978343_267`, `Contact us`),
        paragraphs: [
            _e(`policy.vue_1789433978343_268`, `If you have a question about this Privacy Policy or want to exercise a privacy right, please contact Revoryx & Partners at techr7129@gmail.com or +1 (215) 989-0101. You can also write to us at 3506 S 61st, Philadelphia, PA 19153.`)
        ],
    },
]);
</script>

<style scoped>
.policy-page {
    color: #616161;
}

.section-space {
    padding: 100px 0;
}

.policy-hero {
    position: relative;
    min-height: 500px;
    display: flex;
    align-items: center;
    background: url('/assets/images/slider/hero-bg.jpg') center / cover;
}

.policy-hero__content {
    position: relative;
    z-index: 1;
    color: #fff;
}

.policy-hero h1 {
    max-width: 760px;
    margin: 0 0 20px;
    color: #fff;
    font-size: clamp(40px, 6vw, 74px);
    line-height: 1.08;
}

.policy-hero p:not(.policy-kicker):not(.policy-updated) {
    max-width: 620px;
    margin: 0;
    color: rgba(255, 255, 255, .84);
    font-size: 19px;
}

.policy-kicker {
    margin: 0 0 13px;
    color: #0c5adb;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 2px;
    text-transform: uppercase;
}

.policy-hero .policy-kicker,
.policy-callout .policy-kicker {
    color: #65d5a0;
}

.policy-updated {
    margin: 28px 0 0;
    color: rgba(255, 255, 255, .9);
    font-size: 14px;
    font-weight: 700;
}

.policy-updated i {
    margin-right: 8px;
    color: #65d5a0;
}

.policy-content {
    background: #fff;
}

/*
 * IMPORTANT :
 * On retire position: sticky.
 * Le mouvement est maintenant contrôlé par Vue.
 */
.policy-summary {
    position: relative;
    top: auto;
    padding-right: 35px;
    will-change: transform;
    transition: transform .08s linear;
}

.policy-summary h2 {
    margin: 0 0 18px;
    color: #232323;
    font-size: clamp(30px, 4vw, 45px);
    line-height: 1.2;
}

.policy-summary > p:not(.policy-kicker) {
    line-height: 1.8;
}

.policy-rule {
    width: 55px;
    height: 4px;
    margin: 20px 0;
    background: #0c5adb;
}

.policy-summary__link {
    display: inline-block;
    margin-top: 14px;
    color: #0c5adb;
    font-weight: 700;
}

.policy-summary__link i {
    margin-left: 7px;
    transition: transform .25s ease;
}

.policy-summary__link:hover i {
    transform: translateX(4px);
}

.policy-sections {
    display: grid;
    gap: 30px;
}

.policy-section {
    display: grid;
    grid-template-columns: 56px 1fr;
    gap: 20px;
    padding: 0 0 30px;
    border-bottom: 1px solid #e1eaf7;
}

.policy-section:last-child {
    padding-bottom: 0;
    border-bottom: 0;
}

.policy-section__number {
    color: #0c5adb;
    font-size: 22px;
    font-weight: 700;
}

.policy-section h3 {
    margin: 0 0 12px;
    color: #232323;
    font-size: 22px;
}

.policy-section p {
    margin: 0 0 13px;
    line-height: 1.8;
}

.policy-section p:last-child {
    margin-bottom: 0;
}

.policy-section ul {
    margin: 5px 0 0;
    padding-left: 20px;
}

.policy-section li {
    margin-bottom: 9px;
    padding-left: 5px;
    line-height: 1.7;
}

.policy-callout {
    padding: 78px 0;
    color: #fff;
    background: linear-gradient(110deg, #061d43, #0c5adb);
}

.policy-callout__inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 40px;
}

.policy-callout h2 {
    max-width: 600px;
    margin: 0 0 14px;
    color: #fff;
    font-size: clamp(28px, 4vw, 42px);
    line-height: 1.2;
}

.policy-callout p:not(.policy-kicker) {
    max-width: 650px;
    margin: 0;
    color: rgba(255, 255, 255, .78);
    line-height: 1.8;
}

.policy-callout__button {
    flex: 0 0 auto;
    padding: 14px 22px;
    border: 1px solid rgba(255, 255, 255, .35);
    border-radius: 3px;
    color: #fff;
    font-weight: 700;
    transition: background .25s ease, transform .25s ease;
}

.policy-callout__button:hover {
    color: #061d43;
    background: #fff;
    transform: translateY(-3px);
}

.policy-callout__button i {
    margin-left: 8px;
}

@media (max-width: 991px) {
    .policy-summary {
        transform: none !important;
    }
}

@media (max-width: 767px) {
    .policy-hero {
        min-height: 440px;
    }

    .section-space {
        padding: 70px 0;
    }

    .policy-summary {
        position: static;
        padding-right: 0;
    }

    .policy-section {
        grid-template-columns: 40px 1fr;
        gap: 12px;
    }

    .policy-callout__inner {
        display: block;
    }

    .policy-callout__button {
        display: inline-block;
        margin-top: 24px;
    }
}
</style>

