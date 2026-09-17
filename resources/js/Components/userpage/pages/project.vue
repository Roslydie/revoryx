<template>
    <main class="project-page">
        <section class="project-hero" style="background-image: url('/assets/images/slider/hero-bg.jpg');">
            
            <div class="container project-hero__content">
                <p class="project-kicker">REVORYX &amp; PARTNERS / WORK</p>
                <h1>{{ _e(`project.vue_1789432983524_238`, `Work shaped around real opportunities`) }} </h1>
                <p> {{ _e(`project.vue_1789432983525_239`, `Explore selected projects where strategy, design and technology come together to create useful progress.`) }} </p>
                <div class="project-hero__meta">
                    <span><i class="fa fa-check-circle"></i> {{ _e(`project.vue_1789432983525_240`, `Strategy-led`) }}</span>
                    <span><i class="fa fa-check-circle"></i> {{ _e(`project.vue_1789432983525_241`, `Built with care`) }}</span>
                    <span><i class="fa fa-check-circle"></i> {{ _e(`project.vue_1789432983525_242`, `Designed to evolve`) }}</span>
                </div>
            </div>
        </section>

        <section class="project-intro-band">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 project-intro-point">
                        <span class="project-intro-point__number">01</span>
                        <div><h3> {{ _e(`project.vue_1789432983525_243`, `Understand the opportunity`) }} </h3><p> {{ _e(`project.vue_1789432983525_244`, `Every project starts with the context, the challenge and the outcome that matters.`) }} </p></div>
                    </div>
                    <div class="col-lg-4 project-intro-point">
                        <span class="project-intro-point__number">02</span>
                        <div><h3> {{ _e(`project.vue_1789432983525_245`, `Shape the right solution`) }} </h3><p> {{ _e(`project.vue_1789432983525_246`, `We connect strategy, design and technology around a focused, useful direction.`) }} </p></div>
                    </div>
                    <div class="col-lg-4 project-intro-point">
                        <span class="project-intro-point__number">03</span>
                        <div><h3> {{ _e(`project.vue_1789432983525_247`, `Deliver lasting value`) }} </h3><p> {{ _e(`project.vue_1789432983525_248`, `The work is built to perform today and create room for what comes next.`) }} </p></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="case_study_area project-list section-space" id="portfolio">
            <div class="container-fluid">
                <div class="row project-section-header">
                    <div class="col-lg-9">
                        <div class="section_title text_center">
                            <div class="section_sub_title uppercase"><h6> {{ _e(`project.vue_1789432983525_249`, `FEATURED CASES`) }} </h6></div>
                            <div class="section_main_title">
                                <h1> {{ _e(`project.vue_1789432983525_250`, `Our Latest Case Study`) }} </h1>
                                <h1> {{ _e(`project.vue_1789432983525_251`, `For Your Business`) }} </h1>
                            </div>
                        </div>
                    </div>
                   
                </div>

                <div v-if="loading" class="project-state"> {{ _e(`project.vue_1789432983525_252`, `Loading projects...`) }} </div>
                <div v-else-if="errorMessage" class="project-state project-state--error">{{ errorMessage }}</div>
                <div v-else-if="!projects.length" class="project-state"> {{ _e(`project.vue_1789432983525_253`, `No published projects are available yet.`) }} </div>
                <div v-else id="published-projects" class="project-grid">
                    <article v-for="(project, index) in projects" :key="project.id" class="single_case_study project-card" :style="{ '--card-delay': `${index * 80}ms` }">
                        <div class="single_case_study_inner project-card__inner">
                            <div class="single_case_study_thumb project-card__thumb">
                                <router-link :to="`/project/${project.slug}`">
                                    <img :src="imageUrl(project.images?.[0])" :alt="project.title">
                                    <span class="project-card__veil"></span>
                                    <span class="project-card__view"><i class="fa fa-long-arrow-right"></i> View case</span>
                                </router-link>
                            </div>
                        </div>
                        <div class="single_case_study_content project-card__content">
                            <div class="single_case_study_content_inner">
                                <h2><router-link :to="`/project/${project.slug}`">{{ project.title }}</router-link></h2>
                                <span>{{ project.category?.name || 'Digital solution' }} <b v-if="project.client">/ {{ project.client }}</b></span>
                            </div>
                        </div>
                    </article>
                </div>

                <nav v-if="pagination && pagination.last_page > 1" class="project-pagination" aria-label="Project pagination">
                    <button v-for="page in pagination.last_page" :key="page" type="button" :class="{ active: page === pagination.current_page }" @click="loadProjects(page)">{{ page }}</button>
                </nav>
            </div>
        </section>

        <section class="project-cta">
            <div class="container project-cta__inner">
                <div>
                    <p class="project-kicker"> {{ _e(`project.vue_1789432983525_254`, `YOUR NEXT CHAPTER`) }} </p>
                    <h2> {{ _e(`project.vue_1789432983525_255`, `Have an opportunity worth building?`) }} </h2>
                    <p> {{ _e(`project.vue_1789432983525_256`, `Bring us the challenge. We will help you find a clear and valuable way forward.`) }} </p>
                </div>
                <router-link to="/contact" class="project-cta__button"> {{ _e(`project.vue_1789432983525_257`, `Start a conversation`) }} <i class="fa fa-long-arrow-right"></i></router-link>
            </div>
        </section>
    </main>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import { getData } from '../../plugins/axios.js';

