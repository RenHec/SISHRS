import Vue from 'vue'
import Router from 'vue-router'
import store from '../store/index'
import multiguard from 'vue-router-multiguard' //Middelware
import goTo from 'vuetify/es5/services/goto'

import Default from '@/components/Default'
import Login from '@/components/login/Index'

//Seguridad
import Rol from '@/components/seguridad/RolComponent'
import Usuario from '@/components/seguridad/UsuarioComponent'

//Catalogo General
import Status from '@/components/catalogo/StatusComponent'
import Client from '@/components/catalogo/ClientComponent'
import Coin from '@/components/catalogo/CoinComponent'
import Movement from '@/components/catalogo/MovementComponent'
import Municipality from '@/components/catalogo/MunicipalityComponent'
import Departament from '@/components/catalogo/DepartamentComponent'
import TypeService from '@/components/catalogo/TypeServiceComponent'

//Principal
import PictureRoom from '@/components/principal/PictureRoomComponent'
import Room from '@/components/principal/RoomComponent'
import TypeBed from '@/components/principal/TypeBedComponent'
import TypeRoom from '@/components/principal/TypeRoomComponent'
import Ofert from '@/components/principal/OfertComponent'
import Reservation from '@/components/principal/ReservationComponent'
import TypeCharge from '@/components/principal/TypeChargeComponent'
import TypeMessage from '@/components/principal/TypeMessageComponent'

Vue.use(Router)

//validar authenticacion
const isLoggedIn = (to, from, next) => {
  var user = store.state.usuario
  if (!_.isEmpty(user)) { }
  return store.state.is_login ? next() : next('/login')
}

const isLoggedOut = (to, from, next) => {
  return store.state.is_login ? next('/') : next()
}

const permissionsValidations = (to, from, next) => {
  var permissions = store.state.permissions
  var ruta = to.path.split('/').join('')
  var perm = _.includes(permissions, ruta)
  return perm ? next() : next('/')
}

const routes = [{
  path: '/',
  name: 'Default',
  component: Default,
  beforeEnter: multiguard([isLoggedIn])
},
{
  path: '/login',
  name: 'Login',
  component: Login,
  beforeEnter: multiguard([isLoggedOut])
},
//Seguridad
{
  path: '/rol',
  name: 'Rol',
  component: Rol,
  beforeEnter: multiguard([isLoggedIn, permissionsValidations])
}, {
  path: '/user',
  name: 'Usuario',
  component: Usuario,
  beforeEnter: multiguard([isLoggedIn, permissionsValidations])
},
//Catalogo General
{
  path: '/status',
  name: 'Status',
  component: Status,
  beforeEnter: multiguard([isLoggedIn, permissionsValidations])
}, {
  path: '/clients',
  name: 'Client',
  component: Client,
  beforeEnter: multiguard([isLoggedIn, permissionsValidations])
}, {
  path: '/coin',
  name: 'Coin',
  component: Coin,
  beforeEnter: multiguard([isLoggedIn, permissionsValidations])
}, {
  path: '/movement',
  name: 'Movement',
  component: Movement,
  beforeEnter: multiguard([isLoggedIn, permissionsValidations])
}, {
  path: '/municipality',
  name: 'Municipality',
  component: Municipality,
  beforeEnter: multiguard([isLoggedIn, permissionsValidations])
}, {
  path: '/departament',
  name: 'Departament',
  component: Departament,
  beforeEnter: multiguard([isLoggedIn, permissionsValidations])
}, {
  path: '/type_service',
  name: 'TypeService',
  component: TypeService,
  beforeEnter: multiguard([isLoggedIn, permissionsValidations])
},
//Principal
{
  path: '/pictures_rooms',
  name: 'PictureRoom',
  component: PictureRoom,
  beforeEnter: multiguard([isLoggedIn, permissionsValidations])
}, {
  path: '/rooms',
  name: 'Room',
  component: Room,
  beforeEnter: multiguard([isLoggedIn, permissionsValidations])
}, {
  path: '/type_bead',
  name: 'TypeBed',
  component: TypeBed,
  beforeEnter: multiguard([isLoggedIn, permissionsValidations])
}, {
  path: '/type_room',
  name: 'TypeRoom',
  component: TypeRoom,
  beforeEnter: multiguard([isLoggedIn, permissionsValidations])
}, {
  path: '/oferts',
  name: 'Ofert',
  component: Ofert,
  beforeEnter: multiguard([isLoggedIn, permissionsValidations])
}, {
  path: '/reservations',
  name: 'Reservation',
  component: Reservation,
  beforeEnter: multiguard([isLoggedIn, permissionsValidations])
}, {
  path: '/type_charge',
  name: 'TypeCharge',
  component: TypeCharge,
  beforeEnter: multiguard([isLoggedIn, permissionsValidations])
}, {
  path: '/type_massage',
  name: 'TypeMessage',
  component: TypeMessage,
  beforeEnter: multiguard([isLoggedIn, permissionsValidations])
}
]

export default new Router({
  scrollBehavior: (to, from, savedPosition) => {
    let scrollTo = 0

    if (to.hash) {
      scrollTo = to.hash
    } else if (savedPosition) {
      scrollTo = savedPosition.y
    }

    return goTo(scrollTo)
  },
  routes
})
