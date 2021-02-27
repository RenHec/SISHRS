class PictureRoomService {
  axios
  baseUrl

  constructor(axios, baseUrl) {
    this.axios = axios
    this.baseUrl = baseUrl + 'service/rest/v1/principal/picture_room'
  }

  show(data) {
    let self = this;
    return self.axios.get(`${self.baseUrl}/${data.id}`);
  }

  update(data) {
    let self = this;
    return self.axios.put(`${self.baseUrl}/${data.id}`, data);
  }

  masive(data) {
    let self = this;
    return self.axios.post(`${self.baseUrl}`, data);
  }

  view(data) {
    let self = this;
    return self.axios.get(`${self.baseUrl}/view/${data.id}`);
  }

  up(data) {
    let self = this;
    return self.axios.get(`${self.baseUrl}/up/${data.id}`);
  }

  down(data) {
    let self = this;
    return self.axios.get(`${self.baseUrl}/down/${data.id}`);
  }

  destroy(data) {
    let self = this;
    return self.axios.delete(`${self.baseUrl}/${data.id}`);
  }
}

export default PictureRoomService