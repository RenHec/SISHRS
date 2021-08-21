class IncomeService {
    axios
    baseUrl

    constructor(axios, baseUrl) {
        this.axios = axios
        this.baseUrl = baseUrl + 'service/rest/v1/principal/income'
    }

    index() {
        let self = this;
        return self.axios.get(`${self.baseUrl}`);
    }

    store(data) {
        let self = this
        return self.axios.post(`${self.baseUrl}`, data)
    }
}

export default IncomeService