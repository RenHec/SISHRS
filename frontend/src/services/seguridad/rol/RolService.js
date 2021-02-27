class RolService {
  axios
  baseUrl

  constructor(axios, baseUrl) {
    this.axios = axios
    this.baseUrl = baseUrl + 'service/rest/v1/security/rol'
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
}

export default RolService