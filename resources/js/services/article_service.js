import {http, httpFile} from './http_service';

export function createArticle(data) {
    return httpFile().post('/Article', data);
}

export function loadArticleies() {
    return http().get('/Article');
}

export function deleteArticle(id) {
    return http().delete(`/Article/${id}`);
}

export function updateArticle(id, data) {
    return httpFile().post(`Article/${id}`, data);
}

export function loadMore(nextPage) {
    return http().get(`Article?page=${nextPage}`);
}