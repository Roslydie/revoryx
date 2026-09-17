<template>
    <main class="blog-page">
        <section class="blog-hero" style="background-image: url('/assets/images/slider/slider-4.jpg');">
            <div class="blog-hero__overlay"></div>
            <div class="container blog-hero__content">
                <p class="blog-kicker">REVORYX &amp; PARTNERS / INSIGHTS</p>
                <h1>{{ _e(`blog.vue_1789431937005_101`, `Ideas for building what comes next`) }}</h1>
                <p> {{ _e(`blog.vue_1789431937005_102`, `Practical perspectives on technology, digital growth and the decisions that move organizations forward.`) }} </p>
            </div>
        </section>

        <section class="blog_area blog-list section-space">
            <div class="container">
                <div class="row blog-section-header">
                    <div class="col-lg-9">
                        <div class="section_title text_center">
                            <div class="section_sub_title uppercase">
                                <h6>LATEST ARTICLE</h6>
                            </div>
                            <div class="section_main_title">
                                <h1>{{ _e(`blog.vue_1789431937005_103`, `See Our Latest`) }}</h1>
                                <h1>{{ _e(`blog.vue_1789431937005_104`, `Blog Posts`) }}</h1>
                            </div>
                        </div>
                    </div>
                   
                </div>

                <div v-if="loading" class="blog-state">Loading articles...</div>
                <div v-else-if="errorMessage" class="blog-state blog-state--error">{{ errorMessage }}</div>
                <div v-else-if="!blogs.length" class="blog-state">{{ _e(`blog.vue_1789431937005_105`, `No published articles are available yet.`) }}</div>
                <div v-else id="published-articles" class="row">
                    <div v-for="(blog, index) in blogs" :key="blog.id" class="col-lg-4 col-md-6 mb-4">
                        <article class="single_blog blog-card" :style="{ '--card-delay': `${index * 80}ms` }">
                            <div class="single_blog_thumb blog-card__image-wrap">
                                <router-link :to="`/blog/${blog.slug}`">
                                    <img :src="imageUrl(blog.image)" :alt="blog.title" class="blog-card__image">
                                </router-link>
                                <router-link :to="`/blog/${blog.slug}`" class="blog-card__arrow"><i class="fa fa-long-arrow-right"></i></router-link>
                            </div>
                            <div class="single_blog_content blog-card__body">
                                <div class="techno_blog_meta blog-card__meta">
                                    <span>{{ blog.tags?.[0]?.name || 'Insights' }}</span>
                                    <span class="meta-date">{{ formatDate(blog.created_at) }}</span>
                                </div>
                                <div class="blog_page_title">
                                    <h3><router-link :to="`/blog/${blog.slug}`">{{ blog.title }}</router-link></h3>
                                </div>
                                <div class="blog_description">
                                    <p>{{ blog.description }}</p>
                                </div>
                                <div class="blog_page_button">
                                    <router-link :to="`/blog/${blog.slug}`" class="blog-card__link">Read More <i class="fa fa-long-arrow-right"></i></router-link>
                                </div>
                            </div>
                        </article>
                    </div>
                </div>

                <nav v-if="pagination && pagination.last_page > 1" class="blog-pagination" aria-label="Blog pagination">
                    <button v-for="page in pagination.last_page" :key="page" type="button" :class="{ active: page === pagination.current_page }" @click="loadBlogs(page)">
                        {{ page }}
                    </button>
                </nav>
            </div>
        </section>

        <section class="blog-cta">
            <div class="container blog-cta__inner">
                <div>
                    <p class="blog-kicker"> {{ _e(`blog.vue_1789431937005_106`, `LET'S BUILD WITH PURPOSE`) }} </p>
                    <h2> {{ _e(`blog.vue_1789431937006_107`, `Have a challenge worth exploring?`) }} </h2>
                    <p> {{ _e(`blog.vue_1789431937006_108`, `Start a conversation and turn a good idea into a clear next step.`) }} </p>
                </div>
                <router-link to="/contact" class="blog-cta__button"> {{ _e(`blog.vue_1789431937006_109`, `Talk to our team`) }} <i class="fa fa-long-arrow-right"></i></router-link>
            </div>
        </section>
    </main>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue';
import { getData } from '../../plugins/axios.js';

const blogs = ref([]);
const pagination = ref(null);
const loading = ref(true);
const errorMessage = ref('');
const featuredBlog = computed(() => blogs.value[0] ?? null);

const imageUrl = (path) => path?.startsWith('http') ? path : `/storage/${path}`;

const formatDate = (date) => date
    ? new Intl.DateTimeFormat('en-US', { month: 'long', day: 'numeric', year: 'numeric' }).format(new Date(date))
    : 'Recent article';

const loadBlogs = async (page = 1) => {
    loading.value = true;
    errorMessage.value = '';
    try {
        const response = await getData(`/public/blogs?page=${page}`);
        blogs.value = response.data?.data ?? [];
        pagination.value = response.data ?? null;
    } catch (error) {
        errorMessage.value = 'We could not load the articles right now. Please try again shortly.';
    } finally {
        loading.value = false;
    }
};

onMounted(() => loadBlogs());
</script>

