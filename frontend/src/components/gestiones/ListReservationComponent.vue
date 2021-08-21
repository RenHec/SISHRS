<template>
  <v-row justify="center" align="center">
    <v-overlay :value="loading">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
    <v-col cols="12" md="12">
      <v-data-table
        :headers="headers"
        :items="desserts"
        :search="search"
        sort-by="calories"
        class="elevation-1"
        dark
        :footer-props="footer"
      >
        <template v-slot:top>
          <v-toolbar flat color="success">
            <v-toolbar-title>Historial de Reservaciones</v-toolbar-title>
            <v-divider class="mx-4" inset vertical></v-divider>
            <v-text-field
              v-model="search"
              append-icon="search"
              label="Buscar"
              single-line
              hide-details
            ></v-text-field>
            <v-btn class="ma-2" color="primary" @click="initialize">
              Actualizar
            </v-btn>
          </v-toolbar>
        </template>
        <template v-slot:item.actions="{ item }">
          <v-btn
            color="error"
            v-if="item.status_id == 1 && item.advance_price == 0"
            @click="cancelar_reservacion(item)"
          >
            Cancelar
          </v-btn>
          <v-btn
            color="info"
            v-if="item.status_id == 1 && item.advance_price != 0"
            @click="checkInFunction(item)"
          >
            Check In
          </v-btn>
          <v-btn
            color="success"
            v-if="item.status_id == 2 && item.advance_price != 0"
            @click="checkOutFunction(item)"
          >
            Check Out
          </v-btn>
        </template>
        <template v-slot:no-data>
          <br />
          <v-alert type="error">No hay información para mostrar.</v-alert>
        </template>
      </v-data-table>
    </v-col>

    <v-col cols="12" md="12" class="text-center" v-if="dialog_checkIn">
      <v-dialog
        v-model="dialog_checkIn"
        persistent
        class="mx-auto my-12"
        max-width="600px"
      >
        <v-card color="light-blue darken-4">
          <v-card-title>
            <span class="text-h5">
              CheckIn | {{ checkIn.information.name }}
            </span>
          </v-card-title>
          <v-card-text>
            <v-container>
              <v-list-item>
                <v-list-item-subtitle>
                  Cliente: {{ checkIn.information.client }}
                </v-list-item-subtitle>
              </v-list-item>
              <v-list-item>
                <v-list-item-subtitle>
                  NIT: {{ checkIn.information.nit }}
                </v-list-item-subtitle>
              </v-list-item>
              <v-list-item
                v-for="(item, index) in checkIn.number_room"
                :key="index"
              >
                <v-list-item-subtitle>
                  Número de habitación: {{ item }}
                </v-list-item-subtitle>
              </v-list-item>
              <v-list-item>
                <v-list-item-subtitle>
                  Entrada: {{ checkIn.information.start }}
                </v-list-item-subtitle>
              </v-list-item>
              <v-list-item>
                <v-list-item-subtitle>
                  Salida: {{ checkIn.information.end }}
                </v-list-item-subtitle>
              </v-list-item>
              <v-list-item>
                <v-list-item-subtitle>
                  Total Reservación: {{ checkIn.information.total }}
                </v-list-item-subtitle>
              </v-list-item>

              <hr />
              <div class="font-weight-bold ml-8 mb-2">
                Detalle de la reservación
              </div>

              <v-timeline align-top dense>
                <v-timeline-item
                  v-for="(item, index) in checkIn.information.servicios"
                  :key="index"
                  small
                >
                  <div>
                    <div class="font-weight-normal">
                      <strong>{{ item.description }}</strong>
                    </div>
                    <div v-if="item.accommodation != 0">
                      {{ item.accommodation }} X {{ item.precio }} =
                      {{ item.sub }}
                    </div>
                    <div v-else>
                      {{ item.quote }} X {{ item.precio }} = {{ item.sub }}
                    </div>
                  </div>
                </v-timeline-item>
              </v-timeline>
              <hr />
              <br />
              <v-file-input
                outlined
                v-model="temp"
                @change="cargarImagen"
                accept="image/*"
                placeholder="Documento para checkIn"
                prepend-icon="mdi-camera"
                v-validate="'required'"
                data-vv-scope="checkIn"
                data-vv-name="documento"
              ></v-file-input>
              <FormError
                :attribute_name="'checkIn.documento'"
                :errors_form="errors"
              ></FormError>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="red darken-1" @click="dialog_checkIn = false">
              Cerrar
            </v-btn>
            <v-btn color="blue darken-1" @click="validar_checkIn('checkIn')">
              Guardar
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-col>

    <v-col cols="12" md="12" class="text-center" v-if="dialog_checkOut">
      <v-dialog
        v-model="dialog_checkOut"
        class="mx-auto my-12"
        persistent
        max-width="850px"
      >
        <v-card color="green darken-4">
          <v-card-title>
            <span class="text-h5">
              CheckOut | {{ checkOut.information.name }}
            </span>
          </v-card-title>
          <v-card-text>
            <v-container>
              <v-row>
                <v-col class="text-h7 text-left" cols="12">
                  NIT: {{ checkOut.information.nit }}
                </v-col>
                <v-col class="text-h7 text-left" cols="12">
                  Cliente: {{ checkOut.information.client }}
                </v-col>
                <v-col class="text-h7 text-left" cols="12">
                  Dirección: {{ checkOut.information.ubication }}
                </v-col>
                <v-col
                  class="text-h7 text-left"
                  cols="12"
                  v-for="(item, index) in checkOut.number_room"
                  v-bind:key="index"
                >
                  Número de habitación: {{ item }}
                </v-col>
                <v-col class="text-h7 text-left" cols="6">
                  Entrada: {{ checkOut.information.start }}
                </v-col>
                <v-col class="text-h7 text-right" cols="6">
                  Salida: {{ checkOut.information.end }}
                </v-col>
              </v-row>
              <hr />
              <div class="font-weight-bold ml-8 mb-2">
                Detalle de la reservación
              </div>

              <v-timeline align-top dense>
                <v-timeline-item
                  v-for="(item, index) in checkOut.information.servicios"
                  :key="index"
                  small
                >
                  <div>
                    <div class="font-weight-normal">
                      <strong>{{ item.description }}</strong>
                    </div>
                    <div v-if="item.accommodation != 0">
                      {{ item.accommodation }} X {{ item.precio }} =
                      {{ item.sub }}
                    </div>
                    <div v-else>
                      {{ item.quote == 0 ? '1' : item.quote }} X
                      {{ item.precio }} = {{ item.sub }}
                    </div>
                  </div>
                </v-timeline-item>
              </v-timeline>
              <template v-if="checkOut.restaurant.length > 0">
                <hr />
                <div class="font-weight-bold ml-8 mb-2">
                  Detalle de consumo en restaurante
                </div>

                <v-timeline
                  align-top
                  dense
                  v-for="(grupo, index) in checkOut.restaurant"
                  v-bind:key="index"
                >
                  <v-timeline-item
                    v-for="(item, index_item) in grupo"
                    :key="index_item"
                    small
                    color="green"
                  >
                    <v-row class="pt-1">
                      <v-col cols="3">
                        <strong>No. Orden - {{ item.order_id }}</strong>
                      </v-col>
                      <v-col>
                        <strong>
                          {{ item.order_date }} {{ item.order_time }}
                        </strong>
                        <div class="text-caption">
                          Total Q {{ item.totalamount }}
                        </div>
                      </v-col>
                    </v-row>
                  </v-timeline-item>
                </v-timeline>
              </template>
              <hr />
              <v-row>
                <v-col class="text-h5 text-right" cols="4">
                  Total Reservación +
                </v-col>
                <v-col class="text-h3 text-right" cols="8">
                  {{ checkOut.information.total }}
                </v-col>
                <v-col
                  class="text-h5 text-right"
                  cols="4"
                  v-if="checkOut.restaurant.length > 0"
                >
                  Total Restaurante +
                </v-col>
                <v-col
                  class="text-h3 text-right"
                  cols="8"
                  v-if="checkOut.restaurant.length > 0"
                >
                  {{ checkOut.total_restaurant }}
                </v-col>
                <v-col class="text-h5 text-right" cols="4">
                  Sub total
                </v-col>
                <v-col class="text-h3 text-right" cols="8">
                  {{ checkOut.total }}
                </v-col>
                <v-col class="text-h5 text-right" cols="4">
                  Anticipo -
                </v-col>
                <v-col class="text-h3 text-right" cols="8">
                  {{ checkOut.anticipo }}
                </v-col>
                <v-col class="text-h4 text-right" cols="12"><hr /></v-col>
                <v-col class="text-h4 text-right" cols="4">
                  Total
                </v-col>
                <v-col class="text-h2 text-right" cols="8">
                  {{ checkOut.resta }}
                </v-col>
              </v-row>
              <hr />
              <br />
              <v-row>
                <v-col cols="12" md="3">
                  <v-text-field
                    counter
                    outlined
                    v-model="checkOut.nit"
                    type="text"
                    label="NIT"
                    data-vv-scope="checkOut"
                    data-vv-name="NIT"
                    v-validate="'required'"
                  ></v-text-field>
                  <FormError
                    :attribute_name="'checkOut.NIT'"
                    :errors_form="errors"
                  ></FormError>
                  <ul v-for="(item, index) in filteredList" v-bind:key="index">
                    <li>
                      <a @click="seleccionar_cliente(item)">{{ item.nit }}</a>
                    </li>
                  </ul>
                </v-col>
                <v-col cols="12" md="12">
                  <v-text-field
                    counter
                    outlined
                    v-model="checkOut.name"
                    type="text"
                    label="nombre del cliente"
                    data-vv-scope="checkOut"
                    data-vv-name="nombre del cliente"
                    v-validate="'required|max:100'"
                  ></v-text-field>
                  <FormError
                    :attribute_name="'checkOut.nombre del cliente'"
                    :errors_form="errors"
                  ></FormError>
                </v-col>
                <v-col cols="12" md="12">
                  <v-text-field
                    counter
                    outlined
                    v-model="checkOut.ubication"
                    type="text"
                    label="dirección exacta"
                    data-vv-scope="checkOut"
                    data-vv-name="dirección exacta"
                    v-validate="'max:100'"
                  ></v-text-field>
                  <FormError
                    :attribute_name="'checkOut.dirección exacta'"
                    :errors_form="errors"
                  ></FormError>
                </v-col>
                <v-col cols="12" md="6">
                  <v-autocomplete
                    v-model="checkOut.way_to_pay"
                    :items="way_to_pays_r"
                    chips
                    label="Seleccionar el tipo de pago"
                    outlined
                    :clearable="true"
                    :deletable-chips="true"
                    item-text="name"
                    item-value="id"
                    return-object
                    v-validate="'required'"
                    data-vv-scope="checkOut"
                    data-vv-name="el tipo de pago"
                  ></v-autocomplete>
                  <FormError
                    :attribute_name="'checkOut.el tipo de pago'"
                    :errors_form="errors"
                  ></FormError>
                </v-col>
              </v-row>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="red darken-1" @click="dialog_checkOut = false">
              Cerrar
            </v-btn>
            <v-btn color="blue darken-1" @click="validar_checkOut('checkOut')">
              Guardar
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </v-col>
  </v-row>
