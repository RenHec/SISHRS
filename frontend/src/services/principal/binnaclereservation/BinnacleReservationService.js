class BinnacleReservationService {
  axios
  baseUrl

  constructor(axios, baseUrl) {
    this.axios = axios
    this.baseUrl = baseUrl + 'service/rest/v1/principal/binnacle_reservation'
  }

  index() {
    let self = this;
    return self.axios.get(`${self.baseUrl}`);
  }

  show(data) {
    let self = this;
    return self.axios.get(`${self.baseUrl}/${data.id}`);
  }
}

export default BinnacleReservationService