import i18n from '../../plugins/i18n.js';

const _e = (key, fallback = key) => (
    i18n.global.te(key) ? i18n.global.t(key) : fallback
);

export const getServices = () => [
    {
        slug: 'performance-audit',
        title: _e(`home.vue_1789384786151_54`, `Performance Audit`),
        icon: 'flaticon-analysis',
        short: _e(`home.vue_1789384786151_55`, `We audit your advertising data from the past 12 months, your lead generation, and your CRM to pinpoint exactly where revenue is being lost.`),
        description: [
            _e('service.performance-audit.description-1', `We analyze your entire sales funnel from lead generation to the completed sale to identify where performance is actually declining.`),
            _e('service.performance-audit.description-2', `We review your advertising performance, map the lead journey, and locate and quantify revenue leaks.`),
        ],
        points: [
            _e('service.performance-audit.point-1', `A comprehensive map of your marketing-to-sales funnel`),
            _e('service.performance-audit.point-2', `Estimated amount of lost income, in dollars`),
            _e('service.performance-audit.point-3', `A list of data breaches ranked by financial impact`),
        ],
        metrics: [
            { number: '01', text: _e('service.performance-audit.metric-1', `12 months of performance data`) },
            { number: '02', text: _e('service.performance-audit.metric-2', `Revenue leaks quantified`) },
            { number: '03', text: _e('service.performance-audit.metric-3', `Marketing-to-sales funnel mapped`) },
        ],
    },
    {
        slug: 'recovery-plan',
        title: _e(`home.vue_1789384786151_59`, `Recovery Plan`),
        icon: 'flaticon-report',
        short: _e(`home.vue_1789384786151_60`, `A prioritized 90 day action plan, ranked by financial impact and ease of implementation, so you know exactly what to address first.`),
        description: [
            _e('service.recovery-plan.description-1', `Based on the assessment, we develop a concrete plan that ranks each corrective action by financial impact, urgency, and ease of implementation.`),
            _e('service.recovery-plan.description-2', `You know exactly what will pay off the fastest, and what can wait.`),
        ],
        points: [
            _e('service.recovery-plan.point-1', `A list of actions ranked by impact and effort`),
            _e('service.recovery-plan.point-2', `A 90 day implementation schedule`),
            _e('service.recovery-plan.point-3', `An estimate of earnings per share`),
        ],
        metrics: [
            { number: '01', text: _e('service.recovery-plan.metric-1', `Clear Priorities`) },
            { number: '02', text: _e('service.recovery-plan.metric-2', `90 Day Plan`) },
            { number: '03', text: _e('service.recovery-plan.metric-3', `Impact, in figures per share`) },
        ],
    },
    {
        slug: 'recovery-implementation',
        title: _e(`home.vue_1789384786151_64`, `Recovery Implementation`),
        icon: 'flaticon-process',
        short: _e(`home.vue_1789384786151_65`, `We work with you to implement the necessary adjustments: tracking, landing pages, CRM automations, and training for the customer service team.`),
        description: [
            _e('service.recovery-implementation.description-1', `We implement the identified fixes ourselves: tracking configuration, landing page creation, CRM automations, and intake scripts.`),
            _e('service.recovery-implementation.description-2', `We work directly with your team to ensure that the new processes are adopted not just implemented.`),
        ],
        points: [
            _e('service.recovery-implementation.point-1', `Properly Configured Tracking and Attribution`),
            _e('service.recovery-implementation.point-2', `Existing CRM automations and intake scripts`),
            _e('service.recovery-implementation.point-3', `Your team trained in the new processes`),
        ],
        metrics: [
            { number: '01', text: _e('service.recovery-implementation.metric-1', `Direct Execution`) },
            { number: '02', text: _e('service.recovery-implementation.metric-2', `Team training included`) },
            { number: '03', text: _e('service.recovery-implementation.metric-3', `Implementation Monitoring`) },
        ],
    },
    {
        slug: 'performance-management',
        title: _e(`home.vue_1789384786151_69`, `Performance Management`),
        icon: 'flaticon-analytics',
        short: _e(`home.vue_1789384786151_70`, `Monthly monitoring of ROI and lead quality to continue improving the areas that have already been addressed.`),
        description: [
            _e('service.performance-management.description-1', `We monitor your conversion metrics, automations, and lead quality on a monthly basis to ensure that the performance gains you’ve achieved don’t decline over time.`),
            _e('service.performance-management.description-2', ` We continuously adjust as your volume, campaigns, or team evolve.`),
        ],
        points: [
            _e('service.performance-management.point-1', `A monthly report on ROI and recovered revenue`),
            _e('service.performance-management.point-2', `Regular audits of call recordings and intake`),
            _e('service.performance-management.point-3', `Continuous monitoring of existing automation systems`),
        ],
        metrics: [
            { number: '01', text: _e('service.performance-management.metric-1', `Monthly Follow-Up`) },
            { number: '02', text: _e('service.performance-management.metric-2', `Continuous adjustment`) },
            { number: '03', text: _e('service.performance-management.metric-3', `Reporting on Actual ROI`) },
        ],
    },
];
