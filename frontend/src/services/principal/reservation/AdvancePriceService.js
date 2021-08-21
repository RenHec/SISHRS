class AdvancePriceService {
    axios
    baseUrl

    constructor(axios, baseUrl) {
        this.axios = axios
        this.baseUrl = baseUrl + 'service/rest/v1/principal/advance_price'
    }

    index() {
        let self = this;
        return self.axios.get(`${self.baseUrl}`);
    }

    show(data) {
        let self = this;
        return self.axios.get(`${self.baseUrl}/${data.id}`);
    }

    update(data) {
        let self = this;
        return self.axios.put(`${self.baseUrl}/${data.id}`, data);
    }
}

export default AdvancePriceService