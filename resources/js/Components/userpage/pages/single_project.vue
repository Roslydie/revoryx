<template>
    <main class="single-project-page">
        <div v-if="loading" class="project-state"> {{ _e(`single_project.vue_1789432983527_273`, `Loading project...`) }} </div>
        <div v-else-if="errorMessage" class="project-state project-state--error">{{ errorMessage }}</div>
        <template v-else-if="project">
            <section class="single-project-hero">
                <div class="single-project-hero__background" :style="{ backgroundImage: `url('${imageUrl(project.images?.[0])}')` }"></div>
                <div class="single-project-hero__overlay"></div>
                <div class="container single-project-hero__content">
                    <router-link to="/project" class="single-project__back"><i class="fa fa-long-arrow-left"></i> All projects</router-link>
                    <p class="project-kicker">FEATURED CASE / {{ project.category?.name || 'DIGITAL SOLUTION' }}</p>
                    <h1>{{ project.title }}</h1>
                    <p>{{ project.description }}</p>
                </div>
            </section>

            <section class="single-project-body section-space">
                <div class="container">
                    <div class="row align-items-start">
                        <article class="col-lg-8 project-article">
                            <p class="project-kicker"> {{ _e(`single_project.vue_1789432983527_274`, `THE WORK`) }} </p>
                            <h2> {{ _e(`single_project.vue_1789432983527_275`, `A solution built around the opportunity`) }} </h2>
                            <div class="project-rule"></div>
                            <div class="project-content" v-html="project.content"></div>
                            <router-link to="/contact" class="project-contact"> {{ _e(`single_project.vue_1789432983527_276`, `Discuss your next project`) }} <i class="fa fa-long-arrow-right"></i></router-link>
                        </article>
                        <aside class="col-lg-4 mt-5 mt-lg-0">
                            <div class="project-facts">
                                <p class="project-kicker"> {{ _e(`single_project.vue_1789432983527_277`, `PROJECT DETAILS`) }} </p>
                                <div><small> {{ _e(`single_project.vue_1789432983527_278`, `Client`) }} </small><strong>{{ project.client || 'Private client' }}</strong></div>
                                <div><small> {{ _e(`single_project.vue_1789432983527_279`, `Location`) }} </small><strong>{{ project.location || 'United States' }}</strong></div>
                                <div><small> {{ _e(`single_project.vue_1789432983527_280`, `Year`) }} </small><strong>{{ project.years }}</strong></div>
                                <div><small> {{ _e(`single_project.vue_1789432983527_281`, `Category`) }} </small><strong>{{ project.category?.name || 'Digital solution' }}</strong></div>
                            </div>
                        </aside>
                    </div>
                </div>
            </section>

         
        </template>
    </main>
</template>

<script setup>
import { onMounted, ref, watch } from 'vue';
import { useRoute } from 'vue-router';
import { getData } from '../../plugins/axios.js';

const route = useRoute();
const project = ref(null);
const loading = ref(true);
const errorMessage = ref('');

const imageUrl = (path) => path?.startsWith('http') ? path : `/storage/${path}`;

const loadProject = async (slug) => {
    loading.value = true;
    errorMessage.value = '';
    project.value = null;
    try {
        const response = await getData(`/public/projects/${encodeURIComponent(slug)}`);
        project.value = response.data ?? null;
        if (!project.value) errorMessage.value = 'This project could not be found.';
    } catch (error) {
        errorMessage.value = error.response?.status === 404
            ? 'This project could not be found.'
            : 'We could not load this project right now. Please try again shortly.';
    } finally {
        loading.value = false;
    }
};

watch(() => route.params.slug, (slug) => {
    if (slug) loadProject(slug);
});

onMounted(() => loadProject(route.params.slug));
</script>

<style scoped>
.single-project-page { color: #616161; }
.section-space { padding: 95px 0; }
.single-project-hero { position: relative; min-height: 590px; display: flex; align-items: end; overflow: hidden; color: #fff; }
.single-project-hero__background, .single-project-hero__overlay { position: absolute; inset: 0; }
.single-project-hero__background { background-position: center; background-size: cover; animation: projectZoom 14s ease-out both; }
.single-project-hero__overlay { background: linear-gradient(90deg, rgba(3, 25, 65, .92), rgba(3, 25, 65, .22)); }
.single-project-hero__content { position: relative; z-index: 1; width: 100%; padding-bottom: 82px; animation: projectReveal .8s ease both; }
.single-project__back { display: inline-block; margin-bottom: 38px; color: #fff; font-weight: 700; }
.single-project__back i { margin-right: 8px; }
.project-kicker { margin: 0 0 13px; color: #0B2545; font-size: 13px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; }
.single-project-hero .project-kicker { color: #65d5a0; }
.single-project-hero h1 { max-width: 900px; margin: 0 0 18px; color: #fff; font-size: clamp(40px, 6vw, 72px); line-height: 1.08; }
.single-project-hero p:not(.project-kicker) { max-width: 700px; margin: 0; color: rgba(255, 255, 255, .82); font-size: 19px; line-height: 1.7; }
.single-project-body { background: #fff; }
.project-article h2 { margin: 0 0 18px; color: #232323; font-size: clamp(30px, 4vw, 45px); line-height: 1.2; }
.project-rule { width: 55px; height: 4px; margin: 24px 0; background: #0B2545; }
.project-content { font-size: 17px; line-height: 1.9; }
.project-content :deep(p) { margin: 0 0 22px; }
.project-content :deep(h2), .project-content :deep(h3) { margin: 34px 0 12px; color: #232323; line-height: 1.3; }
.project-content :deep(img) { max-width: 100%; height: auto; }
.project-contact { display: inline-block; margin-top: 20px; padding: 14px 22px; color: #fff; background: #0B2545; font-weight: 700; }
.project-contact:hover { color: #fff; background: #00247e; }
.project-contact i { margin-left: 8px; }
.project-facts { padding: 32px 28px; border-top: 4px solid #0B2545; background: #f7faff; }
.project-facts > div { padding: 16px 0; border-top: 1px solid #dce5f1; }
.project-facts small, .project-facts strong { display: block; }
.project-facts small { margin-bottom: 4px; color: #0B2545; font-size: 12px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; }
.project-facts strong { color: #232323; font-size: 17px; }
.project-gallery-section { padding: 90px 0; background: #f7faff; }
.project-gallery { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 24px; }
.project-gallery__item { display: block; height: 290px; overflow: hidden; }
.project-gallery__item--wide { grid-column: span 2; height: 420px; }
.project-gallery__item img { width: 100%; height: 100%; object-fit: cover; transition: transform .6s ease; }
.project-gallery__item:hover img { transform: scale(1.05); }
.project-state { min-height: 500px; display: grid; place-items: center; padding: 40px; text-align: center; color: #6a7484; }
.project-state--error { color: #b42318; }
@keyframes projectReveal { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }
@keyframes projectZoom { from { transform: scale(1.06); } to { transform: scale(1); } }
@media (max-width: 767px) { .single-project-hero { min-height: 520px; } .single-project-hero__content { padding-bottom: 58px; } .section-space, .project-gallery-section { padding: 70px 0; } .project-gallery { grid-template-columns: 1fr; } .project-gallery__item, .project-gallery__item--wide { grid-column: auto; height: 280px; } }
</style>