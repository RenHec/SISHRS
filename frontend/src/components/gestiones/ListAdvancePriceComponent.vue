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
            <v-toolbar-title>Historial de Anticipos</v-toolbar-title>
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
        <template v-slot:item.contract="{ item }">
          <v-row>
            <v-col cols="12" class="text-center text-md-subtitle-1 text-white">
              {{ item.contract.answer }}
            </v-col>
            <v-col cols="12" v-if="item.contract.firm">
              <img
                :src="item.contract.picture"
                :alt="item.id"
                height="100"
                width="100"
                class="img img-thumbnail"
              />
            </v-col>
          </v-row>
        </template>
        <template v-slot:item.way_to_pay="{ item }">
          <v-row>
            <v-col cols="12" class="text-center text-md-subtitle-1 text-white">
              {{ item.way_to_pay.name }}
            </v-col>
            <v-col cols="12">
              <p class="text-button" v-if="item.way_to_pay.id == 5">
                {{ item.link }}
              </p>
              <p class="text-button" v-else-if="item.way_to_pay.id == 4">
                <template>
                  <div v-if="item.picture == null">
                    Pendiente de subir documento
                  </div>
                  <img
                    v-else
                    :src="item.picture"
                    :alt="item.id"
                    height="380"
                    width="380"
                    class="img img-thumbnail"
                    @click="descargar_imagen(item.picture)"
                  />
                </template>
              </p>
            </v-col>
          </v-row>
        </template>
        <template v-slot:item.actions="{ item }">
          <v-btn
            color="info"
            v-if="!item.link && item.reservation.status_id != 5"
            @click="adjuntar(item)"
          >
            Adjuntar comprobante
          </v-btn>
          <br />
          <br />
          <v-btn
            color="success"
            v-if="item.reservation.status_id != 5"
            @click="estado_reservacion(item)"
          >
            Cambiar estado
          </v-btn>
        </template>
        <template v-slot:no-data>
          <br />
          <v-alert type="error">No hay información para mostrar.</v-alert>
        </template>
      </v-data-table>
    </v-col>

    <v-col cols="12" md="12" class="text-center" v-if="dialog">
      <v-dialog
        v-model="dialog"
        persistent
        class="mx-auto my-12"
        max-width="600px"
      >
        <v-card color="light-blue darken-4">
          <v-card-title>
            <span class="text-h5">
              Adjuntar comprobante de pago
            </span>
          </v-card-title>
          <v-card-subtitle>
            Reservación # {{ form.reservation }}
          </v-card-subtitle>
          <v-card-text>
            <v-container>
              <v-file-input
                outlined
                v-model="temp"
                @change="cargarImagen"
                accept="image/*"
                placeholder="Comprobante de pago"
                prepend-icon="mdi-camera"
                v-validate="'required'"
                data-vv-scope="adjunto"
                data-vv-name="comprobante"
              ></v-file-input>
              <FormError
                :attribute_name="'adjunto.comprobante'"
                :errors_form="errors"
              ></FormError>
            </v-container>
          </v-card-text>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="red darken-1" @click="dialog = false">
              Cerrar
            </v-btn>
            <v-btn color="blue darken-1" @click="validar_adjunto('adjunto')">
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
  name: 'ListAdvancePrice',
  components: {
    FormError,
  },
  data() {
    return {
      loading: false,
      dialog: false,
      search: '',
      headers: [
        {
          text: 'Código',
          align: 'start',
          value: 'reservation.code',
        },
        {
          text: 'Contrato',
          align: 'start',
          value: 'contract',
          sortable: false,
        },
        {
          text: 'Método de Pago',
          align: 'start',
          value: 'way_to_pay',
          sortable: false,
        },
        {
          text: 'Porcentaje',
          align: 'start',
          value: 'payment_percentage',
        },
        {
          text: 'Monto',
          align: 'start',
          value: 'monto',
        },
        {
          text: 'Fecha',
          align: 'start',
          value: 'updated_at',
        },
        {
          text: 'Pagado',
          align: 'start',
          value: 'pagado',
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
      form: {
        id: null,
        document: null,
        reservation: null,
      },
      temp: null,
      accept: ['image/png', 'image/jpeg', 'image/jpg'],
    }
  },

  created() {
    this.initialize()
  },

  methods: {
    initialize() {
      this.loading = true

      this.$store.state.services.advancePriceService
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

          this.desserts = r.data.data
          this.loading = false
        })
        .catch((r) => {
          this.loading = false
        })
    },

    adjuntar(item) {
      this.loading = true
      this.form.id = item.id
      this.form.document = null
      this.form.reservation = item.reservation.code
      this.dialog = true
      this.loading = false
    },

    //necesarios
    cargarImagen(e) {
      if (e !== 'undefined') {
        if (this.accept.includes(e.type.toLowerCase())) {
          const reader = new FileReader()
          reader.readAsDataURL(e)
          reader.onload = () => (this.form.document = reader.result)
        } else {
          this.temp = null
          this.$swal({
            title: 'Comprobaten',
            text: 'El comprobaten de pago debe tener formato PNG, JPG o JPEG',
            type: 'info',
            showCancelButton: false,
          })
        }
      } else {
        this.form.document = null
      }
    },

    estado_reservacion(data) {
      this.$swal({
        title: `Cambiar estado de la reservación \n No.${data.reservation.code}`,
        text: '¿Está seguro de realizar esta acción?',
        type: 'info',
        showCancelButton: true,
      }).then((result) => {
        if (result.value) {
          this.loading = true
          this.$store.state.services.advancePriceService
            .show(data)
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

    validar_adjunto(scope) {
      this.$validator.validateAll(scope).then((result) => {
        if (result) {
          if (this.form.document == null) {
            this.$swal({
              title: 'Comprobaten',
              text: 'El comprobante de pago es obligatorio',
              type: 'warning',
              showCancelButton: false,
            })
            this.loading = false
          } else {
            this.$swal({
              title: 'Adjuntar',
              text: '¿Está seguro de realizar esta acción?',
              type: 'success',
              showCancelButton: true,
            }).then((result) => {
              if (result.value) {
                this.loading = true
                this.$store.state.services.advancePriceService
                  .update(this.form)
                  .then((r) => {
                    this.loading = false

                    if (r.response) {
                      if (r.response.data.code === 404) {
                        this.$toastr.warning(
                          r.response.data.error,
                          'Advertencia',
                        )
                        return
                      } else if (r.response.data.code === 423) {
                        this.$toastr.warning(
                          r.response.data.error,
                          'Advertencia',
                        )
                        return
                      } else {
                        for (let value of Object.values(r.response.data)) {
                          this.$toastr.error(value, 'Mensaje')
                        }
                      }
                      return
                    }

                    this.$toastr.success(r.data, 'Mensaje')
                    this.dialog = false
                    this.loading = false

                    this.$swal({
                      title: `Cambiar estado de la reservación`,
                      text: '¿Está seguro de realizar esta acción?',
                      type: 'info',
                      showCancelButton: true,
                    }).then((result) => {
                      if (result.value) {
                        this.loading = true
                        this.$store.state.services.advancePriceService
                          .show(this.form)
                          .then((r) => {
                            if (r.response) {
                              if (r.response.data.code === 404) {
                                this.$toastr.warning(
                                  r.response.data.error,
                                  'Advertencia',
                                )
                                return
                              } else if (r.response.data.code === 423) {
                                this.$toastr.warning(
                                  r.response.data.error,
                                  'Advertencia',
                                )
                                return
                              } else {
                                for (let value of Object.values(
                                  r.response.data,
                                )) {
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
                      } else {
                        this.initialize()
                      }
                    })
                  })
                  .catch((r) => {
                    this.loading = false
                  })
              } else {
                this.dialog = false
              }
            })
          }
        }
      })
    },

    descargar_imagen(item) {
      this.loading = true
      window.open(
        item,
        'popup',
        'width=' +
          1000 +
          ', height=' +
          800 +
          ', left=' +
          500 / 2 +
          ', top=' +
          500 / 2 +
          '',
      )
      this.loading = false
    },
  },
}
</script>
