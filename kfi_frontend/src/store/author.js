import AuthorAPI from "../services/author";

const UPDATING_AUTHOR = "UPDATING_AUTHOR",
    UPDATING_AUTHOR_SUCCESS = "UPDATING_AUTHOR_SUCCESS",
    UPDATING_AUTHOR_ERROR = "UPDATING_AUTHOR_ERROR",
    FETCHING_AUTHORS = "FETCHING_AUTHORS",
    FETCHING_AUTHORS_SUCCESS = "FETCHING_AUTHORS_SUCCESS",
    FETCHING_AUTHORS_ERROR = "FETCHING_AUTHORS_ERROR",
    FETCHING_AUTHOR = "FETCHING_AUTHOR",
    FETCHING_AUTHOR_SUCCESS = "FETCHING_AUTHOR_SUCCESS",
    FETCHING_AUTHOR_ERROR = "FETCHING_AUTHOR_ERROR",
    CLEAR_IS_SUCCESS = "CLEAR_IS_SUCCESS",
    CLEAR_ERRORS = "CLEAR_ERRORS",
    CLEAR_ALERTS = "CLEAR_ALERTS";

export default {
    namespaced: true,
    state: {
        isLoading: false,
        isSuccess: false,
        error: null,
        author: null,
        authors: []
    },
    getters: {
        isLoading(state) {
            return state.isLoading;
        },
        isSuccess(state) {
            return state.isSuccess;
        },
        hasError(state) {
            return state.error !== null;
        },
        error(state) {
            return state.error;
        },
        hasAuthors(state) {
            return state.authors.length > 0;
        },
        author(state) {
            return state.author;
        },
        authors(state) {
            return state.authors;
        }
    },
    mutations: {
        [CLEAR_IS_SUCCESS](state) {
            state.isSuccess = false;
        },
        [CLEAR_ERRORS](state) {
            state.error = null;
        },
        [CLEAR_ALERTS](state) {
            state.error = null;
            state.isSuccess = false;
        },
        [UPDATING_AUTHOR](state) {
            //state.isLoading = true;
            state.isSuccess = false;
            state.error = null;
        },
        [UPDATING_AUTHOR_SUCCESS](state, author) {
            state.isLoading = false;
            state.isSuccess = true;
            state.error = null;
            state.author = author

            const authorId = author.id;
            const authorInAuthorsIndex = state.authors.findIndex(
                (authorItem) => authorItem.id === authorId
            );
            state.authors[authorInAuthorsIndex].fullName = author.fullName;
        },
        [UPDATING_AUTHOR_ERROR](state, error) {
            state.isLoading = false;
            state.isSuccess = false;
            state.error = error;
        },
        [FETCHING_AUTHORS](state) {
            state.isLoading = true;
            state.error = null;
            state.authors = [];
        },
        [FETCHING_AUTHORS_SUCCESS](state, authors) {
            state.isLoading = false;
            state.error = null;
            state.authors = authors.items;
        },
        [FETCHING_AUTHORS_ERROR](state, error) {
            state.isLoading = false;
            state.error = error;
            state.authors = [];
        },
        [FETCHING_AUTHOR](state) {
            state.isLoading = true;
            state.error = null;
            state.author = null;
        },
        [FETCHING_AUTHOR_SUCCESS](state, author) {
            state.isLoading = false;
            state.error = null;
            state.author = author;
        },
        [FETCHING_AUTHOR_ERROR](state, error) {
            state.isLoading = false;
            state.error = error;
            state.author = null;
        }
    },
    actions: {
        async update({commit}, author) {
            commit(UPDATING_AUTHOR);
            try {
                let response = await AuthorAPI.update(author);
                commit(UPDATING_AUTHOR_SUCCESS, author);
                return response.data;
            } catch (error) {
                commit(UPDATING_AUTHOR_ERROR, error);
                return null;
            }
        },
        async fetchAll({commit}) {
            commit(FETCHING_AUTHORS);
            try {
                let response = await AuthorAPI.fetchAll();
                commit(FETCHING_AUTHORS_SUCCESS, response.data);
                return response.data;
            } catch (error) {
                commit(FETCHING_AUTHORS_ERROR, error);
                return null;
            }
        },
        async fetchAuthor({commit}, id) {
            commit(FETCHING_AUTHOR);
            try {
                let response = await AuthorAPI.fetchAuthor(id);
                commit(FETCHING_AUTHOR_SUCCESS, response.data);
                return response.data;
            } catch (error) {
                commit(FETCHING_AUTHOR_ERROR, error);
                return null;
            }
        },
        clearSuccess({commit}) {
            commit(CLEAR_IS_SUCCESS);
        },
        clearErrors({commit}) {
            commit(CLEAR_ERRORS);
        },
        clearAlerts({commit}) {
            commit(CLEAR_ALERTS);
        }
    }
};