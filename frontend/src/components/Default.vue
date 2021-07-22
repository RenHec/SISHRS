<template>
  <v-row class="fill-height">
    <v-overlay :value="loading">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>
    <v-col cols="12" md="1"></v-col>
    <v-col cols="12" md="10">
      <v-sheet height="64">
        <v-toolbar flat>
          <v-btn outlined class="mr-4" color="grey darken-2" @click="setToday">
            Today
          </v-btn>
          <v-btn fab text small color="grey darken-2" @click="prev">
            <v-icon small>mdi-chevron-left</v-icon>
          </v-btn>
          <v-btn fab text small color="grey darken-2" @click="next">
            <v-icon small>mdi-chevron-right</v-icon>
          </v-btn>
          <v-toolbar-title v-if="$refs.calendar">
            {{ $refs.calendar.title }}
          </v-toolbar-title>
          <v-spacer></v-spacer>
          <v-menu bottom right>
            <template v-slot:activator="{ on, attrs }">
              <v-btn outlined color="grey darken-2" v-bind="attrs" v-on="on">
                <span>{{ typeToLabel[type] }}</span>
                <v-icon right>mdi-menu-down</v-icon>
              </v-btn>
            </template>
            <v-list>
              <v-list-item @click="type = 'day'">
                <v-list-item-title>Day</v-list-item-title>
              </v-list-item>
              <v-list-item @click="type = 'week'">
                <v-list-item-title>Week</v-list-item-title>
              </v-list-item>
              <v-list-item @click="type = 'month'">
                <v-list-item-title>Month</v-list-item-title>
              </v-list-item>
              <v-list-item @click="type = '4day'">
                <v-list-item-title>4 days</v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>
        </v-toolbar>
      </v-sheet>
      <v-sheet height="750">
        <v-calendar
          ref="calendar"
          v-model="focus"
          color="primary"
          :events="events"
          :event-color="getEventColor"
          :type="type"
          @click:event="showEvent"
          @click:more="viewDay"
          @click:date="viewDay"
          @change="updateRange"
          locale="es-es"
          :light="true"
        ></v-calendar>
        <v-menu
          v-model="selectedOpen"
          :close-on-content-click="false"
          :activator="selectedElement"
          offset-x
        >
          <v-card color="grey lighten-4" v-if="selectedEvent" flat>
            <v-toolbar :color="selectedEvent.color" dark>
              <v-toolbar-title v-html="selectedEvent.name"></v-toolbar-title>
              <v-spacer></v-spacer>
              <v-btn icon @click="selectedOpen = false">
                <v-icon>mdi-close</v-icon>
              </v-btn>
            </v-toolbar>
            <v-card-text>
              <v-list-item>
                <v-list-item-subtitle style="color: black;">
                  Cliente: {{ selectedEvent.client }}
                </v-list-item-subtitle>
              </v-list-item>
              <v-list-item>
                <v-list-item-subtitle style="color: black;">
                  NIT: {{ selectedEvent.nit }}
                </v-list-item-subtitle>
              </v-list-item>
              <v-list-item>
                <v-list-item-subtitle style="color: black;">
                  Inicio: {{ selectedEvent.start }}
                </v-list-item-subtitle>
              </v-list-item>
              <v-list-item>
                <v-list-item-subtitle style="color: black;">
                  Finaliza: {{ selectedEvent.end }}
                </v-list-item-subtitle>
              </v-list-item>
              <v-list-item>
                <v-list-item-subtitle style="color: black;">
                  Total Reservación: {{ selectedEvent.total }}
                </v-list-item-subtitle>
              </v-list-item>
              <hr />
              <div class="font-weight-bold ml-8 mb-2" style="color: black;">
                Detalle de la reservación
              </div>

              <v-timeline align-top dense>
                <v-timeline-item
                  v-for="(item, index) in selectedEvent.servicios"
                  :key="index"
                  small
                >
                  <div style="color: black;">
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
            </v-card-text>
            <v-card-actions>
              <v-btn
                color="error"
                v-if="selectedEvent.status == 1"
                @click="cancelar_reservacion(selectedEvent)"
              >
                Cancelar
              </v-btn>
              <v-btn
                color="info"
                v-if="selectedEvent.status == 1"
                @click="checkInFunction(selectedEvent)"
              >
                Check In
              </v-btn>
              <v-btn
                color="success"
                v-if="selectedEvent.status == 2"
                @click="checkOutFunction(selectedEvent)"
              >
                Check Out
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-menu>
      </v-sheet>
    </v-col>
    <v-col cols="12" md="1"></v-col>

    <v-row justify="center" v-if="dialog_checkIn">
      <v-dialog v-model="dialog_checkIn" persistent max-width="600px">
        <v-card>
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
                  Inicio: {{ checkIn.information.start }}
                </v-list-item-subtitle>
              </v-list-item>
              <v-list-item>
                <v-list-item-subtitle>
                  Finaliza: {{ checkIn.information.end }}
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
    </v-row>

    <v-row justify="center" v-if="dialog_checkOut">
      <v-dialog v-model="dialog_checkOut" persistent max-width="800px">
        <v-card>
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
                  Inicio: {{ checkOut.information.start }}
                </v-col>
                <v-col class="text-h7 text-right" cols="6">
                  Finaliza: {{ checkOut.information.end }}
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
                      {{ item.quote }} X {{ item.precio }} = {{ item.sub }}
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
                <v-col
                  class="text-h5 text-right"
                  cols="4"
                  v-if="checkOut.restaurant.length > 0"
                >
                  Total Reservación
                </v-col>
                <v-col
                  class="text-h2 text-right"
                  cols="8"
                  v-if="checkOut.restaurant.length > 0"
                >
                  {{ checkOut.information.total }}
                </v-col>
                <v-col
                  class="text-h5 text-right"
                  cols="4"
                  v-if="checkOut.restaurant.length > 0"
                >
                  Total Restaurante
                </v-col>
                <v-col
                  class="text-h2 text-right"
                  cols="8"
                  v-if="checkOut.restaurant.length > 0"
                >
                  {{ checkOut.total_restaurant }}
                </v-col>
                <v-col class="text-h4 text-right" cols="4">
                  Total
                </v-col>
                <v-col class="text-h2 text-right" cols="8">
                  {{ checkOut.total }}
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
                    :items="way_to_pays"
                    chips
                    label="Seleccionar el tipo de pago"
                    outlined
                    :clearable="true"
                    :deletable-chips="true"
                    item-text="id"
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
    </v-row>
  </v-row>