</template>

<script>
import FormError from '../shared/FormError'

export default {
  name: 'ListReservation',
  components: {
    FormError,
  },
  data() {
    return {
      loading: false,
      dialog_checkIn: false,
      dialog_checkOut: false,
      search: '',
      headers: [
        {
          text: 'Código',
          align: 'start',
          value: 'code',
        },
        {
          text: 'Nombre',
          align: 'start',
          value: 'name',
        },
        {
          text: 'Total Reservación',
          align: 'start',
          value: 'monto_reservacion',
        },
        {
          text: 'Total Restaurante',
          align: 'start',
          value: 'monto_restaurante',
        },
        {
          text: 'Anticipo',
          align: 'start',
          value: 'monto_anticipo',
        },
        {
          text: 'Total',
          align: 'start',
          value: 'monto',
        },
        {
          text: 'Pagado',
          align: 'start',
          value: 'pagado',
        },
        {
          text: 'Estado',
          align: 'start',
          value: 'status.name',
        },
        { text: 'Opciones', value: 'actions', sortable: false },
      ],
      footer: {
        showFirstLastPage: true,
        firstIcon: 'mdi-arrow-collapse-left',
        lastIcon: 'mdi-arrow-collapse-right',
        prevIcon: 'mdi-minus',
        nextIcon: 'mdi-plus',
      },
      desserts: [],
      way_to_pays_r: [],
      way_to_pays_a: [],
      checkIn: {
        id: null,
        status_id: 2,
        document: null,
        information: null,
        number_room: [],
      },
      checkOut: {
        id: null,
        status_id: 3,
        way_to_pay: null,
        nit: null,
        name: null,
        ubication: null,
        information: null,
        restaurant: [],
        total_restaurant: '',
        total: '',
        total_restaurant_sf: 0,
        number_room: [],
        anticipo: null,
        resta: null,
      },
      temp: null,
      accept: ['image/png', 'image/jpeg', 'image/jpg'],
      clientes: [],
    }
  },

  created() {
    this.initialize()
    this.getClient()
  },

  methods: {
    initialize() {
      this.loading = true

      this.$store.state.services.reservationService
        .index()
        .then((r) => {
          if (r.response) {
            if (r.response.data.code === 423) {
              this.$toastr.error(r.response.data.error, 'Mensaje')
            } else {
              for (let value of Object.values(r.response.data.error)) {
                this.$toastr.error(value, 'Mensaje')
              }
            }
            this.loading = false
            return
          }

          r.data.way_to_pay.forEach((element) => {
            if (element.reservation) {
              this.way_to_pays_r.push(element)
            }
            if (element.reservation) {
              this.way_to_pays_a.push(element)
            }
          })

          this.desserts = r.data.data
          this.loading = false
        })
        .catch((r) => {
          this.loading = false
        })
    },

    cancelar_reservacion(data) {
      this.$swal({
        title: 'Cancelar Reservación No. \n' + data.code,
        text: '¿Está seguro de realizar esta acción?',
        type: 'error',
        showCancelButton: true,
      }).then((result) => {
        if (result.value) {
          this.loading = true
          this.$store.state.services.reservationService
            .destroy(data)
            .then((r) => {
              if (r.response) {
                if (r.response.data.code === 404) {
                  this.$toastr.warning(r.response.data.error, 'Advertencia')
                  return
                } else if (r.response.data.code === 423) {
                  this.$toastr.warning(r.response.data.error, 'Advertencia')
                  return
                } else {
                  for (let value of Object.values(r.response.data)) {
                    this.$toastr.error(value, 'Mensaje')
                  }
                }
                return
              }

              this.$toastr.success(r.data, 'Mensaje')
              this.loading = false
              this.initialize()
            })
            .catch((r) => {
              this.loading = false
            })
        }
      })
    },

    checkInFunction(item) {
      this.loading = true
      this.$store.state.services.reservationService
        .show(item)
        .then((r) => {
          if (r.response) {
            if (r.response.data.code === 423) {
              this.$toastr.error(r.response.data.error, 'Mensaje')
            } else {
              for (let value of Object.values(r.response.data.error)) {
                this.$toastr.error(value, 'Mensaje')
              }
            }
            this.loading = false
            return
          }

          let colores = {
            '1': 'primary',
            '2': 'success',
          }

          let objeto = new Object()
          objeto.id = r.data.data.length != 0 ? r.data.data[0].id : null
          objeto.name = r.data.data.length != 0 ? r.data.data[0].name : null
          objeto.ubication =
            r.data.data.length != 0 ? r.data.data[0].ubication : null
          objeto.client = r.data.data.length != 0 ? r.data.data[0].client : null
          objeto.nit = r.data.data.length != 0 ? r.data.data[0].nit : null
          objeto.start = r.data.data.length != 0 ? r.data.data[0].start : null
          objeto.end = r.data.data.length != 0 ? r.data.data[0].end : null
          objeto.total_sf =
            r.data.data.length != 0 ? r.data.data[0].total_sf : null
          objeto.total = r.data.data.length != 0 ? r.data.data[0].total : null
          objeto.status = r.data.data.length != 0 ? r.data.data[0].status : null
          objeto.color = r.data.data.length != 0 ? colores[objeto.status] : null
          objeto.servicios = r.data.data
          objeto.restaurant = r.data.restaurante
          objeto.total_restaurant = r.data.total_restaurant
          objeto.total_general = r.data.total
          objeto.total_restaurant_sf = r.data.total_restaurant_sf
          objeto.number_room = r.data.number_room
          objeto.anticipo =
            r.data.data.length != 0 ? r.data.data[0].anticipo : null
          objeto.anticipo_sf =
            r.data.data.length != 0 ? r.data.data[0].anticipo_sf : null
          objeto.resta = r.data.resta

          this.checkIn.status_id = 2
          this.checkIn.document = null
          this.checkIn.id = objeto.id
          this.checkIn.information = objeto
          this.checkIn.number_room = objeto.number_room
          this.dialog_checkIn = true

          this.loading = false
        })
        .catch((r) => {
          this.loading = false
        })
    },

    //necesarios
    cargarImagen(e) {
      if (e !== 'undefined') {
        if (this.accept.includes(e.type.toLowerCase())) {
          const reader = new FileReader()
          reader.readAsDataURL(e)
          reader.onload = () => (this.checkIn.document = reader.result)
        } else {
          this.temp = null
          this.$swal({
            title: 'Documento',
            text: 'El documento debe tener formato PNG, JPG o JPEG',
            type: 'info',
            showCancelButton: false,
          })
        }
      } else {
        this.checkIn.document = null
      }
    },

    validar_checkIn(scope) {
      this.$validator.validateAll(scope).then((result) => {
        if (result) {
          this.$swal({
            title: 'CheckIn',
            text: '¿Está seguro de realizar esta acción?',
            type: 'success',
            showCancelButton: true,
          }).then((result) => {
            if (result.value) {
              this.loading = true
              this.$store.state.services.reservationService
                .update(this.checkIn)
                .then((r) => {
                  this.loading = false

                  if (r.response) {
                    if (r.response.data.code === 404) {
                      this.$toastr.warning(r.response.data.error, 'Advertencia')
                      return
                    } else if (r.response.data.code === 423) {
                      this.$toastr.warning(r.response.data.error, 'Advertencia')
                      return
                    } else {
                      for (let value of Object.values(r.response.data)) {
                        this.$toastr.error(value, 'Mensaje')
                      }
                    }
                    return
                  }

                  this.$toastr.success(r.data, 'Mensaje')
                  this.dialog_checkIn = false
                  this.initialize()
                })
                .catch((r) => {
                  this.loading = false
                })
            } else {
              this.dialog_checkIn = false
            }
          })
        }
      })
    },

    getClient() {
      this.loading = true

      this.$store.state.services.clientService
        .index()
        .then((r) => {
          if (r.response) {
            if (r.response.data.code === 423) {
              this.$toastr.error(r.response.data.error, 'Mensaje')
            } else {
              for (let value of Object.values(r.response.data.error)) {
                this.$toastr.error(value, 'Mensaje')
              }
            }
            this.loading = false
            return
          }

          this.clientes = r.data.data
          this.loading = false
        })
        .catch((r) => {
          this.loading = false
        })
    },

    checkOutFunction(item) {
      this.loading = true
      this.$store.state.services.reservationService
        .show(item)
        .then((r) => {
          if (r.response) {
            if (r.response.data.code === 423) {
              this.$toastr.error(r.response.data.error, 'Mensaje')
            } else {
              for (let value of Object.values(r.response.data.error)) {
                this.$toastr.error(value, 'Mensaje')
              }
            }
            this.loading = false
            return
          }

          let colores = {
            '1': 'primary',
            '2': 'success',
          }

          let objeto = new Object()
          objeto.id = r.data.data.length != 0 ? r.data.data[0].id : null
          objeto.name = r.data.data.length != 0 ? r.data.data[0].name : null
          objeto.ubication =
            r.data.data.length != 0 ? r.data.data[0].ubication : null
          objeto.client = r.data.data.length != 0 ? r.data.data[0].client : null
          objeto.nit = r.data.data.length != 0 ? r.data.data[0].nit : null
          objeto.start = r.data.data.length != 0 ? r.data.data[0].start : null
          objeto.end = r.data.data.length != 0 ? r.data.data[0].end : null
          objeto.total_sf =
            r.data.data.length != 0 ? r.data.data[0].total_sf : null
          objeto.total = r.data.data.length != 0 ? r.data.data[0].total : null
          objeto.status = r.data.data.length != 0 ? r.data.data[0].status : null
          objeto.color = r.data.data.length != 0 ? colores[objeto.status] : null
          objeto.servicios = r.data.data
          objeto.restaurant = r.data.restaurante
          objeto.total_restaurant = r.data.total_restaurant
          objeto.total_general = r.data.total
          objeto.total_restaurant_sf = r.data.total_restaurant_sf
          objeto.number_room = r.data.number_room
          objeto.anticipo =
            r.data.data.length != 0 ? r.data.data[0].anticipo : null
          objeto.anticipo_sf =
            r.data.data.length != 0 ? r.data.data[0].anticipo_sf : null
          objeto.resta = r.data.resta

          this.checkOut.status_id = 3
          this.checkOut.way_to_pay = null
          this.checkOut.id = objeto.id
          this.checkOut.nit = objeto.nit
          this.checkOut.name = objeto.client
          this.checkOut.ubication = objeto.ubication
          this.checkOut.information = objeto
          this.checkOut.restaurant = objeto.restaurant
          this.checkOut.total_restaurant = objeto.total_restaurant
          this.checkOut.total = objeto.total_general
          this.checkOut.total_restaurant_sf = objeto.total_restaurant_sf
          this.checkOut.number_room = objeto.number_room
          this.checkOut.anticipo = objeto.anticipo
          this.checkOut.resta = objeto.resta
          this.dialog_checkOut = true

          this.loading = false
        })
        .catch((r) => {
          this.loading = false
        })
    },

    seleccionar_cliente(item) {
      this.checkOut.nit = item.nit
      this.checkOut.name = item.name
      this.checkOut.ubication = `${item.municipality.full_name}, ${item.ubication}`
    },

    validar_checkOut(scope) {
      this.$validator.validateAll(scope).then((result) => {
        if (result) {
          this.$swal({
            title: 'CheckOut',
            text: '¿Está seguro de realizar esta acción?',
            type: 'success',
            showCancelButton: true,
          }).then((result) => {
            if (result.value) {
              this.loading = true
              this.$store.state.services.reservationService
                .update(this.checkOut)
                .then((r) => {
                  this.loading = false

                  if (r.response) {
                    if (r.response.data.code === 404) {
                      this.$toastr.warning(r.response.data.error, 'Advertencia')
                      return
                    } else if (r.response.data.code === 423) {
                      this.$toastr.warning(r.response.data.error, 'Advertencia')
                      return
                    } else {
                      for (let value of Object.values(r.response.data)) {
                        this.$toastr.error(value, 'Mensaje')
                      }
                    }
                    return
                  }

                  this.$toastr.success(r.data, 'Mensaje')
                  this.dialog_checkOut = false
                  this.initialize()
                })
                .catch((r) => {
                  this.loading = false
                })
            } else {
              this.dialog_checkOut = false
            }
          })
        }
      })
    },
  },
}
</script>
