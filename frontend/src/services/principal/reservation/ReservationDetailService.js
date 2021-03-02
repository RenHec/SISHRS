class ReservationDetailService {
  axios
  baseUrl

  constructor(axios, baseUrl) {
    this.axios = axios
    this.baseUrl = baseUrl + 'service/rest/v1/principal/reservation_detail'
  }

  show(data) {
    let self = this;
    return self.axios.get(`${self.baseUrl}/${data.id}`);
  }

  update(data) {
    let self = this;
    return self.axios.put(`${self.baseUrl}/${data.id}`, data);
  }

  destroy(data) {
    let self = this;
    return self.axios.delete(`${self.baseUrl}/${data.id}`);
  }
}

export default ReservationDetailService