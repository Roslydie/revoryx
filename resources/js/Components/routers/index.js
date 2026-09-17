
import { createRouter, createWebHistory } from 'vue-router';

// =========================
// USER PAGES
// =========================
import ContentWrapper from '../userpage/layouts/contentWrapper.vue';
import UserHome from '../userpage/pages/home.vue';
import UserBlog from '../userpage/pages/blog.vue';
import About from '../userpage/pages/about.vue';
import Service from '../userpage/pages/service.vue';
import SingleService from '../userpage/pages/single_service.vue';
import Contact from '../userpage/pages/contact.vue';
import Single_blog from '../userpage/pages/single_blog.vue';
import UserProject from '../userpage/pages/project.vue';
import Single_project from '../userpage/pages/single_project.vue';
import UserPolicy from '../userpage/pages/policy.vue';
// =========================
// ADMIN PAGES
// =========================
import AdminLayout from '../adminpage/layouts/AdminLayout.vue';
import AdminHome from '../adminpage/pages/home.vue';
import Tag from '../adminpage/pages/tag.vue';
import Category from '../adminpage/pages/category.vue';
import AdminBlog from '../adminpage/pages/blog.vue';
import Users from '../adminpage/pages/users.vue';
import Role from '../adminpage/pages/role.vue';
import Login from '../adminpage/pages/login.vue';
import ForgotPassword from '../adminpage/pages/forgotpassword.vue';
import ResetPassword from '../adminpage/pages/resetpassword.vue';
import Profile from '../adminpage/pages/profile.vue';
import AdminProject from '../adminpage/pages/project.vue';
import Testimonial from '../adminpage/pages/testimonial.vue';
import AdminContact from '../adminpage/pages/contact.vue';
import Newsletter from '../adminpage/pages/newsletter.vue';



