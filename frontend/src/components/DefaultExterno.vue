<template>
  <v-row align="center" justify="space-around">
    <v-overlay :value="loading">
      <v-progress-circular indeterminate size="64"></v-progress-circular>
    </v-overlay>

    <v-col cols="12" md="12" v-if="data">
      <v-card
        class="mx-auto"
        :max-width="data.contract.answer == 'ACEPTO' ? '800' : '1500'"
      >
        <v-avatar size="100">
          <img alt="logo" :src="logo" class="white--text" />
        </v-avatar>

        <template v-if="data">
          <v-card-subtitle
            class="text-center display-2"
            v-if="data.contract.answer == 'PENDIENTE'"
          >
            Términos y condiciones pendientes de confirmar
          </v-card-subtitle>
          <v-card-text
            v-if="data.contract.answer == 'PENDIENTE'"
            class="text-md-center"
          >
            Actualmente usted tiene una reservación de hospedaje registrada con
            nosotros,
            <br />
            es necesario confirmar los terminos y condiciones para poder serguir
            con el proceso de reservación.
          </v-card-text>
          <v-card-subtitle
            class="text-center display-2"
            v-else-if="data.contract.answer == 'ACEPTO'"
          >
            Términos y condiciones aceptados
            <br />
            <strong>CANCELAR ANTICIPO</strong>
          </v-card-subtitle>
          <v-card-subtitle
            class="text-center display-2"
            v-else-if="data.contract.answer == 'NO ACEPTO'"
          >
            Términos y condiciones no aceptados, reservación cancelada.
          </v-card-subtitle>

          <v-card-text
            class="text-center display-2 text-success"
            v-if="data.contract.answer == 'ACEPTO' && data.advance != null"
          >
            <template v-if="data.advance.link != null">
              Hemos enviado el link de pago al correo electrónico:
              {{ data.reservation.client.email }}
            </template>
            <template v-else-if="data.advance.document != null">
              Si no adjuntaste el comprobante de pago no te preocupes, puedes
              enviarlo por whatsapp o correo eletrónico.
            </template>
          </v-card-text>

          <v-card-text
            v-if="data.contract.answer == 'ACEPTO' && data.advance == null"
          >
            <v-container>
              <v-row>
                <v-col class="text-h7 text-left" cols="6">
                  Número de reservación: {{ data.reservation.code }}
                </v-col>
                <v-col class="text-h7 text-right" cols="6">
                  Fecha de registro: {{ data.reservation.created_at }}
                </v-col>
                <v-col
                  class="text-h7 text-left"
                  cols="12"
                  v-for="(item, index) in data.detail"
                  v-bind:key="index"
                >
                  Número de habitación: {{ item.room.number }}
                </v-col>
                <v-col class="text-h7 text-left" cols="6">
                  Inicio: {{ data.detail[0].arrival_date }}
                </v-col>
                <v-col class="text-h7 text-right" cols="6">
                  Finaliza: {{ data.detail[0].departure_date }}
                </v-col>
              </v-row>
              <hr />
              <div class="font-weight-bold ml-8 mb-2">
                Detalle de la reservación
              </div>

              <v-timeline align-top dense>
                <v-timeline-item
                  v-for="(item, index) in data.detail"
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
                      {{ item.quote }} X {{ item.precio }} =
                      {{ item.sub_formato }}
                    </div>
                  </div>
                </v-timeline-item>
              </v-timeline>
              <hr />
              <v-row>
                <v-col class="text-h4 text-right" cols="4">
                  Total
                </v-col>
                <v-col class="text-h2 text-right" cols="8">
                  {{ data.reservation.monto_reservacion }}
                </v-col>
                <v-col class="text-h4 text-right" cols="4">
                  Anticipo
                </v-col>
                <v-col class="text-h1 text-right text-success" cols="8">
                  {{ data.reservation.monto_anticipo }}
                </v-col>
              </v-row>
              <hr />
              <br />
              <v-row>
                <v-col cols="12" md="12">
                  <v-radio-group
                    v-model="form.pay"
                    v-validate="'required'"
                    data-vv-scope="advance"
                    data-vv-name="tipo de pago"
                  >
                    <template v-slot:label>
                      <div class="text-small">
                        Por favor escoja el
                        <strong>tipo de pago</strong>
                      </div>
                    </template>
                    <v-radio value="COMPLETO">
                      <template v-slot:label>
                        <div class="text-small">
                          Pagar
                          <strong class="success--text">
                            totalidad de la reservación
                          </strong>
                        </div>
                      </template>
                    </v-radio>
                    <v-radio value="ANTICIPO">
                      <template v-slot:label>
                        <div class="text-small">
                          Pagar
                          <strong class="primary--text">
                            el 50% como anticipo de la reservación
                          </strong>
                        </div>
                      </template>
                    </v-radio>
                  </v-radio-group>
                  <FormError
                    :attribute_name="'advance.tipo de pago'"
                    :errors_form="errors"
                  ></FormError>
                </v-col>
                <v-col cols="12" md="12">
                  <v-autocomplete
                    v-model="form.way_to_pay"
                    :items="way_to_pays_r"
                    chips
                    label="Seleccionar la forma de pago"
                    outlined
                    :clearable="true"
                    :deletable-chips="true"
                    item-text="name"
                    item-value="id"
                    return-object
                    v-validate="'required'"
                    data-vv-scope="advance"
                    data-vv-name="la forma de pago"
                  ></v-autocomplete>
                  <FormError
                    :attribute_name="'advance.la forma de pago'"
                    :errors_form="errors"
                  ></FormError>
                </v-col>
                <template v-if="form.way_to_pay">
                  <v-col
                    cols="12"
                    md="12"
                    v-if="form.way_to_pay.name == 'Depósito'"
                  >
                    <v-file-input
                      v-model="masiva_image"
                      color="deep-purple accent-4"
                      counter
                      accept="image/*"
                      multiple
                      placeholder="Seleccionar comprobante depago"
                      outlined
                      :show-size="1000"
                      @change="cargaMasiva($event)"
                    ></v-file-input>
                  </v-col>
                </template>
              </v-row>
            </v-container>
          </v-card-text>

          <v-card-actions
            v-if="data.contract.answer == 'ACEPTO' && data.advance == null"
          >
            <v-spacer></v-spacer>
            <v-tooltip bottom>
              <template v-slot:activator="{ on, attrs }">
                <div class="text-center">
                  <v-btn
                    class="ma-2 btn-block"
                    elevation="24"
                    x-large
                    color="primary"
                    dark
                    v-bind="attrs"
                    v-on="on"
                    @click="metodo_pago('advance')"
                    outlined
                    rounded
                  >
                    CREAR METODO DE PAGO
                  </v-btn>
                </div>
              </template>
              <span>Realizar el proceso para pagar el anticipo</span>
            </v-tooltip>
            <v-spacer></v-spacer>
          </v-card-actions>

          <v-card-actions v-if="data.contract.answer == 'PENDIENTE'">
            <v-tooltip bottom>
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  elevation="24"
                  large
                  color="error"
                  dark
                  v-bind="attrs"
                  v-on="on"
                  @click="cancelar()"
                >
                  Cancelar
                </v-btn>
              </template>
              <span>Cancelar reservación</span>
            </v-tooltip>
            <v-spacer></v-spacer>
            <v-tooltip bottom>
              <template v-slot:activator="{ on, attrs }">
                <v-btn
                  elevation="24"
                  large
                  color="success"
                  dark
                  v-bind="attrs"
                  v-on="on"
                  @click="recibir()"
                >
                  Aceptar
                </v-btn>
              </template>
              <span>Aceptar los términos y condiciones</span>
            </v-tooltip>
          </v-card-actions>
        </template>
      </v-card>

      <v-dialog
        v-model="dialog"
        eager
        fullscreen
        hide-overlay
        persistent
        transition="dialog-bottom-transition"
      >
        <v-card>
          <v-overlay :value="loading">
            <v-progress-circular indeterminate size="64"></v-progress-circular>
          </v-overlay>
          <v-toolbar dark color="primary">
            <v-btn icon dark @click="dialog = false">
              <v-icon>mdi-close</v-icon>
            </v-btn>
            <v-toolbar-title>Términos y condiciones</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn
                    color="error"
                    dark
                    v-bind="attrs"
                    v-on="on"
                    @click="limpiar"
                  >
                    Limpiar
                  </v-btn>
                </template>
                <span>Limpiar para agregar otra firma.</span>
              </v-tooltip>
              <v-tooltip bottom>
                <template v-slot:activator="{ on, attrs }">
                  <v-btn
                    color="primary"
                    dark
                    v-bind="attrs"
                    v-on="on"
                    @click="firmar()"
                  >
                    Firmar
                  </v-btn>
                </template>
                <span>
                  El cliente acepta de conformidad los productos facturados y
                  entregados.
                </span>
              </v-tooltip>
            </v-toolbar-items>
          </v-toolbar>
          <canvas
            id="draw-canvas"
            ref="canvas_img"
            @mousedown="startPainting"
            @mouseup="finishedPainting"
            @mousemove="draw"
            @touchstart="startTouch"
            @touchend="finishedTouch"
            @touchleave="leaveTouch"
            @touchmove="moveTouch"
          >
            No tienes un buen navegador.
          </canvas>
        </v-card>
      </v-dialog>
    </v-col>
  </v-row>
