class ReservationService {
  axios
  baseUrl

  constructor(axios, baseUrl) {
    this.axios = axios
    this.baseUrl = baseUrl + 'service/rest/v1/principal/reservation'
  }

  index() {
    let self = this;
    return self.axios.get(`${self.baseUrl}`);
  }

  pendiente() {
    let self = this;
    return self.axios.get(`${self.baseUrl}_pendiente`);
  }

  promocion(item) {
    let self = this;
    return self.axios.get(`${self.baseUrl}_promocion/${item.id}`);
  }

  calendario(status) {
    let self = this;
    return self.axios.get(`${self.baseUrl}_calendario/${status}`);
  }

  precios(item) {
    let self = this;
    return self.axios.get(`${self.baseUrl}_precios/${item.id}`);
  }

  buscar_habitaciones(data) {
    let self = this;
    return self.axios.post(`${self.baseUrl}_buscar_habitaciones`, data);
  }

  show(data) {
    let self = this;
    return self.axios.get(`${self.baseUrl}/${data.id}`);
  }

  store(data) {
    let self = this
    return self.axios.post(`${self.baseUrl}`, data)
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

export default ReservationService