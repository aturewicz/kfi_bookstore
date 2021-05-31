import axios from "axios";

const baseURL = 'https://localhost:8000';
if (typeof baseURL !== 'undefined') {
    axios.defaults.baseURL = baseURL;
}

export default {
    update(product) {
        return axios.patch(
            `/api/product/${product.ean}`, {
                name: product.name,
                stock: product.stock,
                price: product.price,
                publisher: product.publisher
            }
        );
    },
    fetchAll() {
        return axios.get(
            '/api/product'
        );
    },
    fetchProduct(ean) {
        return axios.get(
            `/api/product/${ean}`
        );
    },
    search(search) {
        return axios.get(
            '/api/product/search', {
                params: {
                    author: search.author,
                    publisher: search.publisher
                }
            }
        );
    },
}
