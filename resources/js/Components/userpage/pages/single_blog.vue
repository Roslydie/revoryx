<template>
    <main class="single-blog-page">
        <div v-if="loading" class="single-blog-state">{{ _e(`single_blog.vue_1789431937013_247`, `Loading article...`) }}</div>
        <div v-else-if="errorMessage" class="single-blog-state single-blog-state--error">{{ errorMessage }}</div>
        <template v-else-if="blog">
            <section class="single-blog-hero">
                <div class="single-blog-hero__background" :style="{ backgroundImage: `url('${imageUrl(blog.image)}')` }"></div>
                <div class="single-blog-hero__overlay"></div>
                <div class="container single-blog-hero__content">
                    <router-link to="/blog" class="single-blog__back"><i class="fa fa-long-arrow-left"></i> {{ _e(`single_blog.vue_1789431937013_248`, `All articles`) }}</router-link>
                    <div class="single-blog__tags">
                        <span v-for="tag in blog.tags || []" :key="tag.id">#{{ tag.name }}</span>
                    </div>
                    <p class="single-blog-kicker">REVORYX &amp; PARTNERS / INSIGHTS</p>
                    <h1>{{ blog.title }}</h1>
                    <div class="single-blog__meta">
                        <span>{{ formatDate(blog.created_at) }}</span>
                        <span v-if="blog.user">By {{ blog.user.full_name }}</span>
                    </div>
                </div>
            </section>

            <section class="single-blog-body section-space">
                <div class="container">
                    <div class="row align-items-start">
                        <article class="col-lg-8 single-blog-article">
                            <p class="single-blog-lead">{{ blog.description }}</p>
                            <div class="single-blog-rule"></div>
                            <div class="single-blog-content" v-html="blog.content"></div>
                            <router-link to="/contact" class="single-blog__contact"> {{ _e(`single_blog.vue_1789431937013_249`, `Discuss your next project`) }} <i class="fa fa-long-arrow-right"></i></router-link>
                        </article>
                        <aside class="col-lg-4 mt-5 mt-lg-0">
                            <div class="single-blog-sidebar">
                                <div v-if="blog.tags?.length" class="single-blog-tag-panel">
                                    <p class="single-blog-kicker"> {{ _e(`single_blog.vue_1789431937013_250`, `ARTICLE TAGS`) }} </p>
                                    <div class="single-blog-tag-list">
                                        <span v-for="tag in blog.tags" :key="tag.id">#{{ tag.name }}</span>
                                    </div>
                                </div>
                                <p class="single-blog-kicker"> {{ _e(`single_blog.vue_1789431937013_251`, `KEEP EXPLORING`) }} </p>
                                <h2>More from our journal</h2>
                                <router-link v-for="recentBlog in recentBlogs" :key="recentBlog.id" :to="`/blog/${recentBlog.slug}`" class="recent-blog">
                                    <img :src="imageUrl(recentBlog.image)" :alt="recentBlog.title">
                                    <span><small>{{ formatDate(recentBlog.created_at) }}</small><strong>{{ recentBlog.title }}</strong></span>
                                </router-link>
                                <router-link to="/blog" class="single-blog-sidebar__link"> {{ _e(`single_blog.vue_1789431937013_252`, `View all articles`) }} <i class="fa fa-long-arrow-right"></i></router-link>
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
const blog = ref(null);
const recentBlogs = ref([]);
const loading = ref(true);
const errorMessage = ref('');

const imageUrl = (path) => path?.startsWith('http') ? path : `/storage/${path}`;

const formatDate = (date) => date
    ? new Intl.DateTimeFormat('en-US', { month: 'long', day: 'numeric', year: 'numeric' }).format(new Date(date))
    : 'Recent article';

const loadBlog = async (slug) => {
    loading.value = true;
    errorMessage.value = '';
    blog.value = null;
    try {
        const response = await getData(`/public/blogs/${encodeURIComponent(slug)}`);
        blog.value = response.data ?? null;
        recentBlogs.value = response.recent ?? [];
        if (!blog.value) errorMessage.value = 'This article could not be found.';
    } catch (error) {
        errorMessage.value = error.response?.status === 404
            ? 'This article could not be found.'
            : 'We could not load this article right now. Please try again shortly.';
    } finally {
        loading.value = false;
    }
};

watch(() => route.params.slug, (slug) => {
    if (slug) loadBlog(slug);
});

