import Axios from 'axios'
import router from '../router'
import store from '../store/index'

export default {

  data_refresh_token: {
    grant_type: 'refresh_token',
    refresh_token: '',
    client_id: '',
    client_secret: ''
  },

  getRefreshToken() {
    var token_data = $cookies.get('token_data')
    this.data_refresh_token.refresh_token = token_data.refresh_token
    this.data_refresh_token.client_id = store.state.client_id,
    this.data_refresh_token.client_secret = store.state.client_secret

    return this.data_refresh_token
  },

  saveToken(token) {
    store.dispatch('guardarToken', token)
  },

  noAcceso() {
    store.dispatch('logout')
    router.push('/login')
  },

  getUser() {
    store.state.services.loginService.me()
      .then(r => {
        store.dispatch('setUser', r.data)
        this.getMenus(r.data)
      }).catch(e => {

      })
  },

  getMenus(data) {
    let self = this
    self.loading = true
    store.state.services.userRolService
      .show(data)
      .then(r => {
        self.loading = false
        if (r.response !== undefined) {
          self.$toastr.error(r.response.data.error, 'error')
          return
        }
        this.mapMenu(r.data)
      }).catch(e => {

      })
  },

  mapMenu(items) {
    var menu = []
    var permisions = []
    items.forEach(function (item) {
      permisions.push(item.menu.route_name)
      if (item.menu.father == 0 && !item.menu.hide) {
        var object = new Object
        object.icon = item.menu.icon
        object.text = item.menu.name
        object.path = item.menu.route_name
        object.childrens = []
        items.forEach(function (child, i) {
          if (item.menu.id == child.menu.father && !child.menu.hide) {
            var object2 = new Object()
            object2.icon = child.menu.icon
            object2.text = child.menu.name
            object2.path = child.menu.route_name

            object.childrens.push(object2)
          }
        });
        
        object.childrens.length > 0 ? menu.push(object) : null
      }
    })

    store.dispatch('setMenu', {
      items: menu,
      permissions: permisions
    })
  }
}
