class MenuService {
  axios
  baseUrl

  constructor(axios, baseUrl) {
    this.axios = axios
    this.baseUrl = `${baseUrl}service/rest/v1/security/menu`
  }

  index() {
    let self = this;
    return self.axios.get(`${self.baseUrl}`);
  }
}

export default MenuService