onMounted(() => loadBlog(route.params.slug));
</script>

<style scoped>
.single-blog-page { color: #616161; }
.section-space { padding: 95px 0; }
.single-blog-hero { position: relative; min-height: 590px; display: flex; align-items: end; overflow: hidden; color: #fff; }
.single-blog-hero__background, .single-blog-hero__overlay { position: absolute; inset: 0; }
.single-blog-hero__background { background-position: center; background-size: cover; animation: singleBlogZoom 14s ease-out both; }
.single-blog-hero__overlay { background: linear-gradient(90deg, rgba(3, 25, 65, .92), rgba(3, 25, 65, .24)); }
.single-blog-hero__content { position: relative; z-index: 1; width: 100%; padding-bottom: 82px; animation: singleBlogReveal .8s ease both; }
.single-blog__back { display: inline-block; margin-bottom: 36px; color: #fff; font-weight: 700; }
.single-blog__back i { margin-right: 8px; }
.single-blog__tags { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 18px; }
.single-blog__tags span { padding: 5px 10px; border: 1px solid rgba(255, 255, 255, .35); color: #fff; font-size: 12px; }
.single-blog-kicker { margin: 0 0 12px; color: #65d5a0; font-size: 13px; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; }
.single-blog-hero h1 { max-width: 900px; margin: 0 0 22px; color: #fff; font-size: clamp(38px, 6vw, 70px); line-height: 1.08; }
.single-blog__meta { display: flex; flex-wrap: wrap; gap: 22px; color: rgba(255, 255, 255, .8); font-size: 14px; }
.single-blog-body { background: #fff; }
.single-blog-article { max-width: 850px; }
.single-blog-lead { margin: 0; color: #232323; font-size: 23px; line-height: 1.65; }
.single-blog-rule { width: 55px; height: 4px; margin: 28px 0; background: #0B2545; }
.single-blog-content { color: #616161; font-size: 17px; line-height: 1.9; }
.single-blog-content :deep(h2), .single-blog-content :deep(h3) { margin: 34px 0 12px; color: #232323; line-height: 1.3; }
.single-blog-content :deep(p) { margin: 0 0 22px; }
.single-blog-content :deep(img) { max-width: 100%; height: auto; }
.single-blog__contact { display: inline-block; margin-top: 22px; padding: 14px 22px; color: #fff; background: #0B2545; font-weight: 700; }
.single-blog__contact:hover { color: #fff; background: #00247e; }
.single-blog__contact i { margin-left: 8px; }
.single-blog-sidebar { padding: 32px 28px; border-top: 4px solid #0B2545; background: #f7faff; }
.single-blog-tag-panel { padding-bottom: 24px; margin-bottom: 24px; border-bottom: 1px solid #dce5f1; }
.single-blog-tag-list { display: flex; flex-wrap: wrap; gap: 8px; }
.single-blog-tag-list span { padding: 6px 10px; border: 1px solid #c9d9ee; color: #0B2545; background: #fff; font-size: 12px; font-weight: 700; }
.single-blog-sidebar h2 { margin: 0 0 24px; color: #232323; font-size: 28px; line-height: 1.25; }
.recent-blog { display: flex; gap: 14px; padding: 16px 0; border-top: 1px solid #dce5f1; }
.recent-blog img { flex: 0 0 76px; width: 76px; height: 64px; object-fit: cover; }
.recent-blog small, .recent-blog strong { display: block; }
.recent-blog small { margin-bottom: 4px; color: #0B2545; font-size: 11px; }
.recent-blog strong { color: #232323; font-size: 15px; line-height: 1.4; }
.recent-blog:hover strong { color: #0B2545; }
.single-blog-sidebar__link { display: inline-block; margin-top: 16px; color: #0B2545; font-weight: 700; }
.single-blog-sidebar__link i { margin-left: 7px; }
.single-blog-state { min-height: 500px; display: grid; place-items: center; padding: 40px; color: #6a7484; text-align: center; }
.single-blog-state--error { color: #b42318; }
@keyframes singleBlogReveal { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }
@keyframes singleBlogZoom { from { transform: scale(1.06); } to { transform: scale(1); } }
@media (max-width: 767px) { .single-blog-hero { min-height: 520px; } .single-blog-hero__content { padding-bottom: 58px; } .section-space { padding: 70px 0; } .single-blog-lead { font-size: 20px; } }
</style>