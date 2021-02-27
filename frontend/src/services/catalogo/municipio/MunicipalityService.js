class MunicipalityService {
  axios
  baseUrl

  constructor(axios, baseUrl) {
    this.axios = axios
    this.baseUrl = `${baseUrl}service/rest/v1/catalogo/municipio`
  }

  index() {
    let self = this;
    return self.axios.get(`${self.baseUrl}`);
  }
}

export default MunicipalityService