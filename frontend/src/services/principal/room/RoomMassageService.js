class RoomMassageService {
    axios
    baseUrl

    constructor(axios, baseUrl) {
        this.axios = axios
        this.baseUrl = baseUrl + 'service/rest/v1/principal/massage'
    }

    update(data) {
        let self = this;
        return self.axios.put(`${self.baseUrl}/${data.id}`, data);
    }
}

export default RoomMassageService