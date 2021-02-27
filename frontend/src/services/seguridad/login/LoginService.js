class LoginService {
    axios
    baseUrl

    constructor(axios,baseUrl) {
        this.axios = axios
        this.baseUrl = baseUrl + 'service/rest/v1/security/auth'
    }

    login(credentials) {
        let self = this
        return self.axios.post(`${self.baseUrl}/login`,credentials)
    }

    logout() {
        let self = this
        return self.axios.get(`${self.baseUrl}/logout`)
    }

    me() {
      let self = this
      return self.axios.get(`${self.baseUrl}/me`)
    }
}

export default LoginService