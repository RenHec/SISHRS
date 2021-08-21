class ReservationServiceService {
  axios
  baseUrl

  constructor(axios, baseUrl) {
    this.axios = axios
    this.baseUrl = baseUrl + 'service/rest/v1/principal/reservation_service'
  }

  index() {
    let self = this;
    return self.axios.get(`${self.baseUrl}`);
  }

  store(data) {
    let self = this
    return self.axios.post(`${self.baseUrl}`, data)
  }

  call_services(data) {
    let self = this;
    return self.axios.get(`${self.baseUrl}/call_services/${data.id}`);
  }

  start(data) {
    let self = this;
    return self.axios.get(`${self.baseUrl}/start/${data.id}`);
  }

  end(data) {
    let self = this;
    return self.axios.get(`${self.baseUrl}/end/${data.id}`);
  }
}

export default ReservationServiceService