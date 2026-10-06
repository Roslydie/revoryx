<template>
    <main class="contact-page">
        <section class="contact-hero">
            <div class="contact-hero__overlay"></div>
            <div class="container contact-hero__content">
                <p class="contact-kicker" style="color: #6B7280;">MARKETING PERFORMANCE & REVENUE RECOVERY</p>
                <h1>{{ _e(`contact.vue_1789430796873_101`, `Let's talk about what you're already losing`) }}</h1>
                <p>{{ _e(`contact.vue_1789430796873_102`, `Tell us where you think the problem is. We'll help you figure out if that's really where the leak is.`) }}</p>
                <div class="contact-hero__meta">
                    <span><i class="fa fa-check-circle"></i>{{ _e(`contact.vue_1789430796873_103`, ` Quantitative Analysis`) }}</span>
                    <span><i class="fa fa-check-circle"></i>{{ _e(`contact.vue_1789430796873_104`, ` No recommendations without evidence`) }}</span>
                    <span><i class="fa fa-check-circle"></i>{{ _e(`contact.vue_1789430796873_105`, ` Follow-up Until a Result Is Achieved`) }}</span>
                </div>
            </div>
        </section>

        <section class="contact-intro-band">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 contact-intro-item">
                        <span class="contact-intro-item__number">01</span>
                        <div><h3>{{ _e(`contact.vue_1789430796873_106`, `Share your situation`) }}</h3><p>{{ _e(`contact.vue_1789430796873_107`, `Tell us about the situation, your current marketing budget, and what doesn't seem to be working.`) }}</p></div>
                    </div>
                    <div class="col-lg-4 contact-intro-item">
                        <span class="contact-intro-item__number">02</span>
                        <div><h3>{{ _e(`contact.vue_1789430796873_108`, `Let's locate the leak`) }}</h3><p>{{ _e(`contact.vue_1789430796873_109`, `We'll let you know whether the problem stems from customer acquisition, intake, or sales follow-up.`) }}</p></div>
                    </div>
                    <div class="col-lg-4 contact-intro-item">
                        <span class="contact-intro-item__number">03</span>
                        <div><h3>{{ _e(`contact.vue_1789430796873_110`, `Let's get started`) }}</h3><p>{{ _e(`contact.vue_1789430796873_111`, `You'll leave with a clear next step, with a cost estimate if possible.`) }}</p></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="contact-main section-space">
            <div class="container">
                <div class="row align-items-start">
                    <div class="col-lg-5 mb-5 mb-lg-0">
                        <p class="contact-kicker">{{ _e(`contact.vue_1789430796873_112`, `GET IN TOUCH`) }}</p>
                        <h2>{{ _e(`contact.vue_1789430796873_113`, `Let's see where you're losing income`) }}</h2>
                        <div class="contact-rule"></div>
                        <p class="contact-intro">{{ _e(`contact.vue_1789430796873_114`, `Whether you've already quantified the problem or just have a feeling that something isn't right, please write to us. We'll respond directly to you, not through an automated form.`) }}</p>
                        <p class="contact-intro">{{ _e(`contact.vue_1789430796873_115`, `Tell us where you stand today, what isn't working, and what a concrete improvement would look like. That's enough to start a real conversation.`) }}</p>

                        <div class="contact-details">
                            <a href="mailto:contact@revoryxandpartners.com" class="contact-detail">
                                <span class="contact-detail__icon"><i class="fa fa-envelope-o"></i></span>
                                <span><small>Email us</small><strong>contact@revoryxandpartners.com</strong></span>
                            </a>
                            <a href="tel:+12159890101" class="contact-detail">
                                <span class="contact-detail__icon"><i class="fa fa-phone"></i></span>
                                <span><small>{{ _e(`contact.vue_1789430796873_116`, `Call us`) }}</small><strong>+1 (215) 989-0101</strong></span>
                            </a>
                            <div class="contact-detail">
                                <span class="contact-detail__icon"><i class="fa fa-map-marker"></i></span>
                                <span><small>{{ _e(`contact.vue_1789430796873_117`, `Visit us`) }}</small><strong>3506 S 61st<br>Philadelphia, PA 19153</strong></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <form class="contact-form" @submit.prevent="submitContact">
                            <div v-if="feedback.message" class="contact-form__feedback" :class="`contact-form__feedback--${feedback.type}`" role="alert">
                                {{ feedback.message }}
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="contact-name">{{ _e(`contact.vue_1789430796873_118`, `Your name`) }}</label>
                                    <input id="contact-name" v-model.trim="form.full_name" type="text" name="full_name" placeholder="Your name" autocomplete="name" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="contact-email">{{ _e(`contact.vue_1789430796873_119`, `Email address`) }}</label>
                                    <input id="contact-email" v-model.trim="form.email" type="email" name="email" placeholder="you@example.com" autocomplete="email" required>
                                </div>
                                <div class="col-12">
                                    <label for="contact-phone">{{ _e(`contact.vue_1789430796873_120`, `Phone number`) }}</label>
                                    <PhoneInput
                                        id="contact-phone"
                                        v-model="form.phone"
                                        placeholder="Phone Number"
                                        :required="true"
                                        :class="{ 'is-invalid': errors.phone }"
                                        @update:model-value="clearPhoneError"
                                    />
                                    <small v-if="errors.phone" class="contact-form__field-error">{{ errors.phone }}</small>
                                </div>
                                <div class="col-md-12">
                                    <label for="contact-subject">{{ _e(`contact.vue_1789430796873_121`, `Subject`) }}</label>
                                    <input id="contact-subject" v-model.trim="form.subject" type="text" name="subject" placeholder="What is your line of business?" required>
                                </div>
                                <div class="col-12">
                                    <label for="contact-message">{{ _e(`contact.vue_1789430796873_122`, `Your message`) }}</label>
                                    <textarea id="contact-message" v-model.trim="form.message" name="message" rows="6" placeholder="Tell us about your current situation: your marketing budget, the volume of leads, and what you feel isn't working." required></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="contact-form__button" :disabled="submitting">
                                        {{ submitting ? 'Sending...' : _e(`contact.vue_1789430796873_123`, `Send message`) }}
                                        <i class="fa fa-long-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

        <section class="contact-values section-space">
            <div class="container">
                <div class="section-heading text-center">
                    <p class="contact-kicker">{{ _e(`contact.vue_1789430796873_124`, `WHY CONTACT US`) }}</p>
                    <h2>{{ _e(`contact.vue_1789430796873_125`, `A clear diagnosis before making any recommendations`) }}</h2>
                </div>
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4">
                        <article class="contact-value-card">
                            <span class="contact-value-card__icon"><i class="flaticon-information"></i></span>
                            <h3>{{ _e(`contact.vue_1789430796873_126`, `We review your data before discussing solutions`) }}</h3>
                            <p>{{ _e(`contact.vue_1789430796873_127`, `We take the time to understand your current sales funnel, your leads, and where they’re falling through the cracks before making any recommendations.`) }}</p>
                        </article>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <article class="contact-value-card">
                            <span class="contact-value-card__icon"><i class="flaticon-process"></i></span>
                            <h3>{{ _e(`contact.vue_1789430796873_128`, `We'll let you know if we're not the right fit for you`) }}</h3>
                            <p>{{ _e(`contact.vue_1789430796873_129`, `If your problem isn't a loss of revenue but a genuine lack of demand, we'll tell you straight up.`) }}</p>
                        </article>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <article class="contact-value-card">
                            <span class="contact-value-card__icon"><i class="flaticon-interaction"></i></span>
                            <h3>{{ _e(`contact.vue_1789430796873_130`, `We'll stay until we get the final figure`) }}</h3>
                            <p>{{ _e(`contact.vue_1789430796873_131`, `Each recommendation is followed through until the recovered revenue is measured—not just promised.`) }}</p>
                        </article>
                    </div>
                </div>
            </div>
        </section>

        <section class="contact-callout">
            <div class="container">
                <div class="contact-callout__inner">
                    <div>
                        <p class="contact-kicker">{{ _e(`contact.vue_1789430796873_132`, `A CONVERSATION CAN START ANYWHERE`) }}</p>
                        <h2>{{ _e(`contact.vue_1789430796873_133`, `Do you have a question before you send us a full message?`) }}</h2>
                        <p>{{ _e(`contact.vue_1789430796873_134`, `Call us directly. We can determine in just a few minutes whether your situation is something we handle.`) }}</p>
                    </div>
                    <a href="tel:+12159890101" class="contact-callout__button">{{ _e(`contact.vue_1789430796873_135`, `Call +1 (215) 989-0101`) }} <i class="fa fa-phone"></i></a>
                </div>
            </div>
        </section>

        <section class="contact-map-section">
            <div class="container contact-map-heading">
                <div class="section-heading text-center">
                    <p class="contact-kicker">{{ _e(`contact.vue_1789430796873_136`, `OUR LOCATION`) }}</p>
                    <h2>{{ _e(`contact.vue_1789430796873_137`, `Find us in Philadelphia`) }}</h2>
                    <p>{{ _e(`contact.vue_1789430796873_138`, `3506 S 61st, Philadelphia, PA 19153`) }}</p>
                </div>
            </div>
            <div class="contact-map">
                <iframe
                    title="Revoryx & Partners location"
                    src="https://www.google.com/maps?q=3506+S+61st,+Philadelphia,+PA+19153&output=embed"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    allowfullscreen>
                </iframe>
            </div>
        </section>
    </main>
