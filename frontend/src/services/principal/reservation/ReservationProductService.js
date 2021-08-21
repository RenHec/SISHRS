class ReservationProductService {
    axios
    baseUrl

    constructor(axios, baseUrl) {
        this.axios = axios
        this.baseUrl = baseUrl + 'service/rest/v1/principal/reservation_product'
    }

    index() {
        let self = this;
        return self.axios.get(`${self.baseUrl}`);
    }

    store(data) {
        let self = this
        return self.axios.post(`${self.baseUrl}`, data)
    }

    destroy(data) {
        let self = this;
        return self.axios.delete(`${self.baseUrl}/${data.id}`);
    }

    authorization_code(code) {
        let self = this;
        return self.axios.get(`${self.baseUrl}/authorization_code/${code}`);
    }
}

export default ReservationProductService