const router = createRouter({
    history: createWebHistory('/'),

    routes: [

        // =====================================================
        // PUBLIC / USER
        // =====================================================

        {
            path: '/',
            component: ContentWrapper,
            meta: {
                public: true,
            },
            children: [
                {
                    path: '',
                    name: 'user.home',
                    component: UserHome,
                },
            ],
        },
        {
            path: '/about',
            component: ContentWrapper,
            meta: {
                public: true,
            },
            children: [
                {
                    path: '',
                    name: 'user.about',
                    component: About,
                },
            ],
        },

        {
            path: '/service',
            component: ContentWrapper,
            meta: {
                public: true,
            },
            children: [
                {
                    path: '',
                    name: 'user.service',
                    component: Service,
                },
            ],
        },
        {
            path: '/service/:slug',
            component: ContentWrapper,
            meta: {
                public: true,
            },
            children: [
                {
                    path: '',
                    name: 'user.single_service',
                    component: SingleService,
                },
            ],
        },
        {
            path: '/contact',
            component: ContentWrapper,
            meta: {
                public: true,
            },
            children: [
                {
                    path: '',
                    name: 'user.contact',
                    component: Contact,
                },
            ],
        },
        {
            path: '/blog',
            component: ContentWrapper,
            meta: {
                public: true,
            },
            children: [
                {
                    path: '',
                    name: 'user.blog',
                    component: UserBlog,
                },
            ],
        },
        {
            path: '/blog/:slug',
            component: ContentWrapper,
            meta: { 
                public: true,
            },
            children: [
                {
                    path: '',
                    name: 'user.single_blog',
                    component: Single_blog,
                },
            ],
        },
        {
            path: '/project',
            component: ContentWrapper,
            meta: {
                public: true,
            },
            children: [
                {
                    path: '',
                    name: 'user.project',
                    component: UserProject,
                },
            ],
        },
        {
            path: '/project/:slug',
            component: ContentWrapper,
            meta: {
                public: true,
            },
            children: [
                {
                    path: '',
                    name: 'user.single_project',
                    component: Single_project,
                },
            ],
        },
        {
            path: '/policy',
            component: ContentWrapper,
            meta: {
                public: true,
            },
            children: [
                {
                    path: '',
                    name: 'user.policy',
                    component: UserPolicy,
                },
            ],
        },


        // =====================================================
        // ADMIN LOGIN
        // IMPORTANT : cette route est en dehors de /admin
        // =====================================================

        {
            path: '/admin/login',
            name: 'admin.login',
            component: Login,
            meta: {
                guest: true,
            },
        },
        {
            path: '/admin/forgot-password',
            name: 'admin.forgot-password',
            component: ForgotPassword,
            meta: {
                guest: true,
            },
        },
        {
            path: '/admin/reset-password',
            name: 'admin.reset-password',
            component: ResetPassword,
            meta: {
                guest: true,
            },
        },


        // =====================================================
        // ADMIN
        // Toutes les routes à l'intérieur de ce groupe
        // nécessitent une authentification
        // =====================================================

        {
            path: '/admin',
            component: AdminLayout,
            meta: {
                requiresAdminAuth: true,
            },

            children: [

                // -------------------------
                // Admin Dashboard
                // -------------------------
                {
                    path: '',
                    name: 'admin.home',
                    component: AdminHome,
                },

                // -------------------------
                // Tags
                // -------------------------
                {
                    path: 'tag',
                    name: 'admin.tag',
                    component: Tag,
                },

                // -------------------------
                // Categories
                // -------------------------
                {
                    path: 'category',
                    name: 'admin.category',
                    component: Category,
                },

                // -------------------------
                // Blog
                // -------------------------
                {
                    path: 'blog',
                    name: 'admin.blog',
                    component: AdminBlog,
                },

                // -------------------------
                // Projects
                // -------------------------
                {
                    path: 'project',
                    name: 'admin.project',
                    component: AdminProject,
                },
                {
                    path: 'contact',
                    name: 'admin.contact',
                    component: AdminContact,
                },
                {
                    path: 'newsletter',
                    name: 'admin.newsletter',
                    component: Newsletter,
                },

                // -------------------------
                // Users
                // -------------------------
                {
                    path: 'users',
                    name: 'admin.users',
                    component: Users,
                },

                // -------------------------
                // Roles
                // -------------------------
                {
                    path: 'role',
                    name: 'admin.role',
                    component: Role,
                },

                // -------------------------
                // Profile
                // -------------------------
                {
                    path: 'profile',
                    name: 'admin.profile',
                    component: Profile,
                },
                {
                    path: 'testimonial',
                    name: 'admin.testimonial',
                    component: Testimonial,
                },
            ],
        },
    ],


    // =====================================================
    // SCROLL BEHAVIOR
    // =====================================================

    scrollBehavior(to, from, savedPosition) {

        if (savedPosition) {
            return savedPosition;
        }

        return to.hash
            ? {
                el: to.hash,
                behavior: 'smooth',
            }
            : {
                top: 0,
                behavior: 'smooth',
            };
    },
});


// =========================================================
// NAVIGATION GUARD
// =========================================================

router.beforeEach((to) => {

    const token = localStorage.getItem('token');

    const expiresAt = Number(
        localStorage.getItem('token_expires_at') || 0
    );

    const hasValidToken =
        !!token &&
        expiresAt > Date.now();


    // =====================================================
    // 1. ADMIN LOGIN
    // =====================================================
    //
    // /admin/login est accessible sans authentification.
    //
    // Si l'utilisateur possède déjà un token valide,
    // on peut directement l'envoyer vers le dashboard.
    //

    if (to.name === 'admin.login') {

        if (hasValidToken) {
            return {
                name: 'admin.home',
            };
        }

        return true;
    }


    // =====================================================
    // 2. ROUTES USER
    // =====================================================
    //
    // Les routes user sont indépendantes de l'auth admin.
    //

    if (to.meta.public) {
        return true;
    }


    // =====================================================
    // 3. ROUTES ADMIN PROTÉGÉES
    // =====================================================

    if (to.meta.requiresAdminAuth) {

        if (!hasValidToken) {
            return {
                name: 'admin.login',
                query: {
                    unauthorized: 'true',
                },
            };
        }

        return true;
    }


    // =====================================================
    // 4. PAR DÉFAUT
    // =====================================================

    return true;
});


export default router;