</template>

<script>
import FormError from './shared/FormError'

export default {
  name: 'Default',
  components: { FormError },
  data() {
    return {
      loading: false,
      dialog_checkIn: false,
      dialog_checkOut: false,
      focus: '',
      type: 'month',
      typeToLabel: {
        month: 'Month',
        week: 'Week',
        day: 'Day',
        '4day': '4 Days',
      },
      selectedEvent: null,
      selectedElement: null,
      selectedOpen: false,
      events: [],
      colors: [
        'blue',
        'indigo',
        'deep-purple',
        'cyan',
        'green',
        'orange',
        'grey darken-1',
      ],
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
      },
      temp: null,
      accept: ['image/png', 'image/jpeg', 'image/jpg'],
      clientes: [],
      way_to_pays: [{ id: 'Efectivo' }, { id: 'Tarjeta' }, { id: 'Cortesía' }],
    }
  },
  computed: {
    filteredList() {
      if (this.checkOut.nit) {
        return this.clientes.filter((element) => {
          return element.nit
            .toUpperCase()
            .includes(this.checkOut.nit.toUpperCase())
        })
      } else {
        return []
      }
    },
  },

  mounted() {
    this.$refs.calendar.checkChange()
  },

  methods: {
    viewDay({ date }) {
      this.focus = date
      this.type = 'day'
    },
    getEventColor(event) {
      return event.color
    },
    setToday() {
      this.focus = ''
    },
    prev() {
      this.$refs.calendar.prev()
    },
    next() {
      this.$refs.calendar.next()
    },
    showEvent({ nativeEvent, event }) {
      this.loading = true
      const open = () => {
        this.$store.state.services.reservationService
          .show(event)
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
            objeto.client =
              r.data.data.length != 0 ? r.data.data[0].client : null
            objeto.nit = r.data.data.length != 0 ? r.data.data[0].nit : null
            objeto.start = r.data.data.length != 0 ? r.data.data[0].start : null
            objeto.end = r.data.data.length != 0 ? r.data.data[0].end : null
            objeto.total_sf =
              r.data.data.length != 0 ? r.data.data[0].total_sf : null
            objeto.total = r.data.data.length != 0 ? r.data.data[0].total : null
            objeto.status =
              r.data.data.length != 0 ? r.data.data[0].status : null
            objeto.color =
              r.data.data.length != 0 ? colores[objeto.status] : null
            objeto.servicios = r.data.data
            objeto.restaurant = r.data.restaurante
            objeto.total_restaurant = r.data.total_restaurant
            objeto.total_general = r.data.total
            objeto.total_restaurant_sf = r.data.total_restaurant_sf
            objeto.number_room = r.data.number_room

            this.selectedEvent = objeto
            this.selectedOpen = true
            this.selectedElement = nativeEvent.target
            this.loading = false
          })
          .catch((r) => {
            this.loading = false
          })
      }

      if (this.selectedOpen) {
        this.selectedOpen = false
        setTimeout(open, 10)
        this.loading = false
      } else {
        open()
      }

      nativeEvent.stopPropagation()
    },
    updateRange({ start, end }) {
      this.llenar_calendario()
    },
    rnd(a, b) {
      return Math.floor((b - a + 1) * Math.random()) + a
    },
    cancelar_reservacion(data) {
      this.$swal({
        title: 'Cancelar Reservación No. \n' + data.name,
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
              this.selectedOpen = false
              this.loading = false
              this.llenar_calendario()
            })
            .catch((r) => {
              this.loading = false
            })
        }
      })
    },

    checkInFunction(item) {
      this.checkIn.status_id = 2
      this.checkIn.document = null
      this.checkIn.id = item.id
      this.checkIn.information = item
      this.checkIn.number_room = item.number_room
      this.dialog_checkIn = true
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
                  this.selectedEvent.status = 2
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
      this.getClient()
      this.checkOut.status_id = 3
      this.checkOut.way_to_pay = null
      this.checkOut.id = item.id
      this.checkOut.nit = item.nit
      this.checkOut.name = item.client
      this.checkOut.ubication = item.ubication
      this.checkOut.information = item
      this.checkOut.restaurant = item.restaurant
      this.checkOut.total_restaurant = item.total_restaurant
      this.checkOut.total = item.total_general
      this.checkOut.total_restaurant_sf = item.total_restaurant_sf
      this.checkOut.number_room = item.number_room
      this.dialog_checkOut = true
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
                  this.llenar_calendario()
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

    llenar_calendario() {
      const events = []
      this.loading = true

      this.$store.state.services.reservationService
        .calendario(0)
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

          r.data.data.forEach((element) => {
            events.push({
              id: element.id,
              name: element.name,
              start: new Date(`${element.arrival_date}`),
              end: new Date(`${element.departure_date}`),
              color: this.colors[this.rnd(0, this.colors.length - 1)],
              timed: element.tiempo,
            })
          })

          this.events = events
          this.loading = false
        })
        .catch((r) => {
          this.loading = false
        })
    },
  },
}
</script>