</template>

<script setup>
import { reactive, ref } from 'vue'
import PhoneInput from '../../shared/PhoneInput.vue'
import { postData } from '../../plugins/axios.js'

const form = reactive({
    full_name: '',
    email: '',
    phone: '',
    subject: '',
    message: '',
})
const submitting = ref(false)
const feedback = reactive({ message: '', type: 'success' })
const errors = reactive({ phone: '' })

const clearPhoneError = () => {
    errors.phone = ''
}

const resetForm = () => {
    form.full_name = ''
    form.email = ''
    form.phone = ''
    form.subject = ''
    form.message = ''
}

const submitContact = async () => {
    feedback.message = ''
    errors.phone = ''
    submitting.value = true

    try {
        await postData('/contacts', form)
        feedback.message = 'Thank you. Your message has been sent successfully.'
        feedback.type = 'success'
        resetForm()
    } catch (error) {
        errors.phone = error.response?.data?.errors?.phone?.[0] ?? ''
        feedback.message = error.response?.data?.message ?? 'Unable to send your message. Please try again.'
        feedback.type = 'error'
    } finally {
        submitting.value = false
    }
}
</script>

<style scoped>
.contact-page { color: #616161; }
.section-space { padding: 100px 0; }
.contact-hero { position: relative; min-height: 500px; display: flex; align-items: center; background: url('/assets/images/slider/contact.png') center / cover; }
.contact-hero__overlay { position: absolute; inset: 0; background: rgba(11, 37, 69, .65); }
.contact-hero__content { position: relative; z-index: 1; color: #fff; }
.contact-hero h1 { max-width: 760px; margin: 0 0 20px; color: #fff; font-size: clamp(40px, 6vw, 74px); line-height: 1.08; }
.contact-hero p:not(.contact-kicker) { max-width: 620px; margin: 0; color: rgba(255, 255, 255, .84); font-size: 19px; }
.contact-kicker { margin: 0 0 13px; color: #0B2545; font-size: 13px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; }
.contact-hero .contact-kicker { color: #65d5a0; }
.contact-hero__meta { display: flex; flex-wrap: wrap; gap: 24px; margin-top: 30px; color: rgba(255, 255, 255, .9); font-size: 14px; font-weight: 700; }
.contact-hero__meta i { margin-right: 6px; color: #65d5a0; }
.contact-intro-band { padding: 48px 0; color: #fff; background: #061d43; }
.contact-intro-item { display: flex; gap: 16px; align-items: flex-start; padding: 12px 28px; border-right: 1px solid rgba(255, 255, 255, .14); }
.contact-intro-item:last-child { border-right: 0; }
.contact-intro-item__number { color: #65d5a0; font-size: 24px; font-weight: 700; line-height: 1; }
.contact-intro-item h3 { margin: 0 0 7px; color: #fff; font-size: 18px; }
.contact-intro-item p { margin: 0; color: rgba(255, 255, 255, .68); font-size: 14px; line-height: 1.7; }
.contact-main { background: #fff; }
.contact-main h2, .section-heading h2 { margin: 0 0 18px; color: #232323; font-size: clamp(30px, 4vw, 45px); line-height: 1.2; }
.contact-rule { width: 55px; height: 4px; margin: 20px 0; background: #0B2545; }
.contact-intro { max-width: 480px; line-height: 1.8; }
.contact-details { display: grid; gap: 18px; margin-top: 32px; }
.contact-detail { display: flex; align-items: center; gap: 16px; color: #616161; }
.contact-detail { animation: contactSlideIn .7s ease both; }
.contact-detail:nth-child(2) { animation-delay: .12s; }
.contact-detail:nth-child(3) { animation-delay: .24s; }
.contact-detail:hover { color: #0B2545; transform: translateX(5px); transition: transform .25s ease; }
.contact-detail__icon { display: grid; flex: 0 0 52px; place-items: center; width: 52px; height: 52px; border-radius: 50%; color: #fff; background: #6B7280; font-size: 20px; }
.contact-detail small, .contact-detail strong { display: block; }
.contact-detail small { margin-bottom: 2px; color: #0B2545; font-size: 12px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; }
.contact-detail strong { color: #232323; font-size: 16px; line-height: 1.6; }
.contact-form { padding: 38px; border-top: 4px solid #0B2545; background: #f7faff; box-shadow: 0 12px 35px rgba(8, 35, 80, .08); }
.contact-form { animation: contactLiftIn .8s .15s ease both; }
.contact-form__feedback { margin-bottom: 22px; padding: 12px 15px; border-left: 3px solid; font-size: 14px; line-height: 1.5; }
.contact-form__feedback--success { color: #176b46; background: #e8f7ef; border-color: #2a9d68; }
.contact-form__feedback--error { color: #9a2835; background: #fff0f1; border-color: #bd2d3a; }
.contact-form__field-error { display: block; margin-top: -15px; margin-bottom: 22px; color: #bd2d3a; }
.contact-form label { display: block; margin: 0 0 8px; color: #232323; font-size: 14px; font-weight: 700; }
.contact-form input, .contact-form textarea { width: 100%; margin-bottom: 22px; padding: 13px 16px; border: 1px solid #dce5f1; border-radius: 2px; outline: 0; color: #232323; background: #fff; font: inherit; transition: border-color .25s ease, box-shadow .25s ease; }
.contact-form input:focus, .contact-form textarea:focus { border-color: #0B2545; box-shadow: 0 0 0 3px rgba(11, 37, 69, .1); }
.contact-form textarea { resize: vertical; }
.contact-form__button { padding: 13px 25px; border: 0; border-radius: 0; color: #fff; background: #0B2545; font-weight: 700; cursor: pointer; transition: background .25s ease, transform .25s ease; }
.contact-form__button:hover { background: #6B7280; transform: translateY(-2px); }
.contact-form__button i { margin-left: 8px; }
.contact-values { background: #fff; }
.contact-value-card { height: 100%; padding: 34px 28px; border-top: 3px solid #0B2545; background: #f7faff; box-shadow: 0 8px 25px rgba(8, 35, 80, .06); transition: transform .35s ease, box-shadow .35s ease; }
.contact-value-card:hover { box-shadow: 0 18px 35px rgba(11, 37, 69, .14); transform: translateY(-8px); }
.contact-value-card__icon { display: grid; place-items: center; width: 62px; height: 62px; margin-bottom: 22px; border-radius: 50%; color: #fff; background: #6B7280; font-size: 26px; }
.contact-value-card h3 { margin: 0 0 12px; color: #232323; font-size: 20px; }
.contact-value-card p { margin: 0; line-height: 1.75; }
.contact-callout { padding: 78px 0; color: #fff; background: linear-gradient(110deg, #061d43, #0B2545); }
.contact-callout__inner { display: flex; align-items: center; justify-content: space-between; gap: 40px; }
.contact-callout h2 { max-width: 600px; margin: 0 0 14px; color: #fff; font-size: clamp(28px, 4vw, 42px); line-height: 1.2; }
.contact-callout p:not(.contact-kicker) { max-width: 650px; margin: 0; color: rgba(255, 255, 255, .78); line-height: 1.8; }
.contact-callout .contact-kicker { color: #6B7280; }
.contact-callout__button { flex: 0 0 auto; padding: 14px 22px; border: 1px solid rgba(255, 255, 255, .35); border-radius: 3px; color: #fff; font-weight: 700; transition: background .25s ease, transform .25s ease; }
.contact-callout__button:hover { color: #061d43; background: #fff; transform: translateY(-3px); }
.contact-callout__button i { margin-left: 8px; }
.contact-map-section { padding: 100px 0; background: #f7faff; }
.contact-map-heading .section-heading { max-width: 700px; margin: 0 auto 42px; }
.section-heading > p:last-child { margin: 0; font-size: 17px; line-height: 1.75; }
.contact-map { width: 100%; height: 500px; overflow: hidden; border-top: 8px solid #fff; border-bottom: 8px solid #fff; box-shadow: 0 12px 35px rgba(8, 35, 80, .1); }
.contact-map iframe { width: 100%; height: 100%; border: 0; }
@keyframes contactLiftIn { from { opacity: 0; transform: translateY(28px); } to { opacity: 1; transform: translateY(0); } }
@keyframes contactSlideIn { from { opacity: 0; transform: translateX(-18px); } to { opacity: 1; transform: translateX(0); } }
@media (max-width: 767px) {
    .contact-hero { min-height: 440px; }
    .section-space, .contact-map-section { padding: 70px 0; }
    .contact-hero__meta { display: grid; gap: 10px; }
    .contact-intro-item { padding: 18px 15px; border-right: 0; border-bottom: 1px solid rgba(255, 255, 255, .14); }
    .contact-intro-item:last-child { border-bottom: 0; }
    .contact-form { padding: 25px 20px; }
    .contact-callout__inner { display: block; }
    .contact-callout__button { display: inline-block; margin-top: 24px; border-radius: 0; }
    .contact-map { height: 380px; }
}
</style>