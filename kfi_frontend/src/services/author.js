import axios from "axios";

const baseURL = 'https://localhost:8000';
if (typeof baseURL !== 'undefined') {
    axios.defaults.baseURL = baseURL;
}

export default {
    update(author) {
        return axios.patch(
            `/api/author/${author.id}`,
            {
                'fullName': author.fullName
            }
        );
    },
    fetchAll() {
        return axios.get('/api/author');
    },
    fetchAuthor(id) {
        return axios.get(`/api/author/${id}`);
    },
    search(search) {
        return axios.get(`/api/author/search/${search}`);
    },

}