</template>

<style scoped>
.text-success {
  color: green;
}
#draw-canvas {
  border: 3px solid black;
  border-radius: 5px;
  cursor: crosshair;
  background: white;
  position: fixed;
  left: 10;
  top: 20;
  right: 10;
  bottom: 20;
  width: 100%;
  height: 100%;
}
</style>
<script>
import FormError from './shared/FormError'
import Axios from 'axios'
export default {
  name: 'DefaultExterno',
  components: {
    Axios,
    FormError,
  },

  data() {
    return {
      loading: false,
      dialog: false,
      title: '',
      form: {
        id: null,
        firm: null,
        way_to_pay: null,
        pay: null,
        document: null,
      },
      painting: false,
      canvas: null,
      ctx: null,
      mousePos: { x: 0, y: 0 },
      lastPos: null,
      tint: null,
      data: null,
      way_to_pays_r: [],
      masiva_image: null,
    }
  },

  computed: {
    logo() {
      return `${this.$store.state.base_url}img/logo.svg`
    },
    credit() {
      return `${this.$store.state.base_url}img/credit-card-logos.png`
    },
  },

  created() {
    this.validar(this.$route.params.token)
  },

  methods: {
    validar(id) {
      this.loading = true
      Axios({
        url: `${this.$store.state.base_url}service/rest/v1/principal/contract/${id}`,
        method: 'GET',
      })
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

          this.form.id = r.data.contract.id
          this.data = r.data
          this.way_to_pays_r = r.data.way
        })
        .catch((r) => {
          this.$toastr.error('Ocurrio un error', 'Mensaje')
          this.loading = false
        })
    },

    cerrar() {
      window.close()
    },

    cancelar() {
      this.$swal({
        title: 'Cancelar',
        text: '¿Está seguro que desea cancelar la reservación?',
        type: 'info',
        showCancelButton: true,
      }).then((result) => {
        if (result.value) {
          let object = new Object()
          object.firm = null
          object.answer = 'NO ACEPTO'

          this.loading = true
          Axios({
            url: `${this.$store.state.base_url}service/rest/v1/principal/anticipo/contract/${this.data.contract.id}/reservation/${this.data.reservation.id}`,
            method: 'PUT',
            data: object,
          })
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
              this.validar(this.data.contract.url)
            })
            .catch((r) => {
              this.$toastr.error('Ocurrio un error', 'Mensaje')
              this.loading = false
            })
        }
      })
    },

    firmar() {
      if (
        'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAB4AAAAOpCAYAAAD7TjorAAAbYUlEQVR4nOzBAQEAAACAkP6v7ggKAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACA24MDEgAAAABB/1+3I1ABAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAYCvYDAABaE71tQAAAABJRU5ErkJggg==' ==
        this.canvas.toDataURL()
      ) {
        this.$swal({
          title: 'Firmar por favor',
          text: 'Es obligatorio incluir la firma.',
          type: 'error',
          showCancelButton: false,
        })
      } else {
        this.$swal({
          title: 'Firmar y Aceptar',
          text: '¿Está seguro de aceptar los términos y condiciones?',
          type: 'success',
          showCancelButton: true,
        }).then((result) => {
          if (result.value) {
            let object = new Object()
            object.firm = this.canvas.toDataURL()
            object.answer = 'ACEPTO'

            this.loading = true
            Axios({
              url: `${this.$store.state.base_url}service/rest/v1/principal/anticipo/contract/${this.data.contract.id}/reservation/${this.data.reservation.id}`,
              method: 'PUT',
              data: object,
            })
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
                this.validar(this.data.contract.url)
                this.dialog = false
              })
              .catch((r) => {
                this.$toastr.error('Ocurrio un error', 'Mensaje')
                this.loading = false
              })
          }
        })
      }
    },

    recibir() {
      console.log('sadas')
      this.form.firm = null
      this.canvas = this.$refs.canvas_img
      this.ctx = this.canvas.getContext('2d')
      this.canvas.height = window.innerHeight
      this.canvas.width = window.innerWidth
      this.dialog = true
    },

    //Funciones para la firma
    startPainting(event) {
      this.painting = true
      this.lastPos = this.getMousePos(this.canvas, event)
      this.draw(event)
    },

    finishedPainting() {
      this.painting = false
      this.ctx.beginPath()
    },

    draw(event) {
      this.mousePos = this.getMousePos(this.canvas, event)
      if (this.painting) {
        this.ctx.strokeStyle = '#000000'
        this.ctx.beginPath()
        this.ctx.moveTo(this.lastPos.x, this.lastPos.y)
        this.ctx.lineTo(this.mousePos.x, this.mousePos.y)
        this.ctx.lineWidth = 11
        this.ctx.lineCap = 'round'
        this.ctx.stroke()
        this.ctx.closePath()
        this.lastPos = this.mousePos
      }
    },

    limpiar() {
      this.canvas.width = this.canvas.width
    },

    //Otras funciones
    getMousePos(canvasDom, mouseEvent) {
      var rect = canvasDom.getBoundingClientRect()

      return {
        x: mouseEvent.clientX - rect.left,
        y: mouseEvent.clientY - rect.top,
      }
    },

    getTouchPos(canvasDom, touchEvent) {
      var rect = canvasDom.getBoundingClientRect()

      return {
        x: touchEvent.touches[0].clientX - rect.left,
        y: touchEvent.touches[0].clientY - rect.top,
      }
    },

    startTouch(event) {
      this.mousePos = this.getTouchPos(this.canvas, event)
      event.preventDefault()
      var touch = event.touches[0]
      var mouseEvent = new MouseEvent('mousedown', {
        clientX: touch.clientX,
        clientY: touch.clientY,
      })
      this.canvas.dispatchEvent(mouseEvent)
    },

    finishedTouch(event) {
      event.preventDefault()
      var mouseEvent = new MouseEvent('mouseup', {})
      this.canvas.dispatchEvent(mouseEvent)
    },

    leaveTouch(event) {
      event.preventDefault() // Prevent scrolling when touching the canvas
      var mouseEvent = new MouseEvent('mouseup', {})
      this.canvas.dispatchEvent(mouseEvent)
    },

    moveTouch(event) {
      event.preventDefault() // Prevent scrolling when touching the canvas
      var touch = event.touches[0]
      var mouseEvent = new MouseEvent('mousemove', {
        clientX: touch.clientX,
        clientY: touch.clientY,
      })
      this.canvas.dispatchEvent(mouseEvent)
    },

    metodo_pago(scope) {
      this.$validator.validateAll(scope).then((validacion) => {
        if (validacion) {
          this.$swal({
            title: 'Crear método de pago',
            text: '¿Está seguro de crear este tipo de pago?',
            type: 'success',
            showCancelButton: true,
          }).then((result) => {
            if (result.value) {
              let object = new Object()
              object.way_to_pay = this.form.way_to_pay
              object.document = this.form.document
              object.pay = this.form.pay

              this.loading = true
              Axios({
                url: `${this.$store.state.base_url}service/rest/v1/principal/metodo/pago/${this.data.contract.id}/reservation/${this.data.reservation.id}`,
                method: 'PUT',
                data: object,
              })
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
                  this.validar(this.data.contract.url)
                  this.dialog = false
                })
                .catch((r) => {
                  this.$toastr.error('Ocurrio un error', 'Mensaje')
                  this.loading = false
                })
            }
          })
        }
      })
    },

    cargaMasiva(e) {
      this.form.document = null
      e.forEach((file) => {
        const reader = new FileReader()
        reader.readAsDataURL(file)
        reader.onload = () => (this.form.document = reader.result)
      })
    },
  },
}
</script>