<style scoped>
.blog-page { color: #616161; }
.section-space { padding: 90px 0; }
.blog-hero { position: relative; min-height: 500px; display: flex; align-items: center; background-color: #061d43; background-position: center; background-size: cover; }
.blog-hero__overlay { position: absolute; inset: 0; background: linear-gradient(90deg, rgba(3, 25, 65, .94), rgba(12, 90, 219, .42)); }
.blog-hero__content { position: relative; z-index: 1; color: #fff; }
.blog-kicker { margin: 0 0 13px; color: #0c5adb; font-size: 13px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; }
.blog-hero .blog-kicker, .blog-cta .blog-kicker { color: #65d5a0; }
.blog-hero h1 { max-width: 780px; margin: 0 0 20px; color: #fff; font-size: clamp(40px, 6vw, 74px); line-height: 1.08; }
.blog-hero p:not(.blog-kicker) { max-width: 620px; margin: 0; color: rgba(255, 255, 255, .84); font-size: 19px; }
.blog-list { background: #fff; }
.blog-section-header { align-items: center; justify-content: center; margin-bottom: 42px; text-align: center; }
.blog-section-header > div { width: 100%; }
.blog-section-header .section_title { text-align: center; }
.blog-section-intro { max-width: 630px; margin: 22px 0 0; color: #6a7484; line-height: 1.8; }
.blog-section-action { display: flex; justify-content: flex-end; margin-top: 34px; }
.blog-template-button { display: inline-block; padding: 13px 25px; color: #fff; background: #0c5adb; font-weight: 700; transition: transform .3s ease, background .3s ease; }
.blog-template-button:hover { color: #fff; background: #00247e; transform: translateY(-3px); }
.blog-template-button i { margin-left: 8px; }
.blog-card { height: 100%; overflow: hidden; background: #fff; box-shadow: 0 8px 25px rgba(8, 35, 80, .07); animation: blogCardReveal .7s ease var(--card-delay) both; transition: box-shadow .35s ease, transform .35s ease; }
.blog-card:hover { box-shadow: 0 18px 35px rgba(12, 90, 219, .16); transform: translateY(-8px); }
.blog-card__image-wrap { position: relative; display: block; height: 230px; overflow: hidden; padding-bottom: 0 !important; }
.blog-card__image { width: 100%; height: 100%; object-fit: cover; transition: transform .6s ease; }
.blog-card:hover .blog-card__image { transform: scale(1.07); }
.blog-card__arrow { position: absolute; right: 20px; bottom: 18px; display: grid; place-items: center; width: 42px; height: 42px; border-radius: 50%; color: #fff; background: #0c5adb; opacity: 0; transform: translateY(10px); transition: opacity .3s ease, transform .3s ease; }
.blog-card:hover .blog-card__arrow { opacity: 1; transform: translateY(0); }
.blog-card__body { min-height: 265px; padding: 20px 28px 26px; }
.blog-card__meta { display: flex; justify-content: space-between; gap: 10px; margin-bottom: 14px; color: #0c5adb; font-size: 12px; font-weight: 700; }
.blog-card__meta .meta-date { padding-left: 12px; color: #7d8ba0; font-weight: 400; }
.blog-card h3 { margin: 0 0 12px; font-size: 22px; line-height: 1.3; }
.blog-card h3 a { color: #232323; }
.blog-card h3 a:hover { color: #0c5adb; }
.blog-card__body .blog_description p { min-height: 78px; margin: 0 0 20px; line-height: 1.75; }
.blog-card__link { color: #0c5adb; font-weight: 700; }
.blog-card__link i { margin-left: 7px; transition: margin-left .25s ease; }
.blog-card__link:hover { color: #00247e; }
.blog-card__link:hover i { margin-left: 12px; }
.blog-state { padding: 70px 20px; text-align: center; color: #6a7484; }
.blog-state--error { color: #b42318; }
.blog-pagination { display: flex; justify-content: center; gap: 8px; margin-top: 28px; }
.blog-pagination button { width: 38px; height: 38px; border: 1px solid #dce5f1; color: #0c5adb; background: #fff; cursor: pointer; }
.blog-pagination button.active, .blog-pagination button:hover { color: #fff; background: #0c5adb; }
.blog-cta { padding: 78px 0; color: #fff; background: linear-gradient(110deg, #061d43, #0c5adb); }
.blog-cta__inner { display: flex; align-items: center; justify-content: space-between; gap: 30px; }
.blog-cta h2 { margin: 0 0 12px; color: #fff; font-size: clamp(28px, 4vw, 42px); }
.blog-cta p:not(.blog-kicker) { margin: 0; color: rgba(255, 255, 255, .78); }
.blog-cta__button { flex: 0 0 auto; padding: 14px 24px; color: #fff; background: #0c5adb; font-weight: 700; }
.blog-cta__button:hover { color: #061d43; background: #fff; }
.blog-cta__button i { margin-left: 8px; }
@keyframes blogCardReveal { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }
@media (max-width: 767px) { .blog-hero { min-height: 440px; } .section-space { padding: 70px 0; } .blog-section-action, .blog-cta__inner { display: block; } .blog-section-action { margin-top: 24px; justify-content: flex-start; } .blog-cta__button { display: inline-block; margin-top: 24px; } }
</style>