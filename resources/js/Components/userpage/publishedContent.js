import { ref } from 'vue';
import { getData } from '../plugins/axios.js';

export const hasPublishedProjects = ref(false);
export const hasPublishedBlogs = ref(false);

let publishedContentLoaded = false;
let publishedContentRequest = null;

export const loadPublishedContent = () => {
    if (publishedContentLoaded) return Promise.resolve();
    if (publishedContentRequest) return publishedContentRequest;

    publishedContentRequest = Promise.all([
        getData('/public/projects/recent')
            .then((response) => {
                hasPublishedProjects.value = Array.isArray(response.data) && response.data.length > 0;
                return true;
            })
            .catch((error) => {
                hasPublishedProjects.value = false;
                console.error('Could not check for published projects:', error);
                return false;
            }),
        getData('/public/blogs/recent')
            .then((response) => {
                hasPublishedBlogs.value = Array.isArray(response.data) && response.data.length > 0;
                return true;
            })
            .catch((error) => {
                hasPublishedBlogs.value = false;
                console.error('Could not check for published blog posts:', error);
                return false;
            }),
    ]).then((results) => {
        publishedContentLoaded = results.every(Boolean);
    }).finally(() => {
        publishedContentRequest = null;
    });

    return publishedContentRequest;
};