const projects = ref([]);
const pagination = ref(null);
const loading = ref(true);
const errorMessage = ref('');
const featuredProject = computed(() => projects.value[0] ?? null);

const imageUrl = (path) => path?.startsWith('http') ? path : `/storage/${path}`;

const loadProjects = async (page = 1) => {
    loading.value = true;
    errorMessage.value = '';
    try {
        const response = await getData(`/public/projects?page=${page}`);
        projects.value = response.data?.data ?? [];
        pagination.value = response.data ?? null;
    } catch (error) {
        errorMessage.value = 'We could not load the projects right now. Please try again shortly.';
    } finally {
        loading.value = false;
    }
};

onMounted(() => loadProjects());
</script>

<style scoped>
.project-page { color: #616161; }
.section-space { padding: 90px 0; }
.project-hero { position: relative; min-height: 560px; display: flex; align-items: center; overflow: hidden; background-color: #061d43; background-position: center; background-size: cover; animation: projectHeroDrift 18s ease-in-out infinite alternate; }
.project-hero__content { position: relative; z-index: 1; color: #fff; }
.project-kicker { margin: 0 0 13px; color: #0c5adb; font-size: 13px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; }
.project-hero .project-kicker { color: #65d5a0; }
.project-hero h1 { max-width: 800px; margin: 0 0 20px; color: #fff; font-size: clamp(40px, 6vw, 74px); line-height: 1.08; }
.project-hero p:not(.project-kicker) { max-width: 620px; margin: 0; color: rgba(255, 255, 255, .84); font-size: 19px; }
.project-hero__meta { display: flex; flex-wrap: wrap; gap: 24px; margin-top: 30px; color: rgba(255, 255, 255, .9); font-size: 14px; font-weight: 700; }
.project-hero__meta i { margin-right: 6px; color: #65d5a0; }
.project-intro-band { padding: 48px 0; color: #fff; background: #061d43; }
.project-intro-point { display: flex; gap: 16px; align-items: flex-start; padding: 12px 28px; border-right: 1px solid rgba(255, 255, 255, .14); }
.project-intro-point:last-child { border-right: 0; }
.project-intro-point__number { color: #65d5a0; font-size: 24px; font-weight: 700; line-height: 1; }
.project-intro-point h3 { margin: 0 0 7px; color: #fff; font-size: 18px; }
.project-intro-point p { margin: 0; color: rgba(255, 255, 255, .68); font-size: 14px; line-height: 1.7; }
.project-list { background: #fff; }
.project-section-header { align-items: center; justify-content: center; margin: 0 0 42px; text-align: center; }
.project-section-header > div { width: 100%; }
.project-section-header .section_title { text-align: center; }
.project-intro { max-width: 620px; margin: 22px 0 0; color: #6a7484; line-height: 1.8; }
.project-section-action { display: flex; justify-content: flex-end; margin-top: 34px; }
.project-template-button { display: inline-block; padding: 13px 25px; color: #fff; background: #0c5adb; font-weight: 700; transition: transform .3s ease, background .3s ease; }
.project-template-button:hover { color: #fff; background: #00247e; transform: translateY(-3px); }
.project-template-button i { margin-left: 8px; }
.project-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 24px; }
.project-card { overflow: hidden; background: #fff; animation: projectReveal .7s ease var(--card-delay) both; transition: transform .35s ease, box-shadow .35s ease; }
.project-card:hover { box-shadow: 0 18px 35px rgba(12, 90, 219, .16); transform: translateY(-8px); }
.project-card__thumb { position: relative; height: 280px; overflow: hidden; }
.project-card__thumb a { display: block; width: 100%; height: 100%; }
.project-card__thumb img { width: 100%; height: 100%; object-fit: cover; transition: transform .8s cubic-bezier(.2,.8,.2,1), filter .5s ease; }
.project-card__veil { position: absolute; inset: 0; display: block; background: linear-gradient(145deg, rgba(6, 29, 67, .12), rgba(12, 90, 219, .9)); opacity: 0; transform: translateY(25%); transition: opacity .45s ease, transform .55s ease; }
.project-card__view { position: absolute; right: 24px; bottom: 22px; display: flex; align-items: center; gap: 9px; color: #fff; font-size: 14px; font-weight: 700; opacity: 0; transform: translateY(16px); transition: opacity .35s ease .08s, transform .45s ease .08s; }
.project-card__view i { display: grid; place-items: center; width: 38px; height: 38px; border: 1px solid rgba(255, 255, 255, .7); border-radius: 50%; }
.project-card:hover .project-card__thumb img { transform: scale(1.08); }
.project-card:hover .project-card__thumb img { filter: saturate(1.15) contrast(1.05); }
.project-card:hover .project-card__veil, .project-card:hover .project-card__view { opacity: 1; transform: translateY(0); }
.project-card__content { padding: 18px 4px 8px; }
.project-card__content h2 { margin: 0 0 6px; font-size: 21px; line-height: 1.25; }
.project-card__content h2 a { color: #232323; }
.project-card__content h2 a:hover { color: #0c5adb; }
.project-card__content span { color: #0c5adb; font-size: 14px; }
.project-card__content b { color: #7d8ba0; font-weight: 400; }
.project-state { padding: 70px 20px; text-align: center; color: #6a7484; }
.project-state--error { color: #b42318; }
.project-pagination { display: flex; justify-content: center; gap: 8px; margin-top: 36px; }
.project-pagination button { width: 38px; height: 38px; border: 1px solid #dce5f1; color: #0c5adb; background: #fff; cursor: pointer; }
.project-pagination button.active, .project-pagination button:hover { color: #fff; background: #0c5adb; }
.project-cta { padding: 82px 0; color: #fff; background: linear-gradient(110deg, #061d43, #0c5adb); }
.project-cta__inner { display: flex; align-items: center; justify-content: space-between; gap: 32px; }
.project-cta .project-kicker { color: #65d5a0; }
.project-cta h2 { margin: 0 0 12px; color: #fff; font-size: clamp(28px, 4vw, 42px); line-height: 1.2; }
.project-cta p:not(.project-kicker) { margin: 0; color: rgba(255, 255, 255, .78); line-height: 1.8; }
.project-cta__button { flex: 0 0 auto; padding: 14px 24px; color: #fff; background: #0c5adb; font-weight: 700; transition: background .25s ease, transform .25s ease; }
.project-cta__button:hover { color: #061d43; background: #fff; transform: translateY(-3px); }
.project-cta__button i { margin-left: 8px; }
@keyframes projectReveal { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }
@keyframes projectHeroDrift { from { background-position: 46% 50%; } to { background-position: 54% 50%; } }
@media (max-width: 1199px) { .project-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
@media (max-width: 767px) { .project-hero { min-height: 480px; } .section-space { padding: 70px 0; } .project-hero__meta { display: grid; gap: 10px; } .project-intro-point { padding: 18px 15px; border-right: 0; border-bottom: 1px solid rgba(255, 255, 255, .14); } .project-intro-point:last-child { border-bottom: 0; } .project-section-action { justify-content: flex-start; margin-top: 24px; } .project-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px; } .project-card__thumb { height: 220px; } .project-cta__inner { display: block; } .project-cta__button { display: inline-block; margin-top: 24px; } }
@media (max-width: 480px) { .project-grid { grid-template-columns: 1fr; } }
</style>