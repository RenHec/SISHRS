import Axios from 'axios'
import auth from '../auth'
import {
  isNullOrUndefined
} from 'util';

/* ::::::::::::::::::::::::::::::::::::::::::::: SEGURIDAD :::::::::::::::::::::::::::::::::::::::::::::::: */
import loginService from '../services/seguridad/login/LoginService'
import menuService from '../services/seguridad/menu/MenuService'
import rolMenuService from '../services/seguridad/rol/RolMenuService'
import rolService from '../services/seguridad/rol/RolService'
import userRolService from '../services/seguridad/usuario/UserRolService'
import userService from '../services/seguridad/usuario/UserService'
/* ::::::::::::::::::::::::::::::::::: FIN DE LOS IMPORT DE SEGURIDAD ::::::::::::::::::::::::::::::::::::: */

/* ::::::::::::::::::::::::::::::::::::::::::::: CATALOGO :::::::::::::::::::::::::::::::::::::::::::::::: */
import departamentService from '../services/catalogo/departamento/DepartamentService'
import municipalityService from '../services/catalogo/municipio/MunicipalityService'
import coinService from '../services/catalogo/coin/CoinService'
import movementService from '../services/catalogo/movement/MovementService'
import statusService from '../services/catalogo/status/StatusService'
import typeBedService from '../services/catalogo/typebed/TypeBedService'
import typeRoomService from '../services/catalogo/typeroom/TypeRoomService'
import typeChargeService from '../services/catalogo/typecharge/TypeChargeService'
import typeMessageService from '../services/catalogo/typemessage/TypeMessageService'
import typeServiceService from '../services/catalogo/typeservice/TypeServiceService'
import categoryService from '../services/catalogo/category/CategoryService'
import subCategoryService from '../services/catalogo/category/SubCategoryService'
import kardexStatusService from '../services/catalogo/kardex/KardexStatusService'
import supplierService from '../services/catalogo/supplier/SupplierService'
/* :::::::::::::::::::::::::::::::::::: FIN DE LOS IMPORT DE CATALOGO ::::::::::::::::::::::::::::::::::::: */

/* ::::::::::::::::::::::::::::::::::::::::::::: PRINCIPAL :::::::::::::::::::::::::::::::::::::::::::::::: */
import binnacleReservationService from '../services/principal/binnaclereservation/BinnacleReservationService'
import clientService from '../services/principal/client/ClientService'
import clientPhoneService from '../services/principal/client/ClientPhoneService'
import roomService from '../services/principal/room/RoomService'
import roomMassageService from '../services/principal/room/RoomMassageService'
import roomPriceService from '../services/principal/room/RoomPriceService'
import pictureRoomService from '../services/principal/room/PictureRoomService'
import ofertRoomService from '../services/principal/room/OfertRoomService'
import reservationService from '../services/principal/reservation/ReservationService'
import reservationDetailService from '../services/principal/reservation/ReservationDetailService'
import reservationServiceService from '../services/principal/reservation/ReservationServiceService'
import advancePriceService from '../services/principal/reservation/AdvancePriceService'
import incomeService from '../services/principal/kardex/IncomeService'
import kardexService from '../services/principal/kardex/KardexService'
import saleService from '../services/principal/kardex/SaleService'
import changePriceService from '../services/principal/product/ChangePriceService'
import pictureProductService from '../services/principal/product/PictureProductService'
import productService from '../services/principal/product/ProductService'
import reservationProductService from '../services/principal/reservation/ReservationProductService'
/* :::::::::::::::::::::::::::::::::::: FIN DE LOS IMPORT DE PRINCIPAL ::::::::::::::::::::::::::::::::::::: */


// Para desarrollo
let baseUrl = 'https://api.hotelspa.app/'
baseUrl = 'http://localhost:8001/SISHRS/backend/public/' //base url desarrollo
let token_data = $cookies.get('token_data')

// Axios Configuration
Axios.defaults.headers.common.Accept = 'application/json'
if (token_data !== null) {
  Axios.defaults.headers.common.Authorization = `Bearer ${token_data.access_token}`
}

const instance = Axios.create();
Axios.interceptors.response.use(response => {
  return response
}, error => {
  if (error.response.status === 401) {
    var token_data = $cookies.get('token_data')
    if (isNullOrUndefined(token_data)) {
      return error
    }

    var original_request = error.config
    return refreshToken().then(res => {
      auth.saveToken(res.data)
      original_request.headers['Authorization'] = 'Bearer ' + res.data.access_token
      return Axios.request(original_request)
    })
  }

  return error
});

function refreshToken() {
  var data = auth.getRefreshToken()
  return new Promise(function (resolve, reject) {
    instance.post(baseUrl + 'service/passport/generar/token', data)
      .then(r => {
        resolve(r)
      }).catch(e => {
        reject(e)
      })
  })
}

instance.interceptors.response.use(response => {
  return response
}, error => {
  if (error.response.status === 401) {
    auth.noAcceso()
  }

  return error
});

export default {
  //Seguridad
  loginService: new loginService(Axios, baseUrl),
  menuService: new menuService(Axios, baseUrl),
  rolMenuService: new rolMenuService(Axios, baseUrl),
  rolService: new rolService(Axios, baseUrl),
  userRolService: new userRolService(Axios, baseUrl),
  userService: new userService(Axios, baseUrl),
  //Catalogo
  departamentService: new departamentService(Axios, baseUrl),
  municipalityService: new municipalityService(Axios, baseUrl),
  coinService: new coinService(Axios, baseUrl),
  movementService: new movementService(Axios, baseUrl),
  statusService: new statusService(Axios, baseUrl),
  typeBedService: new typeBedService(Axios, baseUrl),
  typeRoomService: new typeRoomService(Axios, baseUrl),
  typeChargeService: new typeChargeService(Axios, baseUrl),
  typeMessageService: new typeMessageService(Axios, baseUrl),
  typeServiceService: new typeServiceService(Axios, baseUrl),
  categoryService: new categoryService(Axios, baseUrl),
  subCategoryService: new subCategoryService(Axios, baseUrl),
  kardexStatusService: new kardexStatusService(Axios, baseUrl),
  supplierService: new supplierService(Axios, baseUrl),
  //Principal
  binnacleReservationService: new binnacleReservationService(Axios, baseUrl),
  clientService: new clientService(Axios, baseUrl),
  clientPhoneService: new clientPhoneService(Axios, baseUrl),
  roomService: new roomService(Axios, baseUrl),
  roomMassageService: new roomMassageService(Axios, baseUrl),
  roomPriceService: new roomPriceService(Axios, baseUrl),
  pictureRoomService: new pictureRoomService(Axios, baseUrl),
  ofertRoomService: new ofertRoomService(Axios, baseUrl),
  reservationService: new reservationService(Axios, baseUrl),
  reservationDetailService: new reservationDetailService(Axios, baseUrl),
  reservationServiceService: new reservationServiceService(Axios, baseUrl),
  advancePriceService: new advancePriceService(Axios, baseUrl),
  incomeService: new incomeService(Axios, baseUrl),
  kardexService: new kardexService(Axios, baseUrl),
  saleService: new saleService(Axios, baseUrl),
  changePriceService: new changePriceService(Axios, baseUrl),
  pictureProductService: new pictureProductService(Axios, baseUrl),
  productService: new productService(Axios, baseUrl),
  reservationProductService: new reservationProductService(Axios, baseUrl)
}
