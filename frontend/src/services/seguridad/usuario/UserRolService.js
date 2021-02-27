class UserRolService {
  axios
  baseUrl

  constructor(axios, baseUrl) {
    this.axios = axios
    this.baseUrl = baseUrl + 'service/rest/v1/security/user_rol'
  }

  store(data) {
    let self = this
    return self.axios.post(`${self.baseUrl}`, data)
  }

  show(data) {
    let self = this;
    return self.axios.get(`${self.baseUrl}/${data.id}`);
  }

  destroy(data) {
    let self = this;
    return self.axios.delete(`${self.baseUrl}/${data.id}`);
  }
}

export default UserRolService