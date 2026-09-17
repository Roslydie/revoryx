import i18n from '../../plugins/i18n.js';

const _e = (key, fallback = key) => (
    i18n.global.te(key) ? i18n.global.t(key) : fallback
);

export const services = [
    {
        slug: 'web-development',
        title: _e(`services.js_1789424760527_0`, `Web Development`),
        icon: 'flaticon-code',
        short: _e(`services.js_1789424760527_1`, `Modern, responsive websites and web applications tailored to your business needs.`),
        description: _e(`services.js_1789424760527_2`, `From corporate websites to custom web applications, we build secure, scalable and user-friendly digital experiences that help your organization move forward.`),
        points: [_e(`services.js_1789424760527_3`, `Responsive digital experiences`), _e(`services.js_1789424760527_4`, `Secure and maintainable architecture`), _e(`services.js_1789424760527_5`, `Solutions designed around your users`)],
    },
    {
        slug: 'seo-digital-visibility',
        title: _e(`services.js_1789424760527_6`, `SEO & Digital Visibility`),
        icon: 'flaticon-bar-chart',
        short: _e(`services.js_1789424760527_7`, `Strategic SEO, content optimization and search positioning that improve your reach.`),
        description: _e(`services.js_1789424760527_8`, `We help your business reach the right audience and build a stronger presence across search engines and digital channels.`),
        points: [_e(`services.js_1789424760527_9`, `Search visibility strategy`), _e(`services.js_1789424760527_10`, `Content and technical optimization`), _e(`services.js_1789424760527_11`, `Continuous performance improvement`)],
    },
    {
        slug: 'custom-digital-solutions',
        title: _e(`services.js_1789424760527_12`, `Custom Digital Solutions`),
        icon: 'flaticon-intelligent',
        short: _e(`services.js_1789424760527_13`, `Tailor-made applications, automation tools and management systems for your operations.`),
        description: _e(`services.js_1789424760527_14`, `We transform your business requirements into reliable digital tools designed to improve productivity, efficiency and decision-making.`),
        points: [_e(`services.js_1789424760527_15`, `Business process automation`), _e(`services.js_1789424760527_16`, `Custom management platforms`), _e(`services.js_1789424760527_17`, `Scalable technical foundations`)],
    },
    {
        slug: 'mobile-app-development',
        title: _e(`services.js_1789424760527_18`, `Mobile App Development`),
        icon: 'flaticon-smartphone',
        short: _e(`services.js_1789424760527_19`, `Intuitive, high-performance mobile applications for seamless digital experiences.`),
        description: _e(`services.js_1789424760527_20`, `Our mobile solutions are designed to engage users while meeting the specific needs of your business across modern devices.`),
        points: [_e(`services.js_1789424760527_21`, `User-centered mobile journeys`), _e(`services.js_1789424760527_22`, `Fast and reliable interfaces`), _e(`services.js_1789424760527_23`, `Experiences ready to scale`)],
    },
    {
        slug: 'digital-marketing',
        title: _e(`services.js_1789424760527_24`, `Digital Marketing`),
        icon: 'flaticon-content-writing',
        short: _e(`services.js_1789424760527_25`, `Targeted campaigns and content strategies that strengthen your brand and create opportunity.`),
        description: _e(`services.js_1789424760527_26`, `We connect your brand with its audience through effective digital campaigns, compelling content and measurable marketing initiatives.`),
        points: [_e(`services.js_1789424760527_27`, `Campaign and content planning`), _e(`services.js_1789424760527_28`, `Brand consistency across channels`), _e(`services.js_1789424760527_29`, `Data-informed optimization`)],
    },
    {
        slug: 'it-consulting',
        title: _e(`services.js_1789424760527_30`, `IT Consulting & Digital Transformation`),
        icon: 'flaticon-business-and-finance',
        short: _e(`services.js_1789424760527_31`, `Strategic technology guidance to modernize operations and support sustainable growth.`),
        description: _e(`services.js_1789424760527_32`, `We help organizations choose the right technologies, optimize processes and adopt efficient digital solutions with confidence.`),
        points: [_e(`services.js_1789424760527_33`, `Technology and process audits`), _e(`services.js_1789424760527_34`, `Transformation roadmaps`), _e(`services.js_1789424760527_35`, `Practical change management`)],
    },
];